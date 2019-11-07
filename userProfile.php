<?php
	include_once "config/config.php";

	if ($_SESSION['login'] == "")
	{
		header('Location: login.php');
		die();
	}
?>
<html>
    <head>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        <title>User profile</title>
        <style>
            body
            {
                text-align: center;
                height: 100%;
            }
			#snackbar
			{
				visibility: hidden;
				min-width: 250px;
				margin-left: -125px;
				background-color: #333;
				color: #fff;
				text-align: center;
				border-radius: 5px;
				padding: 16px;
				position: fixed;
				z-index: 1;
				left: 50%;
				bottom: 30px;
			}

			#snackbar.show
			{
				visibility: visible;
			}
            #user_pics
            {
                position: relative;
                width: 98%;
                border-radius: 10px;
                display: grid;
  				grid-template-columns: auto auto auto;
                padding: 10px;
                grid-gap: 10px;
				overflow: auto;
                border: 5px solid grey;
                margin-top: 10px;
                min-height: 95%;
            }
            .grid_img
            {
                width: 100%;
            }
            #header
			{
				position: fixed;
				top: 0px;
				left: 0px;
				background-color: #A9A9A9;
				width: 100%;
				padding: 10px;
				box-shadow: 0px 8px 16px 0px grey;
				display: inline-grid;
  				grid-template-columns: auto auto auto;
				text-align: center;
				z-index: 1;
			}
			.web_icon
			{
				width: 50px;
				display: inline;
			}
			.user_icon
			{
				width: 50px;
				display: inline;
			}
            #profile
            {
                visibility: hidden;
            }
			.header_item
			{
				text-align: center;
			}
			#web_name
			{
				font-style: bold;
				color: white;
				font-family: monospace;
				font-size: 18px;
			}
            #edit_profile
            {
                margin-top: 120px;
            }
            #cr
            {
                display: inline;
                float: right;
                margin-right: 10px;
            }
            #f_msg
            {
                display: inline;
                float: left;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
    <div id="header">
			<a href="index.php"><img class="web_icon" src="https://www.freeiconspng.com/uploads/courses-icon-10.png"></a>
			<div class="header_item">
                <?php
                    include_once "config.php";

                    $username = $_SESSION['login'];
                    if ($username != "")
                    {
                        echo "<p id='web_name'>"."Welcome ".$username."</p>";
                    }
                    else
                    {
                        echo "<p id='web_name'>Camagru</p>";
                    }
                ?>
			</div>
			<div class="header_item">
				<a href="userProfile.php"><img class="user_icon" id="profile" src="https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png"></a>
				<div class="header_item" style="display: inline; width: 30px;">
					<img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
				</div>
			</div>
		</div>

        <div id="edit_profile">
            <input type="button" name="login" value="Change username" onclick="changeUsername()"/>
            <input type="button" name="email" value="Change email" onclick="changeEmail()"/>
            <input type="button" name="password" value="Change password" onclick="changePassword()"/>
            
            <?php
                include_once "config/config.php";

                $username = $_SESSION['login'];

                $prefR = $conn->query("SELECT `notif` FROM `user` WHERE `username` = '$username'");
                $pref = ($prefR->fetch())['notif'];
                if ($pref == '1')
                {
                    echo "<label for='notif' style='margin-left: 5px;'>Notification</label><input id='notif' name='notif' type='checkbox' checked/>";
                }
                else
                {
                    echo "<label for='notif' style='margin-left: 5px;'>Notification</label><input id='notif' name='notif' type='checkbox'/>";
                }
            ?>
        </div>
        <div id="user_pics">
        <?php

            include_once "config/config.php";

            $username = $_SESSION['login'];
            $getPostQuery = "SELECT * FROM `post` WHERE `username`=? ORDER BY `id` DESC";

            $getPostResult = $conn->prepare($getPostQuery);
            $getPostResult->execute([$username]);
            if ($getPostResult->rowCount())
            {
                while ($post = $getPostResult->fetch())
                {
                    echo "<img class='grid_img' src='".$post['image_path']."'>";
                }
            }

        ?>

        </div>
		<div id="snackbar"></div>
        <script type="text/javascript">
            function changeUsername()
            {
                var newUsername = prompt("Please enter your new Username", "kondie_");
                if (newUsername != "" && newUsername != null)
                {
                    $.ajax({url:"changeUsername.php?newUsername=" + newUsername, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No username entered");
                }
            }

            function changeEmail()
            {
                var newEmail = prompt("Please enter your new Email", "knedzing@student.wethinkcode.co.za");
                if (newEmail != "" && newEmail != null)
                {
                    $.ajax({url:"changeEmail.php?newEmail=" + newEmail, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("No email entered");
                }
            }

            function changePassword()
            {
                var oldPassword = prompt("Please enter your OLD PASSWORD", "");
                var newPassword = prompt("Please enter your NEW PASSWORD", "");
                var confPassword = prompt("Please re-enter your NEW PASSWORD", "");
                if (oldPassword != "" && newPassword != "" && confPassword != "")
                {
                    $.ajax({url:"changePassword.php?oldPassword=" + oldPassword + "&newPassword=" + newPassword + "&confPassword=" + confPassword, success: function(result)
                    {
                        showSnackbar(result);
                    }})
                }
                else
                {
                    showSnackbar("Enter all the values");
                }
            }

            (document.getElementById('notif')).onclick = function()
            {
                var pref = $('#notif:checked').val();

                $.ajax({url:"changeNotifPref.php?pref="+pref, success: function(result)
				{
					showSnackbar(result);
				}})
            }

			function logOut()
			{
				$.ajax({url:"logout.php", success: function(result)
				{
					location.reload();
				}})
            }

			function showSnackbar(message)
            {
				var snackbar = document.getElementById("snackbar");
				snackbar.innerHTML = message;
				snackbar.className = "show";
				setTimeout(function()
				{
					snackbar.className = "";
				}, 3000);
			}
        </script>
 
        <div id="footer">
            <p id="f_msg">This website is proundly provided to you by Nedzingahe Kondelelani</p>
            <p id="cr">knedzing©2018</p>
        </div>

    </body>
</html>
