@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('View Parcel Handling Assistant') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.parcel-handling-assistants.view-parcel-handling-assistant :id="$id" />
    </div>
@endsection
