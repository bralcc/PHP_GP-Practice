<?php
declare(strict_types=1);

namespace Entities;

class Examination {
    private $id;
    private $nomenclature;
    private $price;
    private $description;
    private $taxpatient;
    private $taxriziv;

    public function __construct(?int $id, ?int $nomenclature, ?float $price, ?string $description, ?float $taxpatient, ?float $taxriziv) {
        $this->id = $id;
        $this->nomenclature = $nomenclature;
        $this->price = $price;
        $this->description = $description;
        $this->taxpatient = $taxpatient;
        $this->taxriziv = $taxriziv;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNomenclature(): ?int {
        return $this->nomenclature;
    }
    public function getPrice(): ?float {
        return $this->price;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getTaxPatient(): ?float {
        return $this->taxpatient;
    }

    public function getTaxRiziv(): ?float {
        return $this->taxriziv;
    }

    public function setId(?int $id): Examination {
        $this->id = $id;
        return $this;
    }

    public function setNomenclature(?int $nomenclature): Examination {
        $this->nomenclature = $nomenclature;
        return $this;
    }

    public function setPrice(?float $price): Examination {
        $this->price = $price;
        return $this;
    }
    public function setDescription(string $description): Examination {
        $this->description = $description;
        return $this;
    }

    public function setTaxPatient(?float $taxpatient): Examination {
        $this->taxpatient = $taxpatient;
        return $this;
    }

    public function setTaxRiziv(?float $taxriziv): Examination {
        $this->taxriziv = $taxriziv;
        return $this;
    }
    public function toArray(): array {
        return get_object_vars($this);
    }
}
