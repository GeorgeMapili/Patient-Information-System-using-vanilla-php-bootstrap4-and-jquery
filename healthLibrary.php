<?php
session_start();

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
    <meta content="Patient Information System" name="description">
    <meta content="sumc doctorcs clinic, patient information system" name="keywords">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="icon" href="img/sumc.png">
    <title>Patient | Health Library</title>
    <style>
        body{
            background-image: linear-gradient(to right, #205072 , #329D9C);
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" style="border-bottom: 2px solid rgb(15, 208, 214);">
            <a class="navbar-brand " i id="primaryColor" href="main.php">SUMC Doctors Clinic</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment.php">Set Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="contactus.php">Contact Us</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link " href="healthLibrary.php">Health Library</a>
                    </li>
                    <div class="btn-group dropbottom">
                        <a href="myappointment.php" class="nav-link">
                            Appointment
                        </a>
                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropright</span>
                        </button>
                        <div class="dropdown-menu bg-dark text-light text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="acceptedAppointment.php">Accepted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="finishedAppointment.php">Finished</a>
                            </li>
                        </div>
                    </div>
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
                            <a class="dropdown-item" href="deleteAcc.php" onclick="return confirm('Are you sure?')">Delete Account</a>
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
                <h1 class="Display-4" id="primaryColor">Health Library</h1>
            </div>
            <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Search Treatment or Diseases..." autocomplete="off" aria-label="Search">
            <div class="list-group" id="data">
            </div>

        </div>
        <div class="container">
            <hr class="featurette-divider">
        </div>

        <h2 class="text-center my-3 text-white">COVID UPDATE</h2>

        <?php

        $activeTotalCases = " ";
        $country = " ";
        $newCases = " ";
        $newDeath = " ";
        $totalCases = " ";
        $totalDeaths = " ";
        $totalRecovered = " ";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://covid-19-tracking.p.rapidapi.com/v1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: covid-19-tracking.p.rapidapi.com",
                "x-rapidapi-key: be2c696d44mshb9b799af2a01320p16607fjsnf0cff2149651"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            if (isset($_POST['search'])) {
                $searchCoutryName = strtolower(trim(htmlspecialchars($_POST['searchCountry'])));

                $datas = json_decode($response);

                $country = $searchCoutryName;

                $data = (array) $datas;

                foreach ($data as $key => $value) {
                    $countryInfo = (array) $datas[$key];
                    // var_dump(strtolower($countryInfo['Country_text']));
                    $countryName = strtolower($countryInfo['Country_text']);
                    // var_dump($countryName);
                    if ($countryName == $country) {
                        $currentData =  (array) $data[$key];
                        break;
                    }
                }
                // var_dump($currentData);
                // die();
                // $data = (array) $datas[$currentData];

                $activeTotalCases = $currentData['Active Cases_text'];
                $country = $currentData['Country_text'];
                $newCases = $currentData['New Cases_text'];
                $newDeath = $currentData['New Deaths_text'];
                $totalCases = $currentData['Total Cases_text'];
                $totalDeaths = $currentData['Total Deaths_text'];
                $totalRecovered = $currentData['Total Recovered_text'];
            }
        }
        ?>


        <div class="container my-5">
            <div class="w-50 m-auto">
                <form action="healthLibrary.php" method="post">
                    <select name="searchCountry" class="form-control" required>
                        <option value="">Select a Country</option>
                        <?php
                        $datas = json_decode($response);
                        // var_dump($datas);
                        // die();
                        foreach ($datas as $key => $value) {
                            $countryInfo = (array) $datas[$key];
                            $countryName = strtolower($countryInfo['Country_text']);
                        ?>
                            <option value="<?= $countryName ?>"><?= ucwords($countryName) ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="text-center my-3">
                        <input class="btn btn-secondary" type="submit" value="Search" name="search">
                    </div>
                </form>
            </div>
        </div>

        <div class="container text-center">
            <h2 class="lead text-white">Covid 19 Information</h2>
        </div>

        <div class="container">
            <div class="row text-center">

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">Country</h5>
                            <p class="card-text"><?php echo $country; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">Total Active Cases</h5>
                            <p class="card-text"><?php echo $activeTotalCases; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">New Cases</h5>
                            <p class="card-text"><?= empty($newCases) ? 'No New Cases': $newCases ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">New Death</h5>
                            <p class="card-text"><?= empty($newDeath) ? 'No New Deaths': $newDeath ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">Total Cases</h5>
                            <p class="card-text"><?php echo $totalCases; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">Total Deaths</h5>
                            <p class="card-text"><?php echo $totalDeaths; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="text-white bg-dark m-3" style="box-shadow: 3px 5px #fff;">
                        <div class="card-body">
                            <h5 class="card-title">Recovered</h5>
                            <p class="card-text"><?php echo $totalRecovered; ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <hr class="featurette-divider">



        <!-- FOOTER -->
        <footer class="container">
            <p class="text-white">&copy; <?= date("Y") ?> SUMC Doctors Clinic &middot; <a href="privacyPolicy.php" id="primaryColor">Privacy Policy</a> &middot; <a href="aboutUs.php" id="primaryColor">About Us</a></p>
        </footer>
    </main>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#search').keyup(function() {
                // Get input value on change
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings("#data");

                if (inputVal.length) {
                    $.get("action.php", {
                        term: inputVal
                    }).done(function(data) {
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else {
                    resultDropdown.empty();
                }
            });
            // Set search input value on click of result item
            $(document).on("click", "#data", function() {
                $(this).parents("#search-box").find('input[type="text"]').val($(this).text());
                $(this).parent("#result").empty();
            });
        });
    </script>
</body>

</html>