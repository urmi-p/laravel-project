<?php

namespace App\Http\Controllers;

use App\Helper;
use FileUploader;
use App\Models\Media;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jobs\MediaModeration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;
use App\Services\BunnyStreamService;
use App\Services\BunnyStorageService;
use League\Glide\ServerFactory;
use League\Glide\Responses\SymfonyResponseFactory;

class UploadMediaController extends Controller
{
	protected $bunnyStreamService;
	protected $bunnyStorageService;
	protected $request, $status, $postId;
	public function __construct(Request $request, BunnyStreamService $bunnyStreamService, BunnyStorageService $bunnyStorageService)
	{
		$this->request = $request;
		$this->status = $this->request->postId ? 'active' : 'pending';
		$this->postId = $this->request->postId ?: 0;
		$this->bunnyStreamService = $bunnyStreamService;
		$this->bunnyStorageService = $bunnyStorageService;
	}

	/**
	 * submit the form
	 */
	public function store(): JsonResponse
	{
		try {
			$publicPath = public_path('temp/');
			$file = strtolower(auth()->id() . uniqid() . time() . str_random(20));
			
			// if (config('settings.video_encoding') == 'off') {
			// 	$extensions = ['png', 'jpeg', 'jpg', 'gif', 'ief', 'video/mp4', 'audio/x-matroska', 'audio/mpeg'];
			// } else {
			$extensions = [
				'png',
				'jpeg',
				'jpg',
				'gif',
				'ief',
				'video/mp4',
				'video/quicktime',
				'video/3gpp',
				'video/mpeg',
				'video/x-matroska',
				'video/x-ms-wmv',
				'video/vnd.avi',
				'video/avi',
				'video/x-flv',
				'audio/x-matroska',
				'audio/mpeg'
			];
			// }

			// initialize FileUploader
			$FileUploader = new FileUploader('photo', array(
				'limit' => config('settings.maximum_files_post'),
				'fileMaxSize' => floor(config('settings.file_size_allowed') / 1024),
				'extensions' => $extensions,
				'title' => $file,
				'uploadDir' => $publicPath
			));

			// upload
			$upload = $FileUploader->upload();

			if (!$upload['isSuccess']) {
				$upload = $this->appendUploadLimitWarning($upload);
			}

			if ($upload['isSuccess']) {
				foreach ($upload['files'] as $key => $item) {
					$upload['files'][$key] = [
						'extension' => $item['extension'],
						'format' => $item['format'],
						'name' => $item['name'],
						'size' => $item['size'],
						'size2' => $item['size2'],
						'type' => $item['type'],
						'uploaded' => true,
						'replaced' => false
					];

					switch ($item['format']) {
						case 'image':
							$this->resizeImage($item);
							break;

						case 'video':
							$this->uploadVideo($item);
							break;

						case 'audio':
							$this->uploadMusic($item);
							break;
					}
				}
			}

			return response()->json($upload);
		} catch (Throwable $e) {
			Log::error('Upload media failed', [
				'user_id' => auth()->id(),
				'post_id' => $this->postId,
				'message' => $e->getMessage(),
			]);

			return response()->json([
				'isSuccess' => false,
				'hasWarnings' => true,
				'warnings' => [
					'The upload failed on the server. Please try again. If it keeps happening, check PHP upload limits and request timeouts.'
				],
			], 500);
		}
	}

	protected function appendUploadLimitWarning(array $upload): array
	{
		$hasFiles = !empty(request()->file('photo'));
		$contentLength = (int) request()->server('CONTENT_LENGTH', 0);

		if ($hasFiles || $contentLength <= 0) {
			return $upload;
		}

		$warnings = $upload['warnings'] ?? [];
		$warnings[] = sprintf(
			'The server did not receive the uploaded file. Check PHP upload limits (upload_max_filesize, post_max_size), max_input_time, and web server/proxy timeouts. Current app limit: %s.',
			Helper::formatBytes((int) config('settings.file_size_allowed') * 1024)
		);

		$upload['hasWarnings'] = true;
		$upload['warnings'] = array_values(array_unique($warnings));

		return $upload;
	}

	/**
	 * Resize image and add watermark
	 */
	protected function resizeImage($image): void
	{
		$fileName = $image['name'];
		$pathImage = public_path('temp/') . $image['name'];
		$img   = Image::read($pathImage);
		$token = str_random(150) . uniqid() . now()->timestamp;
		$path  = config('path.images');
		$originalStoragePath = $this->getOriginalStoragePath($fileName);

		$width = $img->width();
		$height = $img->height();

		if ($image['extension'] == 'gif') {
			$this->insertImage($fileName, $width, $height, 'gif', $token, $image);

			// Move file to Storage
			$this->moveFileStorage($fileName, $path);
		} else {
			// Image Large
			$scale = $width > 2000 ? 2000 : $width;

			$img = $img->scale(width: $scale);
			$this->persistImageToStorage($img, $originalStoragePath, $image['extension'] ?? null);

			if (config('settings.watermark') == 'on') {
				$this->applyImageWatermark($img);
			}

			$img->save();

			// Insert in Database
			$this->insertImage($fileName, $width, $height, null, $token, $image);

			// Move file to Storage
			$this->moveFileStorage($fileName, $path);
		}
	}


	/**
	 * Insert Image to Database
	 */
	protected function insertImage($fileName, $width, $height, $imgType, $token, $image): void
	{
		$media = Media::create([
			'updates_id' => $this->postId,
			'user_id' => auth()->id(),
			'type' => 'image',
			'image' => $fileName,
			'width' => $width,
			'height' => $height,
			'video' => '',
			'video_embed' => '',
			'music' => '',
			'file' => '',
			'file_name' => $image['old_name'],
			'file_size' => '',
			'bytes' => $image['size'],
			'mime' => $image['type'],
			'img_type' => $imgType ?? '',
			'token' => $token,
			'status' => $this->status,
			'created_at' => now()
		]);

		// Dispatch Media Moderation Videos
		if ($this->postId && config('settings.moderation_status')) {
			$media->update([
				'status' => 'pending'
			]);

			dispatch(new MediaModeration($media));
		}

	}

	/**
	 * Upload Video
	 */
	protected function uploadVideo($video): void
	{
		$status = $this->status;

		Media::create([
			'updates_id' => $this->postId,
			'user_id' => auth()->id(),
			'type' => 'video',
			'image' => '',
			'video' => $video['name'],
			'video_poster' => '',
			'video_embed' => '',
			'music' => '',
			'file' => '',
			'file_name' => $video['old_name'],
			'file_size' => '',
			'bytes' => $video['size'],
			'mime' => $video['type'],
			'img_type' => '',
			'token' => $this->getToken(),
			'status' => $status,
			'created_at' => now()
		]);

		// Persist the source video outside temp so the queued Bunny job can always find it.
		$this->moveFileStorage($video['name'], config('path.videos'));
	}

	/**
	 * Upload Music
	 */
	protected function uploadMusic($music): void
	{
		Media::create([
			'updates_id' => $this->postId,
			'user_id' => auth()->id(),
			'type' => 'music',
			'image' => '',
			'video' => '',
			'video_embed' => '',
			'music' => $music['name'],
			'file' => '',
			'file_name' => '',
			'file_size' => '',
			'bytes' => $music['size'],
			'mime' => $music['type'],
			'img_type' => '',
			'token' => $this->getToken(),
			'status' => $this->status,
			'created_at' => now()
		]);

		// Move file to Storage
		$this->moveFileStorage($music['name'], config('path.music'));
	}

	/**
	 * Move file to Storage
	 */
	protected function moveFileStorage($file, $path): void
	{
		$localFile = public_path('temp/' . $file);

		if ($path == config('path.images') && $this->bunnyStorageService->isConfigured()) {
			$uploaded = $this->bunnyStorageService->uploadFromLocal($localFile, $path . $file);
			if (!$uploaded) {
				Storage::putFileAs($path, new File($localFile), $file);
			}
		} else {
			Storage::putFileAs($path, new File($localFile), $file);
		}

		// Delete temp file
		if (file_exists($localFile)) {
			unlink($localFile);
		}
	}

	protected function getToken(): mixed
	{
		return str_random(150) . uniqid() . now()->timestamp;
	}

	/**
	 * delete a file
	 */
	public function delete()
	{
		$path = config('path.images');
		$pathOriginal = $path . 'originals/';
		$pathVideo = config('path.videos');
		$pathMusic = config('path.music');
		$pathFile = config('path.files');
		$local = 'temp/';

		$media = Media::whereUserId(auth()->id())
			->whereImage($this->request->file)
			->orWhere('video', $this->request->file)
			->whereUserId(auth()->id())
			->orWhere('music', $this->request->file)
			->whereUserId(auth()->id())
			->orWhere('file', $this->request->file)
			->whereUserId(auth()->id())
			->first();

		if (!$media) {
			return false;
		}

		if ($media->image) {
			Storage::delete($path . $media->image);
			Storage::delete($pathOriginal . $media->image);
			$this->bunnyStorageService->delete($path . $media->image);
			$this->bunnyStorageService->delete($pathOriginal . $media->image);
			// Delete local file (if exist)
			Storage::disk('default')->delete($local . $media->image);

			$media->delete();
		}

		if ($media->video) {
			if ($media && $media->bunny_video_id) {
				$bunnyStreamService = app(\App\Services\BunnyStreamService::class);
				if ($bunnyStreamService->isConfigured()) {
					$bunnyStreamService->deleteVideo($media->bunny_video_id);
				}
			}
			if ($media->video && Storage::exists($pathVideo . $media->video)) {
				Storage::delete($pathVideo . $media->video);
			}
			if($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)){
				Storage::delete($pathVideo . $media->video_poster);
			}
			// Delete local file (if exist)
			Storage::disk('default')->delete($local . $media->video);

			$media->delete();
		}

		if ($media->music) {
			Storage::delete($pathMusic . $media->music);
			// Delete local file (if exist)
			Storage::disk('default')->delete($local . $media->music);

			$media->delete();
		}

		if ($media->file) {
			Storage::delete($pathFile . $media->file);

			$media->delete();
		}

		return response()->json([
			'success' => true
		]);
	}

	/**
	 * Crop an uploaded image and overwrite the stored file.
	 */
	public function crop(Request $request): JsonResponse
	{
		$validator = Validator::make($request->all(), [
			'file' => 'required|string',
			'crop' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()->toArray()
			], 422);
		}

		$file = (string) $request->input('file');
		$crop = $request->input('crop');
		if (is_string($crop)) {
			$decoded = json_decode($crop, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				$crop = $decoded;
			}
		}

		if (!is_array($crop)) {
			return response()->json([
				'success' => false,
				'errors' => ['crop' => ['Invalid crop payload.']]
			], 422);
		}

		$left = isset($crop['left']) ? (int) $crop['left'] : 0;
		$top = isset($crop['top']) ? (int) $crop['top'] : 0;
		$width = isset($crop['width']) ? (int) $crop['width'] : 0;
		$height = isset($crop['height']) ? (int) $crop['height'] : 0;

		if ($width <= 0 || $height <= 0) {
			return response()->json([
				'success' => false,
				'errors' => ['crop' => ['Crop size is invalid.']]
			], 422);
		}

		$media = Media::whereUserId(auth()->id())
			->whereImage($file)
			->first();

		if (!$media) {
			return response()->json([
				'success' => false,
				'errors' => ['file' => ['Media not found.']]
			], 404);
		}

		$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if ($extension === 'gif') {
			return response()->json([
				'success' => false,
				'errors' => ['file' => ['Cropping animated GIFs is not supported.']]
			], 422);
		}

		$path = config('path.images');
		$storagePath = $path . $file;
		$originalStoragePath = $this->getOriginalStoragePath($file);
		$imageData = null;
		$usedOriginalSource = false;

		if ($this->bunnyStorageService->isConfigured() && env('BUNNY_PULL_ZONE_URL')) {
			$remoteUrl = rtrim(env('BUNNY_PULL_ZONE_URL'), '/') . '/' . ltrim($originalStoragePath, '/');
			$response = Http::timeout(15)->get($remoteUrl);
			if ($response->successful()) {
				$imageData = $response->body();
				$usedOriginalSource = true;
			}
		}

		if ($imageData === null && Storage::exists($originalStoragePath)) {
			$imageData = Storage::get($originalStoragePath);
			$usedOriginalSource = true;
		}

		if ($imageData === null && $this->bunnyStorageService->isConfigured() && env('BUNNY_PULL_ZONE_URL')) {
			$remoteUrl = rtrim(env('BUNNY_PULL_ZONE_URL'), '/') . '/' . ltrim($storagePath, '/');
			$response = Http::timeout(15)->get($remoteUrl);
			if ($response->successful()) {
				$imageData = $response->body();
			}
		}

		if ($imageData === null && Storage::exists($storagePath)) {
			$imageData = Storage::get($storagePath);
		}

		if ($imageData === null) {
			return response()->json([
				'success' => false,
				'errors' => ['file' => ['Image source not available.']]
			], 404);
		}

		$img = Image::read($imageData);
		$imageWidth = $img->width();
		$imageHeight = $img->height();

		$left = max(0, min($left, $imageWidth - 1));
		$top = max(0, min($top, $imageHeight - 1));
		$width = max(1, min($width, $imageWidth - $left));
		$height = max(1, min($height, $imageHeight - $top));

		$img->crop($width, $height, $left, $top);

		$this->persistImageToStorage($img, $originalStoragePath, $extension);

		if (config('settings.watermark') == 'on' && $usedOriginalSource) {
			$this->applyImageWatermark($img);
		}

		$this->persistImageToStorage($img, $storagePath, $extension);

		$media->update([
			'width' => $width,
			'height' => $height
		]);

		// Clear Glide cache so the new crop is served immediately.
		try {
			$server = ServerFactory::create([
				'response' => new SymfonyResponseFactory(app('request')),
				'source' => Storage::disk()->getDriver(),
				'cache' => Storage::disk()->getDriver(),
				'source_path_prefix' => 'uploads/updates/images/',
				'cache_path_prefix' => '.cache',
				'base_url' => 'uploads/updates/images/',
				'group_cache_in_folders' => true,
			]);

			$server->deleteCache($file);

			if (env('BUNNY_PULL_ZONE_URL')) {
				$ext = pathinfo($file, PATHINFO_EXTENSION) ?: 'jpg';
				$cachedRemotePath = '__bunny_files_cache/' . sha1($file) . '.' . $ext;
				$server->deleteCache($cachedRemotePath);
			}
		} catch (\Throwable $e) {
			// Cache clearing is best-effort; ignore failures.
		}

		return response()->json([
			'success' => true,
			'file' => $file,
			'width' => $width,
			'height' => $height
		]);
	}

	protected function getOriginalStoragePath(string $file): string
	{
		return config('path.images') . 'originals/' . $file;
	}

	protected function applyImageWatermark($img): void
	{
		$url = ucfirst(Helper::urlToDomain(url('/')));
		$username = auth()->user()->username;
		$fontSize = max(12, round($img->width() * 0.03));

		$img->text($url . '/' . $username, $img->width() - 30, $img->height() - 30, function (FontFactory $font)
		use ($fontSize) {
			$font->filename(public_path('webfonts/arial.TTF'));
			$font->size($fontSize);
			$font->color('#eaeaea');
			$font->stroke('000000', 1);
			$font->align('right');
			$font->valign('bottom');
		});
	}

	protected function persistImageToStorage($img, string $storagePath, ?string $extension = null): void
	{
		$tempPath = tempnam(sys_get_temp_dir(), 'img_');
		$tempWithExt = $tempPath . ($extension ? '.' . strtolower($extension) : '');
		@rename($tempPath, $tempWithExt);
		$img->save($tempWithExt);

		$uploaded = false;
		if ($this->bunnyStorageService->isConfigured()) {
			$uploaded = $this->bunnyStorageService->uploadFromLocal($tempWithExt, $storagePath);
		}

		if (!$uploaded) {
			Storage::put($storagePath, file_get_contents($tempWithExt));
		}

		if (file_exists($tempWithExt)) {
			unlink($tempWithExt);
		}
	}
}
