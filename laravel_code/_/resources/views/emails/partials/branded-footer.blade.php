<?php
    $footerSettings = $settings ?? App\Models\AdminSettings::first();
    $footerTitle = $title ?? ($title_site ?? ($footerSettings->title ?? config('app.name')));
    $socialLinks = array_filter([
        [
            'href' => $footerSettings->facebook ?? null,
            'label' => 'Facebook',
            'short' => 'FB',
        ],
        [
            'href' => $footerSettings->instagram ?? null,
            'label' => 'Instagram',
            'short' => 'IG',
        ],
        [
            'href' => $footerSettings->twitter ?? null,
            'label' => 'X',
            'short' => 'X',
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
                                                        <table role="presentation" cellpadding="0" cellspacing="0"
                                                            border="0">
                                                            <tr>
                                                                <td width="38" height="38" align="center"
                                                                    valign="middle"
                                                                    style="width: 38px; height: 38px; border: 1px solid #ffffff; text-align: center; vertical-align: middle;">
                                                                    <a href="{{ $socialLink['href'] }}" target="_blank"
                                                                        rel="noopener"
                                                                        style="display: block; width: 38px; line-height: 38px; color: #ffffff; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 0.04em; text-align: center; text-decoration: none; text-transform: uppercase; mso-line-height-rule: exactly;">
                                                                        {{ $socialLink['short'] }}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
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
