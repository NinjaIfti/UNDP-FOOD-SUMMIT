<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogController extends Controller
{
    private function getBlogSettings(): array
    {
        if (Storage::exists('admin/blog-settings.json')) {
            return json_decode(Storage::get('admin/blog-settings.json'), true);
        }
        return ['blog_enabled' => false];
    }

    public function index(Request $request)
    {
        $settings = $this->getBlogSettings();
        if (empty($settings['blog_enabled'])) {
            abort(404);
        }

        $posts = Blog::published()
            ->orderByDesc('published_at')
            ->paginate(12);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug)
    {
        $settings = $this->getBlogSettings();
        if (empty($settings['blog_enabled'])) {
            abort(404);
        }

        $post = Blog::published()->where('slug', $slug)->firstOrFail();
        $post->incrementViews();

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }
}
