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
    $stmt = $pdo->query('SELECT * FROM users WHERE id = '.$_GET["user"]);
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
    <title>Social Gamia | <?= $user->username?>'s profile</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JS/profile_script.js"></script>
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
            <a href="profile.php?user=<?= $_COOKIE["loggedInUser"]?>">
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
        <?php
        if($user->id == $_COOKIE["loggedInUser"]) {
        ?>
            <a class="edit_account" href="account_edit.php?user_id=<?= $user->id?>">Edit account</a>
        <?php
        }

        try {
            $stmt = $pdo->query('SELECT * FROM profile_pages WHERE user_id = '.$user->id);
            if($stmt->rowCount() == 0) {
                throw new Exception("No profile page found!");
            }

            while($row = $stmt->fetch()){
                $profile_id = $row["id"];
                ?>
                <div class="profile_page_content">
                    <?php
                    if($user->id == $_COOKIE["loggedInUser"]) {
                        ?>
                        <div contenteditable="true">
                        <?php
                    } else {
                        ?>
                        <div contenteditable="false">
                        <?php
                    }
                    ?>
                        <h1 id="profile_page_title"><?= $row["title"]?></h1>
                    </div>
                    <!-- PROFILE PAGE CONTENT START -->
                    <div id="profile_page_content">
                        <?php
                        $html = $row["html"];
                        if($user->id != $_COOKIE["loggedInUser"]) {
                            $html = str_replace( 'contenteditable="true"', 'contenteditable="false"', $html);
                            $html = str_replace( '<button class="delete danger padding" onclick="deleteHtml(this)">Delete</button>', '', $html);
                        }
                        echo $html;
                        ?>
                    </div>
                    <!-- PROFILE PAGE CONTENT END -->
                </div>
                <?php
                if($user->id == $_COOKIE["loggedInUser"]) {
                    ?>
                    <div class="profile_page_controls">
                        <select id="element_to_add" class="blue padding">
                            <option value="text">Text</option>
                            <option value="h1">Title</option>
                            <option value="h3">Header</option>
                            <option value="ul">Unordered list</option>
                            <option value="ol">Ordered list</option>
                        </select>
                        <br>
                        <label for="text_color">Text: </label>
                        <input type="color" id="text_color" class="blue padding">
                        <br>
                        <label for="bg_color">Background: </label>
                        <input type="color" id="bg_color" class="blue padding" value="#ffffff">
                        <br>
                        <button class="margin_top blue padding" onclick="addHtml()">Add selected element</button>
                        <br>
                        <button class="green padding" onclick="getHtml(<?= $_GET['user']?>)">Save changes</button>
                    </div>
                    <?php
                }
            }
        }catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>

    </main>
    
    <footer></footer>

</body>
</html>

<?php
//saving changes to profile page

if(isset($_POST["profile_html"]) && $user->id == $_COOKIE["loggedInUser"]) {
    $profile_html = $_POST["profile_html"];
    $profile_title = $_POST["profile_title"];
    $stmt = $pdo->prepare(
        "UPDATE profile_pages
        SET html = '$profile_html', title = '$profile_title'
        WHERE id = ".$profile_id
    );
    $stmt->execute();
    header("Refresh:1");
}
?>