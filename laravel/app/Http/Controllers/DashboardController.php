<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $claims = auth()->user()
            ->claims()
            ->with(['document', 'payment'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('dashboard.index', compact('claims'));
    }
}
