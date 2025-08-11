<?php
// Function to load environment variables from a .env file
function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, "\"'");
        putenv(sprintf('%s=%s', $name, $value));
    }
    return true;
}

// Load environment variables
loadEnv(__DIR__ . '/.env');

// Retrieve DB connection details
$dbHost = getenv('MYSQL_HOST');
$dbUser = getenv('MYSQL_USER');
$dbPassword = getenv('MYSQL_PASSWORD');
$dbName = getenv('MYSQL_DATABASE');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SapSecOps E-Commerce</title>
    <link rel="icon" href="img/favicon.png" type="image/png" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/linearicons/linearicons-1.0.0.css">
    <link rel="stylesheet" href="vendors/wow-js/animate.css">
    <link rel="stylesheet" href="vendors/owl_carousel/owl.carousel.css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!--==========Main Header==========-->
<header class="main_header_area">
    <nav class="navbar navbar-default navbar-fixed-top" id="main_navbar">
        <div class="container-fluid searchForm">
            <form action="#" class="row">
                <div class="input-group">
                    <span class="input-group-addon"><i class="lnr lnr-magnifier"></i></span>
                    <input type="search" name="search" class="form-control" placeholder="Type & Hit Enter">
                    <span class="input-group-addon form_hide"><i class="lnr lnr-cross"></i></span>
                </div>
            </form>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-2 p0">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.html">
                            <img src="img/SSO.png" alt="">
                            <img src="img/SSO.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <div class="col-md-9 p0">
                        <ul class="nav navbar-nav main_nav">
                            <li><a href="#">Laptops</a></li>
                            <li><a href="#">Drones</a></li>
                            <li><a href="#">Gadgets</a></li>
                            <li><a href="#">Phones</a></li>
                            <li><a href="#">VR</a></li>
                            <li><a href="#">Contact us</a></li>
                        </ul>
                    </div>
                    <div class="col-md-1 p0">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#" class="nav_searchFrom"><i class="lnr lnr-magnifier"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!--==========End Main Header==========-->

<!--==========Slider area==========-->
<section class="slider_area row m0">
    <div class="slider_inner">
        <div class="camera_caption">
            <h2 class="wow fadeInUp animated">Make Your Shopping Easy</h2>
            <h5 class="wow fadeIn animated" data-wow-delay="0.3s">Find everything accordingly</h5>
            <a class="learn_mor wow fadeInU" data-wow-delay="0.6s" href="#product-list">Show Now!</a>
        </div>
    </div>
</section>
<!--==========End Slider area==========-->

<!--==========Product List==========-->
<section class="best_business_area row">
    <div class="check_tittle wow fadeInUp" data-wow-delay="0.7s" id="product-list">
        <h2>Product List</h2>
    </div>
    <div class="row it_works">
        <?php
        $link = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

        if ($link) {
            $res = mysqli_query($link, "SELECT * FROM products;");
            while ($row = mysqli_fetch_assoc($res)) { ?>
                <div class="col-md-3 col-sm-6 business_content">
                    <img src="img/<?php echo htmlspecialchars($row['ImageUrl']); ?>" alt="">
                    <div class="media">
                        <div class="media-body">
                            <a href="#"><?php echo htmlspecialchars($row['Name']); ?></a>
                            <p>Purchase <?php echo htmlspecialchars($row['Name']); ?> at the lowest price 
                               <span><?php echo htmlspecialchars($row['Price']); ?>$</span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div style="width: 100%">
                <div class="error-content">
                    <h1>Database connection error</h1>
                    <p><?php echo mysqli_connect_errno() . ": " . mysqli_connect_error(); ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<!--==========Footer==========-->
<footer class="footer_area row">
    <div class="container custom-container">
        <div class="copy_right_area">
            <h4 class="copy_right">© Copyright 2019 SapSecOps Ecommerce | All Rights Reserved</h4>
        </div>
    </div>
</footer>

<!--==========Scripts==========-->
<script src="js/jquery-1.12.4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="vendors/wow-js/wow.min.js"></script>
<script src="vendors/Counter-Up/waypoints.min.js"></script>
<script src="vendors/Counter-Up/jquery.counterup.min.js"></script>
<script src="vendors/stellar/jquery.stellar.js"></script>
<script src="vendors/owl_carousel/owl.carousel.min.js"></script>
<script src="js/theme.js"></script>

</body>
</html>
