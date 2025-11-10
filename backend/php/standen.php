<?php
session_start();
require "conn.php";

$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
$errors = [];
$success = '';



$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
include "standen_view.php";
