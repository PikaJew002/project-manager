@props(['task'])

@php
$priorityColors = [
    'Urgent' => '#dc2626',
    'Important' => '#f59e0b',
    'Medium' => '#4f46e5',
    'Low' => '#4b5563',
];
@endphp

<table class="task-card" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px; background-color: #ffffff; border: 1px solid #e5e7eb; border-left: 4px solid {{ $priorityColors[$task['priority']->value] }}; border-radius: 6px;">
<tr>
<td style="padding: 16px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
<!-- Card Top Line (Title and Priority Badge) -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 12px;">
<tr>
<!-- Task Title -->
<td valign="top" style="font-size: 15px; font-weight: 600; color: #111827; line-height: 1.45;">
{{ $task['name'] }}
</td>
<!-- Priority Badge -->
<td valign="top" width="90" align="right" style="padding-left: 12px;">
<table border="0" cellpadding="0" cellspacing="0" style="background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px;">
<tr>
<td style="padding: 3px 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 700; color: {{ $priorityColors[$task['priority']->value] }}; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; line-height: 12px;">
<span style="display: inline-block; width: 6px; height: 6px; background-color: {{ $priorityColors[$task['priority']->value] }}; border-radius: 50%; margin-right: 4px; vertical-align: middle;"></span>{{ $task['priority']->value }}
</td>
</tr>
</table>
</td>
</tr>
</table>

<!-- Meta Grid (Table based for reliability) -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<!-- Left Column -->
<td width="50%" valign="top" style="padding-right: 8px;">
<p style="margin: 0 0 4px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
Created By
</p>
<p style="margin: 0 0 10px 0; font-size: 13px; font-weight: 500; color: #374151;">
{{ $task['created_by'] }}
</p>
<p style="margin: 0 0 4px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
Assigned By
</p>
<p style="margin: 0 0 0 0; font-size: 13px; font-weight: 500; color: #374151;">
{{ $task['assigned_by'] }}
</p>
</td>
<!-- Right Column -->
<td width="50%" valign="top" style="padding-left: 8px;">
<p style="margin: 0 0 4px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
Assigned To</p>
<p style="margin: 0 0 10px 0; font-size: 13px; font-weight: 500; color: #374151;">
{{ $task['assigned_to'] }}
</p>
@if ($task['due_at'])
<p style="margin: 0 0 4px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
Due At
</p>
<p style="margin: 0 0 0 0; font-size: 13px; font-weight: 500; color: #374151;">
{{ $task['due_at'] }}
</p>
@endif
</td>
</tr>
</table>

@if ($task['badges']->isNotEmpty())
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 12px;">
<tr>
<td align="right">
<table border="0" cellpadding="0" cellspacing="0" align="right">
<tr>
@foreach ($task['badges'] as $badge)
<td style="padding-left: {{ $loop->first ? 0 : 6 }}px;">
<table border="0" cellpadding="0" cellspacing="0" style="background-color: #eef2ff; border: 1px solid #e0e7ff; border-radius: 12px;">
<tr>
<td style="padding: 3px 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 700; color: {{ $priorityColors['Medium'] }}; letter-spacing: 0.5px; white-space: nowrap; line-height: 12px;">
{{ $badge }}
</td>
</tr>
</table>
</td>
@endforeach
</tr>
</table>
</td>
</tr>
</table>
@endif
</td>
</tr>
</table>
