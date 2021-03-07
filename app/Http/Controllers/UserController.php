<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Devuelve la lista de todos los usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Crea un usuario nuevo y lo guarda en sistema.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user=User::create($request->only(['nombre','apellido', 'email', 'usuario']));

        if(!$user){
            return response('No se pudo guardar el usuario, espere unos segundos e intente de nuevo', 400);
        }

        return response( $user, 201 );
    }

    /**
     * Devuelve el usuario especificado.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Acutaliza los datos del usuario.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update($request->only(['nombre','apellido', 'email', 'usuario']));
        } catch (Exception $e) {
            return response('No se pudo modificar el usuario, espere unos segundos e intente de nuevo', 400);
        }

        return $user;
    }

    /**
     * Elimina al usuario del sistema.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(! $user->delete() ){
            return response('No se pudo borrar el usuario, espere unos segundos e intente de nuevo', 500);
        }

        return response('',204);
    }
}
