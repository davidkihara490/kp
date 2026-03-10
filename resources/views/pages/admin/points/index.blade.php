@extends('components.layouts.master')
@section('page-title')
{{ __('Pick Up And Drop Off Points') }}
@endsection
@section('page-sub-title')
{{ __('Pick Up And Drop Off Points') }}
@endsection

@section('content')
<div>
    <livewire:admin.pick-up-and-drop-off-points.pick-up-and-drop-off-points />
</div>
@endsection