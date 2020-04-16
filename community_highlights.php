<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

if(!isset($_COOKIE["loggedInUser"])) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
</head>

<body>
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
        <a href="direct_messages.php">
                <div class="tooltip">
                <i class="fas fa-paper-plane"></i>
                    <span class="tooltiptext">Messages</span>
                </div>
                </a>
    </div>

    <center><header class="community_header">
        <?php
        try {
            $stmt = $pdo->query('SELECT * FROM communities WHERE id = '.$_GET["community_id"]);
            if($stmt->rowCount() == 0) {
                throw new Exception("No communities found!");
            }

            ?>
            <div>
            <?php
            
            while($row = $stmt->fetch()) {
                ?>
                <h1> <?= $row["name"] ?> </h1>
                <?php
                $stmt_user = $pdo->query('SELECT * FROM users WHERE id = '.$row["created_user_id"]);
                while($row_user = $stmt_user->fetch()) {
                    ?>
                    <label>Created by <?= $row_user["username"] ?></label>
                    <?php
                    if($row["created_user_id"] == $_COOKIE["loggedInUser"]){
                        ?>
                        <a class="edit_community" href="community_edit.php?community_id=<?= $_GET["community_id"] ?>">Edit community</a>
                        <?php
                    }
                }
                ?>
                <p> <?= $row["description"]?> </p>
                <?php
            }

            ?>
            </div>
            <?php

        } catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>
        <br>
        <div id='menu_2'>
            <a href="community_highlights.php?community_id=<?= $_GET["community_id"] ?>">Highlights</a>
            <a href="community_questions.php?community_id=<?= $_GET["community_id"] ?>">Questions</a>
            <a href="community_rules.php?community_id=<?= $_GET["community_id"] ?>">Rules</a>
        </div>
        <div class="community_sort">
            <form action="create_highlight.php?community_id=<?= $_GET["community_id"] ?>" method="post">
                <input type="submit" value="Create highlight">
            </form>
            <form action="community_highlights.php?community_id=<?= $_GET["community_id"] ?>" method="post">
                <input type="text" name="search_term" placeholder="Search...">
                <select name="sort_type">
                    <option value="post_date">Date</option>
                    <option value="caption">Caption</option>
                    <option value="likes">Most Likes</option>
                </select>
                <select name="sort">
                    <option value="ASC">Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
                <input type="submit" name="search" value="Search">
            </form>
        </div>
    </header></center>

    <center><main>
        <?php
        try {
            if(isset($_POST["search"]) && isset($_POST["search_term"])) {
                $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE community_id = '.$_GET["community_id"].' AND caption LIKE "%'.$_POST["search_term"].'%" ORDER BY '.$_POST["sort_type"].' '.$_POST["sort"]);
            }else if(isset($_POST["search"])) {
                $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE community_id = '.$_GET["community_id"].' ORDER BY '.$_POST["sort_type"].' '.$_POST["sort"]);
            } else {
                $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE community_id = '.$_GET["community_id"].' ORDER BY post_date ASC');
            }
            if($stmt->rowCount() == 0) {
                throw new Exception("No highlights found!");
            }

            ?>
            <div class="highlights_container">
            <?php

            while($row = $stmt->fetch()) {

                $user_query = $pdo->query('SELECT * FROM users WHERE id = '.$row["user_id"]);
                $user = $user_query->fetch();

                $time_input = strtotime($row["post_date"]);
                $date = date("d-M-Y", $time_input);
                $time = date("H:i:s", $time_input);
                ?>
                <div class="highlight">
                    <h2> <?= $row["caption"] ?> </h2>
                    <a href="profile.php?user=<?= $user["id"]?>"><?= $user["username"]?></a>
                    <br>
                    <label class="highlight_date"><?= $date?></label>
                    <label class="highlight_date" style="top: 60px;"><?= $time?></label>
                    <?php
                    if(!empty($row["file_path"])) {
                        ?>
                        <img class="highlight_img" src="<?= $row["file_path"]?>" alt="Photo">
                        <?php
                    }
                    ?>
                    <label class="highlight_likes"><?= $row["likes"] ?> Likes</label>
                    <form class="highlight_likes" style="bottom: 0px;" action="community_highlights.php?community_id=<?= $_GET["community_id"]?>" method="post">
                        <input type="hidden" name="post_id" value="<?= $row["id"]?>">
                        <input type="submit" name="submit_like" value="Like" class="green">
                        <input type="submit" name="submit_dislike" value="Disline" class="danger">
                    </form>
                    <br>
                    <br>
                    <a href="highlight_details.php?community_id=<?= $_GET["community_id"]?>&id=<?= $row["id"]?>">Highlight details</a>
                </div>
                <?php
            }

            ?>
            </div>
            <?php

        } catch(Exception $e) {
            echo "<h3>".$e->getMessage()."</h3>";
        }
        
        ?>
    </main></center>
    
    <footer></footer>

</body>
</html>
<?php 
try {
    if (!isset($_COOKIE['loggedInUser'])) {
        throw new Exception("U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.");
    }
} catch (Exception $e) {
    echo "<h3>".$e->getMessage()."</h3>";
    if ($e->getMessage() == "U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.") {
        echo "<script>setTimeout(\"location.href = 'logout.php';\",1500);</script>";
    }
}

if(isset($_POST["submit_like"])) {
    $post_query = $pdo->query("SELECT * FROM highlight_posts WHERE id = ".$_POST["post_id"]);
    $post = $post_query->fetch();

    $user_query = $pdo->query("SELECT * FROM users WHERE id = ".$_COOKIE["loggedInUser"]);
    $user = $user_query->fetch();

    $liked_posts = explode("&;", $user["liked_posts"]);
    if(in_array($post["id"], $liked_posts)) {
        return;
    }

    $disliked_posts = explode("&;", $user["disliked_posts"]);
    if(in_array($post["id"], $disliked_posts)) {
        if (($key = array_search($post["id"], $disliked_posts)) !== false) {
            unset($disliked_posts[$key]);
        }
    }

    $likes = intval($post["likes"]);
    $likes = $likes += 1;

    array_push($liked_posts, $post["id"]);

    $post_update = $pdo->prepare(
        "UPDATE highlight_posts 
        SET likes = $likes
        WHERE id = ".$post["id"]
    );
    $post_update->execute();

    $liked_posts_str = implode("&;", $liked_posts);
    $disliked_posts_str = implode("&;", $disliked_posts);

    $user_update = $pdo->prepare(
        "UPDATE users
        SET disliked_posts = '$disliked_posts_str', liked_posts = '$liked_posts_str'
        WHERE id = ".$user["id"]
    );
    $user_update->execute();
}

if(isset($_POST["submit_dislike"])) {
    $post_query = $pdo->query("SELECT * FROM highlight_posts WHERE id = ".$_POST["post_id"]);
    $post = $post_query->fetch();

    $user_query = $pdo->query("SELECT * FROM users WHERE id = ".$_COOKIE["loggedInUser"]);
    $user = $user_query->fetch();

    $disliked_posts = explode("&;", $user["disliked_posts"]);
    if(in_array($post["id"], $disliked_posts)) {
        return;
    }

    $liked_posts = explode("&;", $user["liked_posts"]);
    if(in_array($post["id"], $liked_posts)) {
        if (($key = array_search($post["id"], $liked_posts)) !== false) {
            unset($liked_posts[$key]);
        }
    }

    $likes = intval($post["likes"]);
    $likes = $likes -= 1;

    array_push($disliked_posts, $post["id"]);

    $post_update = $pdo->prepare(
        "UPDATE highlight_posts 
        SET likes = $likes
        WHERE id = ".$post["id"]
    );
    $post_update->execute();

    $disliked_posts_str = implode("&;", $disliked_posts);
    $liked_posts_str = implode("&;", $liked_posts);

    $user_update = $pdo->prepare(
        "UPDATE users
        SET disliked_posts = '$disliked_posts_str', liked_posts = '$liked_posts_str'
        WHERE id = ".$user["id"]
    );
    $user_update->execute();
}
?>