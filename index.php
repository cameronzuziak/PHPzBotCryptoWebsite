<?php
    session_start();
    $title="User Login";
    $host="localhost";
    $user="root";
    $password="mysql";
    $db="demo";
    $con=mysqli_connect($host,$user,$password, $db);
    $_SESSION["loggedin"]=FALSE;
    
    if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if(isset($_POST['username'])){
        $uname=$_POST['username'];
        $password=$_POST['pass'];

        $sql="select * from loginform where User='".$uname."' limit 1";
        $result=mysqli_query($con,$sql);
        if(mysqli_num_rows($result)==1){
            $row=mysqli_fetch_assoc($result);
            $_SESSION["Running"]=$row['Running'];
            $_SESSION["ID"]=$row['ID'];
            $hash=$row['Pass'];
            if(password_verify(strval($password), strval($hash))){
                $_SESSION["usersname"]=$uname;
                $_SESSION["pass"]=$password;
                $phonenum=strval($row['Phone']);
                $_SESSION["phone"]=$phonenum;
                $command = escapeshellcmd("python ./scripts/python/2FA.py .$phonenum");
                $output1 = shell_exec($command);
                $_SESSION["codeF"]=$output1;
                $_SESSION["2FA"]=TRUE;
                mysqli_close($con);
                header("Location: 2FA.php");
                exit();
            }
        }
        else{
            $title="Wrong Username or Password";
        }
    }

?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>zBot Login</title>
        <meta name="description" content="Login">
        <meta name="author" content="cameron zuziak">
        <link rel="stylesheet" href="home/scripts/css/styles.css">
    </head>

    <body>
        <div class="containerMain">
            <div class="titleContainer">    
                <span class="mainTitle">Welcome To zBot</span>
            </div>
            <div class="containerLogin">
                <div class="Pic"> 
                    <img src="home/scripts/css/images/img-01.png" alt="IMG">
                </div>
                <form class="loginForm" method="POST" action="#">
                    <span class="loginTitle">
                        <?php echo $title; ?>
                    </span>
                    <div class="inputContainer" data-validate="Alphanumeric username required">
                        <input class="inputField" type="text" name="username" placeholder="Username">
                    </div>

                    <div class="inputContainer" data-validate="Password is required">
                        <input class="inputField" type="password" name="pass" placeholder="Password">
                    </div>

                    <div class="containerBtn">
                        <input type="submit" name="submit" class="loginBtn" value="LOGIN">
                    </div>
                    </br>
                    <div style="font-family:helvetica"><p>Not a member? Sign up <a href="signup.php"><b>here</b></a></p></div>
                </form>
            </div>
        </div>
    </body>
</html>