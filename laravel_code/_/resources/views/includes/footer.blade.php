<!-- FOOTER -->

<footer class="py-3 link-footer footer-bg text-center">
	
		<div class="col-md-12 text-center">

				<a href="{{url('/')}}">

					<img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="max-w-125">

				</a>



			</div>
		<div class="row">

			@auth

			<div class="d-lg-none d-block pb-5 mb-2 w-100">
				@include('includes.footer-tiny')
			</div>
			@endauth


			<div class="col-md-12 copyright @auth d-none d-lg-block @endauth">

				&copy; {{date('Y')}} {{$settings->title}}, {{__('emails.rights_reserved')}}
				@if ($settings->show_address_company_footer)
				<small class="ml-2">
					{{ $settings->company }} - {{ __('general.address') }}: {{ $settings->address }} {{ $settings->city }} {{ $settings->country }}
				</small>
				@endif

			</div>

		</div>
	
</footer>
<div class="py-4 footer-bg @auth d-none d-lg-block @endauth">
	<footer class="">
		<div class="row">

			

			<div class="col-md-12 text-center mt-2">

				<ul class="list-inline">

					@foreach (Helper::pages() as $page)



					@if ($page->access == 'all')

					<li class="list-inline-item">

						<a class="link-footer" href="{{ url('/p', $page->slug) }}">

							{{ $page->title }}

						</a>

					</li>



					@elseif ($page->access == 'creators' && auth()->check() && auth()->user()->verified_id == 'yes')

					<li class="list-inline-item">

						<a class="link-footer" href="{{ url('/p', $page->slug) }}">

							{{ $page->title }}

						</a>

					</li>



					@elseif ($page->access == 'members' && auth()->check())

					<li class="list-inline-item">

						<a class="link-footer" href="{{ url('/p', $page->slug) }}">

							{{ $page->title }}

						</a>

					</li>

					@endif



					@endforeach



					@if (! $settings->disable_contact)

					<li class="list-inline-item"><a class="link-footer" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>

					@endif





					@if ($blogsCount != 0)

					<li class="list-inline-item"><a class="link-footer" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>

					@endif

				</ul>

			</div>

		</div>
	</footer>



	@if ($settings->facebook != ''

	|| $settings->twitter != ''

	|| $settings->instagram != ''

	|| $settings->pinterest != ''

	|| $settings->youtube != ''

	|| $settings->github != ''

	|| $settings->tiktok != ''

	|| $settings->snapchat != ''

	|| $settings->telegram != ''

	|| $settings->reddit != ''

	|| $settings->linkedin != ''

	|| $settings->threads != ''

	)



	<div class="col-md-12 text-center">
		<div class="w-100">
			<ul class="list-inline list-social m-0">
				@if ($settings->twitter != '')
				<li class="list-inline-item"><a href="{{$settings->twitter}}" target="_blank" class="ico-social"><i class="bi-twitter-x"></i></a></li>
				@endif

				@if ($settings->facebook != '')
				<li class="list-inline-item"><a href="{{$settings->facebook}}" target="_blank" class="ico-social"><i class="fab fa-facebook"></i></a></li>
				@endif

				@if ($settings->instagram != '')
				<li class="list-inline-item"><a href="{{$settings->instagram}}" target="_blank" class="ico-social"><i class="fab fa-instagram"></i></a></li>
				@endif

				@if ($settings->pinterest != '')
				<li class="list-inline-item"><a href="{{$settings->pinterest}}" target="_blank" class="ico-social"><i class="fab fa-pinterest"></i></a></li>
				@endif

				@if ($settings->youtube != '')
				<li class="list-inline-item"><a href="{{$settings->youtube}}" target="_blank" class="ico-social"><i class="fab fa-youtube"></i></a></li>
				@endif

				@if ($settings->github != '')
				<li class="list-inline-item"><a href="{{$settings->github}}" target="_blank" class="ico-social"><i class="fab fa-github"></i></a></li>
				@endif

				@if ($settings->tiktok != '')
				<li class="list-inline-item"><a href="{{$settings->tiktok}}" target="_blank" class="ico-social"><i class="bi-tiktok"></i></a></li>
				@endif

				@if ($settings->snapchat != '')
				<li class="list-inline-item"><a href="{{$settings->snapchat}}" target="_blank" class="ico-social"><i class="bi-snapchat"></i></a></li>
				@endif

				@if ($settings->telegram != '')
				<li class="list-inline-item"><a href="{{$settings->telegram}}" target="_blank" class="ico-social"><i class="bi-telegram"></i></a></li>
				@endif

				@if ($settings->reddit != '')
				<li class="list-inline-item"><a href="{{$settings->reddit}}" target="_blank" class="ico-social"><i class="bi-reddit"></i></a></li>
				@endif

				@if ($settings->linkedin != '')
				<li class="list-inline-item"><a href="{{$settings->linkedin}}" target="_blank" class="ico-social"><i class="bi-linkedin"></i></a></li>
				@endif

				@if ($settings->threads != '')
				<li class="list-inline-item"><a href="{{$settings->threads}}" target="_blank" class="ico-social"><i class="bi-threads"></i></a></li>
				@endif

			</ul>

		</div>

@endif



		<li>
			<div id="installContainer" class="display-none text-center">
				<button class="btn btn-primary w-50 rounded-pill mb-2 mt-3 mx-auto d-block" id="butInstall" type="button">
					<i class="bi-phone mr-1"></i> {{ __('general.install_web_app') }}
				</button>
			</div>
		</li>

	</div>



</div>
