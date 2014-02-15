<?php
session_start();
if (!isset($_SESSION['username']) && $_SERVER['REQUEST_URI'] != '/~ricardon/project/login.php')
{
	header('Location: login.php');
	exit;
}
if (isset($_SESSION['username']) && $_SERVER['REQUEST_URI'] == '/~ricardon/project/login.php')
{
	header('Location: project.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $title_tag ?> &mdash; My Legal Bill Processor (version 0.9)</title>
		<link rel="stylesheet" type="text/css" href="mystyles.css">
	</head>
	<body>
		<h1>My Legal Bill Processor (version 0.9)</h1>
		<h2><?php if (isset($_SESSION['username'])) {echo 'Welcome, ' . $_SESSION['firstname'] . '&nbsp;' . $_SESSION['lastname'] . '&nbsp;<a href="logout.php">[&nbsp;Log&nbsp;Out&nbsp;]</a>';} ?></h2`>
		<h3><?php echo $title_tag ?></h3>
