<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authentication API | Karibu Parcels</title>

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: #192a22;
            font: 16px/1.6 system-ui, sans-serif;
        }
        main {
            max-width: 960px;
            margin: 0 auto;
            padding: 2rem 1rem 5rem;
            overflow-wrap: anywhere;
        }
        h1, h2, a { color: #006e32; }
        h2 {
            margin-top: 2.5rem;
            border-bottom: 1px solid #dce6df;
        }
        pre {
            padding: 1rem;
            overflow-x: auto;
            background: #f2f6f3;
            border-radius: 6px;
        }
        table {
            display: block;
            overflow-x: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: .6rem .8rem;
            border: 1px solid #dce6df;
            text-align: left;
            vertical-align: top;
        }
        th { background: #edf5ef; }
    </style>
</head>
<body>
    <main>
        {!! \Illuminate\Support\Str::markdown(
            \Illuminate\Support\Facades\File::get(resource_path('docs/auth.md')),
            ['html_input' => 'strip', 'allow_unsafe_links' => false]
        ) !!}
    </main>
</body>
</html>