<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EscritorioController extends Controller
{
    // Pagina principal
    public function index()
    {
        return Inertia::render("Escritorio");
    }
}