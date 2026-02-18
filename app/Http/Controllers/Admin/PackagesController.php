<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PackagesController extends Controller
{
    public function index()
    {
        $tiers = $this->getTiers();

        return Inertia::render('Admin/Packages', [
            'tiers' => $tiers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $tiers = $this->getTiers();

        $newTier = [
            'id' => (string) (count($tiers) > 0 ? max(array_map(fn($t) => (int) $t['id'], $tiers)) + 1 : 1),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => (float) $validated['price'],
            'active' => true,
        ];

        $tiers[] = $newTier;
        $this->saveTiers($tiers);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package tier created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $tiers = $this->getTiers();
        $tiers = array_map(function ($tier) use ($id, $validated) {
            if ($tier['id'] == $id) {
                return [
                    ...$tier,
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'price' => (float) $validated['price'],
                ];
            }
            return $tier;
        }, $tiers);

        $this->saveTiers($tiers);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package tier updated successfully.');
    }

    public function toggle($id)
    {
        $tiers = $this->getTiers();
        $tiers = array_map(function ($tier) use ($id) {
            if ($tier['id'] == $id) {
                return [
                    ...$tier,
                    'active' => !$tier['active'],
                ];
            }
            return $tier;
        }, $tiers);

        $this->saveTiers($tiers);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package tier status updated.');
    }

    public function destroy($id)
    {
        $tiers = $this->getTiers();
        $tiers = array_filter($tiers, fn($tier) => $tier['id'] != $id);
        $this->saveTiers(array_values($tiers));

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package tier deleted successfully.');
    }

    protected function getTiers(): array
    {
        if (Storage::exists('admin/packages.json')) {
            return json_decode(Storage::get('admin/packages.json'), true) ?? [];
        }

        return [
            [
                'id' => '1',
                'name' => 'Standard Package',
                'description' => 'Full summit access with meals and materials',
                'price' => 1500,
                'active' => true,
            ],
            [
                'id' => '2',
                'name' => 'Premium Package',
                'description' => 'Includes networking events and exclusive sessions',
                'price' => 2500,
                'active' => true,
            ],
            [
                'id' => '3',
                'name' => 'VIP Package',
                'description' => 'Complete experience with private meetings and luxury accommodation',
                'price' => 4500,
                'active' => true,
            ],
        ];
    }

    protected function saveTiers(array $tiers): void
    {
        Storage::put('admin/packages.json', json_encode($tiers, JSON_PRETTY_PRINT));
    }
}
