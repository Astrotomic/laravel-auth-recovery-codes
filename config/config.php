<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Amount of Codes
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of codes to generate by default.
    |
    */
    'count' => 8,

    /*
    |--------------------------------------------------------------------------
    | Amount of Blocks per Code
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of blocks in each code.
    |
    */
    'blocks' => 2,

    /*
    |--------------------------------------------------------------------------
    | Amount of Chars per Block
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of chars in each block.
    |
    */
    'chars' => 10,

    /*
    |--------------------------------------------------------------------------
    | Block Separator
    |--------------------------------------------------------------------------
    |
    | Here you may define the separator between each block.
    | By default `-` is used.
    |
    */
    'separator' => '-',

    /*
    |--------------------------------------------------------------------------
    | Only numeric codes
    |--------------------------------------------------------------------------
    |
    | Here you may define if numeric only codes should be generated.
    | By default alpha_numeric is used.
    |
    */
    'numeric' => false,

    /*
    |--------------------------------------------------------------------------
    | Character casing
    |--------------------------------------------------------------------------
    |
    | Here you may define if all characters should be lower or upper cased.
    | By default it's `mixed` but you can change it to `lower` or `upper`.
    |
    */
    'casing' => 'mixed',
];
