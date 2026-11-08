@if($records->hasPages())
<nav class="pagination" aria-label="Pagination">
@if($records->onFirstPage())<span>Previous</span>@else<a href="{{ $records->previousPageUrl() }}" rel="prev">Previous</a>@endif
<span>Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</span>
@if($records->hasMorePages())<a href="{{ $records->nextPageUrl() }}" rel="next">Next</a>@else<span>Next</span>@endif
</nav>
@endif
