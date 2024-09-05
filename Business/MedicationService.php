<?php
declare(strict_types=1);
// medicationService.php
namespace Business;

use Data\MedicationDAO;

class MedicationService
{
    public function getJson() {
        $dao = new MedicationDAO();
        $objects = $dao->getAll();
        $list = [];
        foreach ($objects as $object) {
            $list[] = $object->toArray();
        }
        return json_encode($list);
    }
    
    public function getAll(): array {
        $dao = new MedicationDAO();
        return $dao->getAll();
    }

    public function insertMedication() {
        //Sanitize input
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars(filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $dosage = htmlspecialchars(filter_input(INPUT_POST, 'dosage', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $description = htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW));
            // Check if data is not null
            if ($name && $dosage && $description) {
                $data = [
                    'name' => $name,
                    'dosage' => $dosage,
                    'description' => $description
                ];
                //Create DAO
                $dao = new MedicationDAO();
                $dao->insert($data);
            } else {
                echo "Please fill in all required fields correctly.";
            }
        } else {
            echo "Please fill in all required fields correctly.";
        }
    }

    public function updateMedication() {
        //Sanitize input
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT); // ID should be an integer
            $name = htmlspecialchars(filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $dosage = htmlspecialchars(filter_input(INPUT_POST, 'dosage', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $description = htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW));
            // Check if data is not null
            if ($id && $name && $dosage && $description) {
                $data = [
                    'name' => $name,
                    'dosage' => $dosage,
                    'description' => $description
                ];
                //Create DAO
                $dao = new MedicationDAO();
                $dao->update($data, $id);
            } else {
                echo "Please fill in all required fields correctly.";
            }
        } else {
            echo "Please fill in all required fields correctly.";
        }
    }

}