<?php
declare(strict_types=1);
//editPatientView.php

//Reinsert whitespaces
function reinsertWhitespaces($string) {
    return preg_replace('/(?<!^)([A-Z])/', ' $1', $string);
}

// Split the full name variable
$fullname = explode(" ", $patient->getFullName());
$firstname = $fullname[0];
$lastname = reinsertWhitespaces($fullname[1]);

// Split the address in parts
$address = explode(" ", $patient->getAddress());
$street = reinsertWhitespaces($address[0]);
$number = $address[1];
$zipcode = $address[2];
$city = reinsertWhitespaces($address[3]);

// Set $gender variable
$gender = $patient->getGender();
?>

<!DOCTYPE html>
<body>
    <main>
    <div class="patientDiv">
    <h1>Bewerk patiënt</h1>

        <form action="index.php?action=updatePatient" method="post" class="editPatient">
            <input type="hidden" name="patientId" value=<?php echo intval($patient->getId()); ?>>
            <label for="rr">Rijksregister
                <input type="text" name="rr" id="rr" pattern="\d{11}" title="Rijksregister moet 11 cijfers bevatten"
                    value=<?php echo $patient->getRr(); ?> required>
            </label>
            <label for="name">Voornaam
                <input type="text" name="surname" id="surname" value="<?php echo $firstname ?>" required>
            </label>
            <label for="surname">Naam
                <input type="text" name="name" id="name" value="<?php echo $lastname ?>" required>
            </label>
            <label for="gender">Geslacht
                <select name="gender" id="gender" required>
                    <option value="" disabled <?php echo ($gender == '') ? 'selected' : ''; ?>>Selecteer geslacht</option>
                    <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Man</option>
                    <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Vrouw</option>
                    <option value="other" <?php echo ($gender == 'other') ? 'selected' : ''; ?>>Anders</option>
                </select>
            </label>

            <label for="dateOfBirth">Geboortedatum
                <input type="date" name="dateOfBirth" id="dateOfBirth" value="<?php echo $patient->getDateOfBirth(); ?>"
                    required>
            </label>
            <label for="street">Straatnaam
                <input type="text" name="street" id="street" value="<?php echo $street; ?>" required>
            </label>
            <label for="number">Huisnummer
                <input type="number" name="number" id="number" value="<?php echo intval($number); ?>" required>
            </label>
            <label for="zipcode">Postcode
                <input type="number" name="zipcode" id="zipcode" value="<?php echo intval($zipcode); ?>" required>
            </label>
            <label for="city">Woonplaats
                <input type="text" name="city" id="city" value="<?php echo $city ?>" required>
            </label>
            <label for="email">Email
                <input type="email" name="email" id="email" value="<?php echo $patient->getEmail(); ?>" required>
            </label>
            <label for="phone">Telefoon
                <input type="tel" name="phone" id="phone" pattern="\d{10}"
                    title="Telefoonnummer moet 10 cijfers bevatten" value="<?php echo $patient->getPhone(); ?>"
                    required>
            </label>
            <label for="gmd">GMD
                <input type="text" name="gmd" id="gmd" value="<?php echo $patient->getGmd(); ?>">
            </label>
            <button type="submit">Confirm edit</button>
        </form>
</body>
</main>
</html>
