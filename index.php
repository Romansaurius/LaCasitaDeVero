<?php
/**
 * Punto de entrada principal - La Casita de Vero
 */

// Iniciar sesión
session_start();

// Incluir clases necesarias
require_once __DIR__ . '/src/core/Database.php';
require_once __DIR__ . '/src/models/UserModel.php';
require_once __DIR__ . '/src/controllers/LoginController.php';
require_once __DIR__ . '/src/controllers/RegisterController.php';

use Core\Database;
use Controllers\LoginController;
use Controllers\RegisterController;

try {
    // Inicializar base de datos
    $db = Database::getInstance();
    
    // Obtener la ruta solicitada
    $request = $_SERVER['REQUEST_URI'];
    $request = strtok($request, '?'); // Remover query string
    
    // Enrutamiento simple
    switch ($request) {
        case '/':
        case '/index.php':
            require_once __DIR__ . '/src/views/index.html';
            break;
            
        case '/login':
            $controller = LoginController::create($db);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processLogin();
            } else {
                $controller->showLoginForm();
            }
            break;
            
        case '/register':
            $controller = RegisterController::create($db);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processRegister();
            } else {
                $controller->showRegisterForm();
            }
            break;
            
        case '/logout':
            $controller = LoginController::create($db);
            $controller->logout();
            break;
            
        case '/servicios':
            require_once __DIR__ . '/src/views/servicios.html';
            break;
            
        case '/sobre_nosotros':
            require_once __DIR__ . '/src/views/sobre_nosotros.html';
            break;
            
        case '/contacto':
            require_once __DIR__ . '/src/views/contacto.html';
            break;
            
        case '/guarderia':
            require_once __DIR__ . '/src/views/guarderia.html';
            break;
            
        default:
            http_response_code(404);
            echo "<h1>404 - Página no encontrada</h1>";
            break;
    }
    
} catch (\Exception $e) {
    error_log("Error crítico: " . $e->getMessage(), 0);
    http_response_code(500);
    echo "<h1>Error Interno del Servidor</h1>";
    echo "<p>Ha ocurrido un error. Por favor, inténtelo más tarde.</p>";
}
