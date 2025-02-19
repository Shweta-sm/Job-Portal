<?php
// logout.php

session_start();

// Destroy all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the home page
header("Location: index.php");
exit();
