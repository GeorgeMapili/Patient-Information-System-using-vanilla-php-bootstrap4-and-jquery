<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css" />
    <title>Nurse | Medical Information</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand " i id="primaryColor" href="dashboard.php">Company Name</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="patient.php">Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room.php">Room</a>
                    </li>
                </ul>
                <!-- search bar -->
                <!-- <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form> -->
                <ul class="navbar-nav ml-auto">
                    <img src="../upload/doc_profile_img/doc1.jpg" width="50" style="border:1px solid #fff; border-radius: 50%;" alt="">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Nurse Receptionist
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item disabled" href="">qwerty@gmail.com</a>
                            <a class="dropdown-item" href="nurseProfile.php">My account</a>
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

            <h3 class="display-4 mt-5 my-4" id="primaryColor">Patient Bill</h3>

            <div class="container">
                <div class="row justify-content-center bg-light">
                    <div class="col-lg-6 px-4 pb-4" id="order">
                        <form action="generateBill.php" method="post" id="placeOrder">
                            <input type="hidden" name="orderedfood" value="123">
                            <input type="hidden" name="orderedtotalamount" value="123">
                            <input type="hidden" name="userId" value="123">
                            <h1 class=" text-center mt-3">Patient information</h1>

                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" value="Qwerty" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" value="qwerty@gmail.com" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" name="address" value="12345 St." class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="tel" name="mobilenumber" class="form-control" value="095502135723" readonly>
                            </div>

                            <div class="form-group">
                                <label for="">Patient Status</label>
                                <select name="" id="" class="form-control">
                                    <option value="0">Select</option>
                                    <option value="well">Died</option>
                                    <option value="well">Awful</option>
                                    <option value="better">Good</option>
                                    <option value="died">Getting better</option>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Room Number</label>
                                    <input type="text" name="address" value="102" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Room Fee</label>
                                    <input type="text" name="mobilenumber" class="form-control" value="2000.00" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Name</label>
                                    <input type="text" name="address" value="Dr. Qwerty" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Fee</label>
                                    <input type="text" name="mobilenumber" class="form-control" value="5000.00" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Doctor Prescribe Medicine</label>
                                    <textarea name="prescribeMed" class="form-control" cols="30" rows="10" readonly>Paracetamol</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Medicine Fee</label>
                                    <input type="number" name="mobilenumber" min="1" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">Amount Input</label>
                                    <input type="number" name="address" min="1" value="" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Total Amount</label>
                                    <input type="text" name="mobilenumber" class="form-control" value="10000.00" readonly>
                                </div>
                            </div>


                            <div class="col">
                                <div class="form-group">
                                    <input type="submit" name="placeorder" class="btn btn-primary" value="Discharge">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

            <!-- FOOTER -->
            <footer class="text-center">
                <p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
            </footer>
        </div>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>