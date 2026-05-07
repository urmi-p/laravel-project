<div class="modal fade" id="creatorPublishMenu" tabindex="-1" role="dialog" aria-labelledby="creator-publish-menu-title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content creator-publish-modal">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">
					<div class="card-body px-lg-5 py-lg-5 position-relative">
						<div class="creator-publish-header mb-4">
							<div class="creator-publish-title-wrap">
								<i class="bi-clock-history"></i>
								<strong id="creator-publish-menu-title">{{ __('general.choose_publication_type') }}</strong>
							</div>
							<button type="button" data-dismiss="modal" class="btn-cancel-msg btn-reset" aria-label="{{ __('general.close') }}">
								<i class="bi bi-x-lg"></i>
							</button>
						</div>

						<a class="card choose-type-sale mb-3" href="{{ url('new/update') }}">
							<div class="card-body">
								<h6 class="mb-1"><i class="bi-image mr-2"></i> {{ __('general.post_with_media') }}</h6>
							</div>
						</a>

						<a class="card choose-type-sale mb-3" href="{{ url('new/update?publish=text') }}">
							<div class="card-body">
								<h6 class="mb-1"><i class="bi-type mr-2"></i> {{ __('general.post_text_only') }}</h6>
							</div>
						</a>

						<button type="button" class="card choose-type-sale choose-type-sale-btn mb-0 w-100 text-left btnCreateLive btn-reset" data-dismiss="modal">
							<div class="card-body">
								<h6 class="mb-1"><i class="bi-camera-video mr-2"></i> {{ __('general.create_live_stream') }}</h6>
							</div>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
