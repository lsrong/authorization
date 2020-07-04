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
            $table->string('username', 128)->unique()->comment('用户名');
            $table->string('password', 64)->comment('密码');
            $table->string('name')->default('')->comment('昵称');
            $table->string('avatar')->nullable()->default('')->comment('头像');
            $table->string('remember_token', 128)->nullable()->default('')->comment('token认证');
            $table->boolean('is_super')->nullable()->default(0)->comment('Is super administrator');
            $table->timestamps();
        });

        // Role table
        Schema::create(config('authorization.database.roles_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 64)->unique()->comment('角色名称');
            $table->string('slug', 64)->unique()->comment('角色标记');
            $table->timestamps();
        });

        Schema::create(config('authorization.database.permissions_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 64)->unique()->comment('权限名称');
            $table->string('slug', 64)->unique()->comment('权限标识');
            $table->string('http_method')->nullable()->comment('请求方法');
            $table->text('http_path')->nullable()->comment('请求路径');
            $table->timestamps();
        });

        Schema::create(config('authorization.database.menu_table'), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->default(0)->comment('父级id');
            $table->integer('order')->default(0)->default('排序字段');
            $table->string('title', 64)->comment('标题');
            $table->string('icon', 64)->comment('菜单图标');
            $table->string('uri')->nullable()->comment('路由路径');
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
            $table->integer('user_id')->index('user_id')->comment('用户id');
            $table->string('path')->comment('访问路径');
            $table->string('method', 16)->comment('请求方法');
            $table->string('ip')->nullable()->default('')->comment('客户端ip');
            $table->text('input')->nullable()->default('')->comment('请求包内容');
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
