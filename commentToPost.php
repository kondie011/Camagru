<?php

    include_once "config/config.php";

    if (isset($_POST['id']) && isset($_POST["comment"]) && isset($_POST['email']) && isset($_POST['notif']))
    {
        $id = $_POST['id'];
        $email = $_POST['email'];
        $notif = $_POST['notif'];
        $comment = $_POST["comment"];
        $username = $_SESSION['login'];
        $commentQuery = "INSERT INTO `post_comment`(`id`, `username`, `comment`) VALUES(?, ?, ?)";
        $commentResult = $conn->prepare($commentQuery);
        $commentResult->execute([$id, $username, $comment]);
        $conn->exec("COMMIT");
        if ($notif == '1')
        {
            sendEmail($email, $username." commented to your picture: ".$comment, "Commented picture.");
        }
        echo "Comment sent";
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