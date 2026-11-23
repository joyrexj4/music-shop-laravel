<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('client', function (Blueprint $table) {
        $table->renameColumn('Password', 'password');
        $table->renameColumn('Email', 'email');
        $table->renameColumn('Login', 'login');
    });
    
    Schema::table('employee', function (Blueprint $table) {
        $table->renameColumn('Password', 'password');
        $table->renameColumn('Email', 'email');
        $table->renameColumn('Login', 'login');
    });
}

public function down()
{
    Schema::table('client', function (Blueprint $table) {
        $table->renameColumn('password', 'Password');
        $table->renameColumn('email', 'Email');
        $table->renameColumn('login', 'Login');
    });
    
    Schema::table('employee', function (Blueprint $table) {
        $table->renameColumn('password', 'Password');
        $table->renameColumn('email', 'Email');
        $table->renameColumn('login', 'Login');
    });
}
};
