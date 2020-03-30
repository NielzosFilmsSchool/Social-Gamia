<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$social = $pdo->query("SELECT username FROM users");
$current_user = $pdo->prepare("SELECT username FROM users WHERE id=?");
$current_user->execute([$_COOKIE['loggedInUser']]);
$current_user = $current_user->fetch();
if (isset($_POST['friend'])) {
    $send_request = $pdo->prepare("INSERT INTO friends (name_requester, name_receiver) VALUES (?,?)");
    $send_request->execute([$current_user['username'],$_POST['friend']]);
}
$friend_request = $pdo->query("SELECT name_requester, name_receiver FROM friends");
$notifications = $friend_request->fetch();
$notification = "No new friends :(";
if ($notifications['name_requester'] == $current_user['username']) {
    $notification = "You sent out a friend request!";
} if ($notifications['name_receiver'] == $current_user['username']) {
    $notification = "You have a new friend request from " . $notifications['name_requester'] . "! <form method='post'> <input type='submit' name='yes' value='Accept'> <input type='submit' name='no' value='Decline'></form>";
}
if (isset($_POST['yes'])) {
    $status = true;
    $Accept = $pdo->prepare("UPDATE friends SET status=? WHERE name_receiver=?");
    $Accept->execute([$status, $current_user['username']]);
    $notification="No new friends :(";
}
// if (isset($_POST['no'])) {
//     $Decline = $pdo->prepare("DELETE FROM friends WHERE status=? AND WHERE name_receiver=?");
//     $Decline->execute([]);
// }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Social Gamia | Home</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
    <script src="JS/script.js"></script>
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

    <center><main class="home_main">
        <div id="dropdown">
            <button id="dropbtn">Social</button>
            <div id="dropdown-content">
                <div>
                    <div class="swap_container">
                        <div class="menu_header"><a class="menu_header" href="index.php?pass=FR">Friends</a></div>
                        <div class="menu_header"><a class="menu_header" href="index.php?pass=DM">Direct Messages</a></div>
                        <div class="menu_header"><a class="menu_header" href="index.php?pass=FRR">Friend requests</a></div>
                    </div>
                    <div>
                            <?php
                            if ($_GET['pass'] == 'FR') {
                                echo '  <form method="post">
                                            <input type="text" name="friend" placeholder="Add a new friend!">
                                            <input type="submit" name="submitfriend" value="Add!">
                                        </form>';
                                while ($friends = $social->fetch()) {
                                    echo '<div class="menu">';
                                    echo $friends['username'];
                                    echo '</div>';
                                }
                            } else if ($_GET['pass'] == 'DM') {
                                while ($friends = $social->fetch()) {
                                    echo "Work in progress";
                                }
                            } else if ($_GET['pass'] == 'FRR') {
                                echo $notification;
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="communities_container">
            <?php
            try {
                $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC');
                if($stmt->rowCount() == 0) {
                    throw new Exception("No communities found!");
                }
                if (!isset($_COOKIE['loggedInUser'])) {
                    throw new Exception("U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.");
                }
                ?>
                <table class="communities_table">
                    <tr>
                        <th>Communities</th>
                    </tr>
                <?php
                
                while($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td>
                            <a href="community_highlights.php?community_id=<?= $row["id"] ?>"> <?= $row["name"] ?> </a>
                        </td>
                    </tr>
                    <?php
                }

                ?>
                </table>
                <?php

            } catch(Exception $e) {
                echo "<h3>".$e->getMessage()."</h3>";
                if ($e->getMessage() == "U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.") {
                    echo "<script>setTimeout(\"location.href = 'logout.php';\",1500);</script>";
                }
            }
            ?>
        </div>

        <div class="feed_container">
            <?php
            try {
                // $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC');
                // if($stmt->rowCount() == 0) {
                //     throw new Exception("No communities found!");
                // }

                ?>
                <table class="feed_table">
                    <tr>
                        <th>Feed</th>
                    </tr>
                <?php
                
                //while($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td>
                            Feed item
                        </td>
                    </tr>
                    <?php
                //}

                ?>
                </table>
                <?php

            } catch(Exception $e) {
                echo "<h3>".$e->getMessage()."</h3>";
            }
            ?>
        </div>
        
    </main></center>
        
    <footer></footer>
        
</body>
    
</html>
