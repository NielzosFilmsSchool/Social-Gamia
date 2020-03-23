<?php
function loggingIn() 
{
    $dsn = "mysql:host=localhost;dbname=social_gamia";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['username'])) {
        $check_attempt = $pdo->prepare("SELECT * FROM users WHERE username=?");
        $check_attempt->execute([$_POST['username']]);
        $check_attempt = $check_attempt->fetch();
        if (!$check_attempt) {
            throw new Exception("This username and password combination is not registered.");
        } else if (!password_verify($_POST['password'], $check_attempt['password'])) {
            throw new Exception("This username and password combination is not registered.");
        } else {
            setcookie('loggedInUser', $check_attempt['id'], time() + (86400));
            header('Location: index.php');
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
</head>
<body class="login_body">
    <header>
    </header>

    <div class="navbar">
        <a href="Title">Social Gamia</a>

        <div id="navLeft">
            <a href="About-us">About us</a>
        </div>
    </div>


    <center><div id="test center">
        <div class="login_container center">
            <h1>Login</h1>
            <form method="post">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password"><br>
                <input type="submit" name="submit" value="Login">
            </form>
            <div class="register_container" >
                <h5>Not registered? Click <a href="register.php">here</a></h5>
                <h5>Forgot password? Click <a href="send_email.php">here</a></h5>
            </div>
        </div>
    </div></center>
</body>
</html>

<?php
try {
    loggingIn();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>