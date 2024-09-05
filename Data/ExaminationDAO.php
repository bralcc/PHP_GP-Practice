<?php
declare(strict_types= 1);

namespace Data;

use Data\DBConfig;
use Entities\Examination;
use Exceptions\DatabaseErrorException;
use PDO;
use PDOException;

class ExaminationDAO {
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

    public function getAll(): array {
        try {
            $sql = 'SELECT * FROM onderzoeken';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $list = [];
            foreach ($result as $row) {
                $examination = new Examination(
                    $row['id'],
                    $row['nomenclatuur'],
                    $row['prijs'],
                    $row['beschrijving'],
                    $row['last_patient'],
                    $row['last_riziv']
                );
                $list[] = $examination;
            }
            return $list;
        } catch (PDOException $e) {
            throw new DatabaseErrorException('Error fetching examinations: ' . $e->getMessage());
        }
    }

    public function insert(array $data): void
    {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), '?'));
            $sql = "INSERT INTO onderzoeken ($columns) VALUES ($placeholders)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array_values($data));
            $this->dbh = null;

            echo "Examination added successfully";
        } catch (PDOException $e) {
            throw new DatabaseErrorException('Error creating examination: ' . $e->getMessage());
        }
    }

    public function update(array $data, int $id): void
    {
        try {
            $columns = array_keys($data);
            $placeholders = array_map(fn($column) => "$column = ?", $columns);
            $setClause = implode(', ', $placeholders);
            $sql = "UPDATE onderzoeken SET $setClause WHERE id = ?";
            $stmt = $this->dbh->prepare($sql);
            $values = array_values($data);
            $values[] = $id;
            $stmt->execute($values);

            echo 'Examination updated successfully';
        } catch (PDOException $e) {
            throw new DatabaseErrorException('Error updating examination: ' . $e->getMessage());
        }
    }

    
}
