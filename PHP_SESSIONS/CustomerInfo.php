<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.6/dist/css/bootstrap.min.css">
        <title></title>
    </head>
    <?php
    session_start();

    function ValidateName($fname) {
        if ($fname == "") {
            return "name cannot be blank";
        } else {
            return "";
        }
    }

    function ValidatePostalCode($postcode) {
        if (!preg_match("/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/", $postcode)) {
            return "postal code cannot be blank and must follow XnX nXn";
        } else {
            return "";
        }
    }

    function ValidatePhone($phone) {
        if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) {
            return "phone number cannot be blank and must be in the format ###-###-####";
        } else {
            return "";
        }
    }

    function ValidateEmail($email) {

        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
        if (!preg_match($regex, $email)) {
            return "email address cannot be blank and must be a valid email";
        } else {
            return "";
        }
    }

    function ValidateContactTime($contact, $contact_time) {

        if ($contact == "phone" && count($contact_time) == 0) {
            return "when contact method is phone, you must select at least one available time";
        } else {
            return "";
        }
    }

    if (!isset($_SESSION["agree"])) {
        header("Location: Disclaimer.php");
        exit();
    }

    $fname = $_SESSION["fname"];
    $postcode = $_SESSION["postcode"];
    $phone = $_SESSION["phone"];
    $email = $_SESSION["email"];
    $contact = $_SESSION["contact"];
    $contact_time = $_SESSION["contact_time"];

    $fname_error_message;
    $postcode_error_message;
    $phone_error_message;
    $email_error_message;
    $contact_time_error_message;



    if (isset($_POST["next"])) {
        $fname = $_POST["fname"];
        $postcode = $_POST["postcode"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $contact = $_POST["contact"];
        $contact_time = $_POST["contact_time"];

        $_SESSION["fname"] = $fname;
        $_SESSION["postcode"] = $postcode;
        $_SESSION["phone"] = $phone;
        $_SESSION["email"] = $email;
        $_SESSION["contact"] = $contact;
        $_SESSION["contact_time"] = $contact_time;

        $fname_error_message = ValidateName($fname);
        $postcode_error_message = ValidatePostalCode($postcode);
        $phone_error_message = ValidatePhone($phone);
        $email_error_message = ValidateEmail($email);
        $contact_time_error_message = ValidateContactTime($contact, $contact_time);

        $valid = true;

        if ($fname_error_message != "" || $postcode_error_message != "" || $phone_error_message != "" || $email_error_message != "" || $contact_time_error_message != "") {
            $valid = false;
        }
        
        $_SESSION["customer_info"] = $valid;
        
        echo '<script>' . 'console.log(' . "'Valid:" . " $valid'" . ')' . '</script>';

        if ($valid) {
            header("Location: DepositCalculator.php");
            exit();
        }
    }



    include("./common/header.php");
    ?>

    <body>
        <div class="container">

            <h3>
                Customer Information
            </h3>
            <hr>

            <form action="CustomerInfo.php" method="POST">
                <div class="row form-group">
                    <div class="col-md-2">
                        <label class="font-weight-bold">Name:</label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" name="fname" type="text" value="<?php echo "$fname"; ?>" />
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($fname_error_message != "") {
                            echo "<p class='text-danger'>$fname_error_message</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-2">
                        <label class="font-weight-bold">Postal code:</label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" name="postcode" type="text" value="<?php echo "$postcode"; ?>" />
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($postcode_error_message != "") {
                            echo "<p class='text-danger'>$postcode_error_message</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-2">
                        <label class="font-weight-bold">Phone Number: <br/> (###-###-####)</label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" name="phone" type="text" value="<?php echo "$phone"; ?>" />
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($phone_error_message != "") {
                            echo "<p class='text-danger'>$phone_error_message</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-2">
                        <label class="font-weight-bold">Email:</label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" name="email" type="text" value="<?php echo "$email"; ?>" />
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($email_error_message != "") {
                            echo "<p class='text-danger'>$email_error_message</p>";
                        }
                        ?>
                    </div>
                </div>

                <hr>

                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Preferred contact method:</label>
                    </div>
                    <div class="col-md-2">
                        <input type="radio" name="contact" <?php
                        if ($contact != "phone") {
                            echo "checked";
                        }
                        ?> value="email" /> Email 
                        &nbsp &nbsp
                        <input type="radio" name="contact" <?php
                        if ($contact == "phone") {
                            echo "checked";
                        }
                        ?> value="phone" /> Phone
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="font-weight-bold">If phone is selected, when can we contact you?:</label>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <input type="checkbox" name="contact_time[ ]" 
                        <?php
                        foreach ($contact_time as $ct) {
                            if ($ct == "morning") {
                                echo "checked";
                            }
                        }
                        ?>
                               value="morning" /> morning 
                        &nbsp &nbsp
                        <input type="checkbox" name="contact_time[ ]" 
                        <?php
                        foreach ($contact_time as $ct) {
                            if ($ct == "afternoon") {
                                echo "checked";
                            }
                        }
                        ?> value="afternoon" /> afternoon
                        &nbsp &nbsp
                        <input type="checkbox" name="contact_time[ ]"
                        <?php
                        foreach ($contact_time as $ct) {
                            if ($ct == "evening") {
                                echo "checked";
                            }
                        }
                        ?> value="evening" /> evening
                    </div>
                    <div class="col-md-5">
                        <?php
                        if ($contact_time_error_message != "") {
                            echo "<p class='text-danger'>$contact_time_error_message</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-2">
                        <input class="btn btn-primary" type="submit" name="next" value="next >" />
                    </div>
                </div>
            </form>

        </div>
    </body>

    <?php include('./common/footer.php'); ?>
</html>