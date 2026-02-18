<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CommunicationsController extends Controller
{
    public function index()
    {
        $templates = $this->getTemplates();
        
        $stats = [
            'sent_today' => 0,
            'opened' => 0,
            'clicked' => 0,
        ];

        return Inertia::render('Admin/Communications', [
            'templates' => $templates,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:approval,reminder,confirmation,custom',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $templates = $this->getTemplates();

        $newTemplate = [
            'id' => 'TPL' . str_pad((string) (count($templates) + 1), 3, '0', STR_PAD_LEFT),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'last_modified' => now()->toISOString(),
        ];

        $templates[] = $newTemplate;
        $this->saveTemplates($templates);

        return redirect()->route('admin.communications.index')
            ->with('success', 'Email template created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:approval,reminder,confirmation,custom',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $templates = $this->getTemplates();
        $templates = array_map(function ($template) use ($id, $validated) {
            if ($template['id'] == $id) {
                return [
                    ...$template,
                    'name' => $validated['name'],
                    'type' => $validated['type'],
                    'subject' => $validated['subject'],
                    'body' => $validated['body'],
                    'last_modified' => now()->toISOString(),
                ];
            }
            return $template;
        }, $templates);

        $this->saveTemplates($templates);

        return redirect()->route('admin.communications.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function duplicate($id)
    {
        $templates = $this->getTemplates();
        $templateToDuplicate = collect($templates)->firstWhere('id', $id);

        if (!$templateToDuplicate) {
            return redirect()->route('admin.communications.index')
                ->with('error', 'Template not found.');
        }

        $newTemplate = [
            ...$templateToDuplicate,
            'id' => 'TPL' . str_pad((string) (count($templates) + 1), 3, '0', STR_PAD_LEFT),
            'name' => $templateToDuplicate['name'] . ' (Copy)',
            'last_modified' => now()->toISOString(),
        ];

        $templates[] = $newTemplate;
        $this->saveTemplates($templates);

        return redirect()->route('admin.communications.index')
            ->with('success', 'Template duplicated successfully.');
    }

    public function destroy($id)
    {
        $templates = $this->getTemplates();
        $templates = array_filter($templates, fn($template) => $template['id'] != $id);
        $this->saveTemplates(array_values($templates));

        return redirect()->route('admin.communications.index')
            ->with('success', 'Template deleted successfully.');
    }

    protected function getTemplates(): array
    {
        if (Storage::exists('admin/email-templates.json')) {
            return json_decode(Storage::get('admin/email-templates.json'), true) ?? [];
        }

        return [];
    }

    protected function saveTemplates(array $templates): void
    {
        Storage::put('admin/email-templates.json', json_encode($templates, JSON_PRETTY_PRINT));
    }
}
