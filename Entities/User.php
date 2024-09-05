<?php
declare(strict_types=1);
//user.php

namespace Entities;

use Exceptions\InvalidEmailException;
use Exceptions\PasswordsDontMatchException;
use Exceptions\UserDoesntExistException;
use Data\DBConfig;
use \PDO;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct(?int $cid = null, ?string $cname = null, ?string $cemail = null, ?string $cpassword = null)
    {
        $this->id = $cid;
        $this->name = $cname;
        $this->email = $cemail;
        $this->password = $cpassword;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
        $this->email = $email;
    }

    public function setPassword(string $password, $repeatPassword): void
    {
        if ($password !== $repeatPassword) {
            throw new PasswordsDontMatchException();
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function emailAlreadyExists(): bool
    {
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $dbh = null;
        return $rowCount > 0;
    }

    public function login()
    {
        $existingUser = $this->emailAlreadyExists();
        if (!$existingUser) {
            throw new UserDoesntExistException();
        }
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );

        $stmt = $dbh->prepare("SELECT id, password, surname FROM users WHERE email = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);

        // $passwordVerify = password_verify(
        //     $this->password,
        //     $resultSet["password"]
        // );
        // if (!$passwordVerify) {
        //     throw new PasswordIncorrectException();
        // }
        $this->id = $resultSet["id"];
        $this->name = $resultSet["surname"];
        $dbh = null;
        return $this;
    }
}
