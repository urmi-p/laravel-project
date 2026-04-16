<?php
    $footerSettings = $settings ?? App\Models\AdminSettings::first();
    $footerTitle = $title ?? ($title_site ?? ($footerSettings->title ?? config('app.name')));
    $socialLinks = array_filter([
        [
            'href' => $footerSettings->facebook ?? null,
            'label' => 'Facebook',
            'icon' => asset('img/facebook-square-white-bordered.png'),
        ],
        [
            'href' => $footerSettings->instagram ?? null,
            'label' => 'Instagram',
            'icon' => asset('img/instagram-square-white-bordered.png'),
        ],
        [
            'href' => $footerSettings->twitter ?? null,
            'label' => 'X',
            'icon' => asset('img/x-square-white-bordered.png'),
        ],
    ], fn ($link) => ! empty($link['href']));
?>
<table class="footer-shell" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="footer-wrap" width="760" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="footer-pad">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td class="footer-logo-cell">
                                    <a href="{{ url('/') }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('img/' . $footerSettings->logo) }}" alt="{{ $footerTitle }}"
                                            width="210">
                                    </a>

                                    @if ($socialLinks)
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                                            style="margin-top: 18px;">
                                            <tr>
                                                @foreach ($socialLinks as $socialLink)
                                                    <td style="padding-right: 10px;">
                                                        <a href="{{ $socialLink['href'] }}" target="_blank"
                                                            rel="noopener"
                                                            style="display: inline-block; text-decoration: none;">
                                                            <img src="{{ $socialLink['icon'] }}"
                                                                alt="{{ $socialLink['label'] }}" width="38"
                                                                height="38"
                                                                style="display: block; width: 38px; height: 38px;">
                                                        </a>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                                <td class="footer-text-cell">
                                    <p class="footer-about">
                                        {{ trans('emails.footer_about', ['title' => $footerTitle], 'en') }}
                                    </p>

                                    <p class="copyright">
                                        &copy; {{ date('Y') }} {{ $footerTitle }}, {{ trans('emails.rights_reserved', [], 'en') }}<br>
                                        {{ trans('emails.company_address', [], 'en') }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
