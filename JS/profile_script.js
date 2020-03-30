function getHtml(user) {
    var profile_html = document.getElementById("profile_page_content").innerHTML;
    var profile_title = document.getElementById("profile_page_title").innerHTML;

    var post_data = { 'profile_html':profile_html, 'profile_title':profile_title};
    $.ajax({
        data: post_data,//'profile_html=' + profile_html,
        url: 'profile.php?user='+user,
        method: 'POST',
    });
}

function addHtml() {
    var selected = document.getElementById("element_to_add").value;
    var color = document.getElementById("text_color").value;
    var bg_color = document.getElementById("bg_color").value;
    var container = document.getElementById("profile_page_content");

    console.log(color);

    var html = '<div class="margin_top relative" id="profile_content_div">';
    html += '<button class="delete danger padding" onclick="deleteHtml(this)">Delete</button>';

    if(selected == "text"){
        html += '<div contenteditable="true" class="padding" style="color:'+color+';background-color:'+bg_color+'">Text...</div>';
    }else if(selected == "h1"){
        html += '<h1 contenteditable="true" class="padding" style="color:'+color+';background-color:'+bg_color+'">Title...</h1>';
    }else if(selected == "h3"){
        html += '<h3 contenteditable="true" class="padding" style="color:'+color+';background-color:'+bg_color+'">Header...</h3>';
    }else if(selected == "ul") {
        html += '<ul contenteditable="true" class="padding" style="color:'+color+';background-color:'+bg_color+'"><li></li></ul>';
    }else if(selected == "ol") {
        html += '<ol contenteditable="true" class="padding" style="color:'+color+';background-color:'+bg_color+'"><li></li></ol>';
    }
    html += '</div>';
    container.innerHTML += html;
}

function deleteHtml(button) {
    var div = button.parentElement;
    div.parentNode.removeChild(div);
}

$('[contenteditable=true]').keydown(function(e) {
    // trap the return key being pressed
    if (e.keyCode === 13) {
        // insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
        document.execCommand('insertHTML', false, '<br><br>');
        // prevent the default behaviour of return key pressed
        return false;
    }
});