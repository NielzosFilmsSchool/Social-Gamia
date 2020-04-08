<?php


$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

?>

<?php

// Database
// id =int| user = varchar| message= text| date = timestamp
session_start();
 
switch( $_REQUEST['action']) {

    case"sendMessage":

        
        
        $query = $pdo->prepare("INSERT INTO messages SET user=?, message=?");

        $run = $query->execute([$_SESSION['user'], $_REQUEST['message']]);




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


