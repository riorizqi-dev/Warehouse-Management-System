<?php

namespace App\Http\Controllers;

use App\Events\FeatureToggleUpdated;
use App\Models\FeatureToggle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureToggleController extends Controller
{
    public function index()
    {
        $this->authorizeSuperAdmin();
        $features = FeatureToggle::orderBy('name')->get();

        return view('admin.feature-toggles', compact('features'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'key' => 'required|string|max:100|unique:feature_toggles,key',
            'name' => 'required|string|max:150',
            'route_name' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        if (empty($data['route_name'])) {
            $data['route_name'] = 'feature.'.$data['key'];
        }

        $data['enabled'] = $request->has('enabled') ? (bool) $request->boolean('enabled') : true;

        $feature = FeatureToggle::create($data);

        event(new FeatureToggleUpdated($feature, 'created'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fitur ditambahkan.',
                'feature' => $feature,
            ]);
        }

        return back()->with('success', 'Fitur ditambahkan.');
    }

    public function update(Request $request, FeatureToggle $feature)
    {
        $this->authorizeSuperAdmin();

        $oldEnabled = $feature->enabled;

        $feature->update([
            'enabled' => $request->boolean('enabled'),
        ]);

        if ($oldEnabled !== $feature->enabled) {
            event(new FeatureToggleUpdated($feature, 'updated'));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status fitur diperbarui.',
                'feature' => $feature->fresh(),
            ]);
        }

        return back()->with('success', 'Status fitur diperbarui.');
    }

    public function destroy(FeatureToggle $feature)
    {
        $this->authorizeSuperAdmin();

        $featureKey = $feature->key;
        $feature->delete();

        event(new FeatureToggleUpdated((object) ['key' => $featureKey], 'deleted'));

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fitur dihapus.',
                'key' => $featureKey,
            ]);
        }

        return back()->with('success', 'Fitur dihapus.');
    }

    public function getEnabledFeatures(): JsonResponse
    {
        $features = FeatureToggle::where('enabled', true)
            ->whereNotNull('route_name')
            ->where('route_name', '!=', '')
            ->whereNotIn('key', ['dashboard'])
            ->orderBy('name')
            ->get(['id', 'key', 'name', 'route_name', 'icon']);

        return response()->json($features);
    }

    private function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->isSuperAdmin(), 403);
    }
}
