@props([
    'sortable' => true,
    'direction' => null,  // asc | desc
])

@php
$sortableClass = $sortable ? 'table__th--sortable' : '';
@endphp

<th {{ $attributes->merge(['class' => $sortableClass]) }}
    @if($sortable) role="columnheader" aria-sort="{{ $direction ?? 'none' }}" tabindex="0" @endif>
    {{ $slot }}
</th>
