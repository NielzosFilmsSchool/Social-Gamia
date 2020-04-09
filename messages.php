<?php


$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

?>

<?php

// Database
// id =int| user = varchar| message= text| date = timestamp
$current_user = $pdo->prepare("SELECT username from users where id=?");
$current_user->execute([$_COOKIE['loggedInUser']]);
$current_user = $current_user->fetch();
session_start();


   $_SESSION['User']= $current_user['username'];

// $con = mysqli_connect("localhost","root","", "social_gamia") or die("Didnt Work");
// $get_user = "select * from users where username='$userlog";
// $run_user = mysqli_query($con, $get_user);
// $row = mysqli_fetch_array($run_user);

switch( $_REQUEST['action']) {

    case"sendMessage":

        $query = $pdo->prepare("INSERT INTO messages SET user=?, message=?");
        $run = $query->execute([$_SESSION['User'], $_REQUEST['message']]);

        if( $run){
            echo 1;
            exit;
        }
    break;

    case "getMessages":

        $query = $pdo->prepare("SELECT * FROM messages");
        $run = $query->execute();
        $rs = $query->fetchAll(PDO::FETCH_OBJ);
        
        $chat="";

        foreach($rs as $message){

            // $chat .= $message->message. '<br />';
            $chat .= '<div class="single-message">
            <strong>'.$message->user.': </strong>'.$message->message.'
            </div>';
        }
        echo $chat;

    break;
}


