<?php
declare(strict_types=1);
//index.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';

use Business\MedicationService;
use Business\ConsultationService;
use Business\ExaminationService;
use Business\PatientService;
use Entities\User;
use Exceptions\PasswordIncorrectException;
use Exceptions\UserDoesntExistException;


// Start session
session_start();

// Initialize the error session variable
$_SESSION["error"] = "";

// Handle login
if (isset($_POST["btnLogin"])) {
    $email = "";
    $password = "";

    if (!empty($_POST["email"])) {
        $email = htmlspecialchars($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"] .= "Invalid email format.<br>";
        }
    } else {
        $_SESSION["error"] .= "Email needs to be filled in.<br>";
    }

    if (!empty($_POST["password"])) {
        $password = htmlspecialchars($_POST["password"]);
    } else {
        $_SESSION["error"] .= "Password needs to be filled in.<br>";
    }

    if ($_SESSION["error"] === "") {
        try {
            $user = new User(null, null, $email, $password);
            $user = $user->login();
            $_SESSION["user"] = serialize($user);
            unset($_SESSION['error']);
            header("Location: index.php");
            exit;
        } catch (PasswordIncorrectException $e) {
            $_SESSION["error"] .= "Password is incorrect.<br>";
        } catch (UserDoesntExistException $e) {
            $_SESSION["error"] .= "User doesn't exist.<br>";
        }
    }

    // Redirect back to the login page if there are errors
    header("Location: index.php");
    exit;
}

// Handle logout
if (isset($_GET["logout"])) {
    unset($_SESSION["user"]);
    $_SESSION["logout"] = "Logged out successfully";
    header("Location: index.php");
    exit;
}

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    include_once 'Presentation/publicpage.php';
    includeFooter();
    exit;
}

$user = unserialize($_SESSION['user']);

// Functions for including header and footer
function includeHeader()
{
    global $user; // Make sure $user is accessible in the header
    include 'Presentation/header.php';
}

function includeFooter()
{
    include_once 'Presentation/footer.php';
}

$action = $_GET['action'] ?? 'index';
switch ($action) {
    case 'newPatient':
        includeHeader();
        handleNewPatient();
        includeFooter();
        break;
    case 'addPatient':
        includeHeader();
        handleAddPatient();
        includeFooter();
        break;
    case 'newConsultation':
        includeHeader();
        handleNewConsultation();
        includeFooter();
        break;
    case 'endConsultation':
        includeHeader();
        handleEndConsultation();
        includeFooter();
        break;
    case 'existingPatient':
        includeHeader();
        handleExistingPatient();
        includeFooter();
        break;
    case 'updatePatient':
        includeHeader();
        handleUpdatePatient();
        includeFooter();
        break;
    case 'editMedications':
        includeHeader();
        handleEditMedications();
        includeFooter();
        break;
    case 'editExaminations':
        includeHeader();
        handleEditExaminations();
        includeFooter();
        break;
    case 'addMedication':
    case 'updateMedication':
    case 'deleteMedication':
        handleModifyMedication($action);
        break;
    case 'addExamination':
    case 'updateExamination':
    case 'deleteExamination':
        handleModifyExamination($action);
        break;
    case 'error':
        includeHeader();
        handleError("An error occurred, please try again.");
        includeFooter();
        break;
    default:
        includeHeader();
        handleIndex();
        includeFooter();
        break;
}

// Functions switch statement

function handleNewPatient()
{
    include_once 'Presentation/newPatientView.php';
}

function handleAddPatient()
{
    try {
        $pService = new PatientService();
        $pService->newPatient();
        handleIndex();
    } catch (Exception $e) {
        handleError("Failed to add patient: " . $e->getMessage());
    }
}

function handleEditPatient()
{
    if (isset($_POST['patientId'])) {
        $pService = new PatientService();
        $patient = $pService->getPatient(intval($_POST['patientId']));
        include_once 'Presentation/editPatientView.php';
    } else {
        handleError('No patient selected');
    }
}

function handleUpdatePatient()
{
    $pService = new PatientService();
    $pService->updatePatient();
    handleIndex();
}

function handleNewConsultation()
{
    if (isset($_POST['patientId'])) {
        $pService = new PatientService();
        $mService = new MedicationService();
        $eService = new ExaminationService();
        $examinationsJson = $eService->getJson();
        $medicationsJson = $mService->getJson();
        $patient = $pService->getPatient(intval($_POST['patientId']));
        include_once 'Presentation/consultationView.php';
    } else {
        handleError('No patient selected');
    }
}

function handleEndConsultation()
{
    try {
        $cService = new ConsultationService();
        $consultationId = $cService->addConsultation();
        // Insert Medications and Examinations
        if (isset($_POST['medications']) && !empty($_POST['medications'])) {
            $medicationData = $_POST['medications'];
            $cService->insertConsultMedication($consultationId, $medicationData);
        }

        if (isset($_POST['examinations']) && !empty($_POST['examinations'])) {
            $examinationData = $_POST['examinations'];
            $cService->insertConsultExamination($consultationId, $examinationData);
        }
        handleIndex();
    } catch (Exception $e) {
        handleError("Failed to end consultation: " . $e->getMessage());
    }
}

function handleIndex()
{
    $pService = new PatientService();
    $patients = $pService->patientList();
    global $user; // Gebruik de globale $user variabele
    include_once 'Presentation/dashboard.php';
}

function handleExistingPatient()
{
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'newConsultation':
                handleNewConsultation();
                break;
            case 'editPatient':
                handleEditPatient();
                break;
            default:
                handleError("An error occurred, please try again.");
                break;
        }
    }
}

function handleError(string $message)
{
    echo $message;
    handleIndex();
}

function handleEditMedications()
{
    $mService = new MedicationService();
    $medications = $mService->getAll();
    include_once 'Presentation/editMedicationsView.php';
}

function handleModifyMedication($action)
{
    $mService = new MedicationService();
    switch ($action) {
        case 'addMedication':
            $mService->insertMedication();
            break;
        case 'updateMedication':
            $mService->updateMedication();
            break;
    }
    header("Location: index.php?action=editMedications");
    exit;
}

function handleEditExaminations()
{
    $eService = new ExaminationService();
    $examinations = $eService->getAll();
    include_once 'Presentation/editExaminationsView.php';
}

function handleModifyExamination($action)
{
    $eService = new ExaminationService();
    switch ($action) {
        case 'addExamination':
            $eService->insertExamination();
            break;
        case 'updateExamination':
            $eService->updateExamination();
            break;
    }
    header("Location: index.php?action=editExaminations");
    exit;
}
