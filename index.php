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
        $value = trim($value, "\"'"); // remove quotes if present
        putenv(sprintf('%s=%s', $name, $value));
    }
    return true;
}

// Load environment variables from .env file
loadEnv(__DIR__ . '/.env');

// Retrieve the database connection details from environment variables
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
    <title>Kodekloud E-Commerce</title>
    <link rel="icon" href="img/favicon.png" type="image/png" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/linearicons/linearicons-1.0.0.css">
    <link rel="stylesheet" href="vendors/wow-js/animate.css">
    <link rel="stylesheet" href="vendors/owl_carousel/owl.carousel.css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<header class="main_header_area">
    <!-- header code unchanged -->
</header>

<section class="best_business_area row">
    <div class="check_tittle wow fadeInUp" data-wow-delay="0.7s" id="product-list">
        <h2>Product List</h2>
    </div>
    <div class="row it_works">
        <?php
        // Attempt to connect to the database
        $link = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

        if ($link) {
            $res = mysqli_query($link, "SELECT * FROM products;");
            while ($row = mysqli_fetch_assoc($res)) { ?>
                <div class="col-md-3 col-sm-6 business_content">
                    <?php echo '<img src="img/' . htmlspecialchars($row['ImageUrl']) . '" alt="">'; ?>
                    <div class="media">
                        <div class="media-body">
                            <a href="#"><?php echo htmlspecialchars($row['Name']); ?></a>
                            <p>Purchase <?php echo htmlspecialchars($row['Name']); ?> at the lowest price <span><?php echo htmlspecialchars($row['Price']); ?>$</span></p>
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

<footer class="footer_area row">
    <div class="container custom-container">
        <div class="copy_right_area">
            <h4 class="copy_right">© Copyright 2019 Kodekloud Ecommerce | All Rights Reserved</h4>
        </div>
    </div>
</footer>

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
