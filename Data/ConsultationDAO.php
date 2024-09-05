<?php
declare(strict_types=1);

namespace Data;

use PDO;
use PDOException;
use Exceptions\DatabaseErrorException;

class ConsultationDAO
{
    public function getAll()
    {
        try {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM consultaties INNER JOIN patienten ON patienten.id = consultaties.patient_id ORDER BY consultaties.starttijd DESC";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }

    public function getById($id)
    {
        try {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM consultaties WHERE patient_id = :id ORDER BY starttijd DESC";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }

    public function create($patient_id, $starttime, $endtime, $complaints, $diagnose, $price, $paid)
    {
        try {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO consultaties (patient_id, starttijd, eindtijd, klachten, diagnose, bedrag, betaald)
                VALUES (:patient_id, :starttime, :endtime, :complaints, :diagnose, :price, :paid)";

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":patient_id", $patient_id, PDO::PARAM_INT);
            $stmt->bindParam(":starttime", $starttime);
            $stmt->bindParam(":endtime", $endtime);
            $stmt->bindParam(":complaints", $complaints, PDO::PARAM_STR);
            $stmt->bindParam(":diagnose", $diagnose, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":paid", $paid, PDO::PARAM_BOOL);
            $stmt->execute();

            return $dbh->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }

    public function insertConsultMedication($consultationId, $medicationData)
    {
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $sql = "INSERT INTO consultatie_medicatie (consultatie_id, medicatie_id) VALUES (:consultatie_id, :medicatie_id)";
            $stmt = $dbh->prepare($sql);

            foreach ($medicationData as $medicationId) {
                $stmt->bindParam(":consultatie_id", $consultationId, PDO::PARAM_INT);
                $stmt->bindParam(":medicatie_id", $medicationId, PDO::PARAM_INT);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }

    public function insertConsultExamination($consultationId, $examinationData)
    {
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $sql = "INSERT INTO consultatie_onderzoeken (consultatie_id, onderzoek_id) VALUES (:consultatie_id, :onderzoek_id)";
            $stmt = $dbh->prepare($sql);

            foreach ($examinationData as $examinationId) {
                $stmt->bindParam(":consultatie_id", $consultationId, PDO::PARAM_INT);
                $stmt->bindParam(":onderzoek_id", $examinationId, PDO::PARAM_INT);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }

    public function getMedicationsByConsultationId(int $consultationId): array
    {
        try {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT m.name, m.dosage FROM medicatie m
                    INNER JOIN consultatie_medicatie cm ON m.id = cm.medicatie_id
                    WHERE cm.consultatie_id = :consultationId";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":consultationId", $consultationId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }

    public function getExaminationsByConsultationId(int $consultationId): array
    {
        try {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT o.beschrijving FROM onderzoeken o
                    INNER JOIN consultatie_onderzoeken co ON o.id = co.onderzoek_id
                    WHERE co.consultatie_id = :consultationId";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":consultationId", $consultationId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            $dbh = null;
        }
    }
}
