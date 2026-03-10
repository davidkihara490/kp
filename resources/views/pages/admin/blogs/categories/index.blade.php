@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Categories') }}
@endsection
@section('page-sub-title')
    {{ __('Blog Categories') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.categories.blog-categories />
    </div>
@endsection
