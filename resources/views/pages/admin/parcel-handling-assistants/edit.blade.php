@extends('components.layouts.master')
@section('page-title')
    {{ __('Parcel Handling Assistants') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Parcel Handling Assistant') }}
@endsection

@section('content')
    <div>
        <livewire:admin.parcel-handling-assistants.create-parcel-handling-assistant :id="$id" />
    </div>
@endsection
