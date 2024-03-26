<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myExpenses = auth()->user()->expenses;
        return response()->json($myExpenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Valider les données de la requête
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
        ]);
    
        // Créer une nouvelle dépense pour l'utilisateur authentifié
        $expense = auth()->user()->expenses->create([
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
        ]);
    
        // Retourner la dépense créée en tant que réponse JSON
        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // $this->authorize('update', $expense);

        // Valider les données de la requête
        $request->validate([
            'name' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
        ]);
    
        $expense->update([
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
        ]);
    
        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
    
        return response()->json([
            'message' => 'Expense deleted successfully',
        ]);
    }
    
}
