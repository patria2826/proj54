<?php

session_start();
session_destroy();
header('Content-Type: text/plain');
header('Location: tools.php');

print_r($_SESSION);