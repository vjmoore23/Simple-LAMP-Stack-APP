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
<h1>Moore's Jewelry</h1>

<br><br><br>
<h3>Customer Order Form</h3>
<form name="orderForm" method="POST" action="Homepage.php">
<table border="1" width="100%" cellpadding="0">
		<tr>
		<td width="157">Account #:</td>
		<td><input type="text" name="CustomerID" value='<?php echo $CustomerID ?>' size="30"></td>
		</tr>
		<tr>
		<td width="157">First Name:</td>
		<td><input type="text" name="FirstName" value='<?php echo $FirstName ?>' size="30"></td>
			</tr>
		<tr>
		<td width="157">Last Name:</td>
		<td><input type="text" name="LastName" value='<?php echo $LastName ?>' size="30"></td>
		</tr>
		<tr>
		<td width="157">Email Address:</td>
		<td><input type="text" name="EmailAddress" value='<?php echo $EmailAddress ?>' size="30"></td>
			</tr>
<td width="157"><input type="submit" name= "submit_button" value="Submit"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>	

 <?php
     if($_POST['submit_button'] == "Submit"){
        $CustomerID= $_POST['CustomerID'];
 	$FirstName=$_POST['FirstName'];
 	$LastName=$_POST['LastName'];
 	$EmailAddress=$_POST['EmailAddress'];
     	$dbhost='localhost';
     	$dbuser = 'sdev_owner';
     	$dbpass = 'sdev300';
    	$db='sdev';

	    try{
            $mysql=new PDO("mysql:host=$dbhost;dbname=$db", $dbuser, $dbpass);
            $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	    $verify="SELECT * From Customers WHERE CustomerID='$CustomerID'";
	    $select=$mysql->query($verify);
     	   
	    

  	 if($select->rowCount()==0){
  			//Prepared Statement
			$sqli=$mysql->prepare( "INSERT INTO Customers(CustomerID,FirstName,LastName,EmailAddress)
              		VALUES('$CustomerID','$FirstName','$LastName', '$EmailAddress')");
			
			$sqli->bindParam(':CustomerID', $CustomerID);
  			$sqli->bindParam(':FirstName', $FirstName);
  			$sqli->bindParam(':LastName', $LastName);
			$sqli->bindParam(':EmailAddress', $EmailAddress);

             		$sqli->execute();

             		echo "<p>New customer added to the Customers database.</p>";
     		}
		else{
   			echo "<p>Customer already exists.</p>";
     		}

        	}
		catch (PDOException $e){

            	echo $sqli."<br>".$e->getMessage();
        }
        $mysql=NULL;
    }
?>

<br><br><br><br>
<h3>Store Owner Login:</h3>
<form name="storeLogin" method="POST" action="Database.php">
	<table border="1" width="100%" cellpadding="0">
		<tr>
		<td width="157">Username:</td>
		<td><input type="text" name="username" value='<?php echo $username ?>' size="30"></td>
		</tr>
		<tr>
		<td width="157">Password:</td>
		<td><input type="text" name="password" value='<?php echo $password ?>' size="30"></td>
			</tr>
<td width="157"><input type="submit" value="Login" name="storeLogin"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>

</body>
</html>