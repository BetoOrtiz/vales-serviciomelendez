<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Fortify\Rules\Password;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();
        if ($user->can("usuarios.index")) {
            $filtro = null;
            if ($request->has("orderBy")) {
                $orderBy = $request->orderBy;
                $usuarios = User::orderBy($orderBy["field"], $orderBy["sort"])
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            } else {
                $orderBy = ["field" => "id", "sort" => "asc"];
                $usuarios = User::paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            }

            return Inertia::render(
                "Usuarios",
                compact("usuarios", "filtro", "orderBy")
            );
        } else {
            $request
                ->session()
                ->flash(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );

            return redirect()->back();
        }
    }

    public function search($filtro, Request $request)
    {
        $user = \Auth::user();
        if ($user->can("usuarios.index")) {
            if ($request->has("orderBy")) {
                $orderBy = $request->orderBy;
                $usuarios = $usuarios = User::filtro($filtro)
                    ->orderBy($orderBy["field"], $orderBy["sort"])
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            } else {
                $orderBy = ["field" => "id", "sort" => "asc"];
                $usuarios = $usuarios = User::filtro($filtro)
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            }

            return Inertia::render(
                "Usuarios",
                compact("usuarios", "filtro", "orderBy")
            );
        } else {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );
        }
    }

    public function filter(Request $request)
    {
        $user = \Auth::user();
        if ($user->can("usuarios.index")) {
            $filtros = $request->filtros;

            $orderBy = ["field" => "id", "sort" => "asc"];
            if ($request->has("orderBy")) {
                $orderBy = $request->orderBy;
            }
            //dd($filtros, $orderBy);
            $usuarios = User::filtros($filtros)
                ->orderBy($orderBy["field"], $orderBy["sort"])
                ->paginate(10)
                ->onEachSide(1)
                ->appends(request()->query());

            return Inertia::render(
                "Usuarios",
                compact("usuarios", "filtros", "orderBy")
            );
        } else {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );
        }
    }

    public function store(Request $request)
    {
        $user = \Auth::user();
        if ($user->can("usuarios.store")) {
            $datos = $request->all();
            $validado = Validator::make($datos, [
                "name" => ["required", "string", "max:255"],
                "email" => [
                    "required",
                    "string",
                    "email",
                    "max:255",
                    "unique:users",
                ],
                "password" => ["required", "string", new Password()],
            ])->validate();

            $usuario = User::create([
                "name" => $datos["name"],
                "titulo" => $datos["titulo"],
                "email" => $datos["email"],
                "password" => Hash::make($datos["password"]),
            ]);

            $usuario->assignRole(request("rol") ? request("rol") : "Usuario");

            return redirect()
                ->back()
                ->with("success", "Usuario creado con éxito")
                ->with("passData", $usuario);
        } else {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );
        }
    }

    public function update(Request $request, $id)
    {
        $user = \Auth::user();
        if ($user->can("usuarios.update")) {
            $datos = $request->all();
            $validado = Validator::make($datos, [
                "name" => ["required", "string", "max:255"],
                "email" => ["required", "string", "email", "max:255"],
                "password" => ["nullable", "string", new Password()],
            ])->validate();
            $usuario = User::find($id);
            $usuario->name = request("name");
            $usuario->titulo = request("titulo");
            $usuario->email = request("email");
            if ($datos["password"] != null) {
                $usuario->password = Hash::make($datos["password"]);
            }
            $usuario->roles()->detach();
            $usuario->assignRole(request("rol") ? request("rol") : "Usuario");
            $usuario->save();

            return redirect()
                ->back()
                ->with("success", "Usuario actualizado con éxito")
                ->with("passData", $usuario);
        } else {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );
        }
    }

    public function destroy($id)
    {
        $user = \Auth::user();
        if ($user->can("usuarios.destroy")) {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()
                ->back()
                ->with("success", "Usuario eliminado con éxito");
        } else {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );
        }
    }
}
