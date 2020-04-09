<?php
    $dsn = "mysql:host=localhost;dbname=social_gamia";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);

?>

<?php
    if(!isset($_COOKIE["loggedInUser"])) {
        header('Location: login.php');
    }

function showingProfile() 
{
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
    <script src="JS/script.js"></script>

</head>

<body>

<div id="position_log">
    <div class="logout_btn">
    <a href="logout.php">Logout</a>
    </div>
</div>

    
    
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

                <a href="friends.php">
                <div class="tooltip">
                <i class="fas fa-users"></i>
                    <span class="tooltiptext">Friends</span>
                </div>

                
            </a>
        </div>
    </header>

<?php

// $usser="";
//     $con = mysqli_connect("localhost","root","", "social_gamia") or die("Didnt Work");
//         $user =$_COOKIE["loggedInUser"] ;
//         $get_user = "select * from users where username='$usser'";
//         $run_user = mysqli_query($con, $get_user);
//         $row = mysqli_fetch_array($run_user);



     
      


        // $test = get_user_by('id', $user);
    

        ?>
    <main>

            <div id="wrapper_dm">
    
            <h1>Open Chat</h1>
            <h1>Friends</h1>

            

          <?php  
            $dsn = "mysql:host=localhost;dbname=social_gamia";
            $user = "root";
            $passwd = "";
            $pdo = new PDO($dsn, $user, $passwd);
        
        
            $user_name= $_COOKIE["loggedInUser"];
            echo "user_name=$user_name 'MyChat";

            $chat_frnds = $pdo->query("SELECT * FROM users");
                                while ($row = $chat_frnds->fetch()) {
                                    $friends_arr = explode("&;", $row["folowing_users"]);
                                    if(in_array($_COOKIE["loggedInUser"], $friends_arr)) {
                                        ?>
                                        <!-- onclick="location.href='profile.php?user= $row['id']?>';" -->
                                        <div class="menu">
                                            <div class="frnd_list">
                                           <h5> <?= $row['username']?> </h5>
                                        </div>
                                        </div>
                                        <?php
                                    }
                                }
        ?>
         
                <!-- <div class="chat_wrapper">
                    <div id="chat"></div>
                    <form method="POST" id="messageForm"> 
                        <textarea name="message"  cols="30" rows="7" class="textarea"></textarea>
                        </form>
                </div>        
                                	
            </div> -->
    </main>
    
    <footer></footer>


<!-- 
            <script>

                LoadChat();
                function LoadChat(){
                        $.post('messages.php?action=getMessages', function(response){
                            $('#chat').html(response);


                    });                

                }
                $('.textarea').keyup(function(e){
                    if(e.which == 13){
                        $('form').submit();
                    }
                });


                $('form').submit(function(){
                    // alert("form is submitted by jquery");
                    var message =   $('.textarea').val();
                    $.post('messages.php?action=sendMessage&message='+message, function(response){
                        if(response ==1){
                            LoadChat();
                            document.getElementById('messageForm').reset();
                            
                        }
                    });

                    return false;
                });
                </script> -->
                



            
</body>
</html>
<?php
}
try {
    showingProfile();
} catch (Exception $e) {
    echo "<h3>".$e->getMessage()."</h3>";
}
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
?>