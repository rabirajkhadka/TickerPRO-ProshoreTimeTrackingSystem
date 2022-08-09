<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Proshore')
<img src="{{asset('proshore-logo.svg')}}" class="logo" alt="Proshore Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
