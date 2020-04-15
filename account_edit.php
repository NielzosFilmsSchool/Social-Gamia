<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="JS/script.js"></script>
    <title>Social Gamia | Edit Account</title>
</head>
<body class="register_body">
    <header>
        </header>
        <main>
            <center>
            <div class="register_container">
                <h1>Edit Account</h1>
                <?php
                $user_query = $pdo->query('SELECT * FROM users WHERE id = '.$_GET["user_id"]);
                $user = $user_query->fetch();
                ?>
            	<form id="register" method="post">
                    <input type="email" name="email" placeholder="Email" value="<?= $user["email"]?>">
                    <input type="text" name="username" placeholder="Username" value="<?= $user["username"]?>">

                    <?php
                        try {
                            if (isset($_POST['submit'])) {
                                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST["username"])){
                                    $stmt = $pdo->prepare(
                                        "UPDATE users
                                        SET username = '".$_POST["username"]."', email = '".$_POST["email"]."'
                                        WHERE id = ".$_GET["user_id"]
                                    );
                                    $stmt->execute();
                                    header('Location: profile.php?user='.$_GET["user_id"]);
                                } else {
                                    throw new Exception("<div style='color: red;'>Invalid Email input</div>");
                                }
                                
                            }
                        } catch (Exception $e) {
                            echo '<h5>' . $e->getMessage() . '</h5>';
                        }
                    ?>
                    <input class="disable" type="submit" name="submit" value="Save">
                </form>
            </div>
            </center>
    </main>
</body>
</html>

