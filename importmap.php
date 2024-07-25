<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'searchItem' => [
        'path' => './assets/js/searchItem.js',
        'entrypoint' => true,
    ],
    'itemIndex' => [
        'path' => './assets/js/itemIndex.js',
        'entrypoint' => true,
    ],
    'shareCircle' => [
        'path' => './assets/js/shareCircle.js',
        'entrypoint' => true,
    ],
    'select' => [
        'path' => './assets/js/select.js',
        'entrypoint' => true,
    ],
    'bootstrap-icons/font/bootstrap-icons.min.css' => [
        'version' => '1.11.3',
        'type' => 'css',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'datatables.net-bs5' => [
        'version' => '2.0.4',
    ],
    'datatables.net' => [
        'version' => '2.0.8',
    ],
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css' => [
        'version' => '2.0.4',
        'type' => 'css',
    ],
    'datatables.net-responsive-bs5' => [
        'version' => '3.0.2',
    ],
    'datatables.net-responsive' => [
        'version' => '3.0.2',
    ],
    'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css' => [
        'version' => '3.0.2',
        'type' => 'css',
    ],
    'datatables.net-select' => [
        'version' => '2.0.3',
    ],
    'jspdf' => [
        'version' => '2.5.1',
    ],
    '@babel/runtime/helpers/typeof' => [
        'version' => '7.23.9',
    ],
    'fflate' => [
        'version' => '0.4.8',
    ],
    'select2' => [
        'version' => '4.1.0-rc.0',
    ],
    'select2/dist/css/select2.min.css' => [
        'version' => '4.1.0-rc.0',
        'type' => 'css',
    ],
    'raf' => [
        'version' => '3.4.1',
    ],
    'rgbcolor' => [
        'version' => '1.0.1',
    ],
    'svg-pathdata' => [
        'version' => '6.0.3',
    ],
    'stackblur-canvas' => [
        'version' => '2.7.0',
    ],
    'performance-now' => [
        'version' => '2.1.0',
    ],
    'jquery.qrcode' => [
        'version' => '1.0.3',
    ],
];
