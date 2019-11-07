<?php

    include_once "config/config.php";

    if (isset($_GET['newUsername']) && strlen($_GET['newUsername']) > 0)
    {
        $username = $_SESSION['login'];
        $newUsername = $_GET['newUsername'];

        $findUserQuery = "SELECT * FROM `user` WHERE `username` = ?";
        $findUserResult = $conn->prepare($findUserQuery);
        $findUserResult->execute([$newUsername]);
        if ($findUserResult->rowCount())
        {
            echo "Username already exists";
        }
        else
        {
            $changeUsernameQ = "UPDATE `user` SET `username` = ? WHERE `username` = ?";
            $changePosterQ = "UPDATE `post` SET `username` = ? WHERE `username` = ?";
            $changeLikerQ = "UPDATE `post_like` SET `username` = ? WHERE `username` = ?";
            $changeCommenterQ = "UPDATE `post_comment` SET `username` = ? WHERE `username` = ?";

            $changeUsernameR = $conn->prepare($changeUsernameQ);
            $changeUsernameR->execute([$newUsername, $username]);
            
            $conn->exec("COMMIT");
            if ($changeUsernameR->rowCount())
            {
                $changePosterR = $conn->prepare($changePosterQ);
                $changeLikerR = $conn->prepare($changeLikerQ);
                $changeCommenterR = $conn->prepare($changeCommenterQ);

                $changePosterR->execute([$newUsername, $username]);
                $changeLikerR->execute([$newUsername, $username]);
                $changeCommenterR->execute([$newUsername, $username]);
                $conn->exec("COMMIT");

                $_SESSION['login'] = $newUsername;
                echo "Username changed";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }
    else
    {
        echo "No username was entered";
    }
?>