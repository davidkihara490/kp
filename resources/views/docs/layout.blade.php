<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'API Documentation') | PUDO Express</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: #203329;
            background: #fff;
            font: 16px/1.6 system-ui, sans-serif;
        }

        header {
            padding: 1rem max(1.25rem, calc((100vw - 1120px) / 2));
            background: #008f40;
        }

        header a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .docs {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 2.5rem;
            max-width: 1120px;
            margin: 2rem auto;
            padding: 0 1.25rem;
        }

        nav a {
            display: block;
            padding: .45rem .75rem;
            border-radius: .4rem;
            color: #244a33;
            text-decoration: none;
        }

        nav a:hover,
        nav a[aria-current="page"] {
            background: #e8f5ed;
            color: #005c29;
        }

        nav a[aria-current="page"] { font-weight: 700; }
        main { min-width: 0; overflow-wrap: anywhere; }
        h1, h2, h3 { line-height: 1.25; }
        h1 { margin-top: 0; }
        h2 { margin-top: 2rem; }
        main a { color: #006b30; }

        pre {
            overflow-x: auto;
            padding: 1rem;
            background: #f3f6f4;
            border-radius: .5rem;
        }

        table {
            display: block;
            max-width: 100%;
            overflow-x: auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #dbe5df;
            padding: .55rem .75rem;
            text-align: left;
            vertical-align: top;
        }

        th { background: #eef6f0; }

        @media (max-width: 720px) {
            .docs { grid-template-columns: 1fr; gap: 1.5rem; }
            nav { border-bottom: 1px solid #dbe5df; padding-bottom: 1rem; }
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('docs.index') }}">PUDO Express Developer Docs</a>
    </header>

    <div class="docs">
        <nav aria-label="Documentation">
            <a href="{{ route('docs.index') }}"
               @if(request()->routeIs('docs.index')) aria-current="page" @endif>
                All guides
            </a>

            @foreach($documents as $guide)
                <a href="{{ route('docs.show', $guide['slug']) }}"
                   @if(request()->route('slug') === $guide['slug']) aria-current="page" @endif>
                    {{ $guide['title'] }}
                </a>
            @endforeach
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>