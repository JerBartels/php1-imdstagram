$(document).ready(function() {


    //------------------- AJAX - LIKE POST -------------------//

    $(document).on("click", ".btn_feed_like", function(){

        var $current_post = $(this);
        var current_id = $current_post.attr("id").slice(4);

        $.ajax({
            type: 'POST',
            url: "../ajax/like-post.php",
            data: {current_id: current_id},
            dataType: "JSON",

            success: function (data) {
                var $span = $($current_post).prev("span");
                $span.text(data["likes"] + " likes");
            },
           error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

        return (false);
    });


    //------------------- AJAX - COMMENT -------------------//

    $(document).on("click", ".btn_post_comment", function(){

        var $current_comment = $(this);
        var current_post_id = $current_comment.attr("id").slice(4);
        var comment = $("#input_" + current_post_id).val();

        $.ajax({
            type: 'POST',
            url: "../ajax/post-comment.php",
            data: {current_post_id: current_post_id, comment: comment},
            dataType: "JSON",

            success: function(data){

                console.log(data);

                $("#input_" + current_post_id).val("");

                var id = data["id"];
                var comment = data["comment"];

                var new_comment = '<li><span class="feed-comment-list-username">' + id + '</span>' + comment + '</li>';

                $("#" + data["post"]).append(new_comment);

            }
        });

        return(false);
    });


    //------------------- AJAX - LOAD MORE -------------------//

    var number_of_clicks = 0;

    $("#load").on("click", function () {
        console.log("button clicked");

        number_of_clicks = number_of_clicks + 1;
        var start = number_of_clicks * 5;
        var stop = start + 5;
        var number_of_posts = 5;

        $.ajax({
            type: 'POST',
            url: "../ajax/load-more.php",
            data: {number_of_clicks: number_of_clicks},
            dataType: "JSON",

            success: function (data) {

                console.log(data);
                console.log(data.length);

                if (data.length < 5) {
                    number_of_posts = data.length;
                    $('#load').attr('disabled', 'disabled');
                }

                for (i = 0; i < number_of_posts; i++) {
                    var new_post = "<div class='feed_feed'><div class='feed_username'><span>" + data[i]["username"] + "</span></div>";
                    new_post += '<div class="feed_date"><span>' + data[i]["date"] + '</span></div>';
                    new_post += '<img src="../assets/posts/' + data[i]["photo"] + '" alt="feed_pict_img" class="feed_pict_img">';
                    new_post += '<div class="feed_comment"><span class="comment_username">' + data[i]["username"] + "</span><span class='comment_text'>" + data[i]["comment"] + '</span></div>';

                    new_post += '<div class="feed_likes_form">';
                    new_post += '<span class="btn_feed_like" id="btn_' + data[i]["photo"] + '">like</span>';
                    new_post += '<span class="number_feed_like">' + data[i]["likes"] + '</span>';

                    new_post += '</div>';
                    $(".feed_feed").last().after(new_post);

                    console.log(number_of_clicks);
                }
            }
        });

        //pagina niet opnieuw laden!
        return (false);
    });


    //------------------- AJAX - CHECK USERNAME -------------------//

    var timer;

    //huidige username, om te checken of er iets gewijzigd is
    var current_username = $("#input_change_username").val();

    //p-element waarin we de feedback gaan tonen
    var $username_ajax_feedback = $("#username_ajax_feedback");

    //button save
    var $btn_save = $("#btn_save");

    $("#input_change_username").on("keyup", function () {

        clearTimeout(timer);

        var new_username = $(this).val();

        timer = setTimeout(function () {
            if(new_username == "jer_bartels")
            {
                $username_ajax_feedback.css("color", "#4080A8").html("jer_bartels is the coolest guy in town, he owns this place!");
                $username_ajax_feedback.show();
            }
            else if(current_username != new_username)
            {
                check_username_ajax(new_username);
            }
            else
            {
                $username_ajax_feedback.css("color", "#F46C7C").html("monkeyballs, you tried to fool me!");
                $username_ajax_feedback.show();
            }
        }, 1000);
    });

    function check_username_ajax(username){

        $.post("../ajax/change-username.php", {username: username},
            function(result){
                if(result ==1){
                    $username_ajax_feedback.css("color", "#61AC7E").html("stupid name, but available!");
                    $username_ajax_feedback.show();
                }
                else
                {
                    $username_ajax_feedback.css("color", "#F46C7C").html("you are not original, fucker");
                    $username_ajax_feedback.show();
                }
            })
    }

});

