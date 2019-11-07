<?php

    include_once "config/config.php";

    if (isset($_GET['id']) && isset($_GET['email']) && isset($_GET['notif']))
    {
        $id = $_GET['id'];
        $email = $_GET['email'];
        $notif = $_GET['notif'];
        $username = $_SESSION['login'];
        $isLiked = $conn->query("SELECT * FROM `post_like` WHERE `id` = '$id' AND `username` = '$username'");
        if ($isLiked->rowCount())
        {
            $unLikeQuery = "DELETE FROM `post_like` WHERE `id` = ? AND `username` = ?";
            $unLikeResult = $conn->prepare($unLikeQuery);
            $unLikeResult->execute([$id, $username]);
            if ($notif == '1')
            {
                sendEmail($email, $username." un-liked you picture.", "UN-Liked picture.");
            }
            echo "Post unliked";
        }
        else
        {
            $likeQuery = "INSERT INTO `post_like`(`id`, `username`) VALUES(?, ?)";
            $likeResult = $conn->prepare($likeQuery);
            $likeResult->execute([$id, $username]);
            if ($notif == '1')
            {
                sendEmail($email, $username." liked you picture.", "Liked picture.");
            }
            echo "Post liked";
        }
        $conn->exec("COMMIT");
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