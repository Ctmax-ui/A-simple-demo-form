<?php

require_once("./actions/connectdb.php");
require_once("./vendor/mail/mail.php");

$username = $password = $usermail = $userfile = "";


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_POST["create"])) {
    
    
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    
    $usermail = $_POST["usermail"];
    
    
    
    if (!empty(test_input($username)) && !empty(test_input($password)) && !empty($usermail)) {
        
        
        if (preg_match("/^[a-zA-Z-']+[0-9]*$/", $username) && preg_match("/^[a-zA-Z0-9@!#\$%^&*:\"';>.,?\/~`+=_\-\\|]+$/", $password)) {
            
            // $filesValue = null;
            

            // the file uploading logic
            if (!empty($_FILES['userfile']['name']) || strlen($_FILES['userfile']['name'])) {
                
                $filesValue =  "simple-form_" . time() . "_" . str_replace(" ", "_", $_FILES['userfile']['name']);
                
                move_uploaded_file($_FILES['userfile']['tmp_name'], "userfiledata/" . $filesValue);
            }


            $uploadData = "INSERT INTO users (username, usermail, password, userfile) VALUES ('$username', '$usermail', '$password', '$filesValue') ";
            
            $result = mysqli_query($connect, $uploadData);
            
            
            
            if ($result) {
                
                // sendMail($usermail, $username, $password);
                header("Location: index.php");
                exit();
                
            } else {
                echo "Error: " . mysqli_error($connect);
            };
            
        } else if (!preg_match("/^[a-zA-Z-']+[0-9]*$/", $username)) {
            echo "username start with letter, it cannot contains any space";
        } else if (!preg_match("/^[a-zA-Z0-9@!#\$%^&*:\"';>.,?\/~`+=_\-\\|]+$/", $password)) {
            echo "Password cannot contain space";
        } else {
        }
    } else {
        echo "Username or Pssword is empty!!";
    }
}


$dataSql =  "SELECT * FROM users";
$selectresult = mysqli_query($connect, $dataSql);


// echo "<pre>";
// while($row = mysqli_fetch_assoc($selectresult)){
//         print_r($row);
    
// }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>simple form</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="row m-0 justify-content-center gap-4 mt-4">
        <div class="col-7">
            <form class="text-center form-control p-3 w-50 m-auto was-validated" action="index.php" method="post" enctype="multipart/form-data" >

                <div class="form-floating mb-3" >
                    <input class="form-control has-validated" type="text" name="username" placeholder="Create a Username" required value="dave">
                    <label for="floatingInput">Create a Username</label>
                </div>


                <div class="form-floating mb-3">
                    <input class="form-control" type="email" name="usermail" placeholder="Type your email" required value="dave@gmail.com">
                    <label for="floatingInput">Put an valid Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="password" placeholder="Create a new pasword" required value="dave123">
                    <label for="floatingPassword" >Password</label>
                </div>
            

                <input class="input-group" type="file" name="userfile">  
                
                <br>
                <input class=" mt-2 btn btn-outline-success" type="submit" name="create" value="Create Account">

                </form>
        </div>
    </div>

    <table class="table table-striped table-bordered text-center mt-5">
        <tr>
            <th>Id</th>
            <th>User Name</th>
            <th>User Email</th>
            <th>User Password</th>
            <th>userfile</th>
            <th>Remove</th>
            <th>Edit</th>
        </tr>
        <?php $i = 1;
        while ($row = mysqli_fetch_assoc($selectresult)) : ?>
            <tr>
                <th><?php echo $i++ ?></th>
                <th><?php echo $row["username"] ?></th>
                <th><?php echo $row["usermail"] ?></th>
                <th><?php echo $row["password"] ?></th>

                <th><?php if (!empty($row["userfile"]) /* && add more logic */) { ?>
                        <img style="width: 100px;" src="./userfiledata/<?php echo $row["userfile"]; ?>" alt="">

                    <?php } else {
                        echo "No profile";
                    } ?>
                </th>


                <th><a href="./delete.php?id=<?php echo $row["id"] ?>">Remove</a></th>
                <th><a href="./edit.php?id=<?php echo $row["id"] ?>">Edit</a></th>
            </tr>
        <?php endwhile ?>
    </table>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>