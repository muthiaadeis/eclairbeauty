<?php
require 'C:/laragon/www/eclair-clinic/vendor/autoload.php';
$app = require_once 'C:/laragon/www/eclair-clinic/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$blade = file_get_contents('C:/laragon/www/eclair-clinic/resources/views/resepsionis/jadwal/create.blade.php');
try {
    $compiled = Illuminate\Support\Facades\Blade::compileString($blade);
    echo "COMPILED OK: " . strlen($compiled) . " bytes\n";
    $tempFile = 'C:/laragon/www/eclair-clinic/storage/framework/views/test_syntax.php';
    file_put_contents($tempFile, $compiled);
    $output = shell_exec("php -l \"$tempFile\"");
    echo "LINT: " . $output . "\n";
    @unlink($tempFile);
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
