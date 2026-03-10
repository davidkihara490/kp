@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Posts') }}
@endsection
@section('page-sub-title')
    {{ __('Blog Post') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.blog-posts.blog-posts/>
    </div>
@endsection
