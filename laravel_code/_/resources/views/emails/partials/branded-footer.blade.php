<?php
    $footerSettings = $settings ?? App\Models\AdminSettings::first();
    $footerTitle = $title ?? ($title_site ?? ($footerSettings->title ?? config('app.name')));
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
                                        <img src="{{ url('img', $footerSettings->logo) }}" alt="{{ $footerTitle }}"
                                            width="210">
                                    </a>

                                    <ul class="social-row">
                                        <li>
                                            <a class="social-box" href="{{ App\Models\AdminSettings::value('facebook') }}"
                                                target="_blank" rel="noopener">
                                                <img src="{{ url('img/facebook-square-white-bordered.png') }}"
                                                    alt="Facebook" width="18">
                                            </a>
                                        </li>
                                        <li>
                                            <a class="social-box" href="{{ App\Models\AdminSettings::value('instagram') }}"
                                                target="_blank" rel="noopener">
                                                <img src="{{ url('img/instagram-square-white-bordered.png') }}"
                                                    alt="Instagram" width="18">
                                            </a>
                                        </li>
                                        <li>
                                            <a class="social-box" href="{{ App\Models\AdminSettings::value('twitter') }}"
                                                target="_blank" rel="noopener">
                                                <img src="{{ url('img/x-square-white-bordered.png') }}"
                                                    alt="X" width="18">
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                                <td class="footer-text-cell">
                                    <p class="footer-about">
                                        {{ __('emails.footer_about', ['title' => $footerTitle]) }}
                                    </p>

                                    <p class="copyright">
                                        &copy; {{ date('Y') }} {{ $footerTitle }}, {{ __('emails.rights_reserved') }}<br>
                                        {{ __('emails.company_address') }}
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
