<?php
	include_once "config/config.php";

	if (isset($_GET['login']) && isset($_GET['passwd']) && isset($_GET['email']))
    {
        $login = $_GET['login'];
        $passwd = $_GET['passwd'];
        $email = $_GET['email'];
        $findUserQuery = "SELECT * FROM `user` WHERE `username` = ?";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$login]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else
        {
        	$addUserQuery = "INSERT INTO `user`(`username`, `password`, `email`) VALUES(?, ?, ?)";
            $addUserResult = $conn->prepare($addUserQuery);
            $addUserResult->execute([$login, $passwd, $email]);
            $conn->query("COMMIT");
            $_SESSION['login'] = $login;
            $_SESSION['passwd'] = $passwd;
        }
    }
    else if ($_GET['sp'] == "Sign up")
    {
        ob_start();
        header("Location: signUp.php#");
        ob_end_flush();
        die();
    }
    
?>
<html>
	<head>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
		<title>Camagru</title>
		<style>
			body
			{
				background-color: #DCDCDC;
				height: 100%;
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
			#main
			{
				position:relative;
				margin-top: 5px;
				text-align: center;
				width: 100%;
				top: 80px;
				display: inline-block;
				margin-bottom: 100px;
				min-height: 80%;
			}
			.post_container
			{
				background-color: white;
				border-radius: 5px;
				border: 0.5px solid gray;
				padding: 2px;
				width: 600px;
				text-align: center;
				margin-top: 10px;
				display: inline-block;
				box-shadow: 0px 8px 16px 0px grey;
			}
			.post_header
			{
				background-color: #DCDCDC;
				margin: 5px;
				border-radius: 5px;
				display: grid;
  				grid-template-columns: 0px auto 25px;
			}
			.poster_dp
			{
				width: 30px;
				margin: 10px;
				display: inline;
			}
			.poster_name
			{
				display: inline;
				font-style: bold;
			}
			.image_container
			{
				background-color: #DCDCDC;
				border-radius: 5px;
				text-align: center;
				display: inline-block;
				padding: 0px;
				width: 100%;
			}
			.post_image
			{
				width: 100%;
			}
			.comment_box
			{
				width: 400px;
				height: 50px;
				border-radius: 5px;
				border: 2px solid #1E90FF;
				color: black;
			}
			.like_post
			{
				width: 50px;
			}
			.like_post:hover
			{
				cursor: pointer;
			}
			.comment_container
			{
				display: inline-grid;
				text-align: top;
  				grid-template-columns: auto auto auto;
			}
			.post_comment
			{
				width: 50px;
				display: inline;
				border-radius: 100%;
				border: 1px solid #00FA9A;
				margin-left: 10px;
			}
			.post_comment:hover
			{
				cursor: pointer;
			}
			#main_dropdown
			{
				position: fixed;
				bottom: 85px;
				right: 25px;
			}
			#post_pic
			{
				width: 40px;
				border-radius: 100%;
				background-color: white;
				padding: 10px;
				border: 2px solid #4169E1;
			}
			#main_dropdown:hover #main_dropdown_items
			{
				display: block;
			}
			#main_dropdown_items
			{
				display: none;
				position: absolute;
				background-color: #f9f9f9;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				padding: 12px 16px;
				z-index: 1;
				bottom: 40px;
				right: 40px;
				min-width: 160px;
			}
			#main_dropdown_items p
			{
				background-color: #00008B;
				border-radius: 5px;
				color: white;
				padding: 15px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			}
			#likes_dropdown
			{
				display: none;
				position: fixed;
				z-index: 1;
				padding-top: 100px;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				overflow: auto;
				background-color: rgb(0,0,0);
				background-color: rgba(0,0,0,0.4);
			}
			#likes_dropdown_items
			{
				background-color: #fefefe;
				margin: auto;
				padding: 20px;
				border: 1px solid #888;
				width: 80%;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			}
			#likes_dropdown_items p
			{
				margin: 5px;
			}
			#close
			{
				color: #aaaaaa;
				float: right;
				font-size: 28px;
				margin-right: 9.5%;
				font-weight: bold;
			}
			.delete
			{
				color: #DD0000;
				font-size: 28px;
				font-weight: bold;
			}
			.delete:hover,
			.delete:focus {
				color: red;
				text-decoration: none;
				cursor: pointer;
			}
			#close:hover,
			#close:focus {
				color: #000;
				text-decoration: none;
				cursor: pointer;
			}
			.likes
			{
				cursor: pointer;
				display: inline;
				margin-right: 10px;
				color: grey;
			}
			.comments
			{
				cursor: pointer;
				display: inline;
				color: grey;
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
			#pages
			{
				display: inline-block;
				margin-top: 10px;
			}
			#pages a
			{
				color: black;
				float: left;
				padding: 8px 16px;
				text-decoration: none;
			}
            #footer
            {
				position: relative;
				bottom: 0px;
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
			<img class="web_icon" src="https://www.freeiconspng.com/uploads/courses-icon-10.png">
			<div class="header_item">
                <?php
                    include_once "config/config.php";

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
				<a href="userProfile.php"><img class="user_icon" src="https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png"></a>
				<div class="header_item" style="display: inline; width: 30px;">
					<img class="user_icon" onclick="logOut()" src="https://www.freeiconspng.com/uploads/shutdown-icon-28.png">
				</div>
			</div>
		</div>
		<div id="main">

			<?php

				include_once "getPosts.php";
				include_once "config/config.php";

				if (!isset($_GET['timestamp']))
				{
					getPosts($conn, "5050-10-26 12:36:28");
				}

			?>
		
		</div>

		<div id="main_dropdown">
			<div id="main_dropdown_items">
				<a href="upload_pic.php"><p>Capture picture</p></a>
			</div>
			<img id="post_pic" src="http://3.bp.blogspot.com/-p29CGVu_22Q/VcEIostZOQI/AAAAAAAAgZc/lpFp91hkPzw/s1600/CameraNext.png">
		</div>

		<div id="likes_dropdown">
			<span id="close">&times;</span>
			<div id="likes_dropdown_items"></div>
		</div>
		<div id="snackbar"></div>

		<script type="text/javascript">

				function likePost(id)
				{
                    var emailId = "email" + id.substring(4, 20);
					var notifId = "notif" + id.substring(4, 20);
                    
					$.ajax({url:"likePost.php?id=" + id.substring(4, 20) + "&email=" + document.getElementById(emailId).innerHTML + "&notif=" + document.getElementById(notifId).innerHTML, success: function(result)
					{
						showSnackbar(result);
					}})
				}

				function sendComment(id)
				{
					var comment;

					comment = $("#text" + id.substring(7, 30)).val();
					if (comment != "")
					{
                        var emailId = "email" + id.substring(7, 30);
                        var notifId = "notif" + id.substring(7, 30);
						$.ajax({url:"commentToPost.php", method: "POST", data: {"id":id.substring(7, 30), "comment":comment, "email":document.getElementById(emailId).innerHTML, "notif":document.getElementById(notifId).innerHTML}, success: function(result)
						{
							$("#text" + id.substring(7, 30)).val("");
							showSnackbar(result);
						}})
					}
					else
					{
						showSnackbar("No comment entered");
					}
				}

				function showLikes(id)
				{
					var modal = document.getElementById('likes_dropdown');
					var list = document.getElementById("likes_dropdown_items");
					var span = document.getElementById("close");

					span.onclick = function() {
						modal.style.display = "none";
						list.innerHTML = "";
					}

					window.onclick = function(event) {
						if (event.target == modal) {
							modal.style.display = "none";
							list.innerHTML = "";
						}
					}

					$.ajax({url:"getLikes.php?id=" + id.substring(5, 30), success: function(result)
					{
						if (result == "")
						{
							showSnackbar("There are no likes to show");
						}
						else
						{
							modal.style.display = "block";
							var likers = result.split("\n");
							for (var c=0; likers[c]; c++)
							{
								var liker = document.createElement("p");
								var username = document.createTextNode(likers[c]);
								liker.appendChild(username);
								list.appendChild(liker);
							}
						}
					}})
				}

				function showComments(id)
				{
					var modal = document.getElementById('likes_dropdown');
					var list = document.getElementById("likes_dropdown_items");
					var span = document.getElementById("close");

					span.onclick = function() {
						modal.style.display = "none";
						list.innerHTML = "";
					}

					window.onclick = function(event) {
						if (event.target == modal) {
							modal.style.display = "none";
							list.innerHTML = "";
						}
					}

					$.ajax({url:"getComments.php?id=" + id.substring(8, 30), success: function(result)
					{
						if (result == "")
						{
							showSnackbar("There are no comments to show");
						}
						else
						{
							modal.style.display = "block";
							var commests = result.split("\n");
							for (var c=0; commests[c]; c++)
							{
								var comment = document.createElement("p");
								var txt = document.createTextNode(commests[c]);
								comment.appendChild(txt);
								list.appendChild(comment);
							}
						}
					}})
				}

				function logOut()
				{
					$.ajax({url:"logout.php", success: function(result)
					{
						location.reload();
					}})
				}

				function deletePost(id)
				{
					var srcId = "image"+id;
					var path = document.getElementById(srcId).src;
					$.ajax({url: "deletePost.php?id=" + id + "&path=" + path, success: function(result)
					{
						if (result == "Deleted")
						{
							location.reload();
							showSnackbar("Post deleted");
						}
						else
						{
							showSnackbar(result);
						}
					}})
				}

				function showSnackbar(message) {
					var snackbar = document.getElementById("snackbar");

					snackbar.innerHTML = message;
					snackbar.className = "show";
					setTimeout(function()
					{
						snackbar.className = "";
					}, 3000);
				}
				$(window).scroll(function()
				{
					var scroll = (window.innerHeight + window.scrollY);
					var max = document.body.offsetHeight;
					if (scroll >= max && document.getElementById("main").innerHTML.legth > 0)
					{
						const lastPost = document.getElementById("main").lastElementChild;
						const timestamp = lastPost.lastElementChild.innerHTML;
						showSnackbar("Loading...");
						$.ajax({url:"getPosts.php?timestamp=" + timestamp, success: function(result)
						{
							result = document.getElementById("main").innerHTML + result;
							document.getElementById("main").innerHTML = result;
						}})
					}
                });
		</script>

		<div id="footer">
        	<p id="f_msg">This website is proundly provided to you by Nedzingahe Kondelelani</p>
        	<p id="cr">knedzing©2018</p>
    	</div>

	</body>
</html>
