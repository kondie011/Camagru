<?php
	include_once "config/config.php";

    if (isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['submit']) && $_POST['submit'] == "Login")
    {
		$login = $_POST['login'];
		$passwd = hash('whirlpool',$_POST['passwd']);
		$findUserQuery = "SELECT * FROM `user` WHERE `username` = ? AND `password` = ?";
		$findUserResult = $conn->prepare($findUserQuery);
		$findUserResult->execute([$login, $passwd]);
		if ($findUserResult->rowCount() && $user = $findUserResult->fetch())
		{
			$_SESSION['login'] = $_POST['login'];
			$_SESSION['passwd'] = $passwd;
			ob_start();
			header('Location: index.php');
    		ob_end_flush();
			die();
		}
		else
		{
			echo "Wrong password____".$user;
		}
	}
	else if ($_POST['sp'] == "Sign up")
	{
		ob_start();
        header("Location: signUp.php");
        ob_end_flush();
        die();
	}
?>
<html>
	<head>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
		<title>login page</title>
		<style>
			body
			{
				width: 95%;
				text-align: center;
				background-image: url("https://wallpapers.wallhaven.cc/wallpapers/full/wallhaven-137743.jpg");
			}
			#ze_form
			{
				margin-top: 25%;
				font-size: 30px;
				background-image: linear-gradient(cyan, indigo);
				border-radius: 10px;
				width: 500px;
				position: absolute;
				left: 38%;
				padding: 50px;
				border: 1px solid black;
			}
			#ze_form input
			{
				margin: 5px;
			}
			#welcome
			{
				position: absolute;
				color: white;
				font-size: 55px;
				top: 20%;
				left: 38%;
			}
			#web_name
			{
				font-size: 80px;
				font-style: italic;
				color: #00FFFF;
				shadow: 2px 2px black;
			}
			.input_info
			{
				border-radius: 5px;
				height: 30px;
				width: 300px;
			}
			.text_style1
			{
				font-family: serif;
				font-style: normal;
			}
			.button_style1
			{
				padding: 10px;
				font-size: 25px;
			}
			.button_style2
			{
				padding: 10px;
				font-size: 15px;
				width: 100%;
				background-color: grey;
				color: white;
				font-style: italic;
				border-radius: 5px;
			}
		</style>
	</head>
	<body>
		<a href="index.php"><p id="welcome">Welcome to <span id="web_name">Camagru</span></p></a>
		<form id="ze_form" name="index.php" method="POST" enctype="multipart/form-data">
			<label class="text_style1" for="login">Username: </label>
			<input class="input_info" type="text" name="login" value="<?php echo $_SESSION['login']; ?>"/>
    		<br />
			<label class="text_style1" for="passwd">Password: </label>
			<input class="input_info" type="password" name="passwd" value="<?php echo $_SESSION['passwd'];?>"/>
    		<br />
			<input class="button_style1" type="submit" name="submit" value="Login"/>
			<br />
            <input class="button_style2" type="submit" name="forgot_pass" value="Forgot password?" onclick="forgotPass()"/>
			<br />
            <input class="button_style1" type="submit" name="sp" value="Sign up"/>
		</form>
		<script type="text/javascript">

			function forgotPass()
			{
				var username = prompt("Please enter your username", "kondie_");
				var email = prompt("Please enter your email", "joeblog@mailinator.com");
				var newPass = prompt("Please enter your new password", "");
				var confPass = prompt("Please re-enter your new password", "");
				if (username != "" && email != "" && newPass.length > 5 && confPass.length > 5)
				{
					if (newPass == confPass)
					{
						$.ajax({url: "forgotPassword.php?username="+username+"&email="+email+"&password="+newPass, success: function(result)
						{
							alert(result);
						}});
					}
					else
					{
						alert("Your password doesn't match");
					}
				}
				else
				{
					alert("Please enter all the values");
				}
			}

		</script>
	</body>
</html>
