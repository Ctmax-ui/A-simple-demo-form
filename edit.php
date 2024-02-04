<?php
session_start();
if(!isset($_SESSION["login_status"])){
    header("Location: login.php");
}

if($_SESSION["username"] != "admin"){
    header("Location: user.php");
}

require_once("./actions/connectdb.php");

$id = $_GET["id"];
$dataSql =  "SELECT * FROM users WHERE id='$id'";
$selectresult = mysqli_query($connect, $dataSql);
$getResult = mysqli_fetch_assoc($selectresult);

$errorMsg = [];


function isUsernameExists($inputUsername, $currentUserId = null) {
    global $connect; // Sanitize input to prevent SQL injection
    $inputUsername = mysqli_real_escape_string($connect, $inputUsername);  // Query to check if the username already exists excluding the current user (if provided)
    
    $query = "SELECT COUNT(*) as count FROM users WHERE username = '$inputUsername'";
    if ($currentUserId !== null) {
        $currentUserId = (int) $currentUserId;
        $query .= " AND id != $currentUserId";
    }
    $result = mysqli_query($connect, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return ($row['count'] > 0); // Return true if count is greater than 0 (username exists), otherwise false
    } else {
        return false;
    };
}



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

    $username = str_replace(' ', '', $username);
    $password = str_replace(' ', '', $password);

    if (!empty($username) && !empty($usermail) && !empty($password)) {

        $numrows = mysqli_query($connect, "SELECT id FROM users WHERE username = '$username'");
        $usname = mysqli_query($connect, "SELECT username FROM users WHERE id = '$id'");

        // print_r($usname);

        if (isUsernameExists($username, $id)) {
            $errorMsg['errName'] = "The user name is already exsist.";
            // echo "The user name is already exsist.";
        } 
        
        elseif (preg_match("/^[a-zA-Z-']+[0-9]*$/", $username) && preg_match("/^[a-zA-Z0-9@!#\$%^&*:\"';>.,?\/~`+=_\-\\|]+$/", $password)) {


            $filesValue = $getResult["userfile"];

            if (!empty($userfile["name"])) {
                $filesValue =  "simple-form_" .  time() . "_" . str_replace(" ", "_", $userfile["name"]);
                move_uploaded_file($userfile["tmp_name"], "userfiledata/" . $filesValue);
            } else {
                move_uploaded_file($userfile["tmp_name"], "userfiledata/" . $filesValue);
            };

            $updateData = "UPDATE users SET username = '$username', usermail = '$usermail', password = '$password',  userfile = '$filesValue' WHERE id = '$id'";

            $result = mysqli_query($connect, $updateData);
            mysqli_close($connect);

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

        <form class="text-center form-control p-3 w-25 m-auto was-validated" action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">

            <div class="form-floating mb-3">
                <input class="form-control has-validated" type="text" name="username" placeholder="Create a Username" required value="<?php echo $getResult["username"] ?>">
                <label for="floatingInput">Change UserName to</label>
            </div>


            <div class="form-floating mb-3">
                <input class="form-control" type="email" name="usermail" placeholder="Type your email" required value="<?php echo $getResult["usermail"]; ?>">
                <label for="floatingInput">Put an valid Email</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="password" placeholder="Create a new pasword" required value="<?php echo $getResult["password"] ?>">
                <label for="floatingPassword">Password</label>
            </div>


            <div class=" border border-1 p-2">
                <input class="form-imput" type="file" name="userfile"><br>
                <img class="img-fluid mt-2" style="width: 150px; hight: auto;" src="./userfiledata/<?php echo $getResult["userfile"] ?>" alt="No Image">
            </div>

            <input class="my-2 btn btn-outline-success" type="submit" name="edit" value="Edit"> <br>

            <?php if (!empty($errorMsg['errName'])) {
                echo '<div class="text-danger mt-1">' . $errorMsg['errName'] . "</div>";
            } ?>

        </form>
    </div>
</body>

</html>