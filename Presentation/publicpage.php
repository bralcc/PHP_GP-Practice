<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <title>Dokterspraktijk Mensana</title>
</head>
<body>
    <div class="container">
        <h1>Toegang praktijk</h1>
        <p>Hallo gebruiker, gelieve aan te melden.</p>

        <h3>Login</h3>
        <form action="index.php" method="POST">
            E-mail: <input type="email" name="email" required><br>
            Wachtwoord: <input type="password" name="password" required><br>
            <input type="submit" value="Log in" name="btnLogin">
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
        }
        if (isset($_SESSION["logout"])) {
            echo "<p class='logout-message'>" . $_SESSION["logout"] . "</p>";
        }
        ?>
    </div>
</body>

</html>
