<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/admin.css" />
    <title>Admin | Dashboard</title>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php" id="primaryColor">Company name</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="dashboard.php">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="patient.php">
                                <span data-feather="file"></span>
                                View All Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doctor.php">
                                <span data-feather="shopping-cart"></span>
                                View All Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="room.php">
                                <span data-feather="users"></span>
                                View All Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nurse.php" id="primaryColor">
                                <span data-feather="users"></span>
                                View All Nurse Receptionist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="doneAppointment.php">
                                <span data-feather="users"></span>
                                View Done Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cancelledAppointment.php">
                                <span data-feather="users"></span>
                                View Cancelled Appointment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dischargedPatient.php">
                                <span data-feather="users"></span>
                                View Discharged Patients
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">All Nurse Receptionist</h1>
                </div>
                <div>
                    <form class="form-inline">
                        <input class="form-control mb-3" type="search" placeholder="Search Nurse Receptionist" aria-label="Search">
                    </form>
                </div>
                <table class="table table-hover ">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nurse ID</th>
                            <th scope="col">Nurse Profile Img</th>
                            <th scope="col">Nurse Name</th>
                            <th scope="col">Nurse Email</th>
                            <th scope="col">Nurse Address</th>
                            <th scope="col">Nurse Mobile</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td><img src="../upload/nurse_profile_img/nurse1.jpg" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                            <td>Qwer</td>
                            <td>qwerty@gmail.com</td>
                            <td>12345 St.</td>
                            <td>09510195578</td>
                            <td>
                                <input type="submit" value="Update" class="btn btn-secondary" name="appointmentStatus" onclick="return confirm('Are you sure to update?');">
                                |
                                <input type="submit" value="Delete" class="btn btn-danger" name="appointmentStatus" onclick="return confirm('Are you sure to delete?');">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">1</th>
                            <td><img src="../upload/nurse_profile_img/nurse2.jpg" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                            <td>Qwer</td>
                            <td>qwerty@gmail.com</td>
                            <td>12345 St.</td>
                            <td>09510195578</td>
                            <td>
                                <input type="submit" value="Update" class="btn btn-secondary" name="appointmentStatus" onclick="return confirm('Are you sure to update?');">
                                |
                                <input type="submit" value="Delete" class="btn btn-danger" name="appointmentStatus" onclick="return confirm('Are you sure to delete?');">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">1</th>
                            <td><img src="../upload/nurse_profile_img/nurse3.jpg" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                            <td>Qwer</td>
                            <td>qwerty@gmail.com</td>
                            <td>12345 St.</td>
                            <td>09510195578</td>
                            <td>
                                <input type="submit" value="Update" class="btn btn-secondary" name="appointmentStatus" onclick="return confirm('Are you sure to update?');">
                                |
                                <input type="submit" value="Delete" class="btn btn-danger" name="appointmentStatus" onclick="return confirm('Are you sure to delete?');">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">1</th>
                            <td><img src="../upload/nurse_profile_img/nurse4.jpg" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                            <td>Qwer</td>
                            <td>qwerty@gmail.com</td>
                            <td>12345 St.</td>
                            <td>09510195578</td>
                            <td>
                                <input type="submit" value="Update" class="btn btn-secondary" name="appointmentStatus" onclick="return confirm('Are you sure to update?');">
                                |
                                <input type="submit" value="Delete" class="btn btn-danger" name="appointmentStatus" onclick="return confirm('Are you sure to delete?');">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td><img src="../upload/nurse_profile_img/nurse5.jpg" width="50" style="border:1px solid #333; border-radius: 50%;" alt=""></td>
                            <td>Qwer</td>
                            <td>qwerty@gmail.com</td>
                            <td>12345 St.</td>
                            <td>09510195578</td>
                            <td>
                                <input type="submit" value="Update" class="btn btn-secondary" name="appointmentStatus" onclick="return confirm('Are you sure to update?');">
                                |
                                <input type="submit" value="Delete" class="btn btn-danger" name="appointmentStatus" onclick="return confirm('Are you sure to delete?');">
                            </td>
                        </tr>


                    </tbody>
                </table>
                <div>
                    <div>
                        <a href="addNurse.php" class="btn btn-success mt-3 ">Add Nurse</a>
                    </div>
                </div>

        </div>


        </main>
    </div>
    </div>



    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>