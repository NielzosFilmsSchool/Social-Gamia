<?php
class User
{
    private $id;
    private $username;
    private $email;
    private $phone;
    private $post_count;
    private $comment_count;
    private $question_count;
    private $folowing_count;
    private $folowing_communities;
    private $folowing_users;

    public function __construct($id, $username, $email){
        
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
    $stmt = $pdo->query('SELECT * FROM users WHERE id = '.$_GET["loggedInUser"]);
    if($stmt->rowCount() == 0) {
        throw new Exception("No rules found!");
    }
    while($row = $stmt->fetch()) {
        $username = $row[""];
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

    <main></main>
    
    <footer></footer>

</body>

</html>