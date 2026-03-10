@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Tags') }}
@endsection
@section('page-sub-title')
    {{ __( 'View Blog Tag') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.tags.view-blog-tag :id="$id" />
    </div>
@endsection
