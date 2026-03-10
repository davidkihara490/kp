@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Pick Up and Drop Off Points') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.pickup-and-drop-off-points.pick-up-and-drop-off-points  />
    </div>
@endsection
