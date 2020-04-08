<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

if(!isset($_COOKIE["loggedInUser"])) {
    header('Location: login.php');
}

if(!isset($_GET["pass"])) {
    header('Location: index.php?pass=FR');
}

/**
 * Database changes
 * FROM friends
 * friend_id = id (INT AUTO INCREMENT)
 * name_requester = id_requester (INT)
 * name_reciever = id_reciever (INT)
 */

/*$current_user = $pdo->prepare("SELECT username FROM users WHERE id=?");
$current_user->execute([$_COOKIE['loggedInUser']]);
$current_user = $current_user->fetch();*/
try {
    if (isset($_POST['friend'])) {
        $friend = $pdo->query("SELECT * FROM users WHERE username = '".$_POST["friend"]."' LIMIT 1");
        $row = $friend->fetch();

        $user = $pdo->query("SELECT * FROM users WHERE id = ".$_COOKIE["loggedInUser"]);
        $user = $user->fetch();

        $user_friends_arr = explode("&;", $user["folowing_users"]);
        if(in_array($row["id"], $user_friends_arr)){
            //already friends
            throw new Exception("You are already friends with this person!");
        }
        if($row["id"] == $user["id"]) {
            //friends with yourself
            throw new Exception("You can't be friends with yourseld!");
        }

        $send_request = $pdo->prepare("INSERT INTO friends (id_requester, id_receiver) VALUES (".$_COOKIE["loggedInUser"].", ".$row["id"].")");
        $send_request->execute();
        /*while($row = $friend->fetch()){
            echo $row["id"];
            $send_request = $pdo->prepare("INSERT INTO friends (id_requester, id_receiver) VALUES (".$_COOKIE["loggedInUser"].", ".$row["id"].")");
            $send_request->execute();
        }*/
    }
} catch(Exception $e) {
    echo "<h3>".$e->getMessage()."</h3>";
}

$friend_request = $pdo->query("SELECT * FROM friends");//WHERE id_reciever = ".$_COOKIE["loggedInUser"]);
$notifications = $friend_request->fetch();
$notification = "No new friends :(";

if($notifications) {
    if ($notifications['id_requester'] == $_COOKIE["loggedInUser"]) {
        $notification = "You sent out a friend request!";
    } if ($notifications['id_receiver'] == $_COOKIE['loggedInUser']) {
        $friend = $pdo->query("SELECT * FROM users WHERE id = ".$notifications["id_requester"]);
        $friend = $friend->fetch();
        $notification = "You have a new friend request from " . $friend["username"] . "! <form method='post' action='index.php?pass=".$_GET["pass"]."&friend_id=".$notifications["id"]."'> <input type='submit' name='yes' value='Accept'> <input type='submit' name='no' value='Decline'></form>";
    }
}

if (isset($_POST['yes'])) {
    $status = true;

    /*$add_friend = $pdo->prepare("UPDATE friends SET status=? WHERE name_receiver=?");
    $add_friend->execute([$status, $current_user['username']]);*/

    $user = $pdo->query("SELECT * FROM users WHERE id = ".$_COOKIE["loggedInUser"]);
    $user = $user->fetch();

    $request = $pdo->query("SELECT * FROM friends WHERE id = ".$_GET["friend_id"]);
    $request = $request->fetch();

    $friend = $pdo->query("SELECT * FROM users WHERE id = ".$request["id_requester"]);
    $friend = $friend->fetch();


    $friends_arr = explode("&;", $user["folowing_users"]);
    array_push($friends_arr, $friend["id"]);
    $friends_str = implode("&;", $friends_arr);

    $add_friend = $pdo->prepare("UPDATE users SET folowing_users = '$friends_str' WHERE id = ".$user["id"]);
    $add_friend->execute();


    $friends_arr = explode("&;", $friend["folowing_users"]);
    array_push($friends_arr, $user["id"]);
    $friends_str = implode("&;", $friends_arr);

    $add_friend = $pdo->prepare("UPDATE users SET folowing_users = '$friends_str' WHERE id = ".$friend["id"]);
    $add_friend->execute();


    $delete_request = $pdo->prepare("DELETE FROM friends WHERE id = ".$_GET["friend_id"]);
    $delete_request->execute();

    $notification="No new friends :(";
}
if (isset($_POST['no'])) {
    $delete_request = $pdo->prepare("DELETE FROM friends WHERE id = ".$_GET["friend_id"]);
    $delete_request->execute();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Social Gamia | Home</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
    <script src="JS/script.js"></script>
</head>

<body class="home_body">

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

                <a href="direct_messages.php">
                <div class="tooltip">
                <i class="fas fa-paper-plane"></i>
                    <span class="tooltiptext">Messages</span>
                </div>

                <a href="community_create.php">
                <div class="tooltip">
                <i class="fas fa-users"></i>
                    <span class="tooltiptext">Friends</span>
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
                                ?>
                                <form method="post">
                                    <input type="text" name="friend" placeholder="Add a new friend!">
                                    <input type="submit" name="submitfriend" value="Add!">
                                </form>
                                <?php
                                $social = $pdo->query("SELECT * FROM users");
                                while ($row = $social->fetch()) {
                                    $friends_arr = explode("&;", $row["folowing_users"]);
                                    if(in_array($_COOKIE["loggedInUser"], $friends_arr)) {
                                        ?>
                                        <div class="menu" onclick="location.href='profile.php?user=<?= $row['id']?>';">
                                            <?= $row['username']?>
                                        </div>
                                        <?php
                                    }
                                }
                            } else if ($_GET['pass'] == 'DM') {
                                echo "Work in progress";
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
                $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC LIMIT 10');
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
                        <td class="index_table_item">
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
                $user_query = $pdo->query('SELECT * FROM users WHERE id = '.$_COOKIE["loggedInUser"]);
                $user = $user_query->fetch();

                $user_friends = explode("&;", $user["folowing_users"]);

                unset($user_friends[0]);

                foreach($user_friends as $friend_id) {
                    $friends_ids = implode(", ", $user_friends);
                    $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE user_id IN ('.$friends_ids.') ORDER BY post_date DESC LIMIT 10');
                    if($stmt->rowCount() == 0) {
                        throw new Exception("No items found!");
                    }

                    ?>
                    <table class="feed_table">
                        <tr>
                            <th>Friend activity</th>
                        </tr>
                    <?php
                    if($friend_id == 0){
                        continue;
                    }
                    $friend_query = $pdo->query("SELECT * FROM users WHERE id = $friend_id");
                    $friend = $friend_query->fetch();

                    while($row = $stmt->fetch()) {
                        $time_input = strtotime($row["post_date"]);
                        $date = date("d-M-Y", $time_input);
                        $time = date("H:i:s", $time_input);
                        ?>
                        <tr>
                            <td class="index_table_item">
                                <a href="highlight_details.php?community_id=<?= $row["community_id"]?>&id=<?= $row["id"]?>"><?= $row["caption"]?></a>
                                <br>
                                <label><?= $date?> <?= $time?></label>
                                <br>
                                <label><?= $friend["username"]?></label>
                            </td>
                        </tr>
                        <?php
                    }
                }

                ?>
                </table>
                <?php

            } catch(Exception $e) {
                echo "<h3>".$e->getMessage()."</h3>";
            }

            function remove_element(&$array,$value) {
                if(($key = array_search($value,$array)) != false) {
                    unset($array[$key]);
                }
                return $array;
            }
            ?>
        </div>
        
    </main></center>
        
    <footer></footer>
        
</body>
    
</html>
