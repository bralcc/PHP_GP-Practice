<?php
declare(strict_types=1);

namespace Business;

use Data\ExaminationDAO;

class ExaminationService {
    public function getJson() {
        $dao = new ExaminationDAO();
        $objects = $dao->getAll();
        $list = [];
        foreach ($objects as $object) {
            $list[] = $object->toArray();
        }
        return json_encode($list);
    }

    public function getAll(): array {
        $dao = new ExaminationDAO();
        return $dao->getAll();
    }

    public function insertExamination() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $description = htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $taxpatient = filter_input(INPUT_POST, 'taxpatient', FILTER_VALIDATE_FLOAT);
            $taxriziv = filter_input(INPUT_POST, 'taxriziv', FILTER_VALIDATE_FLOAT);
            $nomenclatuur = filter_input(INPUT_POST, 'nomenclatuur', FILTER_VALIDATE_INT);

            if ($description && $price && $taxpatient !== false && $taxriziv !== false && $nomenclatuur) {
                $data = [
                    'nomenclatuur' => $nomenclatuur,
                    'beschrijving' => $description,
                    'prijs'=> $price,
                    'last_patient' => $taxpatient,
                    'last_riziv' => $taxriziv,
                ];

                $dao = new ExaminationDAO();
                $dao->insert($data);
            } else {
                echo "Please fill in all required fields correctly.";
            }
        }
    }

    public function updateExamination() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
            $description = htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $taxpatient = filter_input(INPUT_POST, 'taxpatient', FILTER_VALIDATE_FLOAT);
            $taxriziv = filter_input(INPUT_POST, 'taxriziv', FILTER_VALIDATE_FLOAT);
            $nomenclatuur = filter_input(INPUT_POST, 'nomenclatuur', FILTER_VALIDATE_INT);

            if ($id && $description && $price && $taxpatient !== false && $taxriziv !== false && $nomenclatuur) {
                $data = [
                    'beschrijving' => $description,
                    'nomenclatuur' => $nomenclatuur,
                    'prijs'=> $price,
                    'last_patient' => $taxpatient,
                    'last_riziv' => $taxriziv,
                ];

                $dao = new ExaminationDAO();
                $dao->update($data, $id);
            } else {
                echo "Please fill in all required fields correctly.";
            }
        }
    }

}
