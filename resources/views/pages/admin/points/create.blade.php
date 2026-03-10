@extends('components.layouts.master')
@section('page-title')
    {{ __('Pick Up And Drop Off Points') }}
@endsection
@section('page-sub-title')
    {{ __('Create Pick Up And Drop Off Point') }}
@endsection

@section('content')
    <div>
        <livewire:admin.pick-up-and-drop-off-points.create-pick-up-and-drop-off-point />
    </div>
@endsection
