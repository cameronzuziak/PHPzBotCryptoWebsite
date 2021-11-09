<?php
    session_start();
    if(!$_SESSION["loggedin"]){
        header("Location: ../signup.php");
        exit();
    }



    $id=$_SESSION["ID"];
    $host="localhost";
    $user="root";
    $password="mysql";
    $db="demo";
    $con=mysqli_connect($host,$user,$password, $db);
    $sql="select * from loginform where ID='".$id."' limit 1";
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($result);
    $coin=$row['Coin'];
    $_SESSION["Running"]=$row['Running'];
    $passhash=$row['Pass'];
    $a=(int)$_SESSION["Running"];
    if($a===0){
        header("Location: index.php");
        exit();
    }


    if(isset($_POST["pass"])){
        if(password_verify(strval($_POST["pass"]), strval($passhash))){
            $sql="update loginform SET Running='0' where ID='".$id."'";
            $result=mysqli_query($con,$sql);
            echo mysqli_error($con); 
            if($result){
                $_SESSION["Running"]=0;
                mysqli_close($con);
                header("Location: index.php");
                exit();
            } 
        }
        else{
            print "Wrong Password";
        }
        
    }

    if(isset($_POST['RSISell'])){
        $rsi_buy = $_POST['RSIBuy'];
        $rsi_sell = $_POST['RSISell'];
        $sql="update loginform SET RSIsell='".$rsi_sell."', RSIbuy='".$rsi_buy."' where ID='".$id."'";
        $result=mysqli_query($con,$sql);
        echo mysqli_error($con);
        if($result){
            print "Success";
        }
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
    <script src="home/scripts/js/jquery/jquery.js"></script>
</head>

<body>
    <div class="topnav">
        <a class="active" href="#">Home.</a>
        <a href="accountsettings.php">Settings </a>
        <a href="about.php">About </a>
    </div>
    <div class="containerMain">
        <span class="overallTitle">Welcome <?php echo $_SESSION["usersname"]; ?></span>
        <div class="containerContent" >
            <span class="loginTitle">Currently Trading <?php echo $coin; ?> . Posistions: </span>
            <div>
                <?php
                    $command = escapeshellcmd("python scripts/python/accounthandler.py $id");
                    $coins = shell_exec($command);
                    $my_arr = (array)json_decode($coins, true);
                    $_SESSION["my_arr"]=$my_arr;
                    print"<table width='100'>";
                    foreach($my_arr as $item){
                        $crypto=$item['asset'];
                        $free=$item['free'];
                        print"<tr><td>{$crypto}:</td>";
                        print"<td>{$free}</td></tr>";
                    }//end while loop
                    print"</table>";
                ?>
            </div>   
        </div>
        </br>
        <span class="overallTitle" style="padding-top: 30px;"> Bot Deployed </span>
        <div class="containerContent">
            <form class="coscForm" method="POST" action="#">
                <span class="loginTitle">Edit RSI Thresholds</span>
                <div class="inputContainer"></divclass>
                    <input class="inputField" type="number" name="RSIBuy" placeholder="Enter RSI Buy Threshold">
                </div>
                </br>

                <div class="inputContainer"></divclass>
                    <input class="inputField" type="number" name="RSISell" placeholder="Enter RSI Sell Threshold">
                </div>
                </br>

                <div class="containerBtn">
                    <input type="submit" name="submit" class="loginBtn" value="To the moon!">
                </div>
            </form>

            <div class="Pic"> 
                <img src="scripts/css/images/img-01.png" alt="IMG">
            </div>  
        </div>

        </br>
        <span class="overallTitle" style="padding-top: 30px;"> Kill Bot </span>
        <div class="containerContent">
            <form class="coscForm" method="POST" action="#">
                <span class="loginTitle">Enter Password</span>
                <div class="inputContainer"></divclass>
                    <input class="inputField" type="password" name="pass" placeholder="Enter Your Password">
                </div>
                </br>

                <div class="containerBtn">
                    <input type="submit" name="submit" class="loginBtn" value="Kill Bot!">
                </div>
            </form>

        </div>
        <br>
        </br>
                  
    </div>
</body>
</html>