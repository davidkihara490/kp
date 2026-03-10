@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Tags') }}
@endsection
@section('page-sub-title')
    {{ __('Create Blog Tag') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.tags.create-blog-tag />
    </div>
@endsection
