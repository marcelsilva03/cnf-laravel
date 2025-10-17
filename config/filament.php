<?php

use App\Models\User;

return [
    'broadcasting' => [],
    'auth' => [
        'guard' => 'web',
    ],
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
    'assets_path' => null,
    'cache_path' => base_path('bootstrap/cache/filament'),
    'livewire_loading_delay' => 'default',
    'branding' => [
        'primary' => 'rgb(113, 42, 0.467)'
    ],
    'resources' => [
        'pluralize' => false,
    ],
];
