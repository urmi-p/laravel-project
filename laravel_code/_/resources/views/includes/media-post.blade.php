<style>
	.media-wrapper.video-inline {
		padding-top: 0 !important;
		min-height: 250px;
	}
	/* Recreate height using same ratio */
	.media-grid-1 .video-inline { padding-top: 60%; }
	.media-grid-2 .video-inline { padding-top: 45%; }
	.media-grid-3 .media-wrapper.video-inline:nth-child(1) {
		padding-top: 70.6% !important;
	}

	.media-grid-3 .media-wrapper.video-inline:nth-child(2),
	.media-grid-3 .media-wrapper.video-inline:nth-child(3) {
		padding-top: 35% !important;
	}
	.media-grid-4 .video-inline { padding-top: 50%; }
	.media-grid-5 .video-inline { padding-top: 45%; }

	.video-inner {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}

	.video-inner video {
		width: 100%;
		height: 100%;
	}

	.video-inner video {
		object-fit: cover;
	}
	.video-inner .plyr--video {
		height: 100% !important;
		background: transparent;
	}

	.video-inner .plyr__poster {
		background-size: cover !important;
		background-position: center !important;
	}
	.video-inner .plyr__video-wrapper {
		height: 100%;
		display: flex;
    	align-items: center;
    	justify-content: center;
	}

	.video-inner .plyr {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100% !important;
	}

	.video-inner .plyr video {
		object-fit: cover;
		width: 100% !important;
    	height: 100% !important;
	}
</style>
@if ($mediaImageVideoTotal == 1)
    @foreach ($mediaImageVideo as $media)
        @if ($media->image != '')
            @php
                $urlImg = Helper::postImageUrl($media);
                $fullViewImage = $media->width < $media->height ? 'post-image-full' : 'd-inline-block w-100 post-image';
            @endphp

            <a href="{{ $urlImg }}" class="glightbox w-100" data-gallery="gallery{{ $response->id }}">

                <img src="{{ $urlImg }}?w=130&h=100" {!! $media->width ? 'width="' . $media->width . '"' : null !!} {!! $media->height ? 'height="' . $media->height . '"' : null !!}
                    data-src="{{ $urlImg }}?w=960&h=980"
                    class="img-fluid lazyload top_left_right_brd {{ $fullViewImage }}"
                    alt="{{ e($response->description) }}">

            </a>
        @endif

        @if ($media->video != '')
		<div class="container-post-media top_left_right_brd">
			<div class="media-wrapper video-inline media-grid-1">
                <div class="video-inner">
					<video class="js-player w-100 @if (!request()->ajax()) invisible @endif" controls
						@if (!Helper::postThumbnailUrl($media)) preload="metadata" @endif
						@if (Helper::postThumbnailUrl($media)) preload="none" data-poster="{{ Helper::postThumbnailUrl($media) }}" @endif>
						<source src="{{ Helper::postPlaybackUrl($media) }}" type="video/mp4" />
					</video>
				</div>
            </div>
		</div>
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

					@if ($media->type == 'video')

						<div class="media-wrapper video-inline">
							<div class="video-inner">
								<video class="js-player w-100" controls
									@if (!Helper::postThumbnailUrl($media)) preload="metadata" @endif
									@if (Helper::postThumbnailUrl($media)) preload="none" data-poster="{{ Helper::postThumbnailUrl($media) }}" @endif>

									<source src="{{ $urlMedia }}" type="video/mp4" />

								</video>
							</div>
						</div>

					@else

						<a href="{{ $urlMedia }}" class="media-wrapper rounded-0 glightbox"
							data-gallery="gallery{{ $response->id }}"
							style="background-image: url('{{ $thumbMedia }}?w=960&h=980')">

							<img src="{{ $thumbMedia }}?w=960&h=980" class="post-img-grid">

						</a>

					@endif
                    {{-- <a href="{{ $popupVideoHref }}" class="media-wrapper rounded-0 glightbox"
                        data-gallery="gallery{{ $response->id }}"
                        @if ($media->type == 'video') data-glightbox="type: inline;" @endif
                        @if ($thumbMedia) style="background-image: url('{{ $thumbMedia }}?w=960&h=980')" @endif>

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



                        @if (!$videoPoster && $media->type == 'video')
                            <video playsinline muted preload="metadata" class="video-poster-html w-100 h-100">

                                <source src="{{ $urlMedia }}" type="video/mp4" />

                            </video>
                        @endif



                        @if ($thumbMedia)
                            <img src="{{ $thumbMedia }}?w=960&h=980" {!! $media->width ? 'width="' . $media->width . '"' : null !!} {!! $media->height ? 'height="' . $media->height . '"' : null !!}
                                class="post-img-grid">
                        @endif

                    </a> --}}

                    
                @endif
            @endforeach

        </div><!-- img-grid -->

    </div><!-- container-post-media -->

@endif
