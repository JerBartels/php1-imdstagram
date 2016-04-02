$(document).ready(function()
{
    $(".button").on("click", function()
    {
        //op welke knop wordt geklikt?
        var btn_value = $(this).val();
        var url_ajax = "ajax.php";

        data =
        {
            'action': btn_value
        };

        $.post(url_ajax, data, function(response)
        {
            if(btn_value == "change")
            {
                $(".input_profile").attr("readonly", false);
            }
            else
            {
                $(".input_profile").attr("readonly", true);
            }
        });
    });

});