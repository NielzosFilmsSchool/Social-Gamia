<?php
function loggingIn() 
{
    //onderstaand moet nog ingevult
    $dsn = "mysql:host=localhost;dbname=";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['username'])) {
        // vervang *insert table name*
        $check_attempt = $pdo->prepare("SELECT username FROM *insert table name* WHERE EXISTS (SELECT password FROM *insert table name* WHERE password=?)");
        $check_attempt->execute([$_POST['password']]);
        $check_attempt = $check_attempt->fetch();
        if (!$check_attempt) {
            throw new Exception("This username and password combination is not registered.");
        } else if ($_POST['username'] != $check_attempt['username']) {
            throw new Exception("This username and password combination is not registered.");
        } else {
            setcookie('loggedInUser', $_POST['username'], time() + (86400));
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
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="text" name="password" placeholder="Password">
            <input type="submit" name="submit" value="Login">
        </form>
    </main>
</body>
</html>
<?php
try {
    loggingIn();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>