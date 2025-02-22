<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('checkPermission')) {
    function checkPermission($action, $resource)
    {
        $permissionName = 'akses ' . $action . ' ' . $resource;
        return auth()->user()->can($permissionName);
    }
}

if (!function_exists('hasAnyPermission')) {
    function hasAnyPermission()
    {
        return auth()->user()->getAllPermissions()->count() > 0;
    }
}

if (!function_exists('getResourcePermissions')) {
    function getResourcePermissions()
    {
        return auth()->user()->getAllPermissions()->pluck('name')->toArray();
    }
}

if (!function_exists('getActionButtons')) {
    function getActionButtons($buttons = [], $params = [])
    {
        $path = request()->path();
        $segments = explode('/', $path);
        array_shift($segments);

        // Keep original segments for route
        $routePrefix = implode('.', $segments);

        // Create multiple permission-friendly module names
        $currentModule = implode('/', $segments);
        $permissionVariants = [
            $currentModule,                                    // original
            str_replace('-', ' ', $currentModule),            // space
            str_replace(' ', '-', $currentModule),            // dash
            str_replace(['-', ' '], '', $currentModule),      // no separator
            str_replace(['-', ' '], '_', $currentModule),     // underscore
            strtolower($currentModule),                       // lowercase
            strtoupper($currentModule),                       // uppercase
            ucwords(str_replace('-', ' ', $currentModule)),   // Title Case
            ucwords(str_replace('_', ' ', $currentModule)),   // Title With Space
            str_replace(['-', '_', ' '], '.', $currentModule) // dot separator
        ];

        $html = '';
        $permissions = collect(getResourcePermissions());

        foreach ($buttons as $button) {
            $hasPermission = $permissions->contains(function ($permission) use ($button, $permissionVariants) {
                if (!str_contains($permission, $button['action'])) {
                    return false;
                }

                foreach ($permissionVariants as $variant) {
                    if (str_contains(strtolower($permission), strtolower($variant))) {
                        return true;
                    }
                }
                return false;
            });

            if ($hasPermission) {
                $routeName = $routePrefix . '.' . $button['action'];
                $class = $button['class'];

                if (!Route::has($routeName)) {
                    $html .= "<button class='{$class} opacity-50' disabled>" . ucfirst($button['action']) . "</button>";
                    continue;
                }

                $routeParams = [];
                if (isset($button['params'])) {
                    foreach ($button['params'] as $param) {
                        if (isset($params[$param])) {
                            $routeParams[$param] = $params[$param];
                        }
                    }
                }

                $url = route($routePrefix . '.' . $button['action'], $routeParams);
                $html .= "<a href='{$url}' class='{$class}'>" . ucfirst($button['action']) . "</a>";
            }
        }

        return $html;
    }
}
