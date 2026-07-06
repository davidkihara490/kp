<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Karibu Parcels · Delivery Note</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet" />
    <style>
        :root {
            --ink: #12241d;
            --forest: #0e4632;
            --forest-deep: #0a3526;
            --sage-bg: #f2f6f3;
            --sage-line: #dde6e0;
            --paper: #210e0e;
            --slate: #5c6b66;
            --amber: #c78a3d;
            --amber-soft: #f7ecdb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            background: var(--sage-bg);
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--ink);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 26px 16px;
        }

        .sheet {
            width: 100%;
            max-width: 860px;
            background: var(--paper);
            border-radius: 18px;
            box-shadow: 0 30px 60px -20px rgba(10, 53, 38, 0.25);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--sage-line);
        }

        /* ===== HEADER: logo + parcel number, centered ===== */
        .head {
            background: linear-gradient(135deg, var(--forest) 0%, var(--forest-deep) 100%);
            color: #fff;
            padding: 28px 34px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.10);
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }

        .brand-name small {
            display: block;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            font-size: 11px;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 4px;
        }

        .parcel-number {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: 20px;
            letter-spacing: 0.6px;
            background: rgba(255, 255, 255, 0.12);
            padding: 8px 28px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: inline-block;
            margin-top: 4px;
        }

        .parcel-number .tag {
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: var(--amber);
            color: #241505;
            padding: 3px 14px;
            border-radius: 20px;
            margin-left: 12px;
            vertical-align: middle;
        }

        /* ===== BODY ===== */
        .body {
            padding: 30px 34px 22px;
        }

        /* ---------- TWO COLUMN: FROM / TO ---------- */
        .from-to-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 30px;
            align-items: start;
        }

        .stop h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--forest);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stop h3 .idx {
            background: var(--sage-bg);
            border: 1px solid var(--sage-line);
            color: var(--slate);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 2px 9px;
            border-radius: 20px;
        }

        .stop .town {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 19px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 14px;
        }

        .field {
            display: flex;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px dashed var(--sage-line);
        }

        .field:last-of-type {
            border-bottom: none;
        }

        .field .label {
            width: 64px;
            flex-shrink: 0;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--slate);
            padding-top: 1px;
        }

        .field .value {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink);
            line-height: 1.45;
        }

        .field .value.mono {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 13px;
        }

        .status-pill {
            margin-top: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--sage-bg);
            border: 1px solid var(--sage-line);
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 12px;
            color: var(--ink);
        }

        .status-pill .led {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--forest);
            flex-shrink: 0;
        }

        .status-pill strong {
            color: var(--forest);
        }

        /* ===== STAT STRIP ===== */
        .stat-strip {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border: 1px solid var(--sage-line);
            border-radius: 12px;
            overflow: hidden;
        }

        .stat {
            padding: 14px 20px;
            border-right: 1px solid var(--sage-line);
            background: var(--paper);
        }

        .stat:last-child {
            border-right: none;
            background: var(--amber-soft);
        }

        .stat .k {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--slate);
            margin-bottom: 5px;
        }

        .stat .v {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
        }

        .stat:last-child .v {
            color: var(--forest);
        }

        /* ===== FOOTER ===== */
        .foot {
            margin-top: 20px;
            padding-top: 14px;
            border-top: 1px solid var(--sage-line);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: var(--slate);
        }

        .foot .stamp {
            font-family: 'IBM Plex Mono', monospace;
            color: var(--forest);
            font-weight: 600;
        }

        /* responsive: stack on small screens */
        @media (max-width: 640px) {
            .from-to-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .stat-strip {
                grid-template-columns: 1fr;
            }

            .stat {
                border-right: none;
                border-bottom: 1px solid var(--sage-line);
            }

            .stat:last-child {
                border-bottom: none;
            }

            .head {
                padding: 20px 18px;
            }

            .brand-name {
                font-size: 20px;
            }

            .parcel-number {
                font-size: 16px;
                padding: 6px 18px;
            }

            .logo-icon {
                width: 44px;
                height: 44px;
                font-size: 24px;
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                background: #fff;
                padding: 0;
            }

            .sheet {
                box-shadow: none;
                border: 1px solid #ddd;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="sheet">

        @php
        $logoPath = public_path('logo.jpeg');
        $logoExists = file_exists($logoPath);
        @endphp

        <!-- HEADER: logo + parcel number, both centered prominently -->
        <div class="head">
            <div class="logo-container">
                <div class="logo-icon">
                    <!-- <img src="{{ $logoPath }}" class="logo" alt="Karibu Parcels"> -->
                </div>
                <div class="brand-name">
                    Karibu Parcels
                </div>
            </div>
            <div class="parcel-number">
                {{ $parcel->parcel_id }}
            </div>
        </div>

        <div class="body">
            <div class="from-to-grid">
                <div class="stop">
                    <h3>From <span class="idx">Sender</span></h3>
                    <div class="town">Nairobi · CBD</div>
                    <div class="field">
                        <span class="label">Point</span>
                        <span class="value">Karibu Hub, Moi Avenue</span>
                    </div>
                    <div class="field">
                        <span class="label">Phone</span>
                        <span class="value mono">+254 712 345 678</span>
                    </div>
                    <div class="field">
                        <span class="label">Address</span>
                        <span class="value">Moi Avenue, 3rd floor, Suite 12, Nairobi</span>
                    </div>
                    <div class="status-pill">
                        <span class="led"></span>
                        <span><strong>Pick-up ready</strong> · Mon–Fri, 8am–6pm</span>
                    </div>
                </div>

                <div class="stop">
                    <h3>To <span class="idx">Receiver</span></h3>
                    <div class="town">Mombasa · Central</div>
                    <div class="field">
                        <span class="label">Point</span>
                        <span class="value">Karibu Depot, Digo Road</span>
                    </div>
                    <div class="field">
                        <span class="label">Phone</span>
                        <span class="value mono">+254 798 654 321</span>
                    </div>
                    <div class="field">
                        <span class="label">Address</span>
                        <span class="value">Digo Road, near post office, Mombasa</span>
                    </div>
                    <div class="status-pill">
                        <span class="led"></span>
                        <span><strong>Delivery window</strong> · 24–48 hrs</span>
                    </div>
                </div>
            </div>

            <div class="stat-strip">
                <div class="stat">
                    <div class="k">Weight</div>
                    <div class="v">3.2 kg</div>
                </div>
                <div class="stat">
                    <div class="k">Tariff</div>
                    <div class="v">KSh 1,450 + 120/kg</div>
                </div>
                <div class="stat">
                    <div class="k">Est. arrival</div>
                    <div class="v">26 Jul 2026</div>
                </div>
            </div>

            <div class="foot">
                <span>© Karibu Parcels · secure & reliable</span>
                <span class="stamp">Invoice #KP-2026-07-06</span>
            </div>
        </div>
    </div>
</body>

</html>