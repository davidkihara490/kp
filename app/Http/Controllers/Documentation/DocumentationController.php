<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    public function index(): View
    {
        return view('docs.index', [
            'documents' => $this->documents(),
        ]);
    }

    public function show(string $slug): View
    {
        $documents = $this->documents();
        $document = $documents->get($slug);

        abort_if($document === null, 404);

        $html = Str::markdown(File::get($document['path']), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('docs.show', [
            'documents' => $documents,
            'document' => $document,
            'html' => $html,
        ]);
    }

    private function documents(): Collection
    {
        $directory = resource_path('docs');

        if (! File::isDirectory($directory)) {
            return collect();
        }

        $documents = collect();

        foreach (File::files($directory) as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $slug = $file->getBasename('.md');

            if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $slug)) {
                continue;
            }

            $path = $file->getPathname();
            $markdown = File::get($path);

            preg_match('/^#\h+(.+)$/m', $markdown, $heading);

            $documents->put($slug, [
                'slug' => $slug,
                'title' => isset($heading[1])
                    ? trim($heading[1])
                    : Str::headline($slug),
                'path' => $path,
            ]);
        }

        return $documents->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE);
    }
}