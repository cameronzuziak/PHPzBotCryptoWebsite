<?php

    session_start();
    $host="localhost";
    $user="root";
    $password="mysql";
    $db="demo";
    $con=mysqli_connect($host,$user,$password, $db);
    $title="Sign Up!";

    if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if(isset($_POST['username'])){
        $uname=$_POST['username'];
        $password=$_POST['pass'];
        $confpass=$_POST['confpass'];
        $phone=$_POST['phonenumber'];
        $command = escapeshellcmd("python scripts/python/validatePhone.py .$phone");
        $phonetype = shell_exec($command);
        echo $phonetype;
        if((strpos($uname, 'mysql') !== false) || (strpos($password, 'mysql') !== false) 
        || (strpos($password, 'mysql') !== false) || (strpos($confpass, 'mysql') !== false)) {
            echo "SQL injections prohibited";
        }
        elseif($confpass !== $password){
            $title="Passwords don't match";
        }
        else{
            $sql="select * from loginform where User='".$uname."' limit 1";
            $result=mysqli_query($con,$sql);
            if(mysqli_num_rows($result)==1){
                $title="Username In Use";
            }
            elseif($result == 1){
                $passhash=password_hash($password, PASSWORD_DEFAULT);
                echo $passhash;
                $sql2="insert into loginform(`User`, `Pass`, `Phone`) values ('".$uname."','".$passhash."','".$phone."')";
                $result2=mysqli_query($con,$sql2);
                echo mysqli_error($con);
                if ($result2) {
                    echo "New record created successfully";
                    $_SESSION["loggedin"]=TRUE;
                    $_SESSION["pass"]=$password;
                    $_SESSION["usersname"]=$uname;
                    header("Location: /home");
                    mysqli_close($con);
                    exit();
                }
            
            }
        }

    }


?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COSC630 Week 2 Tech</title>
    <meta name="description" content="Login">
    <meta name="author" content="cameron zuziak">
    <link rel="stylesheet" href="home/scripts/css/styles-signup.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="containerMain">
        <span class="overallTitle"><?php echo $title; ?></span>
        <div class="containerContent" style="align-content: center;">
            <form class="coscForm" method="POST" action=#>
                <span class="loginTitle">Enter A Username</span>
                <div class="inputContainer">
                    <input class="inputField" type="text" name="username" placeholder="Username" required>
                </div>
                </br>
                <span class="loginTitle">Phone Number</span>
                <div class="inputContainer"></divclass>
                    <input class="inputField" type="text" id="phone" name="phonenumber" placeholder="Phone Number">
                    <script src="home/scripts/js/phone.js"></script>
                </div>
                </br>
                <span class="loginTitle">Password</span>
                <div class="inputContainer"></divclass>
                    <input class="inputField" type="password" name="pass" placeholder="Password" required>
                </div>
                </br>

                <span class="loginTitle">Confirm Your Password</span>
                <div class="inputContainer"></divclass>
                    <input class="inputField" type="password" name="confpass" placeholder="Confirm Password" required>
                </div>
                </br>

                <div class="containerBtn">
                    <input type="submit" name="submit" class="loginBtn" value="SEND">
                </div>
                </br>
                <div style="font-family:helvetica"><p>Already a member? Login <a href="index.php"><b>here</b></a></p></div>
            </form>
        </div>
    </div>
</body>
</html>

