@props(['url'])
<tr>
    <td class="header">
        <a href="https://pra.moh.gov.tt/" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                {{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> --}}
                <p>Procurement Requisition Application</p>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
