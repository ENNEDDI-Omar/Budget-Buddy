<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_user_expenses()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;
        $expense = Expense::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
        ->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/expenses');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'name',
                'amount',
                'date',
                'user_id',
                'created_at',
                'updated_at',
                'id'
            ]
        ]);

        
    }

    public function testStoreExpense()
    {
        // Créer des données de test
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Effectuer une action de création (store) d'une dépense
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/expenses', [
            'name' => 'New Expense',
            'amount' => 100,
            'date' => '2024-03-30', // Exemple de date
            // Autres champs nécessaires pour créer une dépense
        ]);

        // Vérifier la réponse et l'état de la base de données après l'action de création
        $response->assertStatus(201); // Le code de statut 201 indique que la création a réussi
        $response->assertJsonFragment([
            'name' => 'New Expense',
            'amount' => 100,
            'date' => '2024-03-30',
            'user_id' => $user->id,
        ]); // Vérifie si les données créées correspondent aux données envoyées

    }

    

    public function testUpdateExpense()
    {
        // Créer des données de test
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $expense = Expense::factory()->create(['user_id' => $user->id]);

        // Effectuer une action de mise à jour (update) d'une dépense
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/expenses/' . $expense->id, [
            'name' => 'Updated Expense',
            'amount' => 200,
            'date' => '2024-03-31', // Exemple de date
            // Autres champs nécessaires pour mettre à jour une dépense
        ]);

        // Vérifier la réponse et l'état de la base de données après l'action de mise à jour
        $response->assertStatus(200); // Le code de statut 200 indique que la mise à jour a réussi
        $response->assertJsonFragment([
            'name' => 'Updated Expense',
            'amount' => 200,
            'date' => '2024-03-31',
            'user_id' => $user->id,
        ]); // Vérifie si les données mises à jour correspondent aux données envoyées
    }

    public function testDeleteExpense()
    {
        // Créer des données de test
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $expense = Expense::factory()->create(['user_id' => $user->id]);

        // Effectuer une action de suppression (destroy) d'une dépense
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/expenses/' . $expense->id);

        // Vérifier la réponse et l'état de la base de données après l'action de suppression
        $response->assertStatus(204); // Le code de statut 204 indique que la suppression a réussi
        $this->assertSoftDeleted('expenses', ['id' => $expense->id]); // Vérifie si la dépense a été supprimée de la base de données
    }

    
}
