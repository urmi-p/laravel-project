<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $title_site }}</title>
    <style type="text/css" rel="stylesheet" media="all">
        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100%;
            line-height: 1.5;
            -webkit-text-size-adjust: none;
            background-color: #000000;
            color: #12243a;
        }

        table {
            border-collapse: collapse;
        }

        img {
            border: none;
            max-width: 100%;
        }

        a {
            color: #ffffff;
            text-decoration: none;
        }

        .wrapper {
            width: 100%;
            background-color: #000000;
        }

        .header-shell {
            width: 100%;
            background-color: #000000;
        }

        .header-copy {
            color: #ffffff;
            font-size: 16px;
            line-height: 1.4;
            text-align: center;
            padding: 24px 20px 8px;
        }

        .logo-wrap {
            text-align: center;
            padding: 8px 20px 34px;
        }

        .content-shell {
            width: 100%;
            background-color: #f4f4f4;
        }

        .content-card {
            width: 100%;
            max-width: 760px;
            background-color: #f4f4f4;
        }

        .content-pad {
            padding: 52px 56px 64px;
        }

        .title {
            margin: 0 0 40px;
            color: #112640;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: 0.02em;
            text-align: center;
            text-transform: uppercase;
        }

        .greeting,
        .body-copy,
        .body-copy p,
        .salutation {
            color: #112640;
            font-size: 18px;
            line-height: 1.55;
            text-align: left;
        }

        .greeting {
            margin: 0 0 12px;
        }

        .body-copy,
        .body-copy p {
            margin: 0 0 22px;
        }

        .body-copy p:last-child {
            margin-bottom: 0;
        }

        .body-copy a {
            color: #112640;
            font-weight: bold;
            text-decoration: underline;
        }

        .action-wrap {
            padding: 10px 0 4px;
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 14px 24px;
            background-color: #111111;
            border-radius: 4px;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
        }

        .salutation {
            margin: 54px 0 0;
            font-weight: 700;
        }

        .footer-shell {
            width: 100%;
            background-color: #000000;
        }

        .footer-wrap {
            width: 100%;
            max-width: 760px;
        }

        .footer-pad {
            padding: 46px 32px 54px;
        }

        .footer-logo-cell {
            width: 34%;
            vertical-align: top;
            padding-right: 20px;
        }

        .footer-text-cell {
            width: 66%;
            vertical-align: top;
        }

        .footer-about {
            margin: 0 0 26px;
            color: #ffffff;
            font-size: 14px;
            line-height: 1.6;
            text-align: left;
        }

        .social-row {
            margin: 0 0 18px;
            padding: 0;
            list-style: none;
        }

        .social-row li {
            display: inline-block;
            margin-right: 10px;
        }

        .social-box {
            display: inline-block;
            width: 38px;
            height: 38px;
            line-height: 38px;
            border: 1px solid #ffffff;
            text-align: center;
        }

        .copyright {
            margin: 0;
            color: #ffffff;
            font-size: 13px;
            line-height: 1.7;
            text-align: left;
        }

        @media only screen and (max-width: 700px) {
            .content-pad {
                padding: 38px 26px 48px !important;
            }

            .title {
                font-size: 22px !important;
                margin-bottom: 30px !important;
            }

            .greeting,
            .body-copy,
            .body-copy p,
            .salutation {
                font-size: 17px !important;
            }

            .footer-pad {
                padding: 34px 24px 42px !important;
            }

            .footer-logo-cell,
            .footer-text-cell {
                display: block !important;
                width: 100% !important;
                padding-right: 0 !important;
            }

            .footer-logo-cell {
                padding-bottom: 26px !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .header-copy {
                font-size: 14px !important;
                padding: 18px 16px 8px !important;
            }

            .logo-wrap {
                padding: 8px 16px 24px !important;
            }

            .content-pad {
                padding: 30px 18px 38px !important;
            }

            .title {
                font-size: 18px !important;
            }

            .greeting,
            .body-copy,
            .body-copy p,
            .salutation {
                font-size: 16px !important;
            }

            .button {
                display: block !important;
                width: 100% !important;
            }

            .footer-pad {
                padding: 28px 18px 34px !important;
            }
        }
    </style>
</head>

<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="header-shell" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="header-copy">
                            {{ trans('emails.brand_tagline', [], 'en') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="logo-wrap">
                            <a href="{{ url('/') }}" target="_blank" rel="noopener">
                                <img src="{{ url('public/img', $settings->logo) }}" alt="{{ $title_site }}" width="210">
                            </a>
                        </td>
                    </tr>
                </table>

                <table class="content-shell" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td align="center">
                            <table class="content-card" width="760" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-pad">
                                        <h1 class="title">{{ trans('emails.new_subscriber_title', [], 'en') }}</h1>

                                        <p class="greeting">{{ trans('emails.hello', [], 'en') }} {{ $fullname }}</p>

                                        <div class="body-copy">
                                            {!! $body !!}
                                        </div>

                                        <div class="action-wrap">
                                            <a href="{{ url('my/subscribers') }}" class="button" target="_blank" rel="noopener">
                                                {{ trans('emails.go_subscribers', [], 'en') }}
                                            </a>
                                        </div>

                                        <p class="salutation">
                                            {{ trans('emails.regards', [], 'en') }}<br>
                                            {{ trans('emails.team_signature', ['title' => $title_site], 'en') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                @include('emails.partials.branded-footer', ['title' => $title_site, 'settings' => $settings])
            </td>
        </tr>
    </table>
</body>

</html>
