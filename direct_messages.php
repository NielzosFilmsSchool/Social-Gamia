<?php
function showingProfile() 
{
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
</head>

<body>



<?php

    
session_start();
$_SESSION['user'] = "TestWSessions";
?>
    <header></header>

    <main>

            <div id="wrapper_dm">
    
            <h1> Welcome</h1>
                <div class="chat_wrapper">

                    <div id="chat"></div>

                    <form method="POST" id="messageForm"> 
                        <textarea name="message"  cols="30" rows="7" class="textarea"></textarea>

                      
                </div>        

            </div>
    </main>
    
    <footer></footer>



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
                </script>
                



            
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