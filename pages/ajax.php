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
    echo "change";
    exit;
}

function save()
{
    echo "save";
    exit;
}