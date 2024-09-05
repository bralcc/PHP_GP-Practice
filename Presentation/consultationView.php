<?php
//Reinsert whitespaces
function reinsertWhitespaces($string)
{
    return preg_replace('/(?<!^)([A-Z])/', ' $1', $string);
}
$consultationService = new Business\ConsultationService();
$previousConsultations = $consultationService->getPreviousConsultations($patient->getId());
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Pass JSON to script -->
    <script>
        const medications = <?php echo $medicationsJson; ?>;
        const examinations = <?php echo $examinationsJson; ?>;
    </script>
    <script src="script.js" defer></script>
    <title>New consultation</title>
</head>

<body>
    <main>
        <div class="container">
            <h1>Consultatie</h1>
            <p>Verstreken tijd:</p>
            <p id="timeConsultation"></p>

            <form action="index.php?action=endConsultation" method="post">
                <table id="infoPatient" aria-hidden="true">
                    <tr>
                        <td>Patient:</td>
                        <td><?php echo reinsertWhitespaces($patient->getFullName()) . " " . $patient->getAge() . " " . $patient->getGender(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Rijksregister:</td>
                        <td><?php echo $patient->getRr(); ?></td>
                    </tr>
                    <tr>
                        <td>Adres:</td>
                        <td><?php echo reinsertWhitespaces($patient->getAddress()); ?></td>
                    </tr>
                    <tr>
                        <td>Geboortedatum:</td>
                        <td><?php echo $patient->getDateOfBirth(); ?></td>
                    </tr>
                    <tr>
                        <td>Telefoon:</td>
                        <td><?php echo $patient->getPhone(); ?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><?php echo $patient->getEmail(); ?></td>
                    </tr>
                </table>
                <br>
                <!-- put patientId in hidden form element -->
                <input type="hidden" name="patientId"
                    value="<?php if (isset($patient)) {
                        echo $patient->getId();
                    } else {
                        header("Location:index.php?action=error");
                        die();
                    } ?>">
                <!-- generate date in input -->
                <input type="hidden" id="startTime" name="startTime"
                    value="<?php $timeObject = new DateTime('now', new DateTimeZone('Europe/Brussels'));
                    echo $timeObject->format('Y-m-d H:i:s'); ?>">
                <label for="complaints">Klachten:
                    <textarea name="complaints" id="complaints" required></textarea>
                </label>
                <br>
                <p>Onderzoeken</p>
                <div id="examinationsContainer">
                    <!-- Examinations dynamically loaded through script -->
                </div>
                <button type="button" class="add" id="addExaminationButton">Voeg onderzoek toe</button>
                <br><br>
                <label for="diagnose">Diagnose:
                    <textarea name="diagnose" id="diagnose" required></textarea>
                </label>
                <br>
                <p>Medicatie</p>
                <div id="medicationsContainer">
                    <!-- Medications dynamically loaded through script -->
                </div>
                <button type="button" class="add" id="addMedicationButton">Voeg medicatie toe</button>
                <br><br>
                <label for="fullprice">Totale prijs:
                    <input type="number" name="fullprice" id="fullprice" min="0.00" max="10000.00" step="0.01" required>
                </label>
                <label for="price">Prijs patient:
                    <input type="number" name="price" id="price" min="0.00" max="10000.00" step="0.01" required>
                </label>
                <label for="paid">Betaald:
                    <input type="checkbox" name="paid" id="paid">
                </label>
                <br><br>
                <button type="submit">Einde consultatie</button>
            </form>


            <button id="toggle-all-consultations">Toon eerdere consultaties</button>

            <div id="previous-consultations" style="display: none;">
                <h2>Eerdere Consultaties</h2>
                <?php foreach ($previousConsultations as $consultation): ?>
                    <div class="previous-consultation">
                        <p class="toggle-button"><strong>Datum:</strong>
                            <?php echo htmlspecialchars($consultation['starttijd']); ?></p>
                        <div class="consultation-details">
                            <p><strong>Klachten:</strong> <?php echo htmlspecialchars($consultation['klachten']); ?></p>
                            <p><strong>Diagnose:</strong> <?php echo htmlspecialchars($consultation['diagnose']); ?></p>
                            <p><strong>Medicatie:</strong>
                            <ul>
                                <?php foreach ($consultation['medications'] as $medication): ?>
                                    <li><?php echo htmlspecialchars($medication['name']) . " - " . htmlspecialchars($medication['dosage']); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            </p>
                            <p><strong>Onderzoeken:</strong>
                            <ul>
                                <?php foreach ($consultation['examinations'] as $examination): ?>
                                    <li><?php echo htmlspecialchars($examination['beschrijving']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>

</html>
