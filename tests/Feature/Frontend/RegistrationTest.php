<?php

namespace Tests\Feature\Frontend;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Class RegistrationTest
 *
 * @package Tests\Feature\Frontend
 */
class RegistrationTest extends TestCase
{

    /** @test */
    public function the_register_route_exists()
    {
        $this->get('/register')->assertStatus(200);
    }

    /** @test */
    public function validation_is_required()
    {
        $response = $this->post('/register');

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function email_must_be_unique()
    {
        factory(User::class)->create(['email' => 'john@example.com']);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function passwords_must_be_equivalent()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'not_the_same',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function user_registration_can_be_disabled()
    {
        config(['boilerplate.access.user.registration' => false]);

        $this->get('/register')->assertStatus(404);
    }

    /** @test */
    public function a_user_can_register_an_account()
    {
        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'OC4Nzu270N!QBVi%U%qX',
            'password_confirmation' => 'OC4Nzu270N!QBVi%U%qX',
        ])->assertRedirect(route(homeRoute()));

        $user = resolve(UserService::class)
            ->where('email', 'john@example.com')
            ->firstOrFail();

        $this->assertSame($user->name, 'John Doe');
        $this->assertTrue(Hash::check('OC4Nzu270N!QBVi%U%qX', $user->password));
    }
}
