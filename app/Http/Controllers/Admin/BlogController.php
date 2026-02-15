<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $query = Blog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $posts = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $stats = [
            'total' => Blog::count(),
            'published' => Blog::where('status', 'published')->count(),
            'drafts' => Blog::where('status', 'draft')->count(),
            'views' => Blog::sum('views'),
        ];

        return Inertia::render('Admin/Blog/Index', [
            'posts' => $posts,
            'stats' => $stats,
            'blogEnabled' => $settings['blog_enabled'] ?? false,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Blog/Form', [
            'post' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['slug'] = $this->uniqueSlug($slug);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Blog::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/Blog/Form', [
            'post' => $blog,
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
            'delete_image' => 'nullable|boolean',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['slug'] = $this->uniqueSlug($slug, $blog->id);

        if ($request->boolean('delete_image') && $blog->image) {
            if (Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        if ($validated['status'] === 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        unset($validated['delete_image']);
        $blog->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate(['blog_enabled' => 'required|boolean']);

        $settings = $this->getBlogSettings();
        $settings['blog_enabled'] = $request->boolean('blog_enabled');
        Storage::put('admin/blog-settings.json', json_encode($settings, JSON_PRETTY_PRINT));

        return back()->with('success', 'Blog visibility updated.');
    }

    private function uniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $query = Blog::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        if ($query->exists()) {
            return $slug . '-' . Str::random(5);
        }
        return $slug;
    }

    private function storeImage($file): string
    {
        $path = $file->store('blog', 'public');
        return '/storage/' . $path;
    }
}
