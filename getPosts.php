<?php

    include_once "config/config.php";

			if (isset($_GET['timestamp']))
			{
				$ts = $_GET['timestamp'];
                getPosts($conn, $ts);
			}

			function getPosts($conn, $ts)
			{
				$getPostQuery = "SELECT * FROM `post` WHERE `date_created` < '$ts' ORDER BY `date_created` DESC LIMIT 5";

				$getPostResult = $conn->query($getPostQuery);

				if ($getPostResult->rowCount())
				{
					while ($post = $getPostResult->fetch())
					{
                        $id = $post['id'];
                        $timestamp = $post['date_created'];
						$likeId = "like".$id;
						$commentId = "comment".$id;
						$textId = "text".$id;
						$noOfLikesId = "likes".$id;
						$deleteId = "delete".$id;
						$imgId = "image".$id;
						$emailId = "email".$id;
						$notifId = "notif".$id;
						$noOfCommentsId = "comments".$id;
						$username = $post['username'];
						if (file_exists($post['image_path']))
						{
							$image_path = $post['image_path'];
						}
						else
						{
							$image_path = "images/image_not_found.jpg";
						}

						$l = ($conn->query("SELECT COUNT(*) FROM `post_like` WHERE `id` = '$id'"))->fetchColumn();
						$c = ($conn->query("SELECT COUNT(*) FROM `post_comment` WHERE `id` = '$id'"))->fetchColumn();
						$email = ($conn->query("SELECT `email`, `notif` FROM `user` WHERE `username` = '$username'"))->fetch();
						
                        echo "<br/>
                            <div class='post_container'>
								<div class='post_header'>
									<img class='poster_dp' src='https://www.shareicon.net/download/2016/11/09/851666_user_512x512.png'/>
									<p class='poster_name'>".$post['username']."</p>";
						if ($_SESSION['login'] == $post['username'] && $_SESSION['login'] != "" && $_SESSION['passwd'] != "")
						{
							echo	"<span class='delete' id='".$deleteId."' onclick=deletePost('$id') title='Delete post'>&times;</span>";
						}
						else
						{
							echo	"<span class='delete' id='".$deleteId."' title='Delete post' style='visibility: hidden;'>&times;</span>";
						}
						echo	"</div>
								<div class='image_container'>
									<img id=$imgId class='post_image' src='".$image_path."'/>
									<p class='post_caption'>".$post['caption']."</p>
								</div>";
						if ($_SESSION['login'] == "" && $_SESSION['passwd'] == "")
						{
							echo "<div class='respond_container' style='display: none;'>";
						}
						else
						{
							echo "<div class='respond_container'>";
						}
							echo	"<img onclick=likePost('".$likeId."') class='like_post' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUBKlGBnqSEK1jQdTs9s_tP8_ccT5vxSOJrHBGmeSPbOKEnnCkiA'/>
									<div id='likes_and_comments_row'>
										<p class='likes' id='".$noOfLikesId."' onclick=showLikes('".$noOfLikesId."')>".$l." Likes</p>
										<p class='comments' id='".$noOfCommentsId."' onclick=showComments('".$noOfCommentsId."')>".$c." Comments</p>
									</div>
									<div class='comment_container'>
										<input class='comment_box' id='".$textId."' type='text'/>
										<img onclick=sendComment('".$commentId."') class='post_comment' src='https://www.shareicon.net/data/2016/08/04/806708_email_512x512.png'>
                                    </div>
								</div>
								<p id=$emailId style='display: none;'>".$email['email']."</p>
                                <p id=$notifId style='display: none;'>".$email['notif']."</p>
								<p class='timestamp'>".$timestamp."</p>
							</div>";
					}
				}
			}
?>