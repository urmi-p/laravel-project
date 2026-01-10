<!DOCTYPE html

    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>



<body

    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">

    <style>

        @media only screen and (max-width: 600px) {

            .inner-body {

                width: 100% !important;

            }



            .footer {

                width: 100% !important;

            }

        }



        @media only screen and (max-width: 500px) {

            .button {

                width: 100% !important;

            }

        }

    </style>



    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation"

        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #000; margin: 0; padding: 0; width: 100%;">

        <tr>

            <td align="center"

                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">

                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation"

                    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">

                    <tr>

                        <td class="header"

                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; padding: 25px 0; text-align: center;">

                            <a href="{{ url('/') }}"

                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">

                                <img src="{{ url('public/img', $settings->logo) }}" class="logo" alt="Logo"

                                    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border: none; height: auto; width: auto; max-width: 190px !important;">

                            </a>



                            <h4 style="margin: 0px; color:#bdbdbd;">The online space made for real closeness.</h4>

                        </td>

                    </tr>



                    <!-- Email Body -->

                    <tr>

                        <td class="body" width="100%" cellpadding="0" cellspacing="0"

                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #000; border-bottom: 1px solid #000; border-top: 1px solid #000; margin: 0; padding: 0; width: 100%;">

                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"

                                role="presentation"

                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">

                                <!-- Body content -->

                                <tr>

                                    <td class="content-cell"

                                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">

                                        <h1

                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">

                                            {{trans('emails.hello')}} {{$fullname}}</h1>

                                        <p

                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">

                                             {{trans('emails.withdrawal_msg')}} <strong>{{$amount}}</strong>

                                        </p>

                                        

                                        <p

                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">

                                            {{trans('emails.regards')}}<br>

                                            {{$title_site}}</p>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>



                    <tr>

                        <td

                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">

                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"

                                role="presentation"

                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">

                                <tr>

                                    <td class="content-cell" align="center"

                                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">



                                        <ul style="margin: 0px; padding: 0px; list-style-type: none;">

                                            <li style="display: inline-block;"><a href="{{ AdminSettings::value('facebook') }}"

                                                    style="color: #bdbdbd; text-decoration: none;">

                                                    <img src="{{ url('public/img/facebook-square-white-bordered.png') }}" width="40">

                                                </a>

                                            </li>

                                            <li style="display: inline-block;"><a href="{{ AdminSettings::value('twitter') }}"

                                                    style="color: #bdbdbd; text-decoration: none;">

                                                    <img src="{{ url('public/img/x-square-white-bordered.png') }}" width="40">

                                                </a>

                                                </li>

                                            <li style="display: inline-block;"><a href="{{ AdminSettings::value('instagram') }}"

                                                    style="color: #bdbdbd; text-decoration: none;">

                                                    <img src="{{ url('public/img/instagram-square-white-bordered.png') }}" width="40">

                                                </a>

                                                </li>

                                        </ul>

                                        <p

                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">

                                            &copy; {{ date('Y') }} {{$title_site}} {{trans('emails.rights_reserved')}}</p>



                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>

</body>



</html>