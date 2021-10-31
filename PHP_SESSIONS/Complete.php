<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <?php
    session_start();

    if (!isset($_SESSION["agree"])) {
        header("Location: Disclaimer.php");
        exit();
    }

    if (!isset($_SESSION["customer_info"]) && $_SESSION["customer_info"] != true) {
        header("Location: CustomerInfo.php");
        exit();
    }

    if (!isset($_SESSION["deposit_calc"]) && $_SESSION["deposit_calc"] != true) {
        header("Location: DepositCalculator.php");
        exit();
    }

    $fname = $_SESSION["fname"];
    $phone = $_SESSION["phone"];
    $email = $_SESSION["email"];
    $contact = $_SESSION["contact"];
    $contact_time = $_SESSION["contact_time"];

    include("./common/header.php");
    ?>
    <body>
        <div class="container">
            <?php
            echo
            "<h3> Thank you, $fname , for using our deposit calculator!</h3>";

            if ($contact == "phone") {

                $time_slots;

                $time_slots .= $contact_time[0];

                if (count($contact_time) > 1) {
                    for ($i = 1; $i < count($contact_time); $i++) {
                        $time_slots .= " or " . $contact_time[$i];
                    }
                }

                echo "<div class = 'row'>";
                echo "<div class = 'offset-md-2 col-md-8'>";
                echo "<p> Our customer service will call you tomorrow $time_slots at $phone. </p>";
            } else {
                echo "<div class = 'row'>";
                echo "<div class = 'offset-md-2 col-md-8'>";
                echo "<p> Our customer service will email you tomorrow at $email. </p>";
            }
            echo "</div>";
            echo "</div>";
            session_destroy();
            ?>
        </div>
    </body>

    <?php include('./common/footer.php'); ?>
</html>
