<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('ticker-logo.svg')}}" class="logo" alt="Ticker Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
