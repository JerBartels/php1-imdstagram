$(document).ready(function(){

    var number_of_clicks = 0;

    $("#load").click(function(){
        console.log("button clicked");

        number_of_clicks = number_of_clicks + 1;
        var start = number_of_clicks * 5;
        var stop = start + 5;
        var number_of_posts = 5;

        $.ajax({
            type:'POST',
            url:"../ajax/load-more.php",
            data: {number_of_clicks: number_of_clicks},
            dataType: "JSON",

            success: function(data){

                console.log(data);
                console.log(data.length);

                if(data.length < 5)
                {
                    number_of_posts = data.length;
                    $('#load').attr('disabled','disabled');
                }

                for(i = 0; i < number_of_posts; i++){
                    var new_post = "<div class='feed_feed'><div class='feed_username'><span>" + data[i]["username"] + "</span></div>";
                    new_post += '<div class="feed_date"><span>' + data[i]["date"] + '</span></div>';
                    new_post += '<img src="../assets/posts/' + data[i]["photo"] + '" alt="feed_pict_img" class="feed_pict_img">';
                    new_post += '<div class="feed_comment"><span class="comment_username">' + data[i]["username"] + "</span><span class='comment_text'>" + data[i]["comment"] + '</span></div></div>';
                    $(".feed_feed").last().after(new_post);

                    console.log(number_of_clicks);
                }
            }
        });

        //pagina niet opnieuw laden!
        return(false);
    });
});
