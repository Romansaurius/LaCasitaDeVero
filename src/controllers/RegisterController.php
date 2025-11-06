<?php
/** Controlador de Registro */
namespace Controllers;

use Models\UserModel;
use Core\Database;

class RegisterController
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public static function create(Database $db): RegisterController
    {
        $userModel = new UserModel($db);
        return new self($userModel);
    }

    public function showRegisterForm(): void
    {
        $errorMessage = $_SESSION['register_error'] ?? '';
        $successMessage = $_SESSION['register_success'] ?? '';
        $formData = $_SESSION['register_form_data'] ?? [];

        if (!empty($errorMessage)) {
            unset($_SESSION['register_error']);
        }
        if (!empty($successMessage)) {
            unset($_SESSION['register_success']);
        }
        if (!empty($formData)) {
            unset($_SESSION['register_form_data']);
        }

        require_once __DIR__ . '/../views/register.html';
    }

    public function processRegister(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirectWithError('Método no permitido');
                return;
            }

            $username = $this->sanitizeInput($_POST['username'] ?? '');
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $result = $this->userModel->register($username, $email, $password);

            if ($result['success']) {
                $_SESSION['login_success'] = 'Registro exitoso. Por favor inicia sesión.';
                header('Location: /login');
                exit();
            } else {
                $this->showFormWithError($result['message']);
            }

        } catch (\Exception $e) {
            error_log("Error en procesamiento de registro: " . $e->getMessage(), 0);
            $this->redirectWithError('Error interno del servidor');
        }
    }

    private function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    private function showFormWithError(string $message): void
    {
        $_SESSION['register_error'] = $message;
        $_SESSION['register_form_data'] = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        $this->showRegisterForm();
    }

    private function redirectWithError(string $message): void
    {
        $encodedMessage = urlencode($message);
        header("Location: /register?error=$encodedMessage");
        exit();
    }
}
