@if(isset($breadcrumbs))
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $crumb)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
@endif