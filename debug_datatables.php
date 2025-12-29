<?php
use Illuminate\Http\Request;
use App\Http\Controllers\PessoasController;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Mock request
$request = Request::create('/pessoas/data', 'GET');

// Instantiate controller
$controller = new PessoasController();

try {
    $response = $controller->data($request);
    echo "Response content:\n";
    echo $response->getContent();
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
}
