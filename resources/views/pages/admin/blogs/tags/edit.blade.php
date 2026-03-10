@extends('components.layouts.master')
@section('page-title')
    {{ __('Blog Tags') }}
@endsection
@section('page-sub-title')
    {{ __( 'Edit Blog Tag') }}
@endsection

@section('content')
    <div>
        <livewire:admin.blogs.tags.edit-blog-tag :id="$id" />
    </div>
@endsection
