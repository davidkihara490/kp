@extends('docs.layout')

@section('title', 'API Documentation')

@section('content')
    <h1>API documentation</h1>

    @if($documents->isEmpty())
        <p>No API guides have been published yet.</p>
    @else
        <ul>
            @foreach($documents as $guide)
                <li>
                    <a href="{{ route('docs.show', $guide['slug']) }}">
                        {{ $guide['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection