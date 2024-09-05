<?php
declare(strict_types=1);

namespace Business;

use Data\PatientDAO;

class PatientService
{
    public function patientList()
    {
        $dao = new PatientDAO();
        return $dao->getAll();
    }

    public function getPatient(int $patientId)
    {
        $dao = new PatientDAO();
        $id = intval($patientId);
        return $dao->getById($id);
    }

    public function newPatient() : void
    {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate input data
            $rr = htmlspecialchars(filter_input(INPUT_POST, 'rr', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $name = htmlspecialchars(filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $surname = htmlspecialchars(filter_input(INPUT_POST, 'surname', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $gender = htmlspecialchars(filter_input(INPUT_POST, 'gender', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $dateOfBirth = htmlspecialchars(filter_input(INPUT_POST, 'dateOfBirth', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $street = htmlspecialchars(filter_input(INPUT_POST, 'street', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $number = filter_input(INPUT_POST, 'number', FILTER_VALIDATE_INT);
            $city = htmlspecialchars(filter_input(INPUT_POST, 'city', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $zipcode = filter_input(INPUT_POST, 'zipcode', FILTER_VALIDATE_INT);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $phone = htmlspecialchars(filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $gmd = htmlspecialchars(filter_input(INPUT_POST, 'gmd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));

            // Initialize patient DAO
            $dao = new PatientDAO();

            //Check for duplicate rr
            if (!$dao->checkRr($rr)) {

                //Check if the mandatory fields are not empty and valid
                if ($rr && $name && $surname && $gender && $dateOfBirth && $street && $number && $city && $zipcode && $email && $phone) {
                    $newPatientData = [
                        'rr' => trim($rr),
                        'name' => preg_replace('/\s+/', '', $name),
                        'surname' => preg_replace('/\s+/', '', $surname),
                        'street' => preg_replace('/\s+/', '', $street),
                        'number' => $number,
                        'city' => preg_replace('/\s+/', '', $city),
                        'zipcode' => intval($zipcode),
                        'gender' => $gender,
                        'dateOfBirth' => $dateOfBirth,
                        'gmd' => $gmd,
                        'email' => $email,
                        'phone' => $phone,
                    ];
                    //Check if city exists
                    $checkCity = $dao->checkCity($city, $zipcode);
                    if ($checkCity) {
                        $result = $dao->create(...array_values($newPatientData));
                        if ($result) {
                            echo "New patient added successfully!";
                            // $patient = $dao->getByName($surname, $name);
                            // return $patient;
                        } else {
                            echo "Failed to add new patient. Please try again.";
                        }
                    }
                } else {
                    echo "Please fill in all required fields correctly.";
                }
            } else {
                echo "Patient already exists";
            }
        }
    }
    public function updatePatient()
    {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate input data
            $id = filter_input(INPUT_POST, 'patientId', FILTER_VALIDATE_INT);
            $rr = htmlspecialchars(filter_input(INPUT_POST, 'rr', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $name = htmlspecialchars(filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $surname = htmlspecialchars(filter_input(INPUT_POST, 'surname', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $gender = htmlspecialchars(filter_input(INPUT_POST, 'gender', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $dateOfBirth = htmlspecialchars(filter_input(INPUT_POST, 'dateOfBirth', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $street = htmlspecialchars(filter_input(INPUT_POST, 'street', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $number = filter_input(INPUT_POST, 'number', FILTER_VALIDATE_INT);
            $city = htmlspecialchars(filter_input(INPUT_POST, 'city', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $zipcode = filter_input(INPUT_POST, 'zipcode', FILTER_VALIDATE_INT);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $phone = htmlspecialchars(filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $gmd = htmlspecialchars(filter_input(INPUT_POST, 'gmd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));

            //Check if the mandatory fields are not empty and valid
            if ($id && $rr && $name && $surname && $gender && $dateOfBirth && $street && $number && $city && $zipcode && $email && $phone) {
                $newPatientData = [
                    'id' => intval($id),
                    'rr' => trim($rr),
                    'name' => preg_replace('/\s+/', '', $name),
                    'surname' => preg_replace('/\s+/', '', $surname),
                    'street' => preg_replace('/\s+/', '', $street),
                    'number' => $number,
                    'city' => preg_replace('/\s+/', '', $city),
                    'zipcode' => intval($zipcode),
                    'gender' => $gender,
                    'dateOfBirth' => $dateOfBirth,
                    'gmd' => $gmd,
                    'email' => $email,
                    'phone' => $phone,
                ];
                // Initialize patient DAO
                $dao = new PatientDAO();
                return $dao->update(...array_values($newPatientData));
            }
        } else {
            echo "Please fill in all required fields correctly.";
        }
    }
}
