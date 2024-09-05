<?php
declare(strict_types=1);

namespace Entities;

use DateTime;
use DateTimeZone;

class Consultation
{
    private $id;
    private $patient_id;
    private $starttime;
    private $endtime;
    private $complaints;
    private $diagnose;
    private $price;
    private $paid;

    public function __construct(
        ?int $id = null,
        ?int $patient_id = null,
        ?string $starttime = null,
        ?string $endtime = null,
        ?string $complaints = null,
        ?string $diagnose = null,
        ?float $price = null,
        ?bool $paid = null
    ) {
        $this->id = $id;
        $this->patient_id = $patient_id;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
        $this->complaints = $complaints;
        $this->diagnose = $diagnose;
        $this->price = $price;
        $this->paid = $paid;
    }
    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getPatientId(): int
    {
        return $this->patient_id;
    }
    public function getStartTime(): ?string
    {
        return $this->starttime;
    }
    public function getEndTime(): ?string
    {
        return $this->endtime;
    }
    public function getComplaints(): ?string
    {
        return $this->complaints;
    }
    public function getDiagnose(): ?string
    {
        return $this->diagnose;
    }
    public function getPrice(): ?float
    {
        return $this->price;
    }
    public function getPaid(): ?bool
    {
        return $this->paid;
    }
    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setPatientId(int $patient_id): void
    {
        $this->patient_id = $patient_id;
    }
    public function setStartTime(): void
    {
        $timeObject = new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
        $this->starttime = $timeObject->format('Y-m-d h:i:s');
    }
    public function setEndTime($endtime): void
    {
        $this->endtime = $endtime;
    }
    public function setComplaints(string $complaints): void
    {
        $this->complaints = $complaints;
    }
    public function setDiagnose(string $diagnose): void
    {
        $this->diagnose = $diagnose;
    }
    public function setPrice($price): void
    {
        $this->price = $price;
    }
    public function setPaid(bool $paid): void
    {
        $this->paid = $paid;
    }
}
