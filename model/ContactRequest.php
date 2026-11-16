<?php
require_once __DIR__ . '/../Db.php';
require_once __DIR__ . '/Vehicle.php';

class ContactRequest
{
    private ?int $id;
    private int $vehicleId;
    private string $name;
    private string $email;
    private ?string $phone;
    private string $message;
    private ?string $createdAt;

    public function __construct(
        int $vehicleId = 0,
        string $name = '',
        string $email = '',
        ?string $phone = null,
        string $message = '',
        ?int $id = null,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->vehicleId = $vehicleId;
        $this->name = trim($name);
        $this->email = trim($email);
        $this->phone = $phone ? trim($phone) : null;
        $this->message = trim($message);
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getVehicleId(): int { return $this->vehicleId; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getMessage(): string { return $this->message; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    // Setters
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setVehicleId(int $vehicleId): self { $this->vehicleId = $vehicleId; return $this; }
    public function setName(string $name): self { $this->name = trim($name); return $this; }
    public function setEmail(string $email): self { $this->email = trim($email); return $this; }
    public function setPhone(?string $phone): self { $this->phone = $phone ? trim($phone) : null; return $this; }
    public function setMessage(string $message): self { $this->message = trim($message); return $this; }
    public function setCreatedAt(?string $createdAt): self { $this->createdAt = $createdAt; return $this; }

    /**
     * Valide les données selon les règles métier et retourne les messages d'erreur
     * Correspond fidèlement aux messages de la maquette Figma.
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = "Indiquez votre nom.";
        }

        if (empty($this->email)) {
            $errors['email'] = "Saisissez un e-mail valide, ex. nom@exemple.fr";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Saisissez un e-mail valide, ex. nom@exemple.fr";
        }

        if (empty($this->message)) {
            $errors['message'] = "Veuillez saisir votre message.";
        }

        return $errors;
    }

    /**
     * Sauvegarde la demande en base de données
     */
    public function save(): bool
    {
        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO contact_requests (vehicle_id, name, email, phone, message)
            VALUES (:vehicle_id, :name, :email, :phone, :message)
        ");

        $success = $stmt->execute([
            'vehicle_id' => $this->vehicleId,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'message'    => $this->message,
        ]);

        if ($success) {
            $this->id = (int)$db->lastInsertId();
            $this->createdAt = date('Y-m-d H:i:s');
        }

        return $success;
    }

    public static function findById(int $id): ?self
    {
        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM contact_requests WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new self(
            (int)$row['vehicle_id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['message'],
            (int)$row['id'],
            $row['created_at']
        );
    }

    public function getVehicle(): ?Vehicle
    {
        return Vehicle::findById($this->vehicleId);
    }
}
