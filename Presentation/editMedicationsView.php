<!DOCTYPE html>
<body>
    <main>
    <div class="container">
        <h1>Beheer medicatie</h1>
        <form action="index.php?action=addMedication" method="post">
            <label for="name">Naam:</label>
            <input type="text" id="name" name="name" required>
            <label for="dosage">Dosering:</label>
            <input type="text" id="dosage" name="dosage" required>
            <label for="description">Beschrijving:</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit">Voeg toe</button>
        </form>

        <h2>Medicatie</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Dosering</th>
                    <th>Beschrijving</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medications as $medication): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($medication->getId()); ?></td>
                        <td><?php echo htmlspecialchars($medication->getName()); ?></td>
                        <td><?php echo htmlspecialchars($medication->getDosage()); ?></td>
                        <td><?php echo htmlspecialchars($medication->getDescription()); ?></td>
                        <td>
                            <form action="index.php?action=updateMedication" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($medication->getId()); ?>">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($medication->getName()); ?>" required>
                                <input type="text" name="dosage" value="<?php echo htmlspecialchars($medication->getDosage()); ?>" required>
                                <textarea name="description" required><?php echo htmlspecialchars($medication->getDescription()); ?></textarea>
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
