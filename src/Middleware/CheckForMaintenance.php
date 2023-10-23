<?php

namespace Revdojo\MT\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Revdojo\MT\Models\Service;
class CheckForMaintenance
{
    public function handle($request, Closure $next)
    {
        $service = Service::where('system_id', config('revdojo-mt.service_system_id'))->first();
        if ($service->is_maintenance) {
            return abort(403, 'Access denied. Service is undermaintenace.');
        }
        return $next($request);
    }
}
