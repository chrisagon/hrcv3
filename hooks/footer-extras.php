<?php
    $script_name = basename($_SERVER['PHP_SELF']);
    $currDir3=dirname(__FILE__ , 2 );
    $chemin_image = $currDir3.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."foret_focus.png";

    if($script_name == 'index.php' && isset($_GET['signIn'])){
        ?>
        <style>
            body{
                background: url(images/foret_focus.png) no-repeat fixed center center / cover;
            }
        </style>

        <div class="alert alert-success" id="benefits">
            HRCV3 :
            <ul>
                <li> Site prototype
                <li> gestion des missions et des competences
                <li> compte demo : consultant_demo/demodemo01
            </ul>
        </div>

        <script>
            $j(function(){
                $j('#benefits').appendTo('#login_splash');
            })
        </script>
        <?php
    }
?>
