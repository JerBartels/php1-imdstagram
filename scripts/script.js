
$(document).ready(function(){

    var number_of_clicks = 0;

    $("#load").click(function(){
        console.log("button clicked");

        number_of_clicks = number_of_clicks + 1;
        var start = number_of_clicks * 4;
        var stop = start + 4;

        $.ajax({
            url:"../ajax/load-more.php",
            data: " ",
            dataType: "JSON",

            success: function(data){
                for(var i = start; i < stop; i++){
                    var new_post = "<div class='feed_feed'><div class='feed_username'><span>" + data[i]["username"] + "</span></div>";
                    new_post += '<div class="feed_date"><span>' + data[i]["date"] + '</span></div>';
                    new_post += '<img src="../assets/posts/' + data[i]["photo"] + '" alt="feed_pict_img" class="feed_pict_img">';
                    new_post += '<div class="feed_comment"><span class="comment_username">' + data[i]["username"] + "</span><span class='comment_text'>" + data[i]["comment"] + '</span></div></div>';
                    $(".feed_feed").after(new_post);

                    console.log(number_of_clicks);
                }
            }
        });

        //pagina niet opnieuw laden!
        return(false);
    });
});

