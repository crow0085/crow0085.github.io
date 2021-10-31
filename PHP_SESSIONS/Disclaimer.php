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

//    if (isset($_SESSION["agree"])) {
//        header("Location: CustomerInfo.php");
//        exit();
//    }

    $errormessage = "";

    if (isset($_POST["start"])) {

        if (!isset($_POST["agree"])) {
            $errormessage = "You must agree to the terms and services!";
        } else {
            $_SESSION["agree"] = true;
            header("Location: CustomerInfo.php");
        }
    }

    include("./common/header.php");
    ?>
    <body>
        <div class="container">
            <h3>Terms and Conditions</h3>
            <form action="Disclaimer.php" method="POST">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quam elementum pulvinar etiam non quam lacus suspendisse faucibus.
                        </td>
                    </tr>
                    <TR>
                        <td>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Velit dignissim sodales ut eu sem integer vitae justo. Felis imperdiet proin fermentum leo.
                        </td>
                    </TR>
                    <tr>
                        <td>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Id consectetur purus ut faucibus pulvinar elementum integer.
                        </td>
                    </tr>
                </table>
                <div class="row form-group">

                    <div class="col-md-6 checkbox">
                        <?php
                        if ($errormessage != "") {
                            echo "<p class='alert alert-danger'> $errormessage </p>";
                        }
                        ?>
                        <label><input type="checkbox" name="agree" value="">I have read and agree to the terms and conditions</label>
                    </div>
                </div>
                <br/>
                <div class="row form-group ">
                    <div class="col-md-2">
                        <input class="btn btn-primary" type="submit" name="start" value="start" />
                    </div>
                </div>
            </form>            
        </div>
    </body>

    <?php include('./common/footer.php'); ?>
</html>

