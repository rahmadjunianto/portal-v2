<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * Display a listing of agendas.
     */
    public function index(Request $request)
    {
        $query = Agenda::published();

        // Filter by month if provided
        if ($request->has('month') && $request->month) {
            $query->whereMonth('start_date', date('m', strtotime($request->month)))
                  ->whereYear('start_date', date('Y', strtotime($request->month)));
        }

        // Order by start date
        $agendas = $query->orderBy('start_date', 'asc')->paginate(9);

        return view('agenda', compact('agendas'));
    }

    /**
     * Display the specified agenda.
     */
    public function show($slug)
    {
        $agenda = Agenda::where('slug', $slug)->firstOrFail();

        // Increment views
        $agenda->increment('views');

        return view('agenda-detail', compact('agenda'));
    }
}
