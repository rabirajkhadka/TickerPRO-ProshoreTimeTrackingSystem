<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Proshore')
                <img src="https://proshore.eu/wp-content/uploads/2021/06/logo-badge-20s.svg" class="logo"
                    alt="Proshore Logo">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
