@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Edit Pick Up and Drop Off Point') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.pickup-and-drop-off-points.edit-pick-up-and-drop-off-point  :id="$id" />
    </div>
@endsection
