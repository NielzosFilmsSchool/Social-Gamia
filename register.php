<?php
function registeringUser() 
{
    $dsn = "mysql:host=localhost;dbname=social_gamia";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['username'])) {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $check_attempt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            $check_attempt->execute([$_POST['email'], $_POST['username'], $hash]);

            //profile page for the user
            $stmt = $pdo->query('SELECT * FROM users WHERE username = "'.$_POST["username"].'"');
            while($row = $stmt->fetch()) {
                $stmt_profile = $pdo->prepare(
                    "INSERT INTO profile_pages (user_id, title)
                    VALUES (".$row["id"]." , 'Profile page of ".$_POST["username"]."')"
                );
                $stmt_profile->execute();
            }
        } else {
            throw new Exception("Invalid Email input");
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
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password" onchange="checkPass()">
            <input type="password" name="passwordCheck" placeholder="Confirm password" onchange="checkPass()">
            <input class="disable" type="submit" name="submit" value="Register">
        </form>
    </main>
</body>
</html>

<?php
try {
    registeringUser();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>