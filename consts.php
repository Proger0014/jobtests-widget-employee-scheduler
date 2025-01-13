<?php

$consts = [
    'vendor' => 'proger0014',
    'package_name_base' => 'yii2',
];

$consts = array_merge($consts, [
    'alias_base' => '@' . $consts['vendor'],
]);

$consts = array_merge($consts, [
    'alias_tests' => [
        'name' => $consts['alias_base'] . '/tests/' . $consts['package_name_base'],
        'path' => __DIR__ . '/tests'
    ],
    'alias_src' => [
        'name' => $consts['alias_base'] . '/' . $consts['package_name_base'],
        'path' => __DIR__ . '/src'
    ],
    'alias_assets' => [
        'name' => $consts['alias_base'] . '/assets',
        'path' => __DIR__ . '/assets'
    ]
]);

return $consts;