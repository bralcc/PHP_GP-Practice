<?php
declare(strict_types=1);
//patient.php
namespace Entities;

use DateTime;
use DateTimeZone;

class Patient
{
    private $id;
    private $rr;
    private $fullname;
    private $address;
    private $email;
    private $gender;
    private $dateOfBirth;
    private $gmd;
    private $phone;
    private $age;

    public function __construct(?int $id = null, ?string $rr = null, ?string $fullname = null, ?string $address = null, ?string $gender = null, ?string $dateOfBirth = null, ?string $gmd = null, ?string $phone = null, ?int $age = null)
    {
        $this->id = $id;
        $this->rr = $rr;
        $this->fullname = $fullname;
        $this->address = $address;
        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
        $this->gmd = $gmd;
        $this->phone = $phone;
        $this->age = $age;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getRr(): string
    {
        return $this->rr;
    }
    public function getFullName(): string
    {
        return $this->fullname;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getGender(): string
    {
        return $this->gender;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }
    public function getAge(): int
    {
        return $this->age;
    }
    public function getGmd(): string
    {
        return $this->gmd;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    //SETTERS
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setRr(string $rr): void
    {
        $this->rr = $rr;
    }
    public function setFullName(string $lastname, string $surname): void
    {
        $this->fullname = $surname . ' ' . $lastname;
    }
    public function setAddress(string $street, int $number, int $zipcode, string $city): void
    {
        $this->address = $street . ' ' . $number . ', ' . $zipcode . ' ' . $city;
    }
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }
    public function setDateOfBirth(string $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }
    public function setGmd(string $gmd): void
    {
        $this->gmd = $gmd;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setAge() {
        $birthDate = new DateTime($this->dateOfBirth);
        $currentDate = new DateTime('now', new DateTimeZone('Europe/Brussels'));
        $ageInterval = $birthDate->diff($currentDate);
        $this->age = $ageInterval->y;
    }
}
