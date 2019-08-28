<?php
global $ThemeName;
function connect()
{
    session_start();
    $ThemeName = 'nitro';
    $_SESSION['theme'] = $ThemeName;
    $_SESSION['connection'] = oci_connect('hr', 'hr', 'localhost/XE', 'AL32UTF8');
    if(!$_SESSION['connection'])
    {
        $e = oci_error();
        ?>
        <div class="alert alert-danger" role="alert">
        <?php
            trigger_error(htmlentities($e['message'], ENT_QUOTES),E_USER_ERROR);
        ?>
        </div>
        <?php
    }
    //ERROR_REPORTING(2);
    include("oci_objects.php");
    oci_close($_SESSION['connection']);
    session_destroy();
}
?>