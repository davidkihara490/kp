@extends('components.layouts.master')
@section('page-title')
    {{ __('Parcel Handling Assistants') }}
@endsection
@section('page-sub-title')
    {{ __('Parcel Handling Assistants') }}
@endsection

@section('content')
    <div>
        <livewire:admin.parcel-handling-assistants.parcel-handling-assistants />
    </div>
@endsection
