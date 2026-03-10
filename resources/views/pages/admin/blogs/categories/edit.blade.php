@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Categories') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Blog Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.categories.edit-blog-category :id="$id" />
    </div>
@endsection
