<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Moore's Jewelry</title>
</head>
<body> 
<?php 
header("X-Frame-Options: SAMEORIGIN");
header('X-Content-Type-Options: nosniff'); 
header ("Set-Cookie: name=value; httpOnly");
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

?>
<h1>Moore's Jewelry Database</h1>
<br><br><br><br>
<h3>Select your option:</h3>

	<a href=AddProduct.php> Add products </a>
		<p></p>
		<a href=UpdateProduct.php> Update products </a>
		<p></p>
		<a href=DeleteProduct.php> Delete products </a>





</body>
</html>