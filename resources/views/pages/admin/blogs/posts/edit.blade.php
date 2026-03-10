@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Posts') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Blog Post') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.blog-posts.edit-blog-post :id="$id" />
    </div>
@endsection
