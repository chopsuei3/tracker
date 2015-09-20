<?php

session_start();
session_unset();
session_destroy();

// Logged out, return home.
Header("Location: index.html");
?>

<title>the chronicler | logout</title>
