<?php

ob_start();
session_start();

$pageTitle = "login";

if (isset($_SESSION["Name"])) {

    header("location: articles.php"); // redirect
}

include "initialize.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["email"];
    $password = $_POST["pass"];
    $encryptedpass = sha1($password);

    // echo $username . " - " . $encryptedpass;

    $stmt = $db->prepare("SELECT 
                                  Userid, Email, Password 
                            FROM
                                  users 
                            WHERE 
                                  Email = ? 
                            AND
                                  Password = ?  
                            LIMIT
                                  1");
    // $stmt->execute(array($username, $password));
    $stmt->execute(array($username, $encryptedpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    // echo $count;
    if ($count > 0) {

        // echo "welcome " . $username;
        $_SESSION["Name"] = $username;
        $_SESSION["Userid"] = $row["Userid"];
        header("location: articles.php"); // will go to this page if he is true
        exit();
    }
}
?>

<div class="container text-center text-capitalize">
    <form class="login" action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
        <input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="off">
        <input class="btn btn-primary" type="submit" value="login">
    </form>

    <a href="reset.php" class="d-block">forgot password?</a>
    <span>Don't have account?</span> <a href="register.php"> Register</a>
</div>

<?php
include $temp_file . "footer.php";
ob_end_flush();
?>