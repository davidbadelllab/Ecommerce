<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Theme Configuration
    |--------------------------------------------------------------------------
    */
    'shop-default' => 'default',
    'shop' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path'  => 'resources/themes/default/views',
            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
        // Aquí está correctamente ubicado el tema bliss
        'bliss' => [
            'name'        => 'Bliss',
            'views_path'  => 'resources/themes/bliss/views',
            'assets_path' => 'public/themes/bliss/assets',
            'parent'      => 'default',
            'vite'        => [
                'hot_file'                 => 'shop-bliss-vite.hot',
                'build_directory'          => 'themes/shop/bliss/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Theme Configuration
    |--------------------------------------------------------------------------
    */
    'admin-default' => 'default',
    'admin' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path'  => 'resources/admin-themes/default/views',
            'vite'        => [
                'hot_file'                 => 'admin-default-vite.hot',
                'build_directory'          => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];