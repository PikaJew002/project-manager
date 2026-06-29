<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ $app_name }} | {{ $subject_text }}</title>
<style type="text/css">
/* Client-specific Styles */
body,
table,
td,
a {
-webkit-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
}

table,
td {
mso-table-lspace: 0pt;
mso-table-rspace: 0pt;
}

img {
-ms-interpolation-mode: bicubic;
border: 0;
height: auto;
line-height: 100%;
outline: none;
text-decoration: none;
}

table {
border-collapse: collapse !important;
}

body {
height: 100% !important;
margin: 0 !important;
padding: 0 !important;
width: 100% !important;
background-color: #f9fafb;
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
color: #1f2937;
}

/* Hover & Active Effects for modern clients */
.cta-button:hover {
background-color: #1e2538 !important;
}

.task-card {
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
}
</style>
</head>

<body style="margin: 0; padding: 0; background-color: #f9fafb;">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
<tr>
<td align="center" style="padding: 32px 16px; background-color: #f9fafb;">
<!-- Container Table -->
<table border="0" cellpadding="0" cellspacing="0" width="100%"
style="max-width: 600px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">

<!-- Header Area / Brand -->
<tr>
<td
style="padding: 32px 32px 16px 32px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
<span
    style="font-size: 20px; font-weight: 800; color: #2a324b; letter-spacing: -0.5px;">{{ $app_name }}</span>
</td>
</tr>

<!-- Main Email Content -->
<tr>
<td
style="padding: 0 32px 32px 32px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

<!-- Greeting -->
<h1
    style="margin: 0 0 12px 0; font-size: 22px; font-weight: 700; color: #111827; line-height: 1.3;">
    Hello!</h1>
<p style="margin: 0 0 24px 0; font-size: 16px; line-height: 1.5; color: #4b5563;">
    {{ $intro_text }}
</p>

@foreach ($tasks as $task)
<x-email.task-card :task="$task" />
@endforeach

<!-- Primary CTA Button Area -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td align="center" style="padding: 12px 0 24px 0;">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" bgcolor="#2a324b" style="border-radius: 6px;">
<a href="{{ $cta_url }}" target="_blank" class="cta-button" style="display: inline-block; padding: 14px 28px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 6px;">
{{ $cta_text }}
</a>
</td>
</tr>
</table>
</td>
</tr>
</table>

<!-- Sign-off / Signature -->
<p style="margin: 24px 0 0 0; font-size: 15px; line-height: 1.5; color: #4b5563;">
Thank you for using {{ $app_name }}!
<p>
<span style="font-weight: 600; color: #2a324b;">Regards,<br />{{ $app_name }}</span>
</p>

</td>
</tr>

<!-- Divider -->
<tr>
<td style="padding: 0 32px;">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #e5e7eb;">
<tr>
<td></td>
</tr>
</table>
</td>
</tr>

<!-- Secondary/Troubleshooting Footer -->
<tr>
<td
style="padding: 24px 32px 32px 32px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; color: #6b7280;">
If you're having trouble clicking the "{{ $cta_text }}" button, copy and paste the URL
below into your web browser:
<div style="margin-top: 10px; word-break: break-all;">
<a href="{{ $cta_url }}" target="_blank" style="color: #3b82f6; text-decoration: underline;">{{ $cta_url }}</a>
</div>
</td>
</tr>

</table>

<!-- Copyright / System Footer -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin-top: 16px;">
<tr>
<td align="center" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; color: #9ca3af;">
&copy; {{ date('Y') }} {{ $app_name }}. All rights reserved.
</td>
</tr>
</table>

</td>
</tr>
</table>
</body>

</html>
