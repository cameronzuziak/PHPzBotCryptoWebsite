<?php
    session_start();
    $title = "Enter Your 6 digit Code";
    if(!$_SESSION["2FA"]){
        header("Location: signup.php");
        exit();
    }

    if(!empty($_POST['code'])){
        $code_a=$_POST['code'];
        $a=(int)$code_a;
        $b=(int)$_SESSION["codeF"];
        if($a === $b){ 
            $_SESSION["loggedin"]=TRUE;
            header("Location: ./home/index.php");
            exit();
        }
        else{
            $title="Wrong Code";
        }
    }

?>


<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Bot Login</title>
    <meta name="description" content="Login">
    <meta name="author" content="cameron zuziak">
    <link rel="stylesheet" href="./home/scripts/css/styles.css">
</head>

<body>
    <div class="containerMain">
        <div class="containerLogin">
            <div class="Pic">
                <img src="./home/scripts/css/images/img-01.png" alt="IMG">
            </div>
            <form class="loginForm validate-form" method="POST" action="#">
                <span class="loginTitle">
                    <?php echo $title; ?>
                </span>
                <div class="inputContainer">
                    <input class="inputField" type="text" name="code" placeholder="Login Code">
                </div>
                <div class="containerBtn">
                    <input type="submit" name="submit" class="loginBtn" value="LOGIN">
                </div>
            </form>
        </div>
    </div>
</body>
</html>