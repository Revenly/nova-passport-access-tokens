<?php
namespace  R64\NovaPassportAccessTokens\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Laravel\Nova\Nova;
use R64\NovaPassportAccessTokens\NovaPassportAccessToken;

class Authorize extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        return optional($tool)->authorize($request) ? $next($request) : abort(403);
    }

    /**
     * Determine whether this tool belongs to the package.
     *
     * @param  \Laravel\Nova\Tool  $tool
     * @return bool
     */
    public function matchesTool($tool)
    {
        return $tool instanceof NovaPassportAccessToken;
    }
}