@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Create Parcel Handling Assistant') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.parcel-handling-assistants.create-parcel-handling-assistant />
    </div>
@endsection
