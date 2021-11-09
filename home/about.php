<?php
session_start();
if(!$_SESSION["loggedin"]){
    header("Location: ../signup.php");
    exit();
}

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zbot Home</title>
    <meta name="description" content="Login">
    <meta name="author" content="cameron zuziak">
    <link rel="stylesheet" href="scripts/css/styles-home.css">
    <link rel="stylesheet" href="scripts/css/navbar.css">
</head>

<body>
    <div class="topnav">
        <a href="index.php"><b>Home </b></a>
        <a href="accountsettings.php">Settings </a>
        <a class="active" href="about.php">About.</a>
    </div>
    <div class="containerMain">
        <div class="containerContent">
        <span class="loginTitle">What is Zbot?</span>
        <p class="aboutContent">Zbot is an algorithmic cryptocurrency trading platform. Utilizing Binance's API, 
        Zbot is a plug and play systems that allows users to access the power of automated technical analysis and trading. 
        Currently, Zbot allows for automated trading of a select few cryptocurrencies, based off RSI indicators. The platform
        calulates RSI with a period of 14, and candle stick length of 1 minute. Zbot allows users to enter the RSI Thresholds
        in which they would like to enter or exit a position. </p>
        </div>     
    </div>
</body>
</html>