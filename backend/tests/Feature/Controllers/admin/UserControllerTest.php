<?php

namespace Tests\Feature\Controllers\admin;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the necessary permissions exist
        $viewUsersPermission = Permission::firstOrCreate(['name' => 'view-users', 'guard_name' => 'web']);
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage-users', 'guard_name' => 'web']);

        // Create the admin role and assign the necessary permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([$viewUsersPermission, $manageUsersPermission]);

        // Create an admin user and assign the role
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        // Authenticate the admin user
        $this->actingAs($admin);
    }

    public function test_admin_can_view_users()
    {
        // Ensure there is a user to view
        $user = User::factory()->create();

        // Perform the request
        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users'
            ]);
    }

    public function test_admin_can_create_user()
    {
        Mail::fake();

        $response = $this->postJson('/api/admin/users', [
            'username' => 'johndoe',
            'password' => 'secret123',
            'role_id' => 1,
            'email' => 'john@example.com'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'username' => 'johndoe'
            ]);

        $user = User::where('email', 'john@example.com')->first();

        Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->is($user);
        });
    }

    public function test_admin_can_update_user()
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/admin/users/{$user->id}", [
            'username' => 'johnupdated',
            'email' => 'johnupdated@example.com',
            'role_id' => 1
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'johnupdated'
            ]);
    }

    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/admin/users/{$user->id}");

        $response->assertStatus(204);
    }
}
