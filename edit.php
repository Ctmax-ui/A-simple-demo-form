<?php
require_once("./actions/connectdb.php");

$id = $_GET["id"];
$dataSql =  "SELECT * FROM users WHERE id='$id'";
$selectresult = mysqli_query($connect, $dataSql);
$getResult = mysqli_fetch_assoc($selectresult);


if (!$getResult) {
    // Handle the case where the user with the given ID is not found
    echo "User not found!";
    exit();
}

if (isset($_POST["edit"])) {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $usermail = filter_input(INPUT_POST, "usermail", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $userfile = $_FILES["userfile"];

    if (!empty($username) && !empty($usermail) && !empty($password)) {
        if (preg_match("/^[a-zA-Z-']+[0-9]*$/", $username) && preg_match("/^[a-zA-Z0-9@!#\$%^&*:\"';>.,?\/~`+=_\-\\|]+$/", $password)) {


            $filesValue = $getResult["userfile"];
            if(!empty($userfile["name"])){
                $filesValue =  "simple-form_".  time(). "_" . str_replace(" ", "_", $userfile["name"]);
                move_uploaded_file($userfile["tmp_name"], "userfiledata/". $filesValue);
            }else{
                move_uploaded_file($userfile["tmp_name"], "userfiledata/". $filesValue);
            };
            
            $updateData = "UPDATE users SET username = '$username', email = '$usermail', password = '$password',  userfile = '$filesValue' WHERE id = '$id'";

            $result = mysqli_query($connect, $updateData);

            if ($result) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($connect);
            }
        } else {
            echo "Invalid username or password format!";
        }
    } else {
        echo "Username, email, or password is empty!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="d-flex justify-content-center aligin-items-center my-5 text-center">
    <form class="text-center " action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label for="user">User Name :</label>
        <input type="text" name="username" id="user" value="<?php echo $getResult["username"] ?>">
        <br>
        <label class="py-3" for="usermail">User Mail :</label>
        <input type="email" name="usermail" value="<?php echo $getResult["email"] ?>" required>
        <br>
        <label for="password">Password :</label>
        <input type="text" name="password" value="<?php echo $getResult["password"] ?>">
        <br> 

        <div class="mt-4">
        <input class="form-imput" type="file" name="userfile"><br>
        <img class="img-fluid mt-2" style="width: 150px; hight: auto;" src="./userfiledata/<?php echo $getResult["userfile"] ?>" alt="No Image">
        </div>
        
        <input class="my-4 btn btn-outline-success" type="submit" name="edit" value="Edit">
    </form>
    </div>
</body>

</html>