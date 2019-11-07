<?php

    include_once "config/config.php";

    if (isset($_GET['id']))
    {
        $id = $_GET['id'];
        $username = $_SESSION['login'];
        $likesQuery = "SELECT * FROM `post_like` WHERE `id` = '$id'";
        $likesResult = $conn->prepare($likesQuery);
        $likesResult->execute([$id, $username]);
        
        while ($like = $likesResult->fetch())
        {
            echo $like['username']."\n";
        }
    }
?>