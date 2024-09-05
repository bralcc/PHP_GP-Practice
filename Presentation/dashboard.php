<!DOCTYPE html>
<body>
    <main>
    <div class="container">
        <h1>Hallo, <?php echo htmlspecialchars($user->getName(), ENT_QUOTES, 'UTF-8'); ?></h1>
        <div id="newPatient">
            <h2>Nieuwe patiënt</h2>
            <form action="index.php?action=newPatient" method="post">
                <input type="submit" value="Nieuwe patiënt">
            </form>
        </div>

        <div id="existingPatient">
            <h2>Bestaande patiënt</h2>
            <form action="index.php?action=existingPatient" method="post">
                <select name="patientId" id="patientId">
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?php echo htmlspecialchars($patient->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($patient->getFullName(), ENT_QUOTES, 'UTF-8') . ' (' . htmlspecialchars($patient->getRr(), ENT_QUOTES, 'UTF-8') . ')'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="button-container">
                    <button type="submit" name="action" value="newConsultation">Nieuwe consultatie</button>
                    <button type="submit" name="action" value="editPatient">Bewerk patiënt</button>
                </div>
            </form>
        </div>

        <div id="manageMedications">
            <h2>Beheer Medicatie</h2>
            <form action="index.php?action=editMedications" method="post">
                <input type="submit" value="Beheer Medicatie">
            </form>
        </div>

        <div id="manageExaminations">
            <h2>Beheer Onderzoeken</h2>
            <form action="index.php?action=editExaminations" method="post">
                <input type="submit" value="Beheer Onderzoeken">
            </form>
        </div>
    </div>
    </main>
</body>

</html>
