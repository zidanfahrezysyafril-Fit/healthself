<?php
$request = Illuminate\Http\Request::create('/register', 'POST', [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]);
$controller = new App\Http\Controllers\AuthController();
try {
    $response = $controller->register($request);
    echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
    if ($response->headers->has('Location')) {
        echo 'Location: ' . $response->headers->get('Location') . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
