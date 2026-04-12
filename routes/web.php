<?php
use App\Http\Controllers\AreasController;
use App\Http\Controllers\CapturaController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\EscritorioController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/", [EscritorioController::class, "index"])->name("escritorio");

    Route::group(["prefix" => "usuarios"], function () {
        Route::get("/", [UsuariosController::class, "index"])->name(
            "usuarios.index"
        );
        Route::post("/nuevo", [UsuariosController::class, "store"])->name(
            "usuarios.store"
        );
        Route::post("/edita/{id}", [UsuariosController::class, "update"])->name(
            "usuarios.update"
        );
        Route::post("/elimina/{id}", [
            UsuariosController::class,
            "destroy",
        ])->name("usuarios.destroy");
        Route::get("/buscar/{filtro}", [
            UsuariosController::class,
            "search",
        ])->name("usuarios.search");
        Route::get("/filtro", [UsuariosController::class, "filter"])->name(
            "usuarios.filter"
        );
    });

    Route::group(["prefix" => "configuraciones"], function () {
        Route::get("/", [ConfiguracionesController::class, "index"])->name(
            "configuraciones.index"
        );
        Route::post("/nuevo", [
            ConfiguracionesController::class,
            "store",
        ])->name("configuraciones.store");
        Route::post("/edita/{id}", [
            ConfiguracionesController::class,
            "update",
        ])->name("configuraciones.update");
        Route::post("/elimina/{id}", [
            ConfiguracionesController::class,
            "destroy",
        ])->name("configuraciones.destroy");
        Route::get("/buscar/{filtro}", [
            ConfiguracionesController::class,
            "search",
        ])->name("configuraciones.search");
    });
});
