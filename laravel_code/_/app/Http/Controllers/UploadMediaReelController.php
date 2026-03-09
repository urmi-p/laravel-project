<?php

namespace App\Http\Controllers;

use App\Helper;
use FileUploader;
use App\Models\MediaReel;
use App\Services\BunnyStreamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadMediaReelController extends Controller
{
	protected $bunnyStreamService, $request;
	public function __construct(Request $request, BunnyStreamService $bunnyStreamService)
	{
		$this->request = $request;
		$this->bunnyStreamService = $bunnyStreamService;
	}

	public function store()
	{
		$publicPath = public_path('temp/');
		$file = strtolower(auth()->id() . uniqid() . time() . str_random(20));

		// if (config('settings.video_encoding') == 'off') {
		// 	$extensions = ['video/mp4'];
		// } else {
		$extensions = [
			'video/mp4',
			'video/quicktime',
			'video/3gpp',
			'video/mpeg',
			'video/x-matroska',
			'video/x-ms-wmv',
			'video/vnd.avi',
			'video/avi',
			'video/x-flv'
		];
		// }

		// initialize FileUploader
		$FileUploader = new FileUploader('media', array(
			'limit' => 1,
			'fileMaxSize' => floor(config('settings.file_size_allowed') / 1024),
			'extensions' => $extensions,
			'title' => $file,
			'uploadDir' => $publicPath
		));

		// upload
		$upload = $FileUploader->upload();

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

				$this->uploadVideo($item['name']);
			}
		}

		return response()->json($upload);
	}

	protected function uploadVideo($video): void
	{
		MediaReel::create([
			'reels_id' => 0,
			'name' => $video
		]);
	}

	/**
	 * Delete a file
	 */
	public function delete()
	{
		$local = 'temp/';
		$pathVideo = config('path.reels');

		$mediaReel = MediaReel::whereName($this->request->file)->first();

		if ($mediaReel && $mediaReel->bunny_video_id && $this->bunnyStreamService->isConfigured()) {
			$this->bunnyStreamService->deleteVideo($mediaReel->bunny_video_id);
		}

		if ($mediaReel) {
			$mediaReel->delete();
		}

		// Delete local file
		Storage::disk('default')->delete($local . $this->request->file);
		Storage::delete($pathVideo . $this->request->file);

		if ($this->request->thumbnail) {
			// Delete thumbnail
			Storage::disk('default')->delete($local . $this->request->thumbnail);
		}

		return response()->json([
			'success' => true
		]);
	}
}
