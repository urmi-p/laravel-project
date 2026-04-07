@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# {{ trans('emails.hello', [], 'en') }}
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}
@endforeach

{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{ trans('emails.regards', [], 'en') }}<br>
{{ trans('emails.team_signature', ['title' => config('app.name')], 'en') }}
@endif
@endcomponent
