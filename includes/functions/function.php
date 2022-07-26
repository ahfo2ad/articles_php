<?php

        // function to give title to any page if it set 

    function getTitle() {

        global $pageTitle;

        if(isset($pageTitle)) {

            echo $pageTitle;
        }
        else {
            echo "default";
        }
    }

    // redirct to home page and time before redirect
    
    function redirect($themsg, $url = null, $seconds = 1) {

        if($url === null) {

            $url = "articles.php";

            $link = "Home page";
        }
        else {

                    // if shortly

            // $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "" ? $_SERVER['HTTP_REFERER'] : "index.php";

                    // if in details

            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "") {

                $url = $_SERVER['HTTP_REFERER'];

                $link = "Previous page";
            }
            else {

                $url = "articles.php";

                $link = "Home page";
            }
            
        }

        echo $themsg;

        echo '<div class="alert alert-info">' . "you will be redirected to $link in " . $seconds . " seconds" . '</div>';

        header("refresh:$seconds;url=$url");

        exit();
    }

    // check items function 

    function checkItem($select, $from, $value) {

        global $db;
        $statment = $db->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute(array($value));
        $count = $statment->rowCount();
        return $count;
    }



