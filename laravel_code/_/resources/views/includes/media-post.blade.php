@if ($mediaImageVideoTotal == 1)



@foreach ($mediaImageVideo as $media)



@if ($media->image != '')



@php

$urlImg = Helper::postImageUrl($media);

$fullViewImage = $media->width < $media->height ? 'post-image-full' : 'd-inline-block w-100 post-image';

	@endphp



	<a href="{{ $urlImg }}" class="glightbox w-100" data-gallery="gallery{{$response->id}}">

		<img src="{{$urlImg}}?w=130&h=100" {!! $media->width ? 'width="'. $media->width .'"' : null !!} {!! $media->height ? 'height="'. $media->height .'"' : null !!} data-src="{{$urlImg}}?w=960&h=980" class="img-fluid lazyload top_left_right_brd {{ $fullViewImage }}" alt="{{ e($response->description) }}">

	</a>

	@endif





	@if ($media->video != '')

	<video class="js-player w-100 @if (!request()->ajax())invisible @endif" controls @if (!Helper::postThumbnailUrl($media)) preload="metadata" @endif @if (Helper::postThumbnailUrl($media)) preload="none" data-poster="{{ Helper::postThumbnailUrl($media) }}" @endif>
		<source src="{{ Helper::postPlaybackUrl($media) }}" type="video/mp4" />
	</video>

	@endif



	@endforeach



	@endif



	@if ($mediaImageVideoTotal >= 2)

	<div class="container-post-media top_left_right_brd">
		<div class="media-grid-{{ $mediaImageVideoTotal > 5 ? 5 : $mediaImageVideoTotal }}">

			@foreach ($mediaImageVideo as $media)

			@php

			if ($media->type == 'video') {
			$urlMedia = Helper::postPlaybackUrl($media);
			$videoPoster = Helper::postThumbnailUrl($media);
			$thumbMedia = $videoPoster ?: null;
			} else {

			$urlMedia = Helper::postImageUrl($media);

			$videoPoster = null;
			$thumbMedia = $urlMedia;

			}



			$nth++;

			@endphp



			@if ($media->type == 'image' || $media->type == 'video')

			@php
			$inlineVideoPopupId = $media->type == 'video' ? 'post-video-popup-' . $media->id : null;
			$popupVideoHref = $media->type == 'video' ? '#' . $inlineVideoPopupId : $urlMedia;
			@endphp

			<a href="{{ $popupVideoHref }}" class="media-wrapper rounded-0 glightbox" data-gallery="gallery{{$response->id}}" @if ($media->type == 'video') data-glightbox="type: inline;" @endif @if ($thumbMedia) style="background-image: url('{{ $thumbMedia }}?w=960&h=980')" @endif>

				@if ($nth == 5 && $mediaImageVideoTotal > 5)

				<span class="more-media">

					<h2>+{{ $mediaImageVideoTotal - 5 }}</h2>

				</span>

				@endif



				@if ($media->type == 'video')

				<span class="button-play">

					<i class="bi bi-play-fill text-white"></i>

				</span>

				@endif



				@if (! $videoPoster && $media->type == 'video')

				<video playsinline muted preload="metadata" class="video-poster-html w-100 h-100">

					<source src="{{ $urlMedia }}" type="video/mp4" />

				</video>

				@endif



				@if ($thumbMedia)

				<img src="{{ $thumbMedia }}?w=960&h=980" {!! $media->width ? 'width="'. $media->width .'"' : null !!} {!! $media->height ? 'height="'. $media->height .'"' : null !!} class="post-img-grid">

				@endif

			</a>

			@if ($media->type == 'video')
			<div id="{{ $inlineVideoPopupId }}" style="display: none;">
				<div class="glightbox-post-video custom-scrollbar">
					<div class="glightbox-post-video__media">
						<video class="js-player-popup w-100" controls @if (!Helper::postThumbnailUrl($media)) preload="metadata" @endif @if (Helper::postThumbnailUrl($media)) preload="none" data-poster="{{ Helper::postThumbnailUrl($media) }}" @endif>
							<source src="{{ Helper::postPlaybackUrl($media) }}" type="video/mp4" />
						</video>
					</div>

					<div class="glightbox-post-video__footer bg-dark-force">
						<div class="action-bar">
							<div class="action-left">
								<div class="action_avatar">
									<span class="rounded-circle position-relative">
										<a href="{{ url('profile',$response->creator->username) }}">
											<img src="{{ Helper::getFile(config('path.avatar') . $response->creator->avatar) }}"
												alt="{{ $response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name }}"
												class="rounded-circle avatarUser" width="60" height="60">
										</a>
									</span>
								</div>
								<div class="action_user_info">
									<div class="action_user_heading">
										<strong>
											<a href="{{ url('profile',$response->creator->username) }}">
												{{ $response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name }}
											</a>
										</strong>

										@if ($response->creator->verified_id == 'yes')
										<small class="verified" title="{{ __('general.verified_account') }}" data-toggle="tooltip" data-placement="top">
											<i class="bi bi-patch-check-fill"></i>
										</small>
										@endif
									</div>
									<span>
										<small class="text-muted font-14 mt-2">{{ '@' . $response->creator->username }}</small>
									</span>
								</div>
							</div>

							<div class="card-footer action-pill mt-2">
								<span class="action-pill text-white-force">
									<i class="bi bi-hand-thumbs-up-fill"></i>
									@if ($showLikesCount)
									<span class="action-count">{{ $totalLikes }}</span>
									@endif
								</span>

								@if (!$settings->hide_comments)
								<span class="action-pill text-white-force">
									<i class="bi bi-chat-text"></i>
									<span class="action-count">{{ $totalComments }}</span>
								</span>
								@endif

								<span class="action-pill text-white-force">
									<i class="fas fa-share"></i>
								</span>

								<span class="action-pill text-white-force">
									<i class="far fa-bookmark"></i>
									<span class="action-count">{{ $totalBookmarks }}</span>
								</span>

								<span class="action-pill text-white-force">
									<i class="bi bi-three-dots-vertical"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif

			@endif



			@endforeach

		</div><!-- img-grid -->

	</div><!-- container-post-media -->

	@endif
