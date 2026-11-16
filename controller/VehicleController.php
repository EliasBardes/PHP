<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Vehicle.php';
require_once __DIR__ . '/../model/ContactRequest.php';
require_once __DIR__ . '/../model/Reservation.php';

class VehicleController extends BaseController
{
    /**
     * Affiche le catalogue des véhicules (Maquette Figma 1 : Accueil AUTO-MATCH)
     */
    public function index(): void
    {
        $vehicles = Vehicle::findAll();
        $this->render('catalog', [
            'pageTitle' => 'Découvrez nos véhicules',
            'vehicles'  => $vehicles,
            'total'     => count($vehicles)
        ]);
    }

    /**
     * Affiche la fiche détaillée d'un véhicule et le formulaire de contact
     * (Maquette Figma 2 : Honda Civic — Fiche et contact)
     */
    public function show(int $id, array $errors = [], array $old = []): void
    {
        $vehicle = Vehicle::findById($id);

        if (!$vehicle) {
            $this->redirect('index.php');
            return;
        }

        // Valeurs par défaut pour le formulaire si non spécifiées
        if (empty($old)) {
            $old = [
                'name'    => '',
                'email'   => '',
                'phone'   => '',
                'message' => "Bonjour, je souhaiterais en savoir plus sur cette {$vehicle->getFullName()}..."
            ];
        }

        $this->render('vehicle_detail', [
            'pageTitle' => $vehicle->getFullName() . ' — Fiche et contact',
            'vehicle'   => $vehicle,
            'errors'    => $errors,
            'old'       => $old
        ]);
    }

    /**
     * Traite la soumission du formulaire de contact
     * (Maquette Figma 3 : Affichage des erreurs en cas de validation non passée)
     * (Maquette Figma 4 : Redirection vers confirmation si valide)
     */
    public function submitContact(int $vehicleId): void
    {
        $vehicle = Vehicle::findById($vehicleId);
        if (!$vehicle) {
            $this->redirect('index.php');
            return;
        }

        $name    = $_POST['name']    ?? '';
        $email   = $_POST['email']   ?? '';
        $phone   = $_POST['phone']   ?? '';
        $message = $_POST['message'] ?? '';

        $contactRequest = new ContactRequest($vehicleId, $name, $email, $phone, $message);
        $errors = $contactRequest->validate();

        if (!empty($errors)) {
            // Affichage avec erreurs (Maquette Figma 3)
            $this->show($vehicleId, $errors, [
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'message' => $message
            ]);
            return;
        }

        // Sauvegarde en base de données
        if ($contactRequest->save()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['last_request_id'] = $contactRequest->getId();
            $this->redirect('index.php?action=confirmation&id=' . $contactRequest->getId());
        } else {
            $errors['general'] = "Une erreur est survenue lors de l'enregistrement de votre demande.";
            $this->show($vehicleId, $errors, [
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'message' => $message
            ]);
        }
    }

    /**
     * Affiche l'écran de confirmation
     * (Maquette Figma 4 : Honda Civic — Demande confirmée)
     */
    public function confirmation(?int $requestId = null): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $requestId ?? ($_SESSION['last_request_id'] ?? null);

        if (!$id) {
            $this->redirect('index.php');
            return;
        }

        $contactRequest = ContactRequest::findById((int)$id);
        if (!$contactRequest) {
            $this->redirect('index.php');
            return;
        }

        $vehicle = $contactRequest->getVehicle();

        $this->render('confirmation', [
            'pageTitle'      => $vehicle ? $vehicle->getFullName() . ' — Demande confirmée' : 'Demande confirmée',
            'contactRequest' => $contactRequest,
            'vehicle'        => $vehicle
        ]);
    }

    /**
     * Traite l'action "Réserver la voiture"
     */
    public function reserve(int $vehicleId): void
    {
        $vehicle = Vehicle::findById($vehicleId);
        if (!$vehicle) {
            $this->redirect('index.php');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        $userName = $_SESSION['user_name'] ?? ($_POST['customer_name'] ?? 'Client invité');
        $userEmail = $_SESSION['email'] ?? ($_POST['customer_email'] ?? 'contact@client.com');
        $userPhone = $_SESSION['phone'] ?? ($_POST['customer_phone'] ?? null);

        $reservation = new Reservation($vehicleId, $userId, $userName, $userEmail, $userPhone, 'confirmée');
        $reservation->save();

        $this->render('reservation_success', [
            'pageTitle'   => 'Réservation confirmée — ' . $vehicle->getFullName(),
            'vehicle'     => $vehicle,
            'reservation' => $reservation
        ]);
    }
}
