<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * One generic CRUD controller driven by config/admin.php
 */
class ResourceController extends Controller
{
    protected function cfg(string $resource): array
    {
        return config("admin.resources.$resource") ?? abort(404);
    }

    protected function modelClass(array $cfg): string
    {
        return 'App\\Models\\'.$cfg['model'];
    }

    public function index(Request $request, string $resource)
    {
        $cfg = $this->cfg($resource);
        $model = $this->modelClass($cfg);

        $query = $model::query();
        foreach ($cfg['columns'] as $col) {
            if (str_contains($col[0], '.')) {
                $query->with(explode('.', $col[0])[0]);
            }
        }
        [$col, $dir] = $cfg['order'] ?? ['id', 'desc'];
        $items = $query->orderBy($col, $dir)->paginate(20);

        return view('admin.resource.index', compact('resource', 'cfg', 'items'));
    }

    public function create(string $resource)
    {
        $cfg = $this->cfg($resource);
        abort_if(($cfg['can_create'] ?? true) === false, 404);
        $item = new ($this->modelClass($cfg));

        return view('admin.resource.form', ['resource' => $resource, 'cfg' => $cfg, 'item' => $item, 'options' => $this->options($cfg)]);
    }

    public function store(Request $request, string $resource)
    {
        $cfg = $this->cfg($resource);
        abort_if(($cfg['can_create'] ?? true) === false, 404);
        $model = $this->modelClass($cfg);

        $data = $this->payload($request, $cfg, null);
        $model::create($data);

        return redirect()->route('admin.resource.index', $resource)->with('success', $cfg['singular'].' created.');
    }

    public function edit(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        abort_if(($cfg['can_edit'] ?? true) === false, 404);
        $item = $this->modelClass($cfg)::findOrFail($id);

        return view('admin.resource.form', ['resource' => $resource, 'cfg' => $cfg, 'item' => $item, 'options' => $this->options($cfg)]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        abort_if(($cfg['can_edit'] ?? true) === false, 404);
        $item = $this->modelClass($cfg)::findOrFail($id);

        $item->update($this->payload($request, $cfg, $item));

        return redirect()->route('admin.resource.index', $resource)->with('success', $cfg['singular'].' updated.');
    }

    public function destroy(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $item = $this->modelClass($cfg)::findOrFail($id);

        if (isset($item->image) && $item->image && ! str_starts_with($item->image, 'http')) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return back()->with('success', $cfg['singular'].' deleted.');
    }

    /** Validate + build the attribute array (handles checkboxes, images, slugs). */
    protected function payload(Request $request, array $cfg, $item): array
    {
        $rules = [];
        foreach ($cfg['fields'] as $f) {
            $rule = $f['rules'] ?? 'nullable';
            if ($f['type'] === 'faq_list') {
                $rules['faqs'] = 'nullable|array|max:30';
                $rules['faqs.*.question'] = 'nullable|string|max:255';
                $rules['faqs.*.answer'] = 'nullable|string|max:3000';
                continue;
            }
            if ($f['type'] === 'itinerary') {
                $rules['itinerary_days'] = 'nullable|array|max:100';
                $rules['itinerary_days.*.title'] = 'nullable|string|max:255';
                $rules['itinerary_days.*.content'] = 'nullable|string|max:10000';
                continue;
            }
            if ($f['type'] === 'image') {
                $rule = (($f['required_on_create'] ?? false) && ! $item ? 'required' : 'nullable').'|image|max:5120';
            }
            if ($f['type'] === 'checkbox') {
                $rule = 'nullable|boolean';
            }
            if ($f['type'] === 'images') {
                $rules[$f['name']] = 'nullable|array';
                $rules[$f['name'].'.*'] = 'image|max:5120';
                $rules['remove_'.$f['name']] = 'nullable|array';
                continue;
            }
            $rules[$f['name']] = $rule;
        }
        $validated = $request->validate($rules);

        $data = [];
        foreach ($cfg['fields'] as $f) {
            $name = $f['name'];
            switch ($f['type']) {
                case 'checkbox':
                    $data[$name] = $request->boolean($name);
                    break;
                case 'image':
                    if ($request->hasFile($name)) {
                        if ($item && $item->$name && ! str_starts_with($item->$name, 'http')) {
                            Storage::disk('public')->delete($item->$name);
                        }
                        $data[$name] = $request->file($name)->store($cfg['model'] ? Str::snake(Str::plural($cfg['model'])) : 'uploads', 'public');
                    }
                    break;
                case 'images':
                    $keep = collect($item?->$name ?? []);
                    $remove = $request->input('remove_'.$name, []);
                    foreach ($keep->filter(fn ($g) => in_array($g, $remove)) as $gone) {
                        if (! str_starts_with($gone, 'http')) {
                            Storage::disk('public')->delete($gone);
                        }
                    }
                    $keep = $keep->reject(fn ($g) => in_array($g, $remove))->values();
                    foreach ($request->file($name, []) as $file) {
                        $keep->push($file->store('gallery', 'public'));
                    }
                    $data[$name] = $keep->all();
                    break;
                case 'itinerary':
                    $days = collect($request->input('itinerary_days', []))
                        ->map(fn ($day) => [
                            'title' => trim((string) ($day['title'] ?? '')),
                            'content' => trim((string) ($day['content'] ?? '')),
                        ])
                        ->filter(fn ($day) => $day['title'] !== '' || $day['content'] !== '')
                        ->values()
                        ->all();
                    $data[$name] = $days ? json_encode($days, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
                    break;
                case 'faq_list':
                    $faqs = collect($request->input('faqs', []))
                        ->map(fn ($faq) => [
                            'question' => trim((string) ($faq['question'] ?? '')),
                            'answer' => trim((string) ($faq['answer'] ?? '')),
                        ])
                        ->filter(fn ($faq) => $faq['question'] !== '' && $faq['answer'] !== '')
                        ->values()
                        ->all();
                    $data[$name] = $faqs ? json_encode($faqs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
                    break;
                default:
                    $val = $validated[$name] ?? null;
                    $data[$name] = ($val === '' || $val === null) ? (($f['type'] === 'number' && $name === 'sort_order') ? 0 : null) : $val;
            }
        }

        // Auto slug
        if (array_key_exists('slug', $data) && empty($data['slug'])) {
            $base = Str::slug($data['title'] ?? $data['name'] ?? Str::random(6));
            $slug = $base; $i = 2;
            $model = $this->modelClass($cfg);
            while ($model::where('slug', $slug)->when($item, fn ($q) => $q->where('id', '!=', $item->id))->exists()) {
                $slug = $base.'-'.$i++;
            }
            $data['slug'] = $slug;
        }
        if (array_key_exists('published_at', $data) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if (array_key_exists('duration_nights', $data) && $data['duration_nights'] === null) {
            $data['duration_nights'] = 0;
        }

        return $data;
    }

    protected function options(array $cfg): array
    {
        $out = [];
        foreach ($cfg['fields'] as $f) {
            if (($f['type'] ?? '') === 'select' && isset($f['relation'])) {
                [$m, $label] = $f['relation'];
                $out[$f['name']] = ('App\\Models\\'.$m)::orderBy($label)->pluck($label, 'id')->all();
            }
        }
        return $out;
    }
}
