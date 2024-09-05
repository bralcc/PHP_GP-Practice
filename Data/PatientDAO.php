<?php
declare(strict_types=1);
//patientDAO.php
namespace Data;

use PDO;
use PDOException;
use Exception;
use Entities\Patient;
use Exceptions\DatabaseErrorException;

class PatientDAO
{
    public function getAll(): array
    {
        try {
            //Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //SQL Statement
            $sql = "SELECT patienten.id, rr, familienaam, voornaam, straatnaam, huisnr,
            postcode, woonplaats, geslacht, geboortedatum, gmd, email, telefoon FROM patienten INNER JOIN
            plaatsen ON plaatsen.id = patienten.plaats_id";

            //Prepare statement
            $stmt = $dbh->prepare($sql);

            //Execute statement
            $stmt->execute();

            //Make array and add patient
            $patients = [];
            foreach ($stmt as $row) {
                $patient = new Patient();
                $patient->setId($row["id"]);
                $patient->setRr($row["rr"]);
                $patient->setFullName($row["voornaam"], $row["familienaam"]);
                $patient->setAddress($row["straatnaam"], $row["huisnr"], $row["postcode"], $row['woonplaats']);
                $patient->setGender($row['geslacht']);
                $patient->setDateOfBirth($row['geboortedatum']);
                $patient->setGmd($row['gmd']);
                $patient->setEmail($row['email']);
                $patient->setPhone($row['telefoon']);
                $patient->setAge();
                $patients[] = $patient;
            }
            //Return list of patients
            return $patients;

        } catch (PDOException $e) {
            error_log("Database error:" . $e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            $dbh = null;
        }
    }

    public function getById(int $id): Patient
    {
        try {
            // Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // SQL Statement
            $sql = "SELECT patienten.id, rr, familienaam, voornaam, straatnaam, huisnr,
            postcode, woonplaats, geslacht, geboortedatum, gmd, email, telefoon FROM patienten INNER JOIN
            plaatsen ON plaatsen.id = patienten.plaats_id WHERE :id = patienten.id";

            // Prepare statement
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            // Execute statement
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Create patient class
            $patient = new Patient();
            $patient->setId($row["id"]);
            $patient->setRr($row["rr"]);
            $patient->setFullName($row["voornaam"], $row["familienaam"]);
            $patient->setAddress($row["straatnaam"], $row["huisnr"], $row["postcode"], $row['woonplaats']);
            $patient->setGender($row['geslacht']);
            $patient->setDateOfBirth($row['geboortedatum']);
            $patient->setGmd($row['gmd']);
            $patient->setEmail($row['email']);
            $patient->setPhone($row['telefoon']);
            $patient->setAge();

            // Return patient object
            return $patient;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            if ($dbh) {
                $dbh = null;
            }
        }
    }

    public function getByName(string $surname, string $lastname): Patient
    {
        try {
            // Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // SQL Statement
            $sql = "SELECT patienten.id, rr, familienaam, voornaam, straatnaam, huisnr,
            postcode, woonplaats, geslacht, geboortedatum, gmd, email, telefoon FROM patienten INNER JOIN
            plaatsen ON plaatsen.id = patienten.plaats_id WHERE :surname = patienten.voornaam AND :lastname = patienten.familienaam";

            // Prepare statement
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":voornaam", $surname, PDO::PARAM_STR);
            $stmt->bindParam(":familienaam", $lastname, PDO::PARAM_STR);

            // Execute statement
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Create patient class
            $patient = new Patient();
            $patient->setId($row["id"]);
            $patient->setRr($row["rr"]);
            $patient->setFullName($row["voornaam"], $row["familienaam"]);
            $patient->setAddress($row["straatnaam"], $row["huisnr"], $row["postcode"], $row['woonplaats']);
            $patient->setGender($row['geslacht']);
            $patient->setDateOfBirth($row['geboortedatum']);
            $patient->setGmd($row['gmd']);
            $patient->setEmail($row['email']);
            $patient->setPhone($row['telefoon']);
            $patient->setAge();

            // Return patient object
            return $patient;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            if ($dbh) {
                $dbh = null;
            }
        }
    }

    public function create($rr, $name, $surname, $street, $number, $city, $zipcode, $gender, $dateOfBirth, $gmd, $email, $phone)
    {
        try {
            // Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );

            // SQL Statement
            $sql = "INSERT INTO patienten (rr, familienaam, voornaam, straatnaam, huisnr, plaats_id, geslacht, geboortedatum, email, gmd, telefoon)
                    SELECT
                        :rr AS rr,
                        :surname AS voornaam,
                        :name AS familienaam,
                        :street AS straatnaam,
                        :number AS huisnr,
                        (SELECT ID FROM plaatsen WHERE woonplaats = :city AND postcode = :zipcode) AS plaats_id,
                        :gender AS geslacht,
                        :dateOfBirth AS geboortedatum,
                        :email AS email,
                        :gmd AS gmd,
                        :phone AS telefoon";

            // Prepare statement
            $stmt = $dbh->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':rr', $rr);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':street', $street);
            $stmt->bindParam(':number', $number);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':zipcode', $zipcode);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dateOfBirth', $dateOfBirth);
            $stmt->bindParam(':gmd', $gmd);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);

            // Execute statement
            $stmt->execute();

            // Success message or return
            return "Patient successfully added!";

        } catch (PDOException $e) {
            error_log("" . $e->getMessage());
            throw new Exception("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            $dbh = null;
        }
    }

    public function update($id, $rr, $name, $surname, $street, $number, $city, $zipcode, $gender, $dateOfBirth, $gmd, $email, $phone)
    {
        try {
            // Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );

            // SQL Statement
            $sql = "UPDATE patienten SET
            rr = :rr,
            familienaam = :surname,
            voornaam = :name,
            straatnaam = :street,
            huisnr = :number,
            plaats_id = (SELECT ID FROM plaatsen WHERE woonplaats = :city AND postcode = :zipcode),
            geslacht = :gender,
            geboortedatum = :dateOfBirth,
            email = :email,
            gmd = :gmd,
            telefoon = :phone
            WHERE id = :id;
            ";

            // Prepare statement
            $stmt = $dbh->prepare($sql);

            // Bind parameters
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(':rr', $rr);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':street', $street);
            $stmt->bindParam(':number', $number);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':zipcode', $zipcode);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dateOfBirth', $dateOfBirth);
            $stmt->bindParam(':gmd', $gmd);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);

            // Execute statement
            $result = $stmt->execute();

            // Success message or return
            if ($result) {
                echo "Patient successfully edited!";
                return true;
            } else {
                echo "Something went seriously wrong";
                return false;
            }

        } catch (PDOException $e) {
            error_log("" . $e->getMessage());
            throw new Exception("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            $dbh = null;
        }
    }

    public function checkCity($city, $zipcode)
    {
        try {
            // Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // SQL Statement to check if the city and zipcode already exist
            $sql = "SELECT woonplaats, postcode FROM plaatsen WHERE postcode = :postcode AND woonplaats = :woonplaats";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":postcode", $zipcode, PDO::PARAM_INT);
            $stmt->bindParam(":woonplaats", $city, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                // New SQL Statement for adding place
                $sql = $sql = "INSERT INTO plaatsen (woonplaats, postcode) VALUES (:woonplaats, :postcode)";
                $stmt = $dbh->prepare($sql);

                // Bind parameters
                $stmt->bindParam(":woonplaats", $city);
                $stmt->bindParam(":postcode", $zipcode, PDO::PARAM_STR);
                $stmt->execute();
            }

            return true;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            if ($dbh) {
                $dbh = null;
            }
        }
    }

    public function checkRr($rr) {
        try {
            // Open database connection
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // SQL Statement to check if the rr already exists
            $sql = "SELECT rr FROM patienten WHERE rr = :rr";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':rr', $rr, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
    
            if ($result) {
                return "Patient already exists.";
            } else {
                return false;
            }
    
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new DatabaseErrorException("A database error occurred. Please try again later.");
        } finally {
            // Close database connection
            $dbh = null;
        }
    }
}
