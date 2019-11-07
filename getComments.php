<?php

    include_once "config/config.php";

    if (isset($_GET['id']))
    {
        $id = $_GET['id'];
        $username = $_SESSION['login'];
        $commentsQuery = "SELECT * FROM `post_comment` WHERE `id` = '$id'";
        $commentsResult = $conn->prepare($commentsQuery);
        $commentsResult->execute([$id, $username]);
        
        while ($comment = $commentsResult->fetch())
        {
            echo $comment['username'].": ".$comment['comment']."\n";
        }
    }
?>