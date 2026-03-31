<?php $settings = AdminSettings::first(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<style>
@media only screen and (max-width: 700px) {
.content-cell {
padding: 38px 26px 48px !important;
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

.content-cell {
padding: 30px 18px 38px !important;
}

.button {
display: inline-block !important;
width: auto !important;
min-width: 0 !important;
}

.footer-pad {
padding: 28px 18px 34px !important;
}
}
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{{ $header ?? '' }}
<tr>
<td class="body-shell" width="100%" cellpadding="0" cellspacing="0">
<table class="inner-body" align="center" width="760" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>
{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>
