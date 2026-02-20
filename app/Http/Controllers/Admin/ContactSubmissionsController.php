<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactSubmissionsController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactSubmission::query()->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter') && $request->filter === 'unread') {
            $query->where('read', false);
        }

        $submissions = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => ContactSubmission::count(),
            'unread' => ContactSubmission::where('read', false)->count(),
            'read' => ContactSubmission::where('read', true)->count(),
        ];

        return Inertia::render('Admin/ContactSubmissions/Index', [
            'submissions' => $submissions,
            'stats' => $stats,
            'filters' => $request->only(['search', 'filter']),
        ]);
    }

    public function show(ContactSubmission $contact_submission)
    {
        $contact_submission->update(['read' => true]);

        return Inertia::render('Admin/ContactSubmissions/Show', [
            'submission' => $contact_submission,
        ]);
    }

    public function markAsRead(ContactSubmission $contact_submission)
    {
        $contact_submission->update(['read' => true]);

        return back()->with('success', 'Marked as read.');
    }

    public function markAsUnread(ContactSubmission $contact_submission)
    {
        $contact_submission->update(['read' => false]);

        return back()->with('success', 'Marked as unread.');
    }

    public function destroy(ContactSubmission $contact_submission)
    {
        $contact_submission->delete();

        return redirect()->route('admin.contact-submissions.index')
            ->with('success', 'Contact submission deleted.');
    }
}
