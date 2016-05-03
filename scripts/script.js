$(document).ready(function() {

    //------------------- HTML5 GEOLOCATION -------------------//

    var watchID;
    var geoLoc;

    getLocation();

    // Get the latitude & longitude;
    function showLocation(position)
    {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        getAddress(latitude, longitude);
    }

    // Handel any errors that my come
    function errorHandler(err)
    {
        if(err.code == 1)
        {
            alert("error: access is denied!");
        }
        else if( err.code == 2) {
            alert("error: position is unavailable!");
        }
    }

    // Get the location of the current location settings
    function getLocation()
    {
        if(navigator.geolocation)
        {
            geoLoc = navigator.geolocation;
            watchID = geoLoc.watchPosition(showLocation, errorHandler);
        }
        else
        {
            alert("sorry, browser does not support geolocation!");
        }
    }

    // Get the address
    function getAddress(latitude, longitude)
    {
        $.get("http://maps.google.com/maps/api/geocode/xml?latlng=" + latitude + "," + longitude + "&sensor=false", function(data)
        {
            $(data).find("formatted_address").each(function(){

                var adress = $(this).text();
                adress = adress.split(',');

                var city = adress[1].slice(5);

                $("#location_post").val(city);

                return false;
            });
        });
    }


    //------------------- AJAX - LOAD MORE -------------------//

    $(".feed-feed").slice(5).hide();
    var number_of_clicks = 0;

    $("#load").on("click", function () {

        number_of_clicks = number_of_clicks + 1;
        var start = number_of_clicks * 5;
        var stop = start + 5;

        $(".feed-feed").slice(start, stop).show();

        //pagina niet opnieuw laden!
        return (false);
    });

    //------------------- AJAX - INAPP POST -------------------//

    $(document).on("click", ".btn_feed_inapp", function(){

        var $current_post = $(this);
        var current_id = $current_post.attr("id").slice(10);

        $.ajax({
            type: 'POST',
            url: "../ajax/inapp-post.php",
            data: {current_id: current_id},
            dataType: "JSON",

            success: function (data) {
                var $span = $($current_post).prev("span");
                $span.text(data["inapp"] + " inapps");

                if(data["inapp"] > 2){
                    $current_post.parents('.feed-feed').fadeOut();
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

        //return (false);
    });

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

                $("#input_" + current_post_id).val("");

                var username = data["username"];
                var comment = data["comment"];

                var new_comment = '<li><span class="feed-comment-list-username">' + username + '</span>' + comment + '</li>';

                $("#" + data["post"]).append(new_comment);
            }
        });

        return(false);
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

