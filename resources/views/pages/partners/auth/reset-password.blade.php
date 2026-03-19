@extends('pages.partners.layouts.master')

@section('partner-content')
    <div>
        <livewire:partners.auth.partner-reset-password  :token="$token"/>
    </div>
@endsection
