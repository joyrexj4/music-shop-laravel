<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Employee;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin = 1, Employee = 2, Courier = 3
        Gate::define('manage-products', function (Employee $employee) {
            $allowedRoles = [1, 3]; 
            return in_array($employee->Job_Id, $allowedRoles);
        });
        
        Gate::define('manage-orders', function (Employee $employee) {
            $allowedRoles = [1, 2, 3];
            return in_array($employee->Job_Id, $allowedRoles);
        });

        Gate::define('manage-clients', function (Employee $employee) {
            return $employee->Job_Id == 1;
        });
        
        Gate::define('manage-employees', function (Employee $employee) {
            return $employee->Job_Id == 1;
        });
    }
}
