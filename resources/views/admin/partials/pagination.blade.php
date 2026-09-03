@php
    $paginator = $paginator ?? null;
    $label = $label ?? 'records';
@endphp

@if($paginator && $paginator->total())
<div class="admin-pagination">
    <div class="text-muted small">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ number_format($paginator->total()) }} {{ $label }}
    </div>
    @if($paginator->hasPages())
        <div class="admin-pagination-links">
            {{ $paginator->links() }}
        </div>
    @endif
</div>
@endif
