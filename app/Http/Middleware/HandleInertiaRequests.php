<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'passData' => $request->session()->get('passData'),
                ];
            },

            'auth' => function () {
                $user = auth()->user();

                return $user ? [
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ] : null;
            },

            'logo' => config('app.logo'),
            'logoNav' => config('app.logoNav'),
            'appName' => config('app.name'),
        ]);
    }
}