@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img
                    class="mx-auto h-20 w-auto"
                    src="{{ asset('icons/warehouse-stock-svgrepo-com.svg') }}"
                    alt="Invex Logo"
/>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>


