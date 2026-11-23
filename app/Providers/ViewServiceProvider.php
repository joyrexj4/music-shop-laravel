<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ProductCategory;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.main', function ($view) {
            $categories = ProductCategory::orderBy('Name')->get();
            $view->with('categories', $categories);
        });
    }

    public function register()
    {
        //
    }
}