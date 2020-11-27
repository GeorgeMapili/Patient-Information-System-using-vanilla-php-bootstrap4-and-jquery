<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['id'])) {
    header("location:index.php");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <title>Patient | Account</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="main.php">Company Name</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set an Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="contactus.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="healthLibrary.php">Health Library</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="myappointment.php">My Appointments</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <img src="upload/user_profile_img/<?= $_SESSION['profile']; ?>" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href=""><?= $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="myaccount.php">My account</a>
                            <a class="dropdown-item" href="myAppointmentHistory.php">My Appointment History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">

        <div class="container">

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">My Profile</h1>
            </div>
            <div class="text-center">
                <?= (isset($_GET['errInfo'])) ? '<span class="text-danger">Nothing to update!</span>' : ''; ?>
                <?= (isset($_GET['successInfo'])) ? '<span class="text-success">Update successfully!</span>' : ''; ?>
            </div>
            <form action="action.php" method="post">
                <div class="row">
                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">
                    <div class="col">
                        <label>Full Name</label>
                        <?php
                        if (isset($_GET['errName']) || isset($_GET['errName1'])) {
                        ?>
                            <input type="text" name="name" class="form-control is-invalid">
                        <?php
                        } else { ?>
                            <input type="text" name="name" class="form-control" value="<?= $_SESSION['name'] ?>">
                        <?php
                        }
                        ?>
                        <?= (isset($_GET['errName']) && isset($_GET['errName']) == 'name_is_not_valid') ? '<small class="text-danger">Name is not valid!</small>' : ''; ?>
                        <?= (isset($_GET['errName1']) && isset($_GET['errName1']) == 'name_is_already_taken') ? '<small class="text-danger">Name is already taken!</small>' : ''; ?>
                    </div>
                    <div class="col">
                        <label>Email</label>
                        <?php
                        if (isset($_GET['errEmail']) && isset($_GET['errEmail']) == 'email_is_already_existed') {
                        ?>
                            <input type="email" name="email" class="form-control is-invalid">
                        <?php
                        } else { ?>
                            <input type="email" name="email" class="form-control" value="<?= $_SESSION['email']; ?>">
                        <?php
                        }
                        ?>
                        <?= (isset($_GET['errEmail']) && isset($_GET['errEmail']) == 'email_is_already_existed') ? '<small class="text-danger">Email is already taken!</small>' : ''; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= $_SESSION['address']; ?>">
                    </div>
                    <div class="col">
                        <label>Mobile Number</label>
                        <?php
                        if (isset($_GET['errMobile']) && isset($_GET['errMobile']) == 'mobile_number_is_already_existed') {
                        ?>
                            <input type="tel" name="mobileNumber" class="form-control is-invalid">
                        <?php
                        } else { ?>
                            <input type="tel" name="mobileNumber" class="form-control" value="<?= $_SESSION['mobile'] ?>">
                        <?php
                        }
                        ?>
                        <?= (isset($_GET['errMobile']) && isset($_GET['errMobile']) == 'mobile_number_is_already_existed') ? '<small class="text-danger">Mobile Number is already taken!</small>' : ''; ?>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <input type="submit" name="updateInformation" class="btn btn-info" value="Update Information">
                </div>
            </form>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Password Update</h1>
            </div>

            <div class="text-center">
                <?= (isset($_GET['succPass']) && isset($_GET['succPass']) == 'Successfully_updated_password') ? '<span class="text-success">Successfully update password!</span>' : ''; ?>
            </div>

            <form action="action.php" method="post">

                <label>Current Password</label>
                <?php
                if (isset($_GET['errCurrPass']) && isset($_GET['errCurrPass']) == 'Incorrect_current_password') {
                ?>
                    <input type="password" name="currentPassword" class="form-control is-invalid" required>
                <?php
                } else { ?>
                    <input type="password" name="currentPassword" class="form-control" required>
                <?php
                }
                ?>
                <?= (isset($_GET['errCurrPass']) && isset($_GET['errCurrPass']) == 'Incorrect_current_password') ? '<small class="text-danger">Incorrect Current Password!</small>' : ''; ?><br>
                <label>New Password</label>
                <input type="password" name="newPassword" class="form-control" required>


                <label>Confirm New Password</label>
                <?php
                if (isset($_GET['errCurrPass1']) && isset($_GET['errCurrPass1']) == 'Confirm_Password_do_not_match') {
                ?>
                    <input type="password" name="confirmNewPassword" class="form-control is-invalid" required>
                <?php
                } else { ?>
                    <input type="password" name="confirmNewPassword" class="form-control" required>
                <?php
                }
                ?>
                <?= (isset($_GET['errCurrPass1']) && isset($_GET['errCurrPass1']) == 'Confirm_Password_do_not_match') ? '<small class="text-danger">Confirm password do not match!</small>' : ''; ?><br>

                <div class="text-center mt-3">
                    <input type="submit" class="btn btn-info" name="updatePassword" value="Update Password">
                </div>
            </form>

            <div class="mt-4 mb-4">
                <h1 class="Display-4" id="primaryColor">Update Image</h1>
            </div>

            <div class="text-center">
                <?= (isset($_GET['succUpdateImg']) && isset($_GET['succUpdateImg']) == 'Successfully_update_the_img') ? '<span class="text-success">Successfully update profile image!</span>' : ''; ?>
            </div>

            <form action="action.php" method="post" enctype="multipart/form-data">

                <label>Profile Image</label>
                <?php
                if (isset($_GET['errInvalidImg']) || isset($_GET['errImgSize'])) {
                ?>
                    <input type="file" name="profileImg" class="form-control is-invalid" required>
                <?php
                } else { ?>
                    <input type="file" name="profileImg" class="form-control" required>
                <?php
                }
                ?>
                <?= (isset($_GET['errInvalidImg']) && isset($_GET['errInvalidImg']) == 'Invalid_image_only(jpg,jpeg,png)') ? '<small class="text-danger">Invalid image file ONLY(JPEG, JPG, PNG)!</small>' : ''; ?>
                <?= (isset($_GET['errImgSize']) && isset($_GET['errImgSize']) == 'Invalid_image_size_ONLY_less_than_5MB') ? '<small class="text-danger">Invalid image size ONLY less than 5MB!</small>' : ''; ?>

                <div class="text-center mt-3">
                    <input type="submit" name="updateImg" class="btn btn-info" value="Update Image">
                </div>
            </form>

        </div>


        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container text-center">
            <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>