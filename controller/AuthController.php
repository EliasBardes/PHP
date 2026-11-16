<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../Db.php';

class AuthController extends BaseController
{
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Veuillez renseigner tous les champs.";
            } else {
                $user = new User($email, $password);
                if ($user->login()) {
                    $this->redirect('index.php?action=account');
                    return;
                } else {
                    $error = "Identifiants incorrects. Veuillez réessayer.";
                }
            }
        }

        $this->render('auth/login', [
            'pageTitle' => 'Connexion — Mon Compte',
            'error'     => $error,
            'oldEmail'  => $_POST['email'] ?? ''
        ]);
    }

    public function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                $error = "Veuillez remplir les champs obligatoires (Nom, Email, Mot de passe).";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'adresse e-mail invalide.";
            } else {
                $existing = User::findByEmail($email);
                if ($existing) {
                    $error = "Un compte existe déjà avec cette adresse e-mail.";
                } else {
                    $user = new User($email, $password, $name, $phone);
                    if ($user->register()) {
                        $user->login();
                        $this->redirect('index.php?action=account');
                        return;
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription.";
                    }
                }
            }
        }

        $this->render('auth/register', [
            'pageTitle' => 'Créer un compte — AUTO-MATCH',
            'error'     => $error,
            'old'       => $_POST
        ]);
    }

    public function account(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?action=login');
            return;
        }

        // Récupérer les demandes de contact de l'utilisateur
        $db = Db::getInstance()->getConnection();
        $email = $_SESSION['email'] ?? '';
        $stmt = $db->prepare("
            SELECT cr.*, v.brand, v.model, v.version, v.price, v.image
            FROM contact_requests cr
            JOIN vehicles v ON cr.vehicle_id = v.id
            WHERE cr.email = :email
            ORDER BY cr.created_at DESC
        ");
        $stmt->execute(['email' => $email]);
        $requests = $stmt->fetchAll();

        // Récupérer les réservations
        $stmtRes = $db->prepare("
            SELECT r.*, v.brand, v.model, v.version, v.price, v.image
            FROM reservations r
            JOIN vehicles v ON r.vehicle_id = v.id
            WHERE r.user_id = :uid OR r.customer_email = :email
            ORDER BY r.created_at DESC
        ");
        $stmtRes->execute([
            'uid'   => $_SESSION['user_id'],
            'email' => $email
        ]);
        $reservations = $stmtRes->fetchAll();

        $this->render('auth/account', [
            'pageTitle'    => 'Mon Compte — AUTO-MATCH',
            'requests'     => $requests,
            'reservations' => $reservations
        ]);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        $this->redirect('index.php');
    }
}
