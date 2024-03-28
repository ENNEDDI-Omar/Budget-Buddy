<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ExpensesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/expenses",
     * tags={"Expenses"},
     *    summary="Get all expenses",
     *  description="Get all expenses",
     *    @OA\Response(
     *       response=200,
     *      description="Success",
     * ),
     * )
     */
    public function index()
    {
        $myExpenses = auth()->user()->expenses;
        return response()->json($myExpenses);
    }

    /**
     * @OA\Post( 
     *    path="/api/expenses",
     *   tags={"Expenses"},
     *  summary="Create a new expense",
     * description="Create a new expense",
     * @OA\RequestBody(
     *   required=true,
     * @OA\MediaType(
     *    mediaType="application/json",
     *  @OA\Schema(
     *   @OA\Property(
     *     property="user_id",
     *   type="integer",
     * format="int64",
     * ),
     * @OA\Property(
     *    property="name",
     *  type="string",
     * ),
     * @OA\Property(
     *   property="amount",
     * type="number",
     * ),
     * @OA\Property(
     *   property="date",
     * type="string",
     * format="date",
     * ),
     * ),
     * ),
     * ),
     * @OA\Response(
     *  response=200,
     * description="Success",
     * ),
     * )
     */

    public function store(Request $request)
    {

        // Valider les données de la requête
        $request->validate([
            'user_id' => 'exists:users,id',
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // Créer une nouvelle dépense pour l'utilisateur authentifié
        $user = auth()->user();
        $expense = $user->expenses()->create([
            'user_id' => $request->user()->id,
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'date' => $request->input('date'),
        ]);

        // Retourner la dépense créée en tant que réponse JSON
        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense,
        ], 201);
    }


    public function show(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *    path="/api/expenses/{expense}",
     *  tags={"Expenses"},
     * summary="Update an expense",
     * description="Update an expense",
     * @OA\Parameter(
     *  name="expense",
     * in="path",
     * description="ID of the expense",
     * required=true,
     * @OA\Schema(
     *  type="integer",
     * format="int64",
     * ),
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     * property="user_id",
     *    type="integer",
     * format="int64",
     * ),
     * @OA\Property(
     * property="name",
     * type="string",
     * ),
     * @OA\Property(
     * property="amount",
     * type="number",
     * ),
     * @OA\Property(
     * property="date",
     * type="string",
     * format="date",
     * ),
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * ),
     * )
     */

    public function update(Request $request, Expense $expense)
    {
       $this->authorize('update', $expense);

        // Valider les données de la requête
        $request->validate([
            'user_id' => 'exists:users,id',
            'name' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
        ]);

        $expense->update([
            'user_id' => $request->user()->id,
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'date' => $request->input('date'),
        ]);

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{expense}",
     *     tags={"Expenses"},
     *     summary="Delete an expense",
     *     description="Delete an expense",
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         description="ID of the expense",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully',
        ], 204);
    }
}
