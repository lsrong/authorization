<?php
return [
    /*
   |--------------------------------------------------------------------------
   | Basic setting
   |--------------------------------------------------------------------------
   |
   */
    // Route prefix
    'prefix' => '',

    // Route middleware groups
    'route_middleware' => 'authorization',

    // Authenticate switch
    'check_authenticate' => true,

    // Permission switch
    'check_permission' => true,

   /*
   |--------------------------------------------------------------------------
   | Authentication setting
   |--------------------------------------------------------------------------
   |
   | Include an authentication
   | guard , a user provider setting of authentication driver, except routes.
   |
   */
   'auth' => [
       'guard' => 'system',

       'guards' => [
           'system' => [
               'driver' => 'jwt',
               'provider' => 'authorization',
           ],
       ],

       'providers' => [
           'authorization' => [
               'driver' => 'eloquent',
               'model' => Lson\Authorization\Database\User::class
           ],
       ],

       'excepts' => [
           'api/login',
           'api/logout'
       ],
   ],

    /*
    |--------------------------------------------------------------------------
    | Database setting
    |--------------------------------------------------------------------------
    | Here are database settings for authorization builtin model & tables.
    |
    */
    'database' => [
        // User table and model.
        'users_table' => 'auth_users',
        'users_model' => Lson\Authorization\Database\User::class,

        // Role table and model.
        'roles_table' => 'auth_roles',
        'roles_model' => Lson\Authorization\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'auth_permissions',
        'permissions_model' => Lson\Authorization\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'auth_menu',
        'menu_model' => Lson\Authorization\Database\Menu::class,

        // Operation log table and model.
        'operation_log_table'    => 'auth_operation_log',
        'operation_log_model'    => Lson\Authorization\Database\OperationLog::class,

        // Pivot table for table above.
        'user_permissions_table' => 'auth_user_permissions',
        'role_users_table'       => 'auth_role_users',
        'role_permissions_table' => 'auth_role_permissions',
        'role_menu_table'        => 'auth_role_menu',
    ],

    /*
   |--------------------------------------------------------------------------
   | Operation log setting
   |--------------------------------------------------------------------------
   |
   | Setting this option to open or close operation log
   |
   */
    'operation_log' => [
        //
        'enable' => false,

        // logging allowed methods
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        // Routes that will not log to database.
        'except' => [
            'test/*'
        ],
    ],

];
