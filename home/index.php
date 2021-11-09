<?php
    session_start();
    
    if(!$_SESSION["loggedin"]){
        header("Location: ../signup.php");
        exit();
    }

    $a=(int)$_SESSION["Running"];
    if($a===1){
        header("Location: deployed.php");
        exit();
    }


    //$id=$_SESSION["ID"];
    $host="localhost";
    $user="root";
    $password="mysql";
    $db="demo";
    $con=mysqli_connect($host,$user,$password, $db);
    $sql="select * from loginform where User='".$_SESSION["usersname"]."' limit 1";
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($result);
    $id=$row['ID'];


    if(isset($_POST['RSIsell'])){
        $rsi_buy = $_POST['RSIbuy'];
        $rsi_sell = $_POST['RSIsell'];
        $in_pos = $_POST['in_pos'];
        $coin = $_POST['coinpairing'];

        $sql="update loginform SET RSIsell='".$rsi_sell."', RSIbuy='".$rsi_buy."', InPos='".$in_pos."', Coin='".$coin."', Running='1' where ID='".$id."'";
        $result=mysqli_query($con,$sql);
        echo mysqli_error($con);
        if($result){
            $command_bot = "python ./scripts/python/botMergeTest.py $id";
            pclose(popen("start /B " . $command_bot . " 1> temp/update_log 2>&1 &", "r"));
            mysqli_close($con);
            $_SESSION["Running"]=1;
            header("Location: deployed.php");
            exit();
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
            <span class="loginTitle">Current Positions</span>
            <div containerTable>
                <?php
                    $command = escapeshellcmd("python ./scripts/python/accounthandler.py $id");
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
        <span class="overallTitle" style="padding-top: 30px;"> Deploy Bot </span>
        <div class="containerContent">
            <form class="coscForm" method="POST" action=#>
                <span class="loginTitle">Enter Trade Information</span>
                <div class="inputContainer"></divclass>
                    <select class="inputField" name="coinpairing" placeholder="Coin Pairing">
                        <option value="">Select a coin pairing</option>
                        <option value="DOGE/USDT">DOGE/USDT</option>
                        <option value="BTC/USDT">BTC/USDT</option>
                        <option value="ETH/USDT">ETH/USDT</option>
                        <option value="ADA/USDT">ADA/USDT</option>
                        <option value="XRP/USDT">XRP/USDT</option>
                        <option value="BNB/USDT">BNB/USDT</option>
                        <option value="HOT/USDT">HOT/USDT</option>
                    </select>
                </div>
                </br>

                <div class="inputContainer"></divclass>
                    <input class="inputField" type="number" name="RSIbuy" placeholder="Enter RSI Buy Threshold">
                </div>
                </br>

                <div class="inputContainer"></divclass>
                    <input class="inputField" type="number" name="RSIsell" placeholder="Enter RSI Sell Threshold">
                </div>
                </br>

                <div class="inputContainer"></divclass>
                    <select class="inputField" name="in_pos">
                        <option value="">In Position?</option>
                        <option value="1">True</option>
                        <option value="0">False</option>
                    </select>
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
</body>
</html>