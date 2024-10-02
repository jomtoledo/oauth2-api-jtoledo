<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\Customer;
use App\Models\User;

class ApiCustomerControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        Passport::actingAs($this->user);
        $customer = Customer::factory()->make();
        $res = $this->postJson('/api/customer', $customer->toArray());

        $res->assertStatus(201);
        $this->assertDatabaseHas('customers', ['email' => $customer->email]);
    }

    /** @test */
    public function it_can_list_customers()
    {
        Passport::actingAs($this->user);
        
        Customer::factory()->count(3)->create();

        $res = $this->getJson('/api/customers');

        $res->assertStatus(200);
        // Assert that the JSON response contains at least 3 items
        $this->assertGreaterThanOrEqual(3, count($res->json()));
    }

    /** @test */
    public function it_can_show_a_customer()
    {
        Passport::actingAs($this->user);

        $customer = Customer::factory()->create();

        $res = $this->actingAs($this->user)->getJson("/api/customer/{$customer->id}");

        $res->assertStatus(200)
            ->assertJson([
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'age' => $customer->age,
                'dob' => $customer->dob,
                'email' => $customer->email
            ]);
    }

     /** @test */
    public function it_can_update_a_customer()
    {
        Passport::actingAs($this->user);

        $customer = Customer::factory()->create();
        $updatedEmail = 'updated_' . $customer->email;

        $res = $this->actingAs($this->user)->putJson("/api/customer/{$customer->id}", [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'age' => 31,
            'dob' => '1989-02-01',
            'email' => $updatedEmail
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'message' => 'Customer updated successfully.',
                'customer' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'age' => 31,
                    'dob' => '1989-02-01',
                    'email' => $updatedEmail
                ]
            ]);
        $this->assertDatabaseHas('customers', ['email' => $updatedEmail]);
    }

    /** @test */
    public function it_can_delete_a_customer()
    {
        Passport::actingAs($this->user);
        $customer = Customer::factory()->create();

        $res = $this->actingAs($this->user)->deleteJson("/api/customer/{$customer->id}");

        $res->assertStatus(200)
            ->assertJson(['message' => 'Customer deleted successfully.']);

        $this->assertDatabaseMissing($customer);
    }
}