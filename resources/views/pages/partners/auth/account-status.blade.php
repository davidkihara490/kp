@extends('pages.partners.layouts.master')

@section('partner-content')
    <div>
        <livewire:partners.auth.account-status :id="$id"/>
    </div>
@endsection
