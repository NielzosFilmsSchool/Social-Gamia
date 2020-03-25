<?php
class User
{
    public $id;
    public $username;
    public $email;
    public $phone;
    public $post_count;
    public $comment_count;
    public $question_count;
    public $folowing_count;
    public $folowing_communities;
    public $folowing_users;

    public function __construct($id, $username, $email){
        $this->username = $username;
        $this->id = $id;
        $this->email = $email;
    }
}
?>

<?php

$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

if(!isset($_COOKIE["loggedInUser"])){
    header('Location: login.php');
}

try {
    $stmt = $pdo->query('SELECT * FROM users WHERE id = '.$_COOKIE["loggedInUser"]);
    if($stmt->rowCount() == 0) {
        header('Location: login.php');
    }
    while($row = $stmt->fetch()) {
        $user = new User($row["id"], $row["username"], $row["email"]);
    }
}catch(Exception $e) {
    echo "<h3>$e</h3>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Social Gamia | Profile</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
</head>

<body>

    <header>
        <div id="menu">
            <a href="index.php">
                <div class="tooltip">
                    <i class="fas fa-home"></i>
                    <span class="tooltiptext">Home</span>
                </div>
            </a>
            <a href="communities.php">
                <div class="tooltip">
                    <i class="fas fa-satellite-dish"></i>
                    <span class="tooltiptext">Communities</span>
                </div>
            </a>
            <a href="profile.php">
                <div class="tooltip">
                    <i class="fas fa-user"></i>
                    <span class="tooltiptext">Profile</span>
                </div>
            </a>
            <a href="community_create.php">
                <div class="tooltip">
                <i class="fas fa-plus"></i>
                    <span class="tooltiptext">Create Community</span>
                </div>
            </a>
        </div>
    </header>

    <main class="profile_main">
        <a class="edit_account" href="profile_edit.php?user_id=<?= $user->id?>">Edit account</a>
        <?php
        try {
            $stmt = $pdo->query('SELECT * FROM profile_pages WHERE user_id = '.$user->id);
            if($stmt->rowCount() == 0) {
                throw new Exception("No profile page found!");
            }

            while($row = $stmt->fetch()){
                ?>
                <h1><?= $row["title"]?></h1>
                <label><?= $user->email?></label>
                <?php
            }
        }catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>

    </main>
    
    <footer></footer>

</body>

</html>