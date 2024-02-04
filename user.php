<?php
session_start();

if (!isset($_SESSION["login_status"])) {
    header("Location: login.php");
}

// print_r($_SESSION);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">

        <div class="row m-0 p-2 border justify-content-between">
            <div class="col-3">
                <img class="img-fluid rounded-pill" src="./userfiledata/<?php echo $_SESSION["userfile"]; ?>" alt="No image">
            </div>
            <div class="col-5">
                <p class=" text-left"><span class="fw-bold">Username : </span><?php echo $_SESSION["username"];?></p>
                <p class=" text-left"><span class="fw-bold">Email : </span><?php echo $_SESSION["usermail"];?></p>
                <p class=" text-left"><span class="fw-bold">Password : </span><?php echo $_SESSION["password"];?></p>
            </div>
            <div class="col-3 text-end d-block">
                <a class="" href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                <?php 
                if($_SESSION["username"] == "admin"){
                    echo '<a class="pt-3 d-block" href="./index.php"><i class="fa-solid fa-user-gear"></i>  Admin Panel</a>';
                }
                
                
                ?>
            </div>
        </div>



    </div>
</body>

</html>