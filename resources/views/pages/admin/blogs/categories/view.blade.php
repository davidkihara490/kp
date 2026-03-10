@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Categories') }}
@endsection
@section('page-sub-title')
    {{ __('View Blog Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.categories.view-blog-category :id="$id" />
    </div>
@endsection
