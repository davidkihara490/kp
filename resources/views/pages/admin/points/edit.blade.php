@extends('components.layouts.master')
@section('page-title')
{{ __('Pick Up And Drop Off Points') }}
@endsection
@section('page-sub-title')
{{ __('Edit Pick Up And Drop Off Point') }}
@endsection

@section('content')
<div>
    <livewire:admin.pick-up-and-drop-off-points.edit-pick-up-and-drop-off-point id="$id" />
</div>
@endsection