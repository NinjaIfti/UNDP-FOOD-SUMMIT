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

        $storePublicFile = static function ($file, string $dir, string $prefix): string {
            $ext = $file->getClientOriginalExtension() ?: 'pdf';
            $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            Storage::disk('public')->putFileAs($dir, $file, $filename);
            return '/storage/' . trim($dir, '/') . '/' . $filename;
        };

        $attachmentPdfs = [];
        foreach (array_keys($request->all()) as $key) {
            if (preg_match('/^attachment_pdf_\d+$/', $key) && $request->hasFile($key)) {
                $attachmentPdfs[] = $storePublicFile($request->file($key), 'communications', 'email_attachment');
            }
        }

        $newTemplate = [
            'id' => 'TPL' . str_pad((string) (count($templates) + 1), 3, '0', STR_PAD_LEFT),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'attachment_pdfs' => array_values($attachmentPdfs),
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
            'delete_attachment_pdf_paths' => 'nullable|array',
            'delete_attachment_pdf_paths.*' => 'nullable|string',
        ]);

        $templates = $this->getTemplates();

        $deletePublicFile = static function (?string $path): void {
            if (!$path || !is_string($path)) return;
            if (str_starts_with($path, '/storage/')) {
                $rel = ltrim(substr($path, strlen('/storage/')), '/');
                if ($rel !== '') {
                    Storage::disk('public')->delete($rel);
                }
                return;
            }
            if (str_starts_with($path, '/') && file_exists(public_path($path))) {
                @unlink(public_path($path));
            }
        };

        $storePublicFile = static function ($file, string $dir, string $prefix): string {
            $ext = $file->getClientOriginalExtension() ?: 'pdf';
            $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            Storage::disk('public')->putFileAs($dir, $file, $filename);
            return '/storage/' . trim($dir, '/') . '/' . $filename;
        };

        // Validate dynamic attachment file fields (attachment_pdf_0, attachment_pdf_1, ...)
        foreach (array_keys($request->all()) as $key) {
            if (preg_match('/^attachment_pdf_\d+$/', $key)) {
                $request->validate([
                    $key => 'nullable|file|mimes:pdf|max:10240',
                ]);
            }
        }

        $templates = array_map(function ($template) use ($id, $validated, $request, $deletePublicFile, $storePublicFile) {
            if ($template['id'] == $id) {
                $existing = [];
                if (isset($template['attachment_pdfs']) && is_array($template['attachment_pdfs'])) {
                    $existing = array_values(array_filter($template['attachment_pdfs'], fn ($p) => is_string($p) && $p !== ''));
                } elseif (!empty($template['attachment_pdf']) && is_string($template['attachment_pdf'])) {
                    // Backwards compat: migrate single field into array
                    $existing = [$template['attachment_pdf']];
                }

                $toDelete = $validated['delete_attachment_pdf_paths'] ?? [];
                if (!is_array($toDelete)) $toDelete = [];
                $toDelete = array_values(array_filter($toDelete, fn ($p) => is_string($p) && $p !== ''));

                $kept = array_values(array_filter($existing, fn ($p) => !in_array($p, $toDelete, true)));
                foreach ($toDelete as $path) {
                    $deletePublicFile($path);
                }

                $newOnes = [];
                foreach (array_keys($request->all()) as $key) {
                    if (preg_match('/^attachment_pdf_\d+$/', $key) && $request->hasFile($key)) {
                        $newOnes[] = $storePublicFile($request->file($key), 'communications', 'email_attachment');
                    }
                }

                $attachments = array_values(array_merge($kept, $newOnes));

                return [
                    ...$template,
                    'name' => $validated['name'],
                    'type' => $validated['type'],
                    'subject' => $validated['subject'],
                    'body' => $validated['body'],
                    'attachment_pdfs' => $attachments,
                    'attachment_pdf' => null,
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
