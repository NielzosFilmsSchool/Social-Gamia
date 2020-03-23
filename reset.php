<?php
function resettingPassword() 
{
    $dsn = "mysql:host=localhost;dbname=social_gamia";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['password'])) {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $check_attempt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            $check_attempt->execute([$_POST['email'], $_POST['username'], $hash]);
        } else {
            throw new Exception("Invalid Email input");
        }
        
    }
    if (isset($_POST['email'])) {
        require("PHPMAIL/PHPMailer.php");
        require("PHPMAIL/SMTP.php");
        echo '<p hidden>';
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->Username = "social.gamia@gmail.com";
        $mail->Password = "zagntvbvaiwelopt";
        $mail->SetFrom("social-gamia@noreply.com");
        $mail->Subject = "You forgot your password!";
        $mail->Body = '<html>
        <head>
        <title>To reset your password press the button down below!</title>
        </head>
        <body>
            <h1>To reset your password press the button down below!</h1>
            <form action="http://localhost/Social-Gamia/reset.php" method="GET">
                <input type="submit" name="submit" value="Press me to reset!" />
            </form>
        </body>
        </html>';
        $mail->AddAddress($_POST['email']);
        echo '</p>';

        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
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
        <h1>Password recovery</h1>
    </header>
    <main>
        <form id="register" method="post">
            <input type="email" name="email" placeholder="Email of your account">
            <!-- <input type="password" name="password" placeholder="New password" onchange="checkPass()">
            <input type="password" name="passwordCheck" placeholder="Confirm new password" onchange="checkPass()"> -->
            <input type="submit" name="submit" value="Verstuur recovery email">
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