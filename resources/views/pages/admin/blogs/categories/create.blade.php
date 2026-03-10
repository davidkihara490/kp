@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Categories') }}
@endsection
@section('page-sub-title')
    {{ __('Create Blog Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.categories.create-blog-category />
    </div>
@endsection
