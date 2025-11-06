<?php
/**
 * Script de prueba de conexión a la base de datos
 */

require_once __DIR__ . '/src/core/Database.php';

use Core\Database;

echo "<h1>Prueba de Conexión - La Casita de Vero</h1>";

try {
    echo "<p>Intentando conectar a la base de datos...</p>";
    
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    echo "<p style='color: green;'>✓ Conexión exitosa a la base de datos!</p>";
    
    // Verificar si la tabla users existe
    $stmt = $connection->query("SHOW TABLES LIKE 'users'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "<p style='color: green;'>✓ La tabla 'users' existe</p>";
        
        // Contar usuarios
        $stmt = $connection->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<p>Usuarios registrados: " . $result['count'] . "</p>";
    } else {
        echo "<p style='color: orange;'>⚠ La tabla 'users' no existe. Ejecuta el archivo database/schema.sql</p>";
    }
    
    echo "<hr>";
    echo "<h2>Información de la Conexión</h2>";
    echo "<ul>";
    echo "<li>Estado: Conectado</li>";
    echo "<li>Driver: " . $connection->getAttribute(PDO::ATTR_DRIVER_NAME) . "</li>";
    echo "<li>Versión del servidor: " . $connection->getAttribute(PDO::ATTR_SERVER_VERSION) . "</li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<p><a href='/'>Ir a la página principal</a></p>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Verifica la configuración en config/database.php</p>";
}
