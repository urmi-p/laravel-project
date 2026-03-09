<?php
namespace App\Http\Controllers\Traits;

use App\Models\Like;
use App\Models\Reel;
use App\Models\User;
use App\Models\Media;
use App\Models\Reports;
use App\Models\Stories;
use App\Models\Updates;
use App\Models\Comments;
use App\Models\Messages;
use App\Models\Products;
use App\Models\Bookmarks;
use App\Models\AdminSettings;
use App\Models\Conversations;
use App\Models\LoginSessions;
use App\Models\MediaMessages;
use App\Models\MediaWelcomeMessage;
use App\Models\Notifications;
use App\Models\Subscriptions;
use App\Models\PaymentGateways;
use App\Models\VerificationRequests;
use Illuminate\Support\Facades\Storage;
use App\Models\LiveStreamingPrivateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\BunnyStorageService;
trait UserDelete
{

	public function deleteUser($id)
	{

		$user = User::findOrFail($id);

		$settings = AdminSettings::first();

		// Comments Delete
		$comments = Comments::where('user_id', $id)->get();

		// Delete Likes Comments
		foreach ($comments as $key) {

			$key->likes()->delete();

		}

		if (isset($comments)) {

			foreach ($comments as $comment) {

				$comment->delete();

			}

		}

		// Delete Replies

		$user->replies()->delete();

		// Conversations Delete
		$conversations = Conversations::where('user_1',  $id)

			->orWhere('user_2', $id)

			->get();

		if (isset($conversations)) {

			foreach ($conversations as $conversation) {

				$conversation->delete();

			}

		}

		// Likes
		$likes = Like::where('user_id', $id)->get();
		if (isset($likes)) {
			foreach ($likes as $like) {
				$like->delete();
			}
		}

		// Bookmarks
		$bookmarks = Bookmarks::where('user_id', $id)->get();
		if (isset($bookmarks)) {

			foreach ($bookmarks as $bookmark) {

				$bookmark->delete();

			}
		}


		// Messages Delete
		$path = config('path.messages');
		$bunnyStreamService = app(\App\Services\BunnyStreamService::class);
		$bunnyStorageService = app(BunnyStorageService::class);

		$messages = Messages::where('from_user_id', $id)

			->orWhere('to_user_id', $id)

			->get();

		if (isset($messages)) {

			foreach ($messages as $message) {

				$files = MediaMessages::whereMessagesId($message->id)->get();

				foreach ($files as $media) {

					$messageWithSameFile = MediaMessages::whereFile($media->file)

						->where('id', '<>', $media->id)

						->count();

					if ($messageWithSameFile == 0) {
						if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
							try {
								$bunnyStreamService->deleteVideo($media->bunny_video_id);
							} catch (\Exception $e) {
								Log::error('Bunny delete failed for message media in UserDelete', [
									'video_id' => $media->bunny_video_id,
									'error' => $e->getMessage()
								]);
							}
						}
						if ($media->file && Storage::exists($path . $media->file)) {
							Storage::delete($path . $media->file);
						}
						if ($media->file) {
							$bunnyStorageService->delete($path . $media->file);
						}
						if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
							Storage::delete($path . $media->video_poster);
							$bunnyStorageService->delete($path . $media->video_poster);
						}

					}

					$media->delete();

				}

				$message->delete();
			}
		}

		// Welcome Message Delete
		$welcomeMessages = MediaWelcomeMessage::whereCreatorId($id)->get();
		$pathWelcomeMessages = config('path.welcome_messages');

		if ($welcomeMessages->isNotEmpty()) {
			foreach ($welcomeMessages as $welcomeMedia) {
				if ($welcomeMedia->bunny_video_id && $bunnyStreamService->isConfigured()) {
					try {
						$bunnyStreamService->deleteVideo($welcomeMedia->bunny_video_id);
					} catch (\Exception $e) {
						Log::error('Bunny delete failed for welcome message in UserDelete', [
							'video_id' => $welcomeMedia->bunny_video_id,
							'error' => $e->getMessage()
						]);
					}
				}
				if ($welcomeMedia->file && Storage::exists($pathWelcomeMessages . $welcomeMedia->file)) {
					Storage::delete($pathWelcomeMessages . $welcomeMedia->file);
				}
				if ($welcomeMedia->file) {
					$bunnyStorageService->delete($pathWelcomeMessages . $welcomeMedia->file);
				}
				if ($welcomeMedia->video_poster && !filter_var($welcomeMedia->video_poster, FILTER_VALIDATE_URL)) {
					Storage::delete($pathWelcomeMessages . $welcomeMedia->video_poster);
					$bunnyStorageService->delete($pathWelcomeMessages . $welcomeMedia->video_poster);
				}

				$welcomeMedia->delete();
			}
		}

		// Delete Notification
		$notifications = Notifications::where('author', $id)

			->orWhere('destination', $id)

			->get();

		if (isset($notifications)) {

			foreach ($notifications as $notification) {

				$notification->delete();

			}

		}

		// Reports
		$reports = Reports::where('user_id', $id)

			->orWhere('type', 'user')

			->where('report_id', $id)

			->get();

		if (isset($reports)) {

			foreach ($reports as $report) {

				$report->delete();

			}
		}


		// Subscriptions User
		$subscriptions = Subscriptions::whereUserId($id)->get();

		$payment = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->first();

		if (isset($subscriptions)) {

			foreach ($subscriptions as $subscription) {

				if ($subscription->stripe_id == '') {

					$subscription->delete();

				} else {

					try {

						$stripe  = new \Stripe\StripeClient($payment->key_secret);

						$stripe->subscriptions->cancel($subscription->stripe_id, []);

					} catch (\Exception $e) {

					}



					if ($subscription->stripe_id != '') {

						DB::table('subscription_items')->where('subscription_id', '=', $subscription->id)->delete();

						$subscription->delete();

					}

				}

			}

		} // Isset Stripe


		// Subscriptions Creator

		$subscriptionsCreator = Subscriptions::whereStripePrice($user->plan)->get();

		if (isset($subscriptionsCreator)) {

			foreach ($subscriptionsCreator as $subscription) {

				if ($subscription->stripe_id != '') {

					try {

						$stripe  = new \Stripe\StripeClient($payment->key_secret);

						$stripe->subscriptions->cancel($subscription->stripe_id, []);

					} catch (\Exception $e) {

					}



					DB::table('subscription_items')->where('subscription_id', '=', $subscription->id)->delete();

				}

				$subscription->delete();

			}

		}

		//<<<--  Delete All Products -->>>

		$items = Products::where('user_id', $user->id)->get();

		$pathShop = config('path.shop');

		foreach ($items as $item) {

			// Delete Notifications

			Notifications::whereType(15)->whereTarget($item->id)->delete();

			// Delete Preview

			foreach ($item->previews as $previews) {

				Storage::delete($pathShop . $previews->name);
				$bunnyStorageService->delete($pathShop . $previews->name);

			}

			// Delete file
			Storage::delete($pathShop . $item->file);
			$bunnyStorageService->delete($pathShop . $item->file);

			// Delete purchases
			$item->purchases()->delete();

			// Delete item
			$item->delete();

		}

		// Delete All Updates (Posts)
		$this->deleteUserUpdates($id);

		//<<<-- Delete Avatar -->>>/
		if ($user->avatar != $settings->avatar) {

			Storage::delete(config('path.avatar') . $user->avatar);

		}

		//<<<-- Delete Cover -->>>/
		if ($user->cover != '') {

			Storage::delete(config('path.cover') . $user->cover);

		}

		// Delete withdrawals
		$withdrawals = $user->withdrawals()->whereStatus('pending')->get();
		if ($withdrawals) {

			foreach ($withdrawals as $withdrawal) {

				$withdrawal->delete();

			}

		}

		// Delete Login Session
		LoginSessions::whereUserId($user->id)->delete();

		// User Devices
		$oneSignalDevices = $user->oneSignalDevices()->get();
		if ($oneSignalDevices) {

			foreach ($oneSignalDevices as $oneSignalDevice) {

				$oneSignalDevice->delete();

			}

		}

		// Stories Delete
		$stories = Stories::with('media')->whereUserId($id)->get();
		$pathStories = config('path.stories');
		if (isset($stories)) {
			$bunnyStreamService = app(\App\Services\BunnyStreamService::class);
			foreach ($stories as $story) {
				foreach ($story->media as $storyMedia) {
					$storyMedia->views()->delete();

					if ($storyMedia->bunny_video_id && $bunnyStreamService->isConfigured()) {
						try {
							$bunnyStreamService->deleteVideo($storyMedia->bunny_video_id);
						}catch (\Exception $e) {
							Log::error('Bunny delete failed', [
								'video_id' => $storyMedia->bunny_video_id,
								'error' => $e->getMessage()
							]);
						}
					}
					if ($storyMedia->name && Storage::exists($pathStories . $storyMedia->name)) {
						Storage::delete($pathStories . $storyMedia->name);
					}
					if ($storyMedia->name) {
						$bunnyStorageService->delete($pathStories . $storyMedia->name);
					}
					if ($storyMedia->video_poster && !filter_var($storyMedia->video_poster, FILTER_VALIDATE_URL)) {
						$bunnyStorageService->delete($pathStories . $storyMedia->video_poster);
						Storage::delete($pathStories . $storyMedia->video_poster);
					}

					$storyMedia->delete();

				}
				$story->delete();
			}
		}

		// Delete Reels
		$reels = Reel::with(['mediaReel', 'comments'])->whereUserId($id)->get();
		$pathReels = config('path.reels');
		if ($reels->isNotEmpty()) {
			$bunnyStreamService = app(\App\Services\BunnyStreamService::class);
			foreach ($reels as $reel) {
				foreach ($reel->mediaReel as $reelMedia) {
					// \Log::info('Reel Media:', ['media' => $reelMedia]);
					if ($reelMedia->bunny_video_id && $bunnyStreamService->isConfigured()) {
						try {
							$bunnyStreamService->deleteVideo($reelMedia->bunny_video_id);
						}catch (\Exception $e) {
							Log::error('Bunny delete failed', [
								'video_id' => $reelMedia->bunny_video_id,
								'error' => $e->getMessage()
							]);
						}
					}
					if ($reelMedia->name && Storage::exists($pathReels . $reelMedia->name)) {
						Storage::delete($pathReels . $reelMedia->name);
					}
					if ($reelMedia->video_poster && !filter_var($reelMedia->video_poster, FILTER_VALIDATE_URL)) {
						Storage::delete($pathReels . $reelMedia->video_poster);
					}
					$reelMedia->delete();
				}
				$reel->comments()->delete();
				$reel->delete();
			}
		}

		// Live Streaming Private Request Pending
		$liveStreamingPrivateRequestPending = LiveStreamingPrivateRequest::whereUserId($id)
			->whereStatus(0)
			->get();

		if (isset($liveStreamingPrivateRequestPending)) {

			foreach ($liveStreamingPrivateRequestPending as $live) {
				$live->delete();
			}
		}

		$verification = VerificationRequests::whereUserId($id)->first();

		$pathImageVerification = config('path.verification');

		if ($verification) {

			// Delete Image and Form W-9
			Storage::delete([

				$pathImageVerification . $verification->image,

				$pathImageVerification . $verification->image_reverse,

				$pathImageVerification . $verification->image_selfie,

				$pathImageVerification . $verification->form_w9

			]);

			$verification->delete();

		}		


		// User Delete
		$user->delete();
	}


	protected function deleteUserUpdates($idUser)
	{

		$path      = config('path.images');

		$pathVideo = config('path.videos');

		$pathMusic = config('path.music');

		$pathFiles = config('path.files');
		$bunnyStorageService = app(BunnyStorageService::class);

		// Delete Updates

		$updates = Updates::where('user_id', $idUser)->get();

		if (isset($updates)) {

			foreach ($updates as $update) {

				$files = Media::whereUpdatesId($update->id)->get();
				$bunnyStreamService = app(\App\Services\BunnyStreamService::class);
				foreach ($files as $media) {
					if ($media->image) {
						Storage::delete($path . $media->image);
						$bunnyStorageService->delete($path . $media->image);
						$media->delete();
					}

					if ($media->video) {
						if ($media->bunny_video_id && $bunnyStreamService->isConfigured()) {
							try {
								$bunnyStreamService->deleteVideo($media->bunny_video_id);
							} catch (\Exception $e) {
								Log::error('Bunny delete failed for post media in UserDelete', [
									'video_id' => $media->bunny_video_id,
									'error' => $e->getMessage()
								]);
							}
						}
						if ($media->video && Storage::exists($pathVideo . $media->video)) {
							Storage::delete($pathVideo . $media->video);
						}
						if ($media->video_poster && !filter_var($media->video_poster, FILTER_VALIDATE_URL)) {
							Storage::delete($pathVideo . $media->video_poster);
						}
						$media->delete();

					}

					if ($media->music) {

						Storage::delete($pathMusic . $media->music);

						$media->delete();

					}

					if ($media->file) {

						Storage::delete($pathFiles . $media->file);

						$media->delete();

					}

					if ($media->video_embed) {

						$media->delete();

					}

				}

				$update->delete();

			}

		}

	}

	public function userSuspended(User $user)
	{

		// Comments Delete
		$comments = Comments::where('user_id', $user->id)->get();

		// Delete Likes Comments
		foreach ($comments as $key) {

			$key->likes()->delete();
		}

		if (isset($comments)) {
			foreach ($comments as $comment) {
				$comment->delete();
			}
		}

		// Delete Replies
		$user->replies()->delete();

		// Likes
		$likes = Like::where('user_id', $user->id)->get();
		if (isset($likes)) {
			foreach ($likes as $like) {
				$like->delete();
			}
		}
	}
}
