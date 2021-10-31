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
    include("./common/header.php");
    ?>
    <body>
        <div class="container">
            <h3>Welcome to Algonquin Bank</h3>
            <p>Algonquin bank is Algonquin College students most loved bank. We provide a set of tools for Algonquin College students to manage
                their finances.</p>
            <a href="Disclaimer.php">Deposit Calculator</a>
        </div>
    </body>

    <?php include('./common/footer.php'); ?>
</html>
