<?php
session_start();
require_once 'connect.php';

if (isset($_SESSION['id'])) {
    header("location:main.php");
    exit(0);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <title>Patient | Register</title>
</head>

<?php

if (isset($_POST['register'])) {
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $address = trim(htmlspecialchars($_POST['address']));
    $age = trim(htmlspecialchars($_POST['age']));
    $gender = trim(htmlspecialchars($_POST['gender']));
    $mobileNumber = trim(htmlspecialchars($_POST['mobileNumber']));
    $password = trim(htmlspecialchars($_POST['password']));
    $confirmPassword = trim(htmlspecialchars($_POST['confirmPassword']));
    $profileImg = $_FILES['profileImg'];

    // Check the name if valid
    if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
        header("location:register.php?errName=name_is_not_valid");
        exit(0);
    }

    // Check the email if valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location:register.php?errEmail1=email_is_not_valid");
        exit(0);
    }

    // Check if the email is already taken
    $sql = "SELECT * FROM patientappointment WHERE pEmail = :email";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    $emailCount = $stmt->rowCount();

    if ($emailCount >= 1) {
        header("location:register.php?errEmail2=email_already_taken");
        exit(0);
    }

    // Check if the mobile number is already existed
    $sql = "SELECT * FROM patientappointment WHERE pMobile = :mobile";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_INT);
    $stmt->execute();

    $mobileCount = $stmt->rowCount();

    if ($mobileCount >= 1) {
        header("location:register.php?errMobile=mobile_number_already_taken");
        exit(0);
    }

    // Check if the password does not match

    if ($password !== $confirmPassword) {
        header("location:register.php?errPass=password_do_not_match");
        exit(0);
    }

    // Check Image
    $profileName = $profileImg['name'];
    $ext = $profileImg['type'];
    $extF = explode('/', $ext);
    $tmpname = $profileImg['tmp_name'];
    $dest = __DIR__ . "/upload/user_profile_img/" . $profileName;

    // Check if the Extension of image is valid
    $allowed = array('jpg', 'jpeg', 'png');

    if (!in_array(strtolower($extF[1]), $allowed)) {
        header("location:register.php?errorImg1=image_is_not_valid");
        exit(0);
    }

    // Check if the image size is valid

    if ($profileImg['size'] > 5000000) {
        header("location:register.php?errorImg2=image_is_only_less_than_5MB");
        exit(0);
    }

    // Hash the password
    $hashPass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT into patientappointment (pName,pEmail,pAddress,pAge,pGender,pMobile,pPassword,pProfile)VALUES(:name,:email,:address,:age,:gender,:mobile,:password,:profile)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":address", $address, PDO::PARAM_STR);
    $stmt->bindParam(":age", $age, PDO::PARAM_STR);
    $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
    $stmt->bindParam(":mobile", $mobileNumber, PDO::PARAM_STR);
    $stmt->bindParam(":password", $hashPass, PDO::PARAM_STR);
    $stmt->bindParam(":profile", $profileName, PDO::PARAM_STR);

    if ($stmt->execute()) {
        move_uploaded_file($tmpname, $dest);
        header("location:index.php?RegSuccess=Register_success");
    }
}

?>

<body>
    <h2 class="display-4 mb-3 text-center" style="color: rgb(15, 208, 214);">Create an account</h2>

    <form class="form-registration" action="register.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name</label>
            <?= (isset($_GET['errName'])) ? '<input type="text" name="name" class="form-control is-invalid" required>' : '<input type="text" name="name" class="form-control" required>'; ?>
            <?= (isset($_GET['errName'])) ? '<small class="text-danger">Name is not valid!</small>' : ""; ?>
        </div>
        <div class="form-group">
            <label>Email</label>
            <?= (isset($_GET['errEmail1']) || isset($_GET['errEmail2'])) ? '<input type="text" name="email" class="form-control is-invalid" required>' : '<input type="text" name="email" class="form-control" required>'; ?>
            <?= (isset($_GET['errEmail1'])) ? '<small class="text-danger">Email is not valid!</small>' : ""; ?>
            <?= (isset($_GET['errEmail2'])) ? '<small class="text-danger">Email is already taken!</small>' : ""; ?>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Age</label>
            <input type="number" class="form-control" name="age" min="1" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" id="" class="form-control">
                <option value="">Select a gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        </div>

        <div class="form-group">
            <label>Mobile Number</label>
            <?= (isset($_GET['errMobile'])) ? '<input type="tel" class="form-control is-invalid" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>' : '<input type="tel" class="form-control" name="mobileNumber" placeholder="+639876543210 or 09876543210" pattern="((^(\+)(\d){12}$)|(^\d{11}$))" required>'; ?>
            <?= (isset($_GET['errMobile'])) ? '<small class="text-danger">Mobile number is already taken!</small>' : ""; ?>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" minlength="6" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <?= (isset($_GET['errPass'])) ? '<input type="password" name="confirmPassword" minlength="6" class="form-control is-invalid" required>' : '<input type="password" name="confirmPassword" minlength="6" class="form-control" required>'; ?>
            <?= (isset($_GET['errPass'])) ? '<small class="text-danger">Password do not match!</small>' : ""; ?>
        </div>

        <div class="form-group">
            <label>Profile Image</label>
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