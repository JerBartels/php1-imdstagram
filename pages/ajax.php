<?php

if(isset($_POST['action']))
{
    switch($_POST['action'])
    {
        case 'change':
            change();
            break;
        case 'save':
            save();
            break;
    }
}

function change()
{
    print_r("change");
    exit;
}

function save()
{
    print_r("save");
    exit;
}