<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'mesen@example.com',
            'password' => bcrypt('mesen123'),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'mesen@example.com',
            'password' => 'mesen123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        echo "✅ Login berhasil dengan kredensial yang benar.\n";
    }

    #[Test]
    public function user_cannot_login_if_inactive()
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('mesen123'),
            'status' => 'inactive',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'mesen123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();

        echo "✅ Gagal login karena status user tidak aktif.\n";
    }

    #[Test]
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'mesen@example.com',
            'password' => bcrypt('mesen123'),
            'status' => 'active',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'mesen@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        echo "✅ Gagal login karena password salah.\n";
    }
}
