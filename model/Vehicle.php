<?php
require_once __DIR__ . '/../Db.php';

class Vehicle
{
    protected ?int $id;
    protected string $brand;
    protected string $model;
    protected string $version;
    protected int $year;
    protected int $mileage;
    protected string $fuel;
    protected int $price;
    protected string $image;
    protected ?string $description;

    public function __construct(
        string $brand = '',
        string $model = '',
        string $version = '',
        int $year = 0,
        int $mileage = 0,
        string $fuel = '',
        int $price = 0,
        string $image = '',
        ?string $description = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->version = $version;
        $this->year = $year;
        $this->mileage = $mileage;
        $this->fuel = $fuel;
        $this->price = $price;
        $this->image = $image;
        $this->description = $description;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getBrand(): string { return $this->brand; }
    public function getModel(): string { return $this->model; }
    public function getVersion(): string { return $this->version; }
    public function getYear(): int { return $this->year; }
    public function getMileage(): int { return $this->mileage; }
    public function getFuel(): string { return $this->fuel; }
    public function getPrice(): int { return $this->price; }
    public function getImage(): string { return $this->image; }
    public function getDescription(): ?string { return $this->description; }

    // Setters
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setBrand(string $brand): self { $this->brand = $brand; return $this; }
    public function setModel(string $model): self { $this->model = $model; return $this; }
    public function setVersion(string $version): self { $this->version = $version; return $this; }
    public function setYear(int $year): self { $this->year = $year; return $this; }
    public function setMileage(int $mileage): self { $this->mileage = $mileage; return $this; }
    public function setFuel(string $fuel): self { $this->fuel = $fuel; return $this; }
    public function setPrice(int $price): self { $this->price = $price; return $this; }
    public function setImage(string $image): self { $this->image = $image; return $this; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    // Méthodes d'affichage formatées
    public function getFullName(): string
    {
        return $this->brand . ' ' . $this->model;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' €';
    }

    public function getFormattedMileage(): string
    {
        return number_format($this->mileage, 0, ',', ' ') . ' km';
    }

    public function getSummaryLine(): string
    {
        return $this->version . ' • ' . $this->year . ' • ' . $this->getFormattedMileage() . ' • ' . $this->fuel;
    }

    // Méthodes statiques d'accès aux données (Active Record / Repository pattern)
    public static function findAll(): array
    {
        $db = Db::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM vehicles ORDER BY id ASC");
        $results = [];

        while ($row = $stmt->fetch()) {
            $results[] = new static(
                $row['brand'],
                $row['model'],
                $row['version'],
                (int)$row['year'],
                (int)$row['mileage'],
                $row['fuel'],
                (int)$row['price'],
                $row['image'],
                $row['description'],
                (int)$row['id']
            );
        }

        return $results;
    }

    public static function findById(int $id): ?static
    {
        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM vehicles WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new static(
            $row['brand'],
            $row['model'],
            $row['version'],
            (int)$row['year'],
            (int)$row['mileage'],
            $row['fuel'],
            (int)$row['price'],
            $row['image'],
            $row['description'],
            (int)$row['id']
        );
    }
}
