<?php
namespace Models;

use Core\Database;

class UserModel
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function register(string $username, string $email, string $password): array
    {
        try {
            $validation = $this->validateRegistrationData($username, $email, $password);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            if ($this->userExists($username, $email)) {
                return ['success' => false, 'message' => 'El nombre de usuario o correo electrónico ya están registrados'];
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $result = $this->insertUser($username, $email, $hashedPassword);

            if ($result) {
                return ['success' => true, 'message' => 'Usuario registrado exitosamente.'];
            }
            return ['success' => false, 'message' => 'Error al registrar el usuario'];
        } catch (\Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno del servidor'];
        }
    }

    public function authenticate(string $username, string $password): array
    {
        try {
            if (empty($username) || empty($password)) {
                return ['success' => false, 'message' => 'Nombre de usuario y contraseña son obligatorios'];
            }

            $user = $this->findUserByUsernameOrEmail($username);
            if (!$user || !password_verify($password, $user['password_hash'])) {
                return ['success' => false, 'message' => 'Credenciales incorrectas'];
            }

            $this->updateLastLogin($user['user_id']);
            unset($user['password_hash']);

            return ['success' => true, 'message' => 'Autenticación exitosa', 'user' => $user];
        } catch (\Exception $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno del servidor'];
        }
    }

    private function validateRegistrationData(string $username, string $email, string $password): array
    {
        if (empty($username)) {
            return ['valid' => false, 'message' => 'El nombre de usuario es obligatorio'];
        }
        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['valid' => false, 'message' => 'El nombre de usuario debe tener entre 3 y 50 caracteres'];
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return ['valid' => false, 'message' => 'El nombre de usuario solo puede contener letras, números y guiones bajos'];
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'El formato del correo electrónico no es válido'];
        }
        if (empty($password) || strlen($password) < 8) {
            return ['valid' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres'];
        }
        return ['valid' => true, 'message' => 'Datos válidos'];
    }

    private function userExists(string $username, string $email): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?";
            $stmt = $this->db->query($sql, [$username, $email]);
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (\Exception $e) {
            return true;
        }
    }

    private function insertUser(string $username, string $email, string $hashedPassword): bool
    {
        try {
            $sql = "INSERT INTO users (username, email, password_hash, email_verified, created_at) VALUES (?, ?, ?, TRUE, NOW())";
            $stmt = $this->db->query($sql, [$username, $email, $hashedPassword]);
            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            error_log("Error al insertar usuario: " . $e->getMessage());
            return false;
        }
    }

    private function findUserByUsernameOrEmail(string $usernameOrEmail): ?array
    {
        try {
            $sql = "SELECT user_id, username, email, password_hash, created_at, last_login_at FROM users WHERE username = ? OR email = ?";
            $stmt = $this->db->query($sql, [$usernameOrEmail, $usernameOrEmail]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function updateLastLogin(int $userId): bool
    {
        try {
            $sql = "UPDATE users SET last_login_at = NOW() WHERE user_id = ?";
            $stmt = $this->db->query($sql, [$userId]);
            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
