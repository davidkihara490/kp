@if($sortField !== $field)
    <i class="fas fa-sort text-muted"></i>
@elseif($sortDirection === 'asc')
    <i class="fas fa-sort-up"></i>
@else
    <i class="fas fa-sort-down"></i>
@endif