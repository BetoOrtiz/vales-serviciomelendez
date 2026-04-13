<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
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
        }

        $request->session()->flash(
            "error",
            "No cuenta con los permisos necesarios para realizar esta acción."
        );

        return redirect()->back();
    }

    public function search($filtro, Request $request)
    {
        $user = \Auth::user();

        if ($user->can("configuraciones.index")) {
            if ($request->has("orderBy")) {
                $orderBy = $request->orderBy;
                $configuraciones = Configuracion::filtro($filtro)
                    ->orderBy($orderBy["field"], $orderBy["sort"])
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            } else {
                $orderBy = ["field" => "id", "sort" => "asc"];
                $configuraciones = Configuracion::filtro($filtro)
                    ->paginate(10)
                    ->onEachSide(1)
                    ->appends(request()->query());
            }

            return Inertia::render(
                "Configuraciones",
                compact("configuraciones", "filtro", "orderBy")
            );
        }

        return redirect()
            ->back()
            ->with(
                "error",
                "No cuenta con los permisos necesarios para realizar esta acción."
            );
    }

    public function store(Request $request)
    {
        $user = \Auth::user();

        if ($user->can("configuraciones.store.sadmin")) {
            $datos = $request->all();

            Validator::make($datos, [
                "clave" => ["required", "string", "max:255"],
                "valor" => ["required", "string", "max:255"],
                "tipo"  => ["required", "string", "max:255"],
            ])->validate();

            $configuracion = Configuracion::create([
                "clave" => $datos["clave"],
                "valor" => $datos["valor"],
                "tipo"  => $datos["tipo"],
            ]);

            return redirect()
                ->back()
                ->with("success", "Configuración creada con éxito")
                ->with("passData", $configuracion);
        }

        return redirect()
            ->back()
            ->with(
                "error",
                "No cuenta con los permisos necesarios para realizar esta acción."
            );
    }

    public function update(Request $request, $id)
    {
        $user = \Auth::user();

        if (!$user->can("configuraciones.update")) {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "No cuenta con los permisos necesarios para realizar esta acción."
                );
        }

        $datos = $request->all();
        $configuracion = Configuracion::findOrFail($id);

        if ($request->input("tipo") === "Imagen") {
            $file = $request->file("valor");

            if ($file) {
                $timestamp = time();
                $fileName = $timestamp . "." . $file->getClientOriginalName();
                $path = public_path("config/img");

                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }

                $file->move($path, $fileName);
                $configuracion->valor = "/config/img/" . $fileName;

                if ($configuracion->clave === "Acceso / Logo") {
                    $this->updateEnvValue("APP_LOGO", $configuracion->valor);
                }

                if ($configuracion->clave === "Barra de Navegación / Logo") {
                    $this->updateEnvValue("APP_LOGO_NAV", $configuracion->valor);
                }
            }
        } else {
            Validator::make($datos, [
                "valor" => ["required", "string", "max:255"],
            ])->validate();

            $configuracion->valor = $request->input("valor");
        }

        $configuracion->save();

        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return redirect()
            ->back()
            ->with("success", "Configuración actualizada con éxito")
            ->with("passData", $configuracion);
    }

    public function destroy($id)
    {
        $user = \Auth::user();

        if ($user->can("configuraciones.destroy.sadmin")) {
            $configuracion = Configuracion::findOrFail($id);
            $configuracion->delete();

            return redirect()
                ->back()
                ->with("success", "Configuración eliminada con éxito");
        }

        return redirect()
            ->back()
            ->with(
                "error",
                "No cuenta con los permisos necesarios para realizar esta acción."
            );
    }

    private function updateEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        $pattern = "/^" . preg_quote($key, "/") . "=.*$/m";
        $replacement = $key . '="' . addcslashes($value, '\\"') . '"';

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= PHP_EOL . $replacement;
        }

        file_put_contents($envPath, $envContent);
    }
}