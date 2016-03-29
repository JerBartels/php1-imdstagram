$(document).ready(function()
{
    $(".button").on("click", function()
    {
        //op welke knop wordt geklikt?
        var btn_value = $(this).val();
        var url_ajax = '../pages/ajax.php';

        data =
        {
            'action': btn_value
        };

        $.post(url_ajax, data, function(response)
        {
            alert(btn_value)
        });
    });

});