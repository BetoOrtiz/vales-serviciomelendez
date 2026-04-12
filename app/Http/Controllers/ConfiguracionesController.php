<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ConfiguracionesController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();
        if ($user->can("configuraciones.index")) {
            $filtro = null;
            if ($request->has("orderBy")) {
                $orderBy = $request->orderBy;
                $configuraciones = Configuracion::orderBy(
                    $orderBy["field"],
                    $orderBy["sort"]
                )
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            } else {
                $orderBy = ["field" => "id", "sort" => "asc"];
                $configuraciones = Configuracion::paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            }

            return Inertia::render(
                "Configuraciones",
                compact("configuraciones", "filtro", "orderBy")
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
        if ($user->can("configuraciones.index")) {
            if ($request->has("orderBy")) {
                $orderBy = $request->orderBy;
                $configuraciones = $configuraciones = Configuracion::filtro(
                    $filtro
                )
                    ->orderBy($orderBy["field"], $orderBy["sort"])
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            } else {
                $orderBy = ["field" => "id", "sort" => "asc"];
                $configuraciones = $configuraciones = Configuracion::filtro(
                    $filtro
                )
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            }

            return Inertia::render(
                "Configuraciones",
                compact("configuraciones", "filtro", "orderBy")
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
        if ($user->can("configuraciones.store.sadmin")) {
            $datos = $request->all();
            $validado = Validator::make($datos, [
                "clave" => ["required", "string", "max:255"],
                "valor" => ["required", "string", "max:255"],
                "tipo" => ["required", "string", "max:255"],
            ])->validate();

            $configuracion = Configuracion::create([
                "clave" => $datos["clave"],
                "valor" => $datos["valor"],
                "tipo" => $datos["tipo"],
            ]);

            return redirect()
                ->back()
                ->with("success", "Configuración creada con éxito")
                ->with("passData", $configuracion);
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
        if ($user->can("configuraciones.update")) {
            $datos = $request->all();
            $configuracion = Configuracion::find($id);
            if (request("tipo") == "Imagen") {
                $file = $request->file("valor");
                if ($file) {
                    $timestamp = time();
                    $fileName =
                        $timestamp . "." . $file->getClientOriginalName();
                    $path = public_path() . "/config/img/";
                    $file->move($path, $fileName);
                    $configuracion->valor = "/config/img/" . $fileName;

                    if ($configuracion->clave == "Acceso / Logo") {
                        // Modifica el logo de acceso al sistema en el archivo .env
                        $file = file_get_contents(base_path(".env"));
                        $file = str_replace(
                            "APP_LOGO=\"" . env("APP_LOGO") . "\"",
                            "APP_LOGO=\"" . $configuracion->valor . "\"",
                            $file
                        );
                        file_put_contents(base_path(".env"), $file);
                    }
                    if ($configuracion->clave == "Barra de Navegación / Logo") {
                        // Modifica el logo de acceso al sistema en el archivo .env
                        $file = file_get_contents(base_path(".env"));
                        $file = str_replace(
                            "APP_LOGO_NAV=\"" . env("APP_LOGO_NAV") . "\"",
                            "APP_LOGO_NAV=\"" . $configuracion->valor . "\"",
                            $file
                        );
                        file_put_contents(base_path(".env"), $file);
                    }
                }
            } else {
                $validado = Validator::make($datos, [
                    "valor" => ["required", "string", "max:255"],
                ])->validate();
                $configuracion->valor = request("valor");
            }
            $configuracion->save();
            return redirect()
                ->back()
                ->with("success", "Configuración actualizada con éxito")
                ->with("passData", $configuracion);
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
        if ($user->can("configuraciones.destroy.sadmin")) {
            $user = Configuracion::findOrFail($id);
            $user->delete();

            return redirect()
                ->back()
                ->with("success", "Configuración eliminada con éxito");
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
