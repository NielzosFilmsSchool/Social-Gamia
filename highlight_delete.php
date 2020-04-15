<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

if(!isset($_COOKIE["loggedInUser"])) {
    header('Location: login.php');
}

try {
    $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE id = '.$_GET["id"]);
    if($stmt->rowCount() == 0) {
        throw new Exception("No highlight found!");
    }
    
    while($row = $stmt->fetch()) {
        if($row["user_id"] == $_COOKIE["loggedInUser"]){
            header('Location: community_highlights.php?community_id='.$_GET["community_id"]);
        }
    }

    $stmt = $pdo->prepare(
        "DELETE FROM highlight_posts WHERE id = ".$_GET["id"]
    );
    $stmt->execute();
    header('Location: community_highlights.php?community_id='.$_GET["community_id"]);
} catch(Exception $e) {
    echo "<h3>$e</h3>";
}
?>