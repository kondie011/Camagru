<?php
    include "config/config.php";

    if (isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['conf_passwd']) && isset($_POST['email']) && isset($_POST['submit']) && $_POST['submit'] == "Sign up")
    {
        $login = $_POST['login'];
        $passwd = hash('whirlpool',$_POST['passwd']);
        $email = $_POST['email'];
        $findUserQuery = "SELECT * FROM `user` WHERE `username` = ?";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$login]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else if ($_POST['conf_passwd'] == $_POST['passwd'])
        {
            if (strlen($_POST['passwd']) >= 6)
            {
                $url = $_SERVER['HTTP_HOST'] . str_replace("/signUp.php", "", $_SERVER['REQUEST_URI']);
                sendEmail($email,   "<html>
                                        <a href='http://".$url."/index.php?login=".$login."&passwd=".$passwd."&email=".$email."'>
                                            <input type='submit' value='click to verify' style='color: #FFFFFF; padding: 10px; background-color: green;'/>
                                        </a>
                                    </html>", "Verification");
                die("Check your email");
            }
            else
            {
                echo "Password must be at least 6 characters";
            }
        }
        else
        {
            echo "password doesn't match";
        }
    }
    else if ($_POST['sp'] == "Sign up")
    {
        ob_start();
        header("Location: signUp.php");
        ob_end_flush();
        die();
    }
    
    function sendEmail($to, $msg, $sbj)
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $from = "www.kondie@live.com";
        $header = "From:" . $from;

        mail($to, $sbj, $msg, $header);
    }
?>
<html>
    <head>
        <title>login page</title>
        <style>
            body
            {
                text-align: center;
				background-image: url("https://wallpapers.wallhaven.cc/wallpapers/full/wallhaven-137743.jpg");
            }
            #ze_form
            {
                margin-top: 30%;
                font-size: 30px;
                text-align: left;
                position: absolute;
                border: 1px solid grey;
                border-radius: 10px;
                padding: 20px;
                width: 300px;
                left: 40%;
                top: 0%;
            }
            #ze_form input
            {
                margin: 5px;
            }
            #welcome
            {
                position: absolute;
                color: black;
                font-size: 55px;
                top: 20%;
                right: 39%;
            }
            .text_style1
            {
                font-size: 17px;
            }
        </style>
    </head>
    <body>
        <p id="welcome">Fill in the details</p>
        <form id="ze_form" name="index.php" method="POST" enctype="multipart/form-data">
            <label class="text_style1" for="login">Username: </label><input type="text" name="login" value=""/>
            <br />
            <label class="text_style1" for="email">Email: </label><input type="text" name="email" value=""/>
            <br />
            <label class="text_style1" for="passwd">Password: <label><input type="password" name="passwd" value=""/>
            <br />
            <label class="text_style1" for="conf_passwd">Confirm password: <label><input type="password" name="conf_passwd" value=""/>
            <br />
            <input type="submit" name="submit" value="Sign up"/>
        </form>
    </body>
</html>
