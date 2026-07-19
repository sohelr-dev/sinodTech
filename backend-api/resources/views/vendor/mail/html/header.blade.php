@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'FNF Trip')
<img src="{{ config('mail.markdown.logo') }}" class="logo" alt="FNF TRIP">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
