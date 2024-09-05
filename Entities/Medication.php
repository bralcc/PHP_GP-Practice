<?php
declare(strict_types= 1);
namespace Entities;
class Medication {
    private $id;
    private $name;
    private $dosage;
    private $description;

    public function __construct(?int $id = null, ?string $name = null, ?string $dosage = null, ?string $description = null) {
        $this->id = $id;
        $this->name = $name;
        $this->dosage = $dosage;
        $this->description = $description;
    }
    public function getId(): int {
        return $this->id;
    }
    public function getName(): ?string {
        return $this->name;
    }
    public function getDosage(): ?string {
        return $this->dosage;
    }
    public function getDescription(): ?string {
        return $this->description;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setName(string $name): void {
        $this->name = $name;
    }
    public function setDosage(string $dosage): void {
        $this->dosage = $dosage;
    }
    public function setDescription(string $description): void {
    $this->description = $description;
    }
    public function toArray(): array {
        return get_object_vars($this);
    }
}
