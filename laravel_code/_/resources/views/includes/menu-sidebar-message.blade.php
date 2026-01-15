<ul class="list-unstyled d-lg-block d-none menu-left-home sticky-top">
	{{-- USER PROFILE HEADER --}}
	<li class="sidebar-user mb-3">
		<div class="user_avatar">
			<img
				src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}"
				alt="User Avatar">
		</div>

		<div class="user_info">
			<h6 class="mb-0">
				{{ Auth::user()->name }}
			</h6>
			<small>
				{{ Auth::user()->email }}
			</small>
			
		</div>
	</li>
	<li class="sidebar_li">
		<a href="{{ url('messages') }}" data-toggle="modal" data-target="#newMessageForm" title="{{trans('general.new_message')}}">
			<i class="bi bi-chat-left-text"></i>
			<span class="ml-2">{{ __('general.direct_messages') }}</span>
		</a>
	</li>
</ul>