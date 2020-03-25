<?php
function resettingPassword() 
{   
    if (isset($_GET['emailreset'])) {
       $email = $_GET['emailreset']; 
    }
    $dsn = "mysql:host=localhost;dbname=social_gamia";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['submitpassword'])) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $check_attempt = $pdo->prepare("UPDATE users SET password = ? WHERE email=?;");
            $check_attempt->execute([$hash, $_GET['emailreset']]);
        } else if (!isset($_POST['submitpassword'])) {
            throw new Exception("Something went wrong with the communication");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="JS/script.js"></script>
</head>
<body>
    <header>
        <h1>Register</h1>
    </header>
    <main>
        <form id="register" method="post">
            <input type="password" name="password" placeholder="New password" onchange="checkPass()">
            <input type="password" name="passwordCheck" placeholder="Confirm new password" onchange="checkPass()">
            <input class="disable" type="submit" name="submitpassword" value="Reset password">
        </form>
    </main>
</body>
</html>

<?php
try {
    resettingPassword();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>