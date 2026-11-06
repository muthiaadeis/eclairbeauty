<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $pasien = collect([
        (object)['id' => 1, 'nama_pasien' => 'Pasien A', 'no_hp' => '08123456789']
    ]);
    $dokter = collect([
        (object)['id' => 2, 'nama' => 'dr. Rahma']
    ]);
    $view = view('resepsionis.jadwal.create', compact('pasien', 'dokter'))->render();
    echo "SUCCESS: " . strlen($view) . " bytes\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
