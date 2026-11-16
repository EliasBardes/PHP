<?php
require_once __DIR__ . '/Vehicle.php';

/**
 * Classe Car représentant une voiture particulière.
 * Hérite de Vehicle pour illustrer les principes de la POO (Héritage, Polymorphisme).
 */
class Car extends Vehicle
{
    private string $bodyType; // e.g. "Berline", "SUV", "Monospace"
    private int $doors;

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
        ?int $id = null,
        string $bodyType = 'Berline',
        int $doors = 5
    ) {
        parent::__construct($brand, $model, $version, $year, $mileage, $fuel, $price, $image, $description, $id);
        $this->bodyType = $bodyType;
        $this->doors = $doors;
    }

    public function getBodyType(): string { return $this->bodyType; }
    public function setBodyType(string $bodyType): self { $this->bodyType = $bodyType; return $this; }

    public function getDoors(): int { return $this->doors; }
    public function setDoors(int $doors): self { $this->doors = $doors; return $this; }
}
