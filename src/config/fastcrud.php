<?php

return [
    'route_prefix' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Batas Export Audit
    |--------------------------------------------------------------------------
    |
    | Jumlah maksimal record audit yang direkap saat export Excel/PDF.
    | Record yang diambil selalu yang terbaru sesuai filter aktif, sehingga
    | export tidak membebani server ketika tabel audits sudah sangat besar.
    |
    */
    'audit_export_limit' => 500,
];
