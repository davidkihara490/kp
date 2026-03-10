@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Parcel Handling Assistants') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.parcel-handling-assistants.parcel-handling-assistants />
    </div>
@endsection
