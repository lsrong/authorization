<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // User table
        Schema::create(config('authorization.database.users_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 128)->unique();
            $table->string('password', 64);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 128)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Role table
        Schema::create(config('authorization.database.roles_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 64)->unique();
            $table->string('slug', 64)->unique();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('authorization.database.permissions_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 64)->unique();
            $table->string('slug', 64)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('authorization.database.menu_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 64);
            $table->string('icon', 64);
            $table->string('uri')->nullable();
            $table->string('permission')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('authorization.database.role_users_table'), static function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create(config('authorization.database.role_permissions_table'), static function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create(config('authorization.database.user_permissions_table'), static function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create(config('authorization.database.role_menu_table'), static function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });

        Schema::create(config('authorization.database.operation_log_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 16);
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('authorization.database.users_table'));
        Schema::dropIfExists(config('authorization.database.roles_table'));
        Schema::dropIfExists(config('authorization.database.permissions_table'));
        Schema::dropIfExists(config('authorization.database.menu_table'));
        Schema::dropIfExists(config('authorization.database.user_permissions_table'));
        Schema::dropIfExists(config('authorization.database.role_users_table'));
        Schema::dropIfExists(config('authorization.database.role_permissions_table'));
        Schema::dropIfExists(config('authorization.database.role_menu_table'));
        Schema::dropIfExists(config('authorization.database.operation_log_table'));
    }
}
