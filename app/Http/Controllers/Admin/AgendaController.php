<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Display a listing of agendas.
     */
    public function index(Request $request): View
    {
        $query = Agenda::query()
            ->orderBy('start_date', 'desc');

        // Filter by month/year
        if ($request->has('month') && $request->month) {
            $query->whereMonth('start_date', $request->month);
        }
        if ($request->has('year') && $request->year) {
            $query->whereYear('start_date', $request->year);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $agendas = $query->with('author')->paginate(15)->withQueryString();

        // Get unique years and months for filter
        $years = Agenda::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.agendas.index', compact('agendas', 'years'));
    }

    /**
     * Show the form for creating a new agenda.
     */
    public function create(): View
    {
        return view('admin.agendas.create');
    }

    /**
     * Store a newly created agenda.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'sender_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'event_time_text' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);
        
        // Map content to description for database
        if (isset($validated['content'])) {
            $validated['description'] = $validated['content'];
            unset($validated['content']);
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Check if slug exists and make it unique
        $count = Agenda::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . time();
        }
        
        // Set author
        $validated['author_id'] = auth()->id();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'agendas/' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($image));
            $validated['image'] = $filename;
        }
        
        // Format dates
        if (isset($validated['start_date'])) {
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d H:i:s');
        }
        if (isset($validated['end_date'])) {
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d H:i:s');
        }
        if (isset($validated['published_at'])) {
            $validated['published_at'] = Carbon::parse($validated['published_at'])->format('Y-m-d H:i:s');
        }

        // Create agenda
        Agenda::create($validated);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dibuat.');
    }

    /**
     * Show the form for editing an agenda.
     */
    public function edit(int $id): View
    {
        $agenda = Agenda::findOrFail($id);

        return view('admin.agendas.edit', compact('agenda'));
    }

    /**
     * Update an agenda.
     */
    public function update(Request $request, int $id)
    {
        $agenda = Agenda::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'sender_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'event_time_text' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);
        
        // Map content to description for database
        if (isset($validated['content'])) {
            $validated['description'] = $validated['content'];
            unset($validated['content']);
        }

        // Update slug if title changed
        if ($agenda->title !== $validated['title']) {
            $newSlug = Str::slug($validated['title']);
            $count = Agenda::where('slug', $newSlug)->where('id', '!=', $id)->count();
            if ($count > 0) {
                $newSlug = $newSlug . '-' . time();
            }
            $validated['slug'] = $newSlug;
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($agenda->image && Storage::disk('public')->exists($agenda->image)) {
                Storage::disk('public')->delete($agenda->image);
            }
            
            $image = $request->file('image');
            $filename = 'agendas/' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($image));
            $validated['image'] = $filename;
        }
        
        // Format dates
        if (isset($validated['start_date'])) {
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d H:i:s');
        }
        if (isset($validated['end_date'])) {
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d H:i:s');
        }
        if (isset($validated['published_at'])) {
            $validated['published_at'] = Carbon::parse($validated['published_at'])->format('Y-m-d H:i:s');
        }

        // Update agenda
        $agenda->update($validated);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Remove an agenda.
     */
    public function destroy(int $id)
    {
        $agenda = Agenda::findOrFail($id);
        
        // Delete image if exists
        if ($agenda->image && Storage::disk('public')->exists($agenda->image)) {
            Storage::disk('public')->delete($agenda->image);
        }

        $agenda->delete();

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
