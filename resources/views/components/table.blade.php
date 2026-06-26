@props([
    'striped' => false,
    'hover' => false,
    'compact' => false,
    'stickyHeader' => false,
    'empty' => null,
])

@php
$stripedClass = $striped ? 'table--striped' : '';
$hoverClass = $hover ? 'table--hover' : '';
$compactClass = $compact ? 'table--compact' : '';
$stickyClass = $stickyHeader ? 'table--sticky-header' : '';
@endphp

<div class="table-wrapper">
    <table {{ $attributes->merge(['class' => 'table ' . $stripedClass . ' ' . $hoverClass . ' ' . $compactClass . ' ' . $stickyClass]) }}>
        @if(isset($header))
        <thead>
            {{ $header }}
        </thead>
        @endif

        <tbody>
            @if(trim($slot))
                {{ $slot }}
            @elseif($empty)
                <tr>
                    <td colspan="100%" class="table__empty">{{ $empty }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
