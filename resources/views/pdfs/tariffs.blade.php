<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Karibu Parcels - Delivery Tariff</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #008f40;
            padding-bottom: 10px;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        h1 {
            color: #008f40;
            font-size: 18px;
            margin: 5px 0;
        }

        .subtitle {
            color: #666;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .date {
            color: #999;
            font-size: 8px;
            text-align: right;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8px;
        }

        th {
            background-color: #008f40;
            color: white;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #ccc;
            font-weight: bold;
        }

        td {
            padding: 4px;
            text-align: center;
            border: 1px solid #ccc;
        }

        .source-cell {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #008f40;
            text-align: left;
        }

        .price-badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .base-price {
            background-color: #e8f5e9;
            color: #008f40;
        }

        .extra-price {
            background-color: #fff3e0;
            color: #ff9800;
        }

        .zones-table {
            margin-top: 30px;
            page-break-before: avoid;
        }

        .zones-table th {
            background-color: #006633;
        }

        .zone-name-cell {
            font-weight: bold;
            color: #008f40;
            background-color: #f5f5f5;
            text-align: left;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .alert-info {
            background-color: #e3f2fd;
            padding: 8px;
            border-radius: 4px;
            font-size: 8px;
            margin-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>

    <style>
        .tariff-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            /* Smaller font */
        }

        .tariff-table th {
            background: #008f40;
            color: white;
            padding: 4px 2px;
            /* Reduced padding */
            text-align: center;
            font-size: 6px;
            /* Very small for headers */
            font-weight: 600;
            word-break: break-word;
            /* Allow text wrapping */
        }

        .tariff-table td {
            padding: 3px 2px;
            font-size: 6px;
        }

        /* For zone names in headers */
        .zone-name {
            font-size: 6px;
            font-weight: 600;
            line-height: 1.2;
            max-width: 60px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- <img src="{{ asset('logo.jpeg') }}" class="logo" alt="Karibu Parcels"> -->
        @php
        $logoPath = public_path('logo.jpeg');
        $logoExists = file_exists($logoPath);
        @endphp

        @if($logoExists)
        <img src="{{ $logoPath }}" class="logo" alt="Karibu Parcels">
        @else
        <div style="font-size: 24px; font-weight: bold; color: #008f40;">Karibu Parcels</div>
        @endif
        <h1>{{ $company_name }} - Delivery Tariff</h1>
        <div class="subtitle">Nationwide Delivery Pricing Matrix</div>
        <div class="date">Generated on: {{ $generated_date }}</div>
    </div>

    <!-- Stats Section -->
    <table style="width: 100%; margin-bottom: 15px; border: none;">
        <tr>
            <td style="border: none; text-align: center; background-color: #f0f0f0; padding: 8px;">
                <strong>Total Zones:</strong> {{ $zones->count() }}
            </td>
            <td style="border: none; text-align: center; background-color: #f0f0f0; padding: 8px;">
                <strong>Pricing Routes:</strong> {{ $pricingItems->count() }}
            </td>
            <td style="border: none; text-align: center; background-color: #f0f0f0; padding: 8px;">
                <strong>Base Weight:</strong> 0-5 kg
            </td>
            <td style="border: none; text-align: center; background-color: #f0f0f0; padding: 8px;">
                <strong>Extra/kg:</strong> 40-90 KSh
            </td>
        </tr>
    </table>

    <!-- Pricing Matrix Table -->
    <table>
        <thead>
            <tr>
                <!-- <th style="width: 100px;">FROM / TO<br><small>(Base / Extra)</small></th> -->
                <th>FROM / TO<br><small>(Base / Extra)</small></th>

                @foreach($zones as $destination)
                <th>{{ $destination->name }}</th>
                <!-- <th style="min-width: 80px;">{{ $destination->name }}</th> -->
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($zones as $source)
            <tr>
                <td class="source-cell">
                    <strong>{{ $source->name }}</strong>
                </td>
                @foreach($zones as $destination)
                @php
                $pricing = $pricingItems->where('source_zone_id', $source->id)
                ->where('destination_zone_id', $destination->id)
                ->first();
                @endphp
                <td>
                    @if($pricing)
                    <div class="price-badge base-price">
                        KSh {{ number_format($pricing->cost) }}
                    </div>
                    <div style="margin-top: 2px;">
                        <span class="price-badge extra-price">
                            +{{ number_format($pricing->extra) }}/kg
                        </span>
                    </div>
                    @else
                    <span>—</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert-info">
        <strong>Pricing Guide:</strong> Base price shown for 0-5kg. Extra charge applies per kg beyond 5kg.
        <br><small>* Prices are in Kenyan Shillings (KSh) and subject to change.</small>
    </div>

    <div class="alert-info">
        <strong>VAT:</strong> .
        <br><small>All rices are subject to 16% VAT.</small>
    </div>


    <!-- Zones and Towns Table -->
    <div style="margin-top: 30px;">
        <h3 style="color: #008f40; font-size: 14px;">Zones and Covered Towns</h3>
        <table class="zones-table">
            <thead>
                <tr>
                    <th style="width: 30%; text-align: left;">Zone Name</th>
                    <th style="width: 70%; text-align: left;">Towns / Locations Covered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zones as $zone)
                <tr>
                    <td class="zone-name-cell">
                        {{ $zone->name }}
                    </td>
                    <td style="text-align: left;">
                        @php
                        $t = [];
                        $towns = $zone->towns;
                        foreach($towns as $town){
                        $t[] = $town->town->name;
                        }
                        @endphp

                        @if(count($towns) > 0)
                        {{ implode(', ', $t) }}
                        @else
                        <span class="text-muted">Main city center, Surrounding areas</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        {{ $company_name }} &copy; {{ date('Y') }} - Secure & Reliable
    </div>
</body>

</html>