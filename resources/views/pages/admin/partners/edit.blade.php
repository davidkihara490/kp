@extends('components.layouts.master')
@section('page-title')
    {{ __('Partners') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Partner') }}
@endsection

@section('content')
    <div>
        <livewire:admin.partners.edit-partner  :id="$id"/>
    </div>
@endsection
