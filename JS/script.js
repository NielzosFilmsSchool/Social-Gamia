function checkPass() 
{
    pass1 = document.querySelector('input[name="password"]').value;
    pass2 = document.querySelector('input[name="passwordCheck"]').value;
    if (pass1 == pass2) {
        document.querySelector('input[name="passwordCheck"]').style.boxShadow = "10px 20px 30px darkgreen";
        document.querySelector('input[value="Register"]').disabled = false;
        document.querySelector('input[name="submitpassword"]').disabled = false;
    } else {
        document.querySelector('input[name="passwordCheck"]').style.boxShadow = "10px 20px 30px darkred";
        document.querySelector('input[value="Register"]').disabled = true;
        document.querySelector('input[name="submitpassword"]').disabled = true;
    }

}