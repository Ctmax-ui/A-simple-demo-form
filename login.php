<?php
require_once("./actions/connectdb.php");

$username = $password = "";

$errorMsg = [];

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace(' ', '', $data);
    return $data;
}


if (isset($_POST["loginbtn"])) {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    $username = str_replace(' ', '', $username);
    $password = str_replace(' ', '', $password);

    if (!empty(test_input($username)) && !empty(test_input($password))) {

        if (preg_match("/^[a-zA-Z-']+[0-9]*$/", $username) && preg_match("/^[a-zA-Z0-9@!#\$%^&*:\"';>.,?\/~`+=_\-\\|]+$/", $password)) {

            $selectSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

            $result = mysqli_query($connect, $selectSql);

            $fetchedData = mysqli_fetch_assoc($result);

            // var_dump($result);

            if (mysqli_num_rows($result) == 1) {
                // sendMail($usermail, $username, $password);
                session_start();
                $_SESSION["login_status"] = true;

                $_SESSION["id"] = $fetchedData['id'];
                $_SESSION["username"] = $fetchedData['username'];
                $_SESSION["usermail"] = $fetchedData['usermail'];
                $_SESSION["password"] = $fetchedData['password'];
                $_SESSION["userfile"] = $fetchedData['userfile'];



                header("Location: index.php");
                echo 'lol';
                mysqli_close($connect);
                exit();
            } else {
                $errorMsg['errName'] = "Invalid Username or Password";
            }
        } else if (!preg_match("/^[a-zA-Z-']+[0-9]*$/", $username)) {

            // echo "username start with letter, it cannot contains any space";

            $errorMsg['errName'] = "username start with letter, it cannot contains any space or spacial characters.";
        } else if (!preg_match("/^[a-zA-Z0-9@!#\$%^&*:\"';>.,?\/~`+=_\-\\|]+$/", $password)) {

            // echo "Password cannot contain space";
            $errorMsg['errName'] = "Password cannot contain space";
        } else {
        }
    } else {
        // echo "Username or Pssword is empty!!";
        $errorMsg['errName'] = "Username or Pssword is empty!!";
    }
}

mysqli_close($connect);





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
</head>

<body>
    <h2 class="text-center mt-3">Login</h2>

    <form class="text-center form-control mt-5 p-3 w-25 m-auto was-validated" action="./login.php" method="post">
        <div class="form-floating mb-3">
            <input class="form-control" type="text" name="username" placeholder="Create a new pasword" required value="<?php echo $username; ?>">
            <label for="floatingPassword">Username</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" type="text" name="password" placeholder="Create a new pasword" required value="<?php echo $password; ?>">
            <label for="floatingPassword">Password</label>
        </div>
        <input class="my-2 btn btn-outline-success" type="submit" name="loginbtn" value="Login">
        <div class="position-relative mb-4 w-100 text-center d-flex justify-content-center">
            <div class="text-danger position-absolute warning-text ">
                <?php if (!empty($errorMsg['errName'])) {
                    echo  $errorMsg['errName'];
                } ?>
            </div>
        </div>
    </form>

</body>

</html>