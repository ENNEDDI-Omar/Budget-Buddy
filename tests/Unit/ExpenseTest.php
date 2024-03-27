<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Artisan;

class ExpenseTest extends TestCase
{
    
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }

    public function test_user_and_expense()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $expense = Expense::factory()->create(['user_id' =>$user->id]);

        $reponse = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('/api/expenses/');

        $reponse->assertStatus(200);
        $reponse->assertJsonFragment([
         'name' => $expense->name,
         'amount' => $expense->amount,
         'date' => $expense->date,
         'user_id' => $expense->user_id
        ]);    
    }

}
