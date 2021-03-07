<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected $endpointURI='/api/users/';
    /**
     * Se debe poder obtener una lista de usuarios
     *
     * @return void
     */
    public function test_se_debe_poder_obtener_una_lista_de_usuarios()
    {
        $users=User::factory()->count(10)->create();
        $response = $this->get($this->endpointURI);

        $response->assertStatus(200);
        $response->assertSee($users->first()->usuario);
    }

    public function test_se_debe_poder_obtener_un_usuario()
    {
        $user=User::factory()->create();
        $response = $this->get($this->endpointURI.$user->id);

        $response->assertStatus(200);
        $response->assertSee($user->nombre);
        $response->assertSee($user->apellido);
        $response->assertSee($user->email);
        $response->assertSee($user->usuario);
    }


    public function test_se_debe_poder_crear_un_usuario()
    {
        $user=User::factory()->make();

        $response = $this->post($this->endpointURI, $user->toArray());

        $response->assertStatus(201);
        $response->assertSee($user->id);
        $response->assertSee($user->nombre);
        $response->assertSee($user->apellido);
        $response->assertSee($user->email);
        $response->assertSee($user->usuario);
    }


    public function test_se_debe_poder_actualizar_un_usuario()
    {
        $originalUser=User::factory()->create();
        $user=User::factory()->make();

        $response = $this->put($this->endpointURI.$originalUser->id, $user->toArray());

        $response->assertStatus(200);
        $response->assertSee($user->id);
        $response->assertSee($user->nombre);
        $response->assertSee($user->apellido);
        $response->assertSee($user->email);
        $response->assertSee($user->usuario);
    }

    /**
     * Borrado de usuario
     *
     * @return void
     */
    public function test_se_debe_poder_borrar_un_usuario()
    {
        $user=User::factory()->create();

        $response = $this->delete($this->endpointURI.$user->id);

        $response->assertStatus(204);

        /**
         * Agrego el control de que la base de datos quede vacia.
         */
        $this->assertTrue(User::count() == 0);
    }
}