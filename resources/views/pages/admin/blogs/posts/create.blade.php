@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Posts') }}
@endsection
@section('page-sub-title')
    {{ __('Create Blog Post') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.blog-posts.create-blog-post />
    </div>
@endsection
