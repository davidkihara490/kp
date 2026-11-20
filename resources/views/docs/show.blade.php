@extends('docs.layout')

@section('title', $document['title'])

@section('content')
    <article>
        {!! $html !!}
    </article>
@endsection