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

// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?'); // Remover query string

// Rutas que no requieren BD
$publicRoutes = ['/', '/index.php', '/servicios', '/sobre_nosotros', '/contacto', '/guarderia'];

try {
    // Solo inicializar BD si no es ruta pública
    // if (!in_array($request, $publicRoutes)) {
    //     $db = Database::getInstance();
    // }
    
    // Enrutamiento simple
    switch ($request) {
        case '/':
        case '/index.php':
            require_once __DIR__ . '/src/views/index.html';
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
            
        case '/login':
            // if (!isset($db)) $db = Database::getInstance();
            // $controller = LoginController::create($db);
            require_once __DIR__ . '/src/views/login.html';
            break;
            /*
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processLogin();
            } else {
                $controller->showLoginForm();
            }
            */
            break;
            
        case '/register':
            // if (!isset($db)) $db = Database::getInstance();
            // $controller = RegisterController::create($db);
            require_once __DIR__ . '/src/views/register.html';
            break;
            /*
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->processRegister();
            } else {
                $controller->showRegisterForm();
            }
            */
            break;
            
        case '/logout':
            session_destroy();
            header('Location: /');
            exit;
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
