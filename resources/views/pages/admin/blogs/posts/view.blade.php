@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Posts') }}
@endsection
@section('page-sub-title')
    {{ __('View Blog Post') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.blog-posts.view-blog-post :id="$id" />
    </div>
@endsection
