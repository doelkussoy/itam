<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Asset;
use Spatie\Permission\Models\Role;

class AssetFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'User']);
    }

    public function test_admin_can_access_assets_page()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get('/assets');
        $response->assertStatus(200);
    }

    public function test_user_can_access_assets_page()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $response = $this->actingAs($user)->get('/assets');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_assets_page()
    {
        $response = $this->get('/assets');
        $response->assertRedirect('/login');
    }
}
