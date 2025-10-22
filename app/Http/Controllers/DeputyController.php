<?php

namespace App\Http\Controllers;

use App\Models\Deputy;
use App\Http\Requests\SearchDeputyRequest;
use Illuminate\View\View;

class DeputyController extends Controller
{
    public function index(SearchDeputyRequest $request): View
    {
        $search = $request->validated()['search'] ?? null;

        $deputies = Deputy::query()
            ->withCount('expenses')
            ->withSum('expenses', 'value')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('party', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20);

        return view('deputies.index', [
            'deputies' => $deputies,
            'search' => $search
        ]);
    }

    public function show(Deputy $deputy): View
    {
        $expenses = $deputy->expenses()
            ->orderByDesc('date')
            ->paginate(25);

        $totalExpenses = $deputy->expenses()->sum('value');
        $averageExpense = $deputy->expenses()->avg('value');

        return view('deputies.show', [
            'deputy' => $deputy,
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'averageExpense' => $averageExpense
        ]);
    }
}