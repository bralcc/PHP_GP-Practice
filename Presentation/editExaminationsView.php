<!DOCTYPE html>
<body>
    <main>
    <div class="container">
        <h1>Beheer onderzoeken</h1>
        <form action="index.php?action=addExamination" method="post">
            <label for="description">Beschrijving:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="price">Prijs:
            <input name="price" id="price" type="number">
            </label>
            <label for="taxpatient">Tax Patient:</label>
            <input type="number" id="taxpatient" name="taxpatient" step="0.01" required>
            <label for="taxriziv">Tax Riziv:</label>
            <input type="number" id="taxriziv" name="taxriziv" step="0.01" required>
            <label for="nomenclatuur">Nomenclatuur:</label>
            <input type="number" id="nomenclatuur" name="nomenclatuur" required>
            <button type="submit">Voeg onderzoek toe</button>
        </form>

        <h2>Onderzoeken</h2>
        <table>
            <thead>
                <tr>
                    <th>Nomenclatuur</th>
                    <th>Beschrijving</th>
                    <th>Prijs</th>
                    <th>Tax Patient</th>
                    <th>Tax Riziv</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($examinations as $examination): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($examination->getNomenclature()); ?></td>
                        <td><?php echo htmlspecialchars($examination->getDescription()); ?></td>
                        <td><?php echo htmlspecialchars($examination->getPrice()); ?></td>
                        <td><?php echo htmlspecialchars($examination->getTaxPatient()); ?></td>
                        <td><?php echo htmlspecialchars($examination->getTaxRiziv()); ?></td>
                        <td>
                            <form action="index.php?action=updateExamination" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($examination->getId()); ?>">
                                <textarea name="description" required><?php echo htmlspecialchars($examination->getDescription()); ?></textarea>
                                <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($examination->getPrice()); ?>" required>
                                <input type="number" name="taxpatient" step="0.01" value="<?php echo htmlspecialchars($examination->getTaxPatient()); ?>" required>
                                <input type="number" name="taxriziv" step="0.01" value="<?php echo htmlspecialchars($examination->getTaxRiziv()); ?>" required>
                                <input type="number" name="nomenclatuur" value="<?php echo htmlspecialchars($examination->getNomenclature()); ?>" hidden>
                                <button type="submit">Update</button>
                            </form>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </main>
</body>
</html>
