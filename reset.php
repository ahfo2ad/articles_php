<?php

    session_start();

    // $noNavbar = "";     // to hide navbar
    $pageTitle = "reset password";

    // if(isset($_SESSION["Name"])) {

        include "initialize.php";

        $do = (isset($_GET["do"]))? $_GET["do"] : "manage";

        if($do == "manage") {
            ?>
        
            <div class="container">
                <form class="login" action="?do=update" method="POST">
                    <h3 class="text-center">Reset Password</h3>
                    <input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off">
                    <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="off">
                    <input class="form-control" type="password" name="confpass" placeholder="confirm password" autocomplete="off">
                    <input class="btn btn-primary" type="submit" value="Update">
                </form>
            </div>    

            <?php
        }
        elseif($do == "update") {


            if($_SERVER["REQUEST_METHOD"] == "POST") {

                $email      = $_POST["email"];
                $firstpass  = $_POST["pass"];
                $secondpass = $_POST["confpass"];

                $shahpass1 = sha1($firstpass);
                $shahpass2 = sha1($secondpass);

                // validate the form

                $formErrors = array();

                if(empty($email)) {

                    $formErrors[] = '<div class="alert alert-danger" role="alert">email can\'t be empty</div>';
                }
                if(empty($firstpass)) {

                    $formErrors[] = '<div class="alert alert-danger" role="alert">password can\'t be empty</div>';
                }
                if(empty($secondpass)) {

                    $formErrors[] = '<div class="alert alert-danger" role="alert">confirm password can\'t be empty</div>';
                }
                if($shahpass1 !== $shahpass2) {

                    $formErrors[] = '<div class="alert alert-danger" role="alert">passwords not matched</div>';
                }
                foreach($formErrors as $error) {

                    // echo $error;
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }

                    // uppdate database auto

                if(empty($formErrors)) {

                    // check if the email is registered in database or not
                    $check = checkItem("Email", "users", $email);

                    if($check == 1) {   // true == registered

                        $stmt = $db->prepare("UPDATE users SET Password = ?  WHERE Email = ?");
                        $stmt->execute(array($shahpass2, $email));

                        $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";
                        
                        redirect($themsg);
                    
                    }
                    else {   // not registered
                    
                        $themsg = '<div class="alert alert-danger" role="alert">This email is not registered</div>';

                        redirect($themsg);
                    }
                }

            }
            else {

                // calling redirect functon and rhe seconds will be 3s for the default

                $themsg = '<div class="alert alert-danger" role="alert">sorry u r not allowed here</div>';

                redirect($themsg);
            }

        }

        
        include $temp_file . "footer.php";


?>
