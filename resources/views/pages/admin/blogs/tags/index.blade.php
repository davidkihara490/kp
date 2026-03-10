@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Tags') }}
@endsection
@section('page-sub-title')
    {{ __('Blog Tags') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.tags.blog-tags />
    </div>
@endsection
