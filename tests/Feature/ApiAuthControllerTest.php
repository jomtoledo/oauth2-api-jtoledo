<?php 

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\PassportSeeder;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use App\Models\User;

class ApiAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $userPassword;
    private $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PassportSeeder::class);

        $this->userPassword = 'password';
        $this->user = User::factory()->create(['password' => bcrypt($this->userPassword)]);
    }

    /** @test */
    public function it_can_register_a_user()
    {
        $newUser = User::factory()->make();
        $res = $this->postJson('/api/register', [
            'name' => $newUser->name,
            'email' => $newUser->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $res->assertStatus(201)
            ->assertJson([
                'name' => $newUser->name,
                'email' => $newUser->email,
            ]);

        // Verify user is created in the database
        $this->assertDatabaseHas('users', [
            'email' => $newUser->email,
        ]);
    }

    /** @test */
    public function it_cannot_register_a_user_with_duplicate_email()
    {
        $user = User::factory()->make();
        
        User::factory()->create(['email' => $user->email]);

        $res = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $res->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_login_a_user()
    {
        $res = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => $this->userPassword,
        ]);

        $res->assertStatus(200)
            ->assertJsonStructure(['token']);
        
        // Optionally assert that the token is stored in the database
        $this->assertDatabaseHas('oauth_access_tokens', [
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_cannot_login_with_invalid_credentials()
    {
        User::factory()->create(['email' => 'john@example.com', 'password' => bcrypt('password')]);

        $res = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $res->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        Passport::actingAs($this->user, 'api');

        $res = $this->postJson('/api/logout', ['Authorization' => 'Bearer ' . $this->user->userToken]);

        $res->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
        
        // Assert that the token is revoked
        // $this->assertTrue($this->user->tokens()->where('id', $token->revoked);
    }

    /** @test */
    public function it_cannot_logout_without_authentication()
    {
        $res = $this->postJson('/api/logout');

        $res->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
