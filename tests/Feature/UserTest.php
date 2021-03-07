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


    /****************************** Camino "Feliz" ************************************/
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


    /***************************** Camino "No Feliz" ***********************************/

    /**
     * Nota: Dado que los nombres de los tests son sumamente descriptivos
     * para facilitar la lectura en caso de fallas, considero que los comentarios
     * con la descripcion de dichos tests son redundantes.
     */

    public function test_READ_se_debe_devolver_404_si_no_existe_el_usuario_solicitado()
    {
        $response = $this->get($this->endpointURI.'1');

        $response->assertStatus(404);
    }


    public function test_DELETE_se_debe_devolver_404_si_no_existe_el_usuario_solicitado()
    {
        $response = $this->delete($this->endpointURI.'1');

        $response->assertStatus(404);
    }

    public function test_CREATE_se_debe_devolver_error_si_los_datos_para_crear_el_usuario_no_son_validos()
    {
        $user=User::factory()->make();

        $user->nombre='A';

        $response = $this->post($this->endpointURI, $user->toArray());

        $response->assertStatus(400);
    }

    public function test_CREATE_se_debe_devolver_error_si_el_email_para_crear_el_usuario_no_es_valido()
    {
        $user=User::factory()->make();

        $user->email='A';

        $response = $this->post($this->endpointURI, $user->toArray());

        $response->assertStatus(400);
    }

    public function test_CREATE_se_debe_devolver_error_si_el_email_para_crear_el_usuario_esta_repetido()
    {
        $previousUser=User::factory()->create();

        $user=User::factory()->make();

        $user->email=$previousUser->email;

        $response = $this->post($this->endpointURI, $user->toArray());

        $response->assertStatus(400);
    }


    public function test_CREATE_se_debe_devolver_error_si_el_email_para_actualizar_el_usuario_esta_repetido()
    {
        $previousUser=User::factory()->create();

        $user=User::factory()->create();

        $user->email=$previousUser->email;

        $response = $this->put($this->endpointURI.$user->id, $user->toArray());

        $response->assertStatus(400);
    }


    public function test_UPDATE_se_debe_devolver_error_si_los_datos_para_actualizar_el_usuario_no_son_validos()
    {
        $user=User::factory()->create();

        $user->nombre='A';

        $response = $this->put($this->endpointURI.$user->id, $user->toArray());

        $response->assertStatus(400);
    }

    public function test_UPDATE_se_debe_devolver_error_si_el_email_para_actualizar_el_usuario_no_es_valido()
    {
        $user=User::factory()->create();

        $user->email='A';

        $response = $this->put($this->endpointURI.$user->id, $user->toArray());

        $response->assertStatus(400);
    }
}