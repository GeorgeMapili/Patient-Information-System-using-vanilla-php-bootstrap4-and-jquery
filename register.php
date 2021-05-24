<?php
session_start();
require_once 'connect.php';

if (isset($_SESSION['id'])) {
    header("location:home.php");
    exit(0);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Patient Information System" name="description">
    <meta content="sumc doctorcs clinic, patient information system" name="keywords">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="img/sumc.png">
    <title>Patient | Register</title>
</head>

<body>
    <h2 class="display-4 mb-3 text-center" style="color: rgb(15, 208, 214);">Create an account</h2>

    <form class="form-registration my-3" action="./core/patient/Register.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Full Name</label>
            <?= (isset($_GET['errName']) || isset($_GET['errName1'])) ? '<input type="text" name="name" class="form-control is-invalid" required>' : ((isset($_GET['name'])) ? '<input type="text" name="name" value="' . $_GET['name'] . '" class="form-control" required>' : '<input type="text" name="name" class="form-control" required>'); ?>
            <?= (isset($_GET['errName'])) ? '<small class="text-danger">Name is not valid!</small>' : ""; ?>
            <?= (isset($_GET['errName1'])) ? '<small class="text-danger">Name is already taken!</small>' : ""; ?>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <?= (isset($_GET['errEmail1']) || isset($_GET['errEmail2'])) ? '<input type="text" name="email" class="form-control is-invalid" required>' : ((isset($_GET['email'])) ? '<input type="text" name="email" class="form-control" value=' . $_GET['email'] . ' required>' : '<input type="text" name="email" class="form-control" required>') ?>
            <?= (isset($_GET['errEmail1'])) ? '<small class="text-danger">Email is not valid!</small>' : ""; ?>
            <?= (isset($_GET['errEmail2'])) ? '<small class="text-danger">Email is already taken!</small>' : ""; ?>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <?= (isset($_GET['address']) ? '<input type="text" name="address" class="form-control" value="' . $_GET['address'] . '" required>' : '<input type="text" name="address" class="form-control" required>') ?>
        </div>

        <div class="form-group">
            <label for="age">Age</label>
            <?= (isset($_GET['age']) ? '<input type="number" class="form-control" name="age" value=' . $_GET['age'] . ' min="1" required>' : '<input type="number" class="form-control" name="age" min="1" required>') ?>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <div class="d-flex justify-content-around">
                <?php
                if (isset($_GET['gender']) && $_GET['gender'] === "male") {
                ?>
                    <div>
                        <input type="radio" name="gender" id="male" value="male" checked required>
                        <label for="male">Male</label>
                    </div>
                <?php
                } else {
                ?>
                    <div>
                        <input type="radio" name="gender" id="male" value="male" required>
                        <label for="male">Male</label>
                    </div>
                <?php
                }
                ?>

                <?php
                if (isset($_GET['gender']) && $_GET['gender'] === "female") {
                ?>
                    <div>
                        <input type="radio" name="gender" id="female" value="female" checked required>
                        <label for="female">Female</label>
                    </div>
                <?php
                } else {
                ?>
                    <div>
                        <input type="radio" name="gender" id="female" value="female" required>
                        <label for="female">Female</label>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <?= (isset($_GET['errMobile'])) ? '<input type="tel" class="form-control is-invalid" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : ((isset($_GET['mobile'])) ? '<input type="tel" class="form-control" name="mobileNumber" value= "' . str_replace(' ', '+', $_GET['mobile']) . '" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" class="form-control" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>'); ?>
            <?= (isset($_GET['errMobile'])) ? '<small class="text-danger">Mobile number is already taken!</small>' : ""; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" minlength="6" class="form-control" placeholder="Minimum of 6 characters" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <?= (isset($_GET['errPass'])) ? '<input type="password" name="confirmPassword" minlength="6" class="form-control is-invalid" placeholder="Minimum of 6 characters" required>' : '<input type="password" name="confirmPassword" placeholder="Minimum of 6 characters" minlength="6" class="form-control" required>'; ?>
            <?= (isset($_GET['errPass'])) ? '<small class="text-danger">Password do not match!</small>' : ""; ?>
        </div>

        <div class="form-group">
            <label for="profile_image">Profile Image</label>
            <!-- <input type="file" name="profileImg" class="form-control" required> -->
            <?= (isset($_GET['errorImg1']) || isset($_GET['errorImg2'])) ? '<input type="file" name="profileImg" class="form-control is-invalid" required>' : '<input type="file" name="profileImg" class="form-control" required>'; ?>
            <?= (isset($_GET['errorImg1'])) ? '<small class="text-danger">Image is not valid ONLY(JPG,JPEG,PNG)!</small>' : "";  ?>
            <?= (isset($_GET['errorImg2'])) ? '<small class="text-danger">Image file size is required less than 5MB!</small>' : "";  ?>
        </div>

        <input type="submit" class="btn-block btn-info mt-4" value="Register" name="register">

        <div class="text-center mt-3">
            <a class="btn btn-danger" href="index.php">Already have an account?</a>
        </div>
    </form>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>