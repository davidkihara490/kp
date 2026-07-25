@extends('pages.partners.layouts.dashboard')
@section('user-type')
{{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
{{ __('Password Change') }}
@endsection
@section('dashboard-content')
<div>
    <div style="overflow-x: auto; margin: 1rem 0;">
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #4361ee; color: white;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Weight Category</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Parcel Drop-off (Origin)</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Parcel Pick-up (Destination)</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px 16px;"><strong>0kgs – 5 Kgs</strong></td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 25</td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 25</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px 16px;"><strong>6kgs - 25 kgs</strong></td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 35</td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 35</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px 16px;"><strong>26kgs – 50kgs</strong></td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 45</td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 45</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px 16px;"><strong>51kgs to 75 kgs</strong></td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 60</td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 60</td>
                </tr>
                <tr>
                    <td style="padding: 12px 16px;"><strong>75kgs to 100 kgs</strong></td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 75</td>
                    <td style="padding: 12px 16px; color: #10b981; font-weight: 600;">Kesh 75</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection