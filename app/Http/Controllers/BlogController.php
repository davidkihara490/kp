<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogTag;

class BlogController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(9); // 9 posts per page (3x3 grid)

        $categories = BlogCategory::withCount('posts')
            ->whereHas('posts', function ($query) {
                $query->where('status', 'published');
            })
            ->orderBy('posts_count', 'desc')
            ->get();

        $popularPosts = BlogPost::where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        $tags = BlogTag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        return view('blogs.index', compact('blogPosts', 'categories', 'popularPosts', 'tags'));
    }
    public function show($slug)
    {
        $post = BlogPost::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        $post->increment('views_count');

        $relatedPosts = BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(3)
            ->get();
        return view('blogs.view', compact('post', 'relatedPosts'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $blogPosts = BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = BlogCategory::withCount('posts')->get();
        $popularPosts = BlogPost::where('status', 'published')->orderBy('views', 'desc')->limit(5)->get();
        $tags = BlogTag::withCount('posts')->orderBy('posts_count', 'desc')->limit(10)->get();

        return view('blogs.index', compact('blogPosts', 'categories', 'popularPosts', 'tags', 'search'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $blogPosts = BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = BlogCategory::withCount('posts')->get();
        $popularPosts = BlogPost::where('status', 'published')->orderBy('views_count', 'desc')->limit(5)->get();
        $tags = BlogTag::withCount('posts')->orderBy('posts_count', 'desc')->limit(10)->get();

        return view('blogs.index', compact('blogPosts', 'categories', 'popularPosts', 'tags', 'category'));
    }

    public function tag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $blogPosts = $tag->posts()
            ->with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = BlogCategory::withCount('posts')->get();
        $popularPosts = BlogPost::where('status', 'published')->orderBy('views', 'desc')->limit(5)->get();
        $tags = BlogTag::withCount('posts')->orderBy('posts_count', 'desc')->limit(10)->get();

        return view('blogs.index', compact('blogPosts', 'categories', 'popularPosts', 'tags', 'tag'));
    }
}
