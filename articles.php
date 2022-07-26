<?php

session_start();

$pageTitle = "articles";

if (isset($_SESSION["Name"])) {

    include "initialize.php";

    $do = (isset($_GET["do"])) ? $_GET["do"] : "manage";

    if ($do == "manage") { // manage page

        $stmt = $db->prepare("SELECT articles.*, users.Fname AS username FROM articles
                                INNER JOIN users ON users.Userid = articles.uid
                                ORDER BY id DESC ");

        $stmt->execute();
        $row = $stmt->fetchAll();

        if (!empty($row)) {

?>

<div class="container users-page">
    <a href="articles.php?do=add" class="btn btn-primary btn-lg my-5"><i class="fa fa-plus"></i> create article </a>
    <?php

            echo '<div class="row">';
            foreach ($row as $rw) {
                echo '<div class="col-6 py-3">';
                    echo '<div class="card article">';
                        echo "<a href='articles.php?do=view&articleid=" . $rw["id"] . "' class='btn'>";
                            echo "<img src='uploads/articles/" . $rw['image'] . "' class='card-img-top'>";
                        echo "</a>";
                        echo '<div class="card-body">';
                            // echo '<h5 class="card-title">' . $rw["uid"] . '</h5>';
                            echo '<h5 class="card-title">' . $rw["username"] . '</h5>';
                            echo '<p class="card-text">' . $rw["creationDate"] . '</p>';
                            
                            // check id for control actions
                            if($rw["uid"] == $_SESSION["Userid"]) {

                                echo "<div class='d-flex justify-content-around'>";
                                    echo "<a href='articles.php?do=edit&articleid=" . $rw["id"] . "' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>";
                                    echo "<a href='articles.php?do=delete&articleid=" . $rw["id"] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete </a>";
                                echo "</div>";
                            }
                    
                        echo '</div>';
                    echo '</div>';                                              
                echo '</div>';
            }
            echo '</div>';
        ?>
</div>

<?php
        } else {

            echo '<div class="container py-5">';
                echo '<div class="alert alert-info" role="alert">There\'s no data to show</div>';
                echo '<a href="articles.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New article </a>';
            echo '</div>';
        }

        ?>

<?php } elseif ($do == "add") { ?>

<!-- add new user  -->

<div class="container article-page">
    
    <h1 class="text-center"> Add Article </h1>
    <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
        <!-- start 1st form username input  -->
        <div class="form-group">
            <label class="col-sm-2 control-label">title</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" class="form-control" name="title" required="required" placeholder="title">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">describtion</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" class="form-control" name="describtion" required="required"
                    placeholder="describtion">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">body</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" class="form-control" name="body" required="required" placeholder="body">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">article image</label>
            <div class="col-sm-10 col-md-6">
                <input type="file" class="form-control" name="articleimg" required="required">
            </div>
        </div>
        <div class="form-group justify-content-center">
            <div class="col-3 justify-content-around">
                <input type="submit" value="Save" class="btn btn-primary btn-lg">
            </div>

        </div>

    </form>
</div>
<?php
    } elseif ($do == "insert") {          // insert page           

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            echo '<h1 class="text-center"> update profile</h1>';
            echo "<div class='container'>";

            // upload files

            $articleimgName = $_FILES["articleimg"]["name"];
            $articleimgType = $_FILES["articleimg"]["type"];
            $articleimgTmp  = $_FILES["articleimg"]["tmp_name"];
            $articleimgSize = $_FILES["articleimg"]["size"];

            // allowed imag extensions

            $imgExtensions = array("png", "jpeg", "jpg", "gif");

            $convertname = explode('.', $articleimgName);

            $filteredname = strtolower(end($convertname));

            $title        = $_POST["title"];
            $describtion  = $_POST["describtion"];
            $body         = $_POST["body"];

            $userid         = $_SESSION["Userid"];


            // validate the form

            $formErrors = array();

            if (empty($title)) {

                $formErrors[] = 'title can\'t be empty';
            }

            if (empty($describtion)) {

                $formErrors[] = 'describtion can\'t be empty';
            }
            if (empty($body)) {

                $formErrors[] = 'body can\'t be empty';
            }

            if (!empty($articleimgName) && !in_array($filteredname, $imgExtensions)) {

                $formErrors[] = 'image extension not allowed';
            }
            if (empty($articleimgName)) {

                $formErrors[] = 'profile image can\'t be empty';
            }
            if ($articleimgSize > 4194304) {

                $formErrors[] = 'profile can\'t be more than 4MB';
            }
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }


            // uppdate database auto

            if (empty($formErrors)) {

                // check the image uploaded

                $profe = rand(0, 1000000000000) . "-" . $articleimgName;
                move_uploaded_file($articleimgTmp, "uploads\articles\\" . $profe);


                // insert user data in the database

                $stmt = $db->prepare("INSERT INTO articles( title, describtion, body, creationDate, image, uid) 
                                        VALUES(:tit, :desc, :bod, now(), :profil, :usid ) ");
                $stmt->execute(array(
                    "tit"      => $title,
                    "desc"     => $describtion,
                    "bod"      => $body,
                    "profil"   => $profe,
                    "usid"     => $userid,

                ));

                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record inserted</div>";

                redirect($themsg);
            }
        } else {

            echo '<div class="container users-page">';

            // calling redirect function 

            $themsg = '<div class="alert alert-danger" role="alert">sorry you aren\'t allowed to be here directly </div>';

            redirect($themsg);

            echo '</div>';
        }

        echo "</div>";
    } elseif ($do == "edit") {

        $articleid = isset($_GET["articleid"]) && is_numeric($_GET["articleid"]) ? intval($_GET["articleid"]) : 0; // if function shortly in one row

        $stmt = $db->prepare("SELECT 
                                  *
                            FROM
                                  articles
                            WHERE 
                                  id = ? 
                            LIMIT
                                  1");
        $stmt->execute(array($articleid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {

        ?>
<!-- start html code  -->

<div class="container article-page">
    <h1 class="text-center"> edit article</h1>
    <form class="form-horizontal" action="?do=update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="articleid" value="<?php echo $articleid ?>">
        <!-- start 1st form username input  -->


        <div class="form-group">
            <label class="col-sm-2 control-label">title</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" class="form-control" name="title" value="<?php echo $row["title"] ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">describtion</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" class="form-control" name="describtion" value="<?php echo $row["describtion"] ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">body</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" class="form-control" name="body" value="<?php echo $row["body"] ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">article image</label>
            <div class="col-sm-10 col-md-6">
                <input type="file" class="form-control" name="articleimg" value="<?php echo $row["Image"] ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10 col-md-2">
                <input type="submit" value="Save" class="btn btn-primary btn-lg">
            </div>
        </div>
    </form>
</div>


<?php
        } else {

            //redirect function

            echo '<div class="container users-page">';

            $themsg = '<div class="alert alert-danger" role="alert">sorry no id here like that</div>';

            redirect($themsg);

            echo '</div>';
        }
    } elseif ($do == "update") {        //ubdate page

        echo '<h1 class="text-center"> update profile</h1>';
        echo "<div class='container'>";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // upload files

            $articleimgName = $_FILES["articleimg"]["name"];
            $articleimgType = $_FILES["articleimg"]["type"];
            $articleimgTmp = $_FILES["articleimg"]["tmp_name"];
            $articleimgSize = $_FILES["articleimg"]["size"];

            // allowed imag extensions

            $imgExtensions = array("png", "jpeg", "jpg", "gif");

            $convertname = explode('.', $articleimgName);

            $filteredname = strtolower(end($convertname));

            $title = $_POST["title"];
            $describtion = $_POST["describtion"];
            $body = $_POST["body"];

            $articleid = $_POST["articleid"];



            // validate the form

            $formErrors = array();

            if (empty($title)) {

                $formErrors[] = 'title can\'t be empty';
            }
            if (empty($describtion)) {

                $formErrors[] = 'describtion can\'t be empty';
            }
            if (empty($body)) {

                $formErrors[] = 'body can\'t be empty';
            }

            if (!empty($articleimgName) && !in_array($filteredname, $imgExtensions)) {

            $formErrors[] = 'image extension not allowed';
            }
            if (empty($articleimgName)) {

            $formErrors[] = 'profile image can\'t be empty';
            }
            if ($articleimgSize > 4194304) {

            $formErrors[] = 'profile can\'t be more than 4MB';
            }


            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }

            // uppdate database auto

            if (empty($formErrors)) {

                // check the image uploaded

                $profe = rand(0, 1000000000000) . "-" . $articleimgName;
                move_uploaded_file($articleimgTmp, "uploads\articles\\" . $profe);

                $stmt = $db->prepare("UPDATE articles SET title = ?, describtion = ?, body = ?, Image = ?  WHERE id = ?");
                $stmt->execute(array($title, $describtion, $body, $profe, $articleid));

                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                // calling redirect functon and rhe seconds will be 1s for the default
                redirect($themsg);
            }
        } else {
            // calling redirect functon and rhe seconds will be 1s for the default

            $themsg = '<div class="alert alert-danger" role="alert">sorry u r not allowed here</div>';

            redirect($themsg);
        }

        echo "</div>";
    } elseif ($do == "delete") {

        // delete mamber from database page

        echo '<h1 class="text-center"> Delete profile</h1>';
        echo "<div class='container'>";

        $articleid = isset($_GET["articleid"]) && is_numeric($_GET["articleid"]) ? intval($_GET["articleid"]) : 0; // if function shortly in one row

        $check = checkItem("id", "articles", $articleid);

        if ($check > 0) {

            $stmt = $db->prepare("DELETE FROM articles WHERE id = :artid");
            $stmt->bindparam(":artid", $articleid);
            $stmt->execute();

            // redirect function
            $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Record Deleted</div>";
            redirect($themsg, "back");
        } else {

            //  redirect function
            $themsg = '<div class="alert alert-danger" role="alert">not exist member</div>';

            redirect($themsg);
        }

        echo "</div>";
    } elseif ($do == "view") {

                $articleid = isset($_GET["articleid"]) && is_numeric($_GET["articleid"]) ? intval($_GET["articleid"]) :
                0; // if function shortly in one row

                $stmt = $db->prepare("SELECT
                *
                FROM
                articles
                WHERE
                id = ?
                LIMIT
                1");
                $stmt->execute(array($articleid));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($stmt->rowCount() > 0) {

                ?>
                <!-- start html code  -->
                
                <div class="card mb-3 my-5">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <?php echo "<img src='uploads/articles/" . $row['image'] . "' class='card-img-top'>"; ?>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column justify-content-evenly h-100 px-5">
                                <h5 class="card-title">Title: <?php echo $row["title"] ?></h5>
                                <p class="card-text">Describtion: <?php echo $row["describtion"] ?></p>
                                <p class="card-text">Body: <?php echo $row["body"] ?></p>
                                <p class="card-text"><small class="text-muted"><?php echo $row["creationDate"] ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
        } else {

            //redirect function

            echo '<div class="container users-page">';

            $themsg = '<div class="alert alert-danger" role="alert">sorry no id here like that</div>';

            redirect($themsg);

            echo '</div>';
        }

    }


    include $temp_file . "footer.php";
} else {

    header("location: index.php");
    exit();
}