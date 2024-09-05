<?php
declare(strict_types=1);
//medicationDAO.php

namespace Data;

use Data\DBConfig;
use Entities\Medication;
use Exceptions\DatabaseErrorException;
use PDO;
use PDOException;

class MedicationDAO
{
    private $dbh;
    public function __construct()
    {
        try {
            $this->dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new DatabaseErrorException('Database connection error: ' . $e->getMessage());
        }
    }

    public function getAll() : array {
        try {
            $sql = 'SELECT * FROM medicatie';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $list = [];
            foreach ($result as $row) {
                $medication = new Medication(
                    $row['id'],
                    $row['name'],
                    $row['dosage'],
                    $row['description'],
                );
            $list[] = $medication;
        }
        return $list;
    } catch (PDOException $e) {
        throw new DatabaseErrorException('Error creating medication: ' . $e->getMessage());
        }
    }

    public function insert(array $data): void
    {
        try {
            // Prepare SQL
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), '?'));
            $sql = "INSERT INTO medicatie ($columns) VALUES ($placeholders)";
            $stmt = $this->dbh->prepare($sql);

            // Bind values and execute
            $result = $stmt->execute(array_values($data));

            // Kill db
            $this->dbh = null;

            if ($result) {
                echo "Medication added successfully";
            } else {
                echo "Something went seriously wrong";
            }
        } catch (PDOException $e) {
            throw new DatabaseErrorException('Error creating medication: ' . $e->getMessage());
        }
    }

    public function update(array $data, int $id): void
    {
        try {
            // Generate the SET part of the SQL statement dynamically
            $columns = array_keys($data);
            $placeholders = array_map(fn($column) => "$column = ?", $columns);
            $setClause = implode(', ', $placeholders);

            // Construct the SQL statement
            $sql = "UPDATE medicatie SET $setClause WHERE id = ?";
            $stmt = $this->dbh->prepare($sql);

            // Bind values and execute
            $values = array_values($data);
            $values[] = $id; // Add the ID to the end of the values array for the WHERE clause
            $stmt->execute($values);

            echo 'Medication updated successfully';
        } catch (PDOException $e) {
            throw new DatabaseErrorException('Error updating medication: ' . $e->getMessage());
        }
    }
}