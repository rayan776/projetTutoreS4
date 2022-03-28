<?php
    session_start();

    if (isset($_GET['classToDel'])) {

        foreach ($_SESSION["jours"] as $jour) {
            foreach ($_SESSION["moments"] as $moment) {
                if (isset($_SESSION[$jour][$moment][$_GET['classToDel']])) {
                    unset($_SESSION[$jour][$moment][$_GET['classToDel']]);
                    exit;
                }
            }
        }

    }

    if (isset($_GET['reset'])) {

        $response = array();

        foreach ($_SESSION["jours"] as $jour) {
            foreach ($_SESSION["moments"] as $moment) {
                foreach ($_SESSION[$jour][$moment] as $arr => $aliment) {
                    $response[] = $arr;
                    unset($_SESSION[$jour][$moment][$arr]);
                }
            }
        }

        echo json_encode($response);
    }


?>