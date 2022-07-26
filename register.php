<?php

session_start();

$pageTitle = "register";

include "initialize.php";


if (isset($_POST["signup"])) {
    // filtering the form 

    $formErrors = array();

    $Fname            = $_POST["Fname"];
    $Lname            = $_POST["Lname"];
    $mail               = $_POST["email"];
    $Address               = $_POST["Address"];
    $Date               = $_POST["Date"];
    $password      = $_POST["password"];


    // filtering the username input

    if (isset($Fname)) {

        $filteredfirst = filter_var($Fname, FILTER_SANITIZE_STRING);

        if (strlen($filteredfirst) < 4) {

            $formErrors[] = "firstname must be more than 4 chars";
        }
    }

    if (isset($Lname)) {

        $filteredlast = filter_var($Lname, FILTER_SANITIZE_STRING);

        if (strlen($filteredlast) < 4) {

            $formErrors[] = "firstname must be more than 4 chars";
        }
    }


    // email

    if (isset($mail)) {

        $filteredEmail = filter_var($mail, FILTER_SANITIZE_EMAIL);

        if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != TRUE) {

            $formErrors[] = "not valid email ";
        }
    }

    if (isset($Address)) {

        if (empty($Address)) {
            $formErrors[] = "Address can't be empty";
        }
        if (strlen($Address) < 4) {

            $formErrors[] = "Address must be more than 4 chars";
        }
    }

    // encrypting passswords and check if matched or not 

    if (isset($password)) {

        // check if password empty before hashing 

        if (empty($password)) {

            $formErrors[] = "password can't be empty";
        }

        if (strlen($password) < 8) {

            $formErrors[] = "password must be more than 8 chars";
        }

        $shahpass1 = sha1($password);
    }

    // uppdate database auto

    if (empty($formErrors)) {

        // check if the user is exists or not

        $check = checkItem("Fname", "users", $Fname);

        // check if the email is exists or not

        $check2 = checkItem("Email", "users", $mail);

        if ($check == 1) {

            $formErrors[] = "this name is already exists";
        } elseif ($check2 == 1) {

            $formErrors[] = "this email is already exists use another mail";
        } else {


            // insert user data in the database

            $stmt = $db->prepare("INSERT INTO users( Fname, Lname, Password, Email, Address, BirthDate) 

                                            VALUES(:fnm, :lnm, :pass, :mail, :add, :birth ) ");

            $stmt->execute(array(

                "fnm"     => $Fname,
                "lnm"     => $Lname,
                "pass"     => $shahpass1,
                "mail"     => $mail,
                "add"     => $Address,
                "birth"     => $Date,

            ));

            $msgsucces = "Sign up done Successfully";
        }

        // $_SESSION["Name"] = $Fname;  // if want reirect auto to dashboard after register
        $_SESSION["Fname"] = $Fname;
        $_SESSION["password"] = $password;
        header("location: index.php");  
        exit();
    }
    // end update 
}

?>

<div class="container">
    <h5 class="text-center my-5">Registration</h5>
    <div class="row justify-content-center">
        <div class="col-6">
            <!-- start signup form  -->
            <form class="signup" action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">

                <div class="mb-3 rwaast">
                    <label for="exampleInputEmail1" class="form-label">first name</label>
                    <input class="form-control" type="text" name="Fname" autocomplete="off" placeholder="firstname" required="required">
                </div>
                <div class="mb-3 rwaast">
                    <label for="exampleInputEmail1" class="form-label">last name</label>
                    <input class="form-control" type="text" name="Lname" autocomplete="off" placeholder="lastname" required="required">
                </div>
                <div class="mb-3 rwaast">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" placeholder="valid mail" required="required">
                </div>
                <div class="mb-3 rwaast">
                    <label for="exampleInputEmail1" class="form-label">Address</label>
                    <input class="form-control" type="text" name="Address" placeholder="address" required="required">
                </div>
                <div class="mb-3 rwaast">
                    <label for="exampleInputEmail1" class="form-label">Date</label>
                    <input class="form-control" type="text" id="datepicker" name="Date" readonly placeholder="click to choose your date" required="required" autocomplete="off">
                </div>
                <div class="mb-3 rwaast">
                    <label for="exampleInputEmail1" class="form-label">password</label>
                    <input class="form-control" type="password" minlength="8" name="password" autocomplete="new-password" placeholder="password" required="required">
                </div>

                <div class="d-grid col-6 mx-auto">
                    <input type="submit" class="btn btn-success" name="signup" value="Signup">
                </div>
            </form>
        </div>
    </div>
    <!-- print the errors from the form -->

    <div class="msgerror text-center">
        <?php

        if (!empty($formErrors)) {

            foreach ($formErrors as $error) {

                // echo $error . "<br>";

                echo '<div class="alert alert-danger">';
                echo '<strong>Error! </strong>' . $error;
                echo '</div>';
            }
        }

        if (isset($msgsucces)) {

            echo '<div class="alert alert-success">';
            echo '<strong>Congratulations! </strong>' . $msgsucces;
            echo '</div>';
        }
        ?>
    </div>
    <!-- end signup form  -->
</div>



<?php
include $temp_file . "footer.php";
?>