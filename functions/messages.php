<?php

function afficheMessage($message)
{
    echo "<script>alert(" . json_encode($message) . ");</script>";
}

function afficheErreur($message)
{
    echo "<p style='color:red'>" . htmlspecialchars($message) . "</p>";
}