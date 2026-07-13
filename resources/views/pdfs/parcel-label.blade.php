<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Karibu Parcels · Delivery Note</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        @page {
            size: A4;
            margin: 15mm 12mm;
            background-color: #ffffff;
        }

        body {
          margin: 20px;
            color: #1a1a1a;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #ffffff;
            font-size: 10pt;
            line-height: 1.5;
        }

        .invoice-box {
            width: 100%;
            display: block;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            padding-bottom: 5mm;
            border-bottom: 2px solid #1a1a1a;
        }
        .brand {
            display: table-cell;
            font-size: 20pt;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #1a1a1a;
            vertical-align: bottom;
        }
        .header-right {
            display: table-cell;
            text-align: right;
            vertical-align: bottom;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 800;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: #000000;
        }

        /* ROUTE */
        .route-section {
            padding: 6mm 0 4mm;
            border-bottom: 1px solid #e8e8e8;
        }
        .route-table {
            display: table;
            width: 100%;
        }
        .route-city {
            display: table-cell;
            width: 30%;
            vertical-align: middle;
        }
        .route-city.left {
            text-align: left;
        }
        .route-city.right {
            text-align: right;
        }
        .route-city .code {
            font-size: 22pt;
            font-weight: 700;
            line-height: 1.1;
            color: #1a1a1a;
        }
        .route-city .name {
            font-size: 8.5pt;
            font-weight: 500;
            color: #666;
            margin-top: 2px;
        }
        .route-line-cell {
            display: table-cell;
            width: 40%;
            vertical-align: middle;
            text-align: center;
        }
        .route-line {
            height: 2px;
            background: #d0d0d0;
            position: relative;
            margin: 0 10px;
        }
        .route-line .plane {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 0 8px;
            font-size: 12pt;
            color: #1a1a1a;
            line-height: 1;
        }
        .route-meta {
            display: table;
            width: 100%;
            margin-top: 4mm;
            font-size: 7.5pt;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #888;
        }
        .route-meta-cell {
            display: table-cell;
            width: 33.33%;
        }
        .route-meta-cell.center {
            text-align: center;
        }
        .route-meta-cell.right {
            text-align: right;
        }
        .route-meta b {
            color: #1a1a1a;
            font-weight: 700;
        }

        /* TWO COLUMN LAYOUT */
        .two-col-table {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e8e8e8;
        }
        .col {
            display: table-cell;
            width: 50%;
            padding: 5mm 0;
            vertical-align: top;
        }
        .col.left {
            padding-right: 6mm;
        }
        .col.right {
            padding-left: 6mm;
            border-left: 1px solid #e8e8e8;
        }
        .col-title {
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 3mm;
        }
        .col-title .badge {
            font-size: 6.5pt;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #888;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 1px 8px;
            float: right;
        }
        .col-town {
            font-size: 13pt;
            font-weight: 700;
            margin-bottom: 3mm;
            color: #1a1a1a;
        }
        .field {
            display: table;
            width: 100%;
            padding: 3px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .field:last-child {
            border-bottom: none;
        }
        .field-label {
            display: table-cell;
            width: 60px;
            font-size: 6.5pt;
            font-weight: 600;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #888;
            vertical-align: top;
            padding-top: 2px;
        }
        .field-value {
            display: table-cell;
            font-size: 9.5pt;
            font-weight: 500;
            vertical-align: top;
        }
        .status {
            margin-top: 4mm;
            padding: 4px 10px;
            background: #f5f0eb;
            border-radius: 4px;
            font-size: 8.5pt;
            display: block;
        }
        .status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #b8631f;
            display: inline-block;
            margin-right: 6px;
            vertical-align: middle;
        }
        .status b {
            font-weight: 700;
            color: #1a1a1a;
        }

        /* STATS */
        .stats-table {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e8e8e8;
        }
        .stat {
            display: table-cell;
            width: 33.33%;
            padding: 4mm 0;
            text-align: center;
            border-right: 1px solid #e8e8e8;
            vertical-align: middle;
        }
        .stat:last-child {
            border-right: none;
            background: #f8f8f8;
        }
        .stat-label {
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 2px;
        }
        .stat-value {
            font-size: 14pt;
            font-weight: 700;
            color: #1a1a1a;
        }

        /* SIGNATURE & FOOTER AREA */
        .bottom-section {
            margin-top: 15mm;
        }
        
        .signature-container {
            text-align: right;
            margin-bottom: 15mm;
        }
        .signature {
            font-size: 9pt;
            display: inline-block;
        }
        .signature .line {
            display: inline-block;
            width: 150px;
            border-bottom: 1px solid #1a1a1a;
            margin-left: 10px;
            vertical-align: bottom;
        }

        .footer-table {
            display: table;
            width: 100%;
            padding-top: 4mm;
            border-top: 1px solid #e8e8e8;
        }
        .barcode-cell {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }
        .barcode {
            display: block;
            height: 20px;
        }
        .barcode span {
            display: inline-block;
            width: 2px;
            background: #1a1a1a;
            margin-right: 1px;
            vertical-align: bottom;
        }
        .footer-meta-cell {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
            font-size: 7.5pt;
            color: #888;
            line-height: 1.4;
        }
        .footer-meta-cell .inv {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 8.5pt;
        }
    </style>
</head>
<body>
<div class="invoice-box">

    <div class="header">
        <div class="brand">
            Karibu Parcels
        </div>
        <div class="header-right">
            <div class="doc-title">{{ $parcel->parcel_id }}</div>
        </div>
    </div>

    <div class="route-section">
        <div class="route-table">
            <div class="route-city left">
                <div class="code">{{ $parcel->senderTown->name }}</div>
            </div>
            <div class="route-line-cell">
                <div class="route-line">
                    <span class="plane">to</span>
                </div>
            </div>
            <div class="route-city right">
                <div class="code">{{ $parcel->receiverTown->name }}</div>
            </div>
        </div>
    </div>

    <div class="two-col-table">
        <div class="col left">
            <div class="col-title">
                From
            </div>
            <div class="col-town">{{ $parcel->senderTown->name }}</div>
            <div class="field">
                <span class="field-label">Point</span>
                <span class="field-value">{{ $parcel->senderPickUpDropOffPoint->name }}</span>
            </div>
            <div class="field">
                <span class="field-label">Phone</span>
                <span class="field-value">{{ $parcel->senderPickUpDropOffPoint->contact_phone_number }}</span>
            </div>
            <div class="field">
                <span class="field-label">Address</span>
                <span class="field-value">{{ $parcel->senderPickUpDropOffPoint->address }}</span>
            </div>
        </div>
        <div class="col right">
            <div class="col-title">
                To
            </div>
            <div class="col-town">{{ $parcel->receiverTown->name }}</div>
            <div class="field">
                <span class="field-label">Point</span>
                <span class="field-value">{{ $parcel->deliveryStation->name }}</span>
            </div>
            <div class="field">
                <span class="field-label">Phone</span>
                <span class="field-value">{{ $parcel->deliveryStation->contact_phone_number }}</span>
            </div>
            <div class="field">
                <span class="field-label">Address</span>
                <span class="field-value">{{ $parcel->deliveryStation->adress }}</span>
            </div>
        </div>
    </div>

    <div>
      <hr>
    </div>

</div>
</body>
</html>