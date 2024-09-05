<?php
declare(strict_types=1);

namespace Business;

use Data\ConsultationDAO;
use DateTime;
use DateTimeZone;

class ConsultationService
{
    public function getAll()
    {
        $dao = new ConsultationDAO();
        return $dao->getAll();
    }
    
    public function getConsultation($id)
    {
        $dao = new ConsultationDAO();
        return $dao->getById($id);
    }

    public function addConsultation()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $patient_id = filter_input(INPUT_POST, 'patientId', FILTER_VALIDATE_INT);
            $starttime = htmlspecialchars(filter_input(INPUT_POST, 'startTime', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $timeObject = new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
            $endtime = $timeObject->format('Y-m-d H:i:s');
            $complaints = htmlspecialchars(filter_input(INPUT_POST, 'complaints', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $diagnose = htmlspecialchars(filter_input(INPUT_POST, 'diagnose', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $paid = isset($_POST['paid']) && trim($_POST['paid']) === 'on';

            if ($patient_id && $starttime && $endtime && $complaints && $diagnose && $price) {
                $newConsultationData = [
                    'patientId' => $patient_id,
                    'starttime' => $starttime,
                    'endtime' => $endtime,
                    'complaints' => $complaints,
                    'diagnose' => $diagnose,
                    'price' => $price,
                    'paid' => $paid
                ];

                $dao = new ConsultationDAO();
                return $dao->create(...array_values($newConsultationData));
            } else {
                throw new \Exception("Please fill in all required fields correctly.");
            }
        }
    }

    public function insertConsultMedication($consultationId, $medicationData)
    {
        $dao = new ConsultationDAO();
        $dao->insertConsultMedication($consultationId, $medicationData);
    }

    public function insertConsultExamination($consultationId, $examinationData)
    {
        $dao = new ConsultationDAO();
        $dao->insertConsultExamination($consultationId, $examinationData);
    }

    public function addDefaultExamination($consultationId)
    {
        $dao = new ConsultationDAO();
        $defaultExaminationId = 11; // Zorg ervoor dat dit ID overeenkomt met het ID van het "Consultatie" onderzoek in je database
        $dao->insertConsultExamination($consultationId, [$defaultExaminationId]);
    }

    public function getPreviousConsultations(int $patientId): array
    {
        $dao = new ConsultationDAO();
        $consultations = $dao->getById($patientId);

        foreach ($consultations as &$consultation) {
            $consultation['medications'] = $dao->getMedicationsByConsultationId($consultation['id']);
            $consultation['examinations'] = $dao->getExaminationsByConsultationId($consultation['id']);
        }

        return $consultations;
    }
}
