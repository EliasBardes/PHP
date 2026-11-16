<?php
require_once __DIR__ . '/../Db.php';
require_once __DIR__ . '/Vehicle.php';

class Reservation
{
    private ?int $id;
    private int $vehicleId;
    private ?int $userId;
    private ?string $customerName;
    private ?string $customerEmail;
    private ?string $customerPhone;
    private string $status;
    private ?string $createdAt;

    public function __construct(
        int $vehicleId = 0,
        ?int $userId = null,
        ?string $customerName = null,
        ?string $customerEmail = null,
        ?string $customerPhone = null,
        string $status = 'confirmée',
        ?int $id = null,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->vehicleId = $vehicleId;
        $this->userId = $userId;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->customerPhone = $customerPhone;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int { return $this->id; }
    public function getVehicleId(): int { return $this->vehicleId; }
    public function getUserId(): ?int { return $this->userId; }
    public function getCustomerName(): ?string { return $this->customerName; }
    public function getCustomerEmail(): ?string { return $this->customerEmail; }
    public function getCustomerPhone(): ?string { return $this->customerPhone; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function save(): bool
    {
        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO reservations (vehicle_id, user_id, customer_name, customer_email, customer_phone, status)
            VALUES (:vehicle_id, :user_id, :customer_name, :customer_email, :customer_phone, :status)
        ");

        $success = $stmt->execute([
            'vehicle_id'     => $this->vehicleId,
            'user_id'        => $this->userId,
            'customer_name'  => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'status'         => $this->status,
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
        $stmt = $db->prepare("SELECT * FROM reservations WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new self(
            (int)$row['vehicle_id'],
            $row['user_id'] ? (int)$row['user_id'] : null,
            $row['customer_name'],
            $row['customer_email'],
            $row['customer_phone'],
            $row['status'],
            (int)$row['id'],
            $row['created_at']
        );
    }

    public function getVehicle(): ?Vehicle
    {
        return Vehicle::findById($this->vehicleId);
    }
}
