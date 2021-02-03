<?php
namespace R64\NovaPassportAccessTokens;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaPassportAccessToken extends Tool
{
    public function boot()
    {
        Nova::script('nova-passport-manager', __DIR__.'/../dist/js/tool.js');
        Nova::style('nova-passport-manager', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-passport-manager::navigation');
    }
}