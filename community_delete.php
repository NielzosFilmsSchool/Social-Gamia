<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

// DELETE FROM communities WHERE community_id = 

try {
    $stmt = $pdo->query('SELECT * FROM communities WHERE id = '.$_GET["community_id"]);
    if($stmt->rowCount() == 0) {
        throw new Exception("No communities found!");
    }
    
    while($row = $stmt->fetch()) {
        if($row["created_user_id"] == $_COOKIE["loggedInUser"]){
            header('Location: community_highlights.php?community_id='.$_GET["community_id"]);
        }
    }

    $stmt = $pdo->prepare(
        "DELETE FROM communities WHERE id = ".$_GET["community_id"]
    );
    $stmt->execute();
    header('Location: index.php');
} catch(Exception $e) {
    echo "<h3>$e</h3>";
}

?>