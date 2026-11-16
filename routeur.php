<?php
require_once __DIR__ . '/controller/VehicleController.php';
require_once __DIR__ . '/controller/AuthController.php';

class Router
{
    private VehicleController $vehicleController;
    private AuthController $authController;

    public function __construct()
    {
        $this->vehicleController = new VehicleController();
        $this->authController = new AuthController();
    }

    /**
     * Analyse la requête et aiguille vers le bon contrôleur
     */
    public function dispatch(): void
    {
        $action = $_GET['action'] ?? 'index';
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($action) {
            case 'index':
                $this->vehicleController->index();
                break;

            case 'show':
                $id = (int)($_GET['id'] ?? 1);
                $this->vehicleController->show($id);
                break;

            case 'contact':
                $id = (int)($_GET['id'] ?? 1);
                if ($method === 'POST') {
                    $this->vehicleController->submitContact($id);
                } else {
                    $this->vehicleController->show($id);
                }
                break;

            case 'confirmation':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
                $this->vehicleController->confirmation($id);
                break;

            case 'reserve':
                $id = (int)($_GET['id'] ?? 1);
                $this->vehicleController->reserve($id);
                break;

            case 'login':
                $this->authController->login();
                break;

            case 'register':
                $this->authController->register();
                break;

            case 'logout':
                $this->authController->logout();
                break;

            case 'account':
                $this->authController->account();
                break;

            default:
                $this->vehicleController->index();
                break;
        }
    }
}
