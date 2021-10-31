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


    if (!isset($_SESSION["agree"])) {
        header("Location: Disclaimer.php");
        exit();
    }

    if (!isset($_SESSION["customer_info"]) && $_SESSION["customer_info"] != true) {
        header("Location: CustomerInfo.php");
        exit();
    }

    $cInfo = $_SESSION["customer_info"];

    function ValidatePrincipal($amount) {
        if (!is_numeric($amount) || $amount <= 0) {
            return "principal amount must be a number and must be above 0";
        } else {
            return "";
        }
    }

    function ValidateRate($amount) {
        if (!is_numeric($amount) || $amount <= 0) {
            return "interest rate must be a number and must be above 0";
        } else {
            return "";
        }
    }

    $principalAmount = $_SESSION["principalAmount"];
    $interestRate = $_SESSION["interestRate"];
    $yearsToDeposit = $_SESSION["yearsToDeposit"];


    if (isset($_POST["calculate"])) {
        $principalAmount = $_POST["principalAmount"];
        $interestRate = $_POST["interestRate"];
        $yearsToDeposit = $_POST["yearsToDeposit"];

        $_SESSION["principalAmount"] = $principalAmount;
        $_SESSION["interestRate"] = $interestRate;
        $_SESSION["yearsToDeposit"] = $yearsToDeposit;

        $principal_amount_error = ValidatePrincipal($principalAmount);
        $interest_rate_error = ValidateRate($interestRate);

        $valid = true;

        if ($principal_amount_error != "" || $interest_rate_error != "") {
            $valid = false;
        }

        echo '<script>' . 'console.log(' . "'Valid:" . " $valid'" . ')' . '</script>';

        $_SESSION["deposit_calc"] = $valid;
    }
    
    if(isset($_POST["previous"])){
        header("Location: CustomerInfo.php");
        exit();
    }
    if(isset($_POST["complete"]) && $_SESSION["deposit_calc"]){
        header("Location: Complete.php");
        exit();
    }


    include("./common/header.php");
    ?>

    <body>
        <div class="container">

            <h3>
                Enter Principal amount, interest rate, and select number of years to deposit. <br><br>
            </h3>

            <form action="DepositCalculator.php" method="POST">
                <div class="row form-group mt-3">
                    <div class="offset-md-3 col-md-2">
                        <label class="text-left" >Principal Amount:</label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" value="<?php echo "$principalAmount"; ?>" name="principalAmount" />
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($principal_amount_error != "") {
                            echo "<p class='text-danger'>$principal_amount_error</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row form-group mt-3">
                    <div class="offset-md-3 col-md-2">
                        <label class="text-left" >Interest rate (%):</label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" value="<?php
                        $_interestRate = $interestRate * 100;
                        echo "$interestRate";
                        ?>" name="interestRate"  />
                    </div>
                    <div class="col-md-4">
                        <?php
                        if ($interest_rate_error != "") {
                            echo "<p class='text-danger'>$interest_rate_error</p>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row form-group mt-3">
                    <div class="offset-md-3 col-md-2">
                        <label class="text-left" >Years to deposit:</label>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name = "yearsToDeposit" >
                            <?php
//                            echo "<option hidden value = '' selected > -- Select -- </option>";
                            for ($i = 1; $i <= 20; $i++) {
                                if ($yearsToDeposit == $i) {
                                    echo "<option value = '$i' selected > $i </option>";
                                } else {
                                    echo "<option value = '$i' > $i </option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-1">
                        <input class="btn btn-primary" type="submit" name="previous" value="previous" />
                    </div>
                    <div class="col-md-1">
                        <input class="btn btn-primary" type="submit" name="calculate" value="calculate" />
                    </div>
                    <div class="col-md-1">
                        <input class="btn btn-primary" type="submit" name="complete" value="complete" />
                    </div>
                </div>

            </form>

            <?php
            if ($valid) {
                $year = 1;
                $interest_for_year = 0;
                $principal_at_year_start = 0;
                $interestRate = ($interestRate / 100);
                echo "<p> the following is the result of the calculation: </p>";
                echo "<table class = 'table table-bordered'>";
                echo "<tr>";
                echo "<th> Year </th>";
                echo "<th> Principal at year start </th>";
                echo "<th> Interest for the year </th>";
                echo "</tr>";

                for ($i = 0; $i < $yearsToDeposit; $i++) {
                    if ($i == 0) {
                        $interest_for_year = ($interestRate * $principalAmount);
                        $principal_at_year_start = $principalAmount;
                    } else {
                        $principal_at_year_start += $interest_for_year;
                        $interest_for_year = ($interestRate * $principal_at_year_start);
                    }
                    $principal_at_year_start = number_format($principal_at_year_start, 2, '.', '');
                    $interest_for_year = number_format($interest_for_year, 2, '.', '');
                    echo "<tr>";
                    echo "<td> $year </td>";
                    echo "<td> $principal_at_year_start </td>";
                    echo "<td> $interest_for_year </td>";
                    echo "</tr>";
                    $year++;
                }
                echo "</table>";
                echo "</div>";
                echo "</div>";
            }
            ?>

        </div>
    </body>

    <?php include('./common/footer.php'); ?>
</html>
