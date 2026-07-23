@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('logo.png') }}" class="logo" alt="{{ config('app.name') }} Logo" style="max-height: 45px; vertical-align: middle; border-radius: 4px;">
<span style="font-size: 22px; font-weight: bold; color: #2d3748; margin-left: 10px; vertical-align: middle; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  {{ config('app.name') }}
</span>
</a>
</td>
</tr>
