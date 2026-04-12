<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Configuracion;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin = Role::create(["name" => "Administrador"]);
        $usuario = Role::create(["name" => "Usuario"]);

        //Permisos Usuarios
        Permission::create(["name" => "usuarios.index"]);
        Permission::create(["name" => "usuarios.store"]);
        Permission::create(["name" => "usuarios.update"]);
        Permission::create(["name" => "usuarios.destroy"]);

        //Permisos Configuraciones
        Permission::create(["name" => "configuraciones.index"]);
        Permission::create(["name" => "configuraciones.store"]);
        Permission::create(["name" => "configuraciones.update"]);
        Permission::create(["name" => "configuraciones.destroy"]);

        //Permisos de Administrador a Usuarios
        $admin->givePermissionTo("usuarios.index");
        $admin->givePermissionTo("usuarios.store");
        $admin->givePermissionTo("usuarios.update");
        $admin->givePermissionTo("usuarios.destroy");

        //Permisos de Administrador a Configuraciones
        $admin->givePermissionTo("configuraciones.index");
        $admin->givePermissionTo("configuraciones.store");
        $admin->givePermissionTo("configuraciones.update");
        $admin->givePermissionTo("configuraciones.destroy");

        // Crea usuario administrador
        $user = new User();
        $user->name = "Administrador del Sistema";
        $user->email = "correo@admin.com";
        $user->password = bcrypt("C0n7r4s3ña!");
        $user->save();
        $user->assignRole($admin);

        // Configuración logo Login
        $configuracion = new Configuracion();
        $configuracion->clave = "Acceso / Logo";
        $configuracion->tipo = "Imagen";
        $configuracion->valor = "/img/logo.png";
        $configuracion->save();

        // Configuración logo Navbar
        $configuracion = new Configuracion();
        $configuracion->clave = "Barra de Navegación / Logo";
        $configuracion->tipo = "Imagen";
        $configuracion->valor = "/img/logo-nav.png";
        $configuracion->save();
    }
}
