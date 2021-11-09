<?php
    session_start();

    if(!$_SESSION["loggedin"]){
        header("Location: ../signup.php");
        exit();
    }

    $id=$_SESSION["ID"];
    $title="User Login";
    $host="localhost";
    $user="root";
    $password="mysql";
    $db="demo";
    $con=mysqli_connect($host,$user,$password,$db);
    $message2='';

    if(mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // get data from api key form
    if(isset($_POST['Seckey'])){
        $seckey=$_POST['Seckey'];
        $command = escapeshellcmd("python scripts/python/php_encryptionhandler.py $seckey 1");
        $seckey = exec($command);
        $apikey=$_POST['Apikey'];
        $command = escapeshellcmd("python scripts/python/php_encryptionhandler.py $apikey 1");
        $apikey = exec($command);
        if($_POST['pass'] !== $_SESSION["pass"]){$message2="Wrong Password";}
        else{
            $sql="update loginform SET Apikey='".$apikey."', Seckey='".$seckey."' where ID='".$id."'";
            $result=mysqli_query($con,$sql);
            echo mysqli_error($con);
            if($result){
                $message2="API Keys have been set!";
            }
        }
    }

    // get data from password reset form
    if(isset($_POST['confnewpass'])){
        $passin=$_POST['pass'];
        $newpass=$_POST['newpass'];
        $confnewpass=$_POST['confnewpass'];
        if((strpos($passin, 'mysql') !== false) 
        || (strpos($newpass, 'mysql') !== false) || (strpos($confnewpass, 'mysql') !== false)) {
            echo "SQL injections prohibited";
        }
        elseif($passin !== $_SESSION["pass"]){
            $message2="Current password was wrong";
        }

        else{
            $passhash=password_hash($newpass, PASSWORD_DEFAULT);
            //echo $passhash;
            $sql2="update loginform SET Pass='".$passhash."' where ID='".$id."'";
            $result2=mysqli_query($con,$sql2);
            //echo mysqli_error($con);
            if ($result2) {
                echo mysqli_error($con);
                $_SESSION["pass"]=$newpass;
                $message2='Password Has been Updated!';
                mysqli_close($con);
                #header("Location: /home");
                #exit();
            }
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zbot Settings</title>
        <meta name="description" content="Login">
        <meta name="author" content="cameron zuziak">
        <link rel="stylesheet" href="scripts/css/styles-settings.css">
        <link rel="stylesheet" href="scripts/css/navbar.css">
        <script> 
            subFrm1 = function(){document.getElementById("frm1").submit();}
            subFrm2 = function(){document.getElementById("frm2").submit();
        } </script>
    </head>
    
    <body> 
        <div style="font-family:helvetica, padding-top: 10px;"><p><?php echo $message2;?></p></div>
        <div class="topnav">
            <a href="index.php"><b>Home </b></a>
            <a class="active" href="accountsettings.php">Settings.</a>
            <a href="about.php">About </a>
        </div>

        <div class="containerMain">
            <span class="overallTitle">Binance API Keys</span>
            <div class="containerContent" style="align-content: center;">
                <form class="forms" id="frm1" method="POST" action=#>
                    <span class="loginTitle">Enter Your API Key</span>
                    <div class="inputContainer">
                        <input class="inputField" type="password" name="Apikey" placeholder="API Key" required>
                    </div>
                    </br>
                    <span class="loginTitle">Enter your secret key</span>
                    <div class="inputContainer"></divclass>
                        <input class="inputField" type="password" name="Seckey" placeholder="Secret Key" required>
                    </div>
                    </br>
                    <span class="loginTitle">Enter Your Password</span>
                    <div class="inputContainer"></divclass>
                        <input class="inputField" type="password" name="pass" placeholder="Password" required>
                    </div>
                    </br>
                    <div class="containerBtn">
                        <input type="submit" name="btn1" class="loginBtn" value="SET KEYS">
                    </div>
                </form>
            </div>
            </br>
            
            <span class="overallTitle" style="padding-top: 30px;">Change Password</span>
            <div class="containerContent" style="align-content: center;">
                <form class="forms" method="POST" action=#>
                    <span class="loginTitle">Enter your Current Password</span>
                    <div class="inputContainer"></divclass>
                        <input class="inputField" type="password" name="pass" placeholder="Current Password" required>
                    </div>
                    </br>
                    <span class="loginTitle">Enter Your New Password</span>
                    <div class="inputContainer"></divclass>
                        <input class="inputField" type="password" name="newpass" placeholder="New Password" required>
                    </div>
                    </br>

                    <span class="loginTitle">Confirm your New Password</span>
                    <div class="inputContainer"></divclass>
                        <input class="inputField" type="password" name="confnewpass" placeholder="Confirm New Password" required>
                    </div>
                    </br>
                    <div class="containerBtn">
                        <input type="submit" name="btn2" class="loginBtn" value="SET PASSWORD">
                    </div>
                </form>
                </br>
                
            </div>
        </div>
    </br>
    </br>
    </body>
</html>