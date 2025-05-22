<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;

class CompanyTest extends TestCase
{
    use RefreshDatabase; // Resets database for each test

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user for testing authenticated routes
        $this->user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => bcrypt('password'),
        ]);
    }

    /** @test */
    public function a_guest_cannot_view_companies()
    {
        $this->get('/companies')->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_view_companies()
    {
        $this->actingAs($this->user)
             ->get('/companies')
             ->assertStatus(200)
             ->assertSee('List of Companies');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_company()
    {
        $this->actingAs($this->user)
             ->post('/companies', [
                 'name' => 'Test Company',
                 'email' => 'test@company.com',
                 'website' => 'http://testcompany.com',
             ])
             ->assertRedirect('/companies')
             ->assertSessionHas('success', 'Company created successfully.');

        $this->assertDatabaseHas('companies', ['name' => 'Test Company']);
    }

    // Add more tests for update, delete, validation, etc.
}
