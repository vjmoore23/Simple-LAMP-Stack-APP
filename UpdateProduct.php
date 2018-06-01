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

<br><br><br>
<h3>Update Products</h3>
<?php   			

if (isset($_GET["ProductID"])) {
  $toUpdate = $_GET["ProductID"];

  
  updateIt($toUpdate);

    
}
else if (isset($_POST["UpdateMe"])) {
	
  $productID = $_POST["ProductID"];
  $type = $_POST["Type"];
  $price = $_POST["Price"];

  
  $product = new ProductClass($productID,$type,$price);
  
  FinalUpdate($product);

  showMsg();

	    

 	 
}
 else {
	    show_updateform();  
	    
	    }
  	
	?>
		
<?php
 function showMsg(){
echo "<p></p>";

  echo "Product has been updated in the database." ;
	echo "<p></p>"; 
	echo "<a href=Homepage.php> Homepage </a>";	
	     	echo "<p></p>";
		echo "<a href=AddProduct.php> Add products </a>";
		echo "<p></p>";
		echo "<a href=UpdateProduct.php> Update products </a>";
		echo "<p></p>";
		echo "<a href=DeleteProduct.php> Delete products </a>";

$products = selectProducts();
	
	echo "<p> " . "Number of Products in the Database is:  " . sizeof($products) . "</p>";
	
	echo "<table border='1'>";
	foreach ($products as $data) {
	echo "<tr>";	
	
	
	 echo "<td>" . $data->getProductID() . "</td>";
	 echo "<td>" . $data->getType() . "</td>";
	 echo "<td>" . $data->getPrice() . "</td>";
	 
	echo "</tr>";
}
	echo "</table>";
}
 function show_updateform() { 			
	
	
	echo "<h3> Select the Product to Update</h3>";
		
	
	$products = selectProducts();
	
	echo "<p> " . "Number of Products in the Database is:  " . sizeof($products) . "</p>";
	
	echo "<table border='1'>";
	foreach ($products as $data) {
	echo "<tr>";	
	
	echo "<td> <a href=UpdateProduct.php?ProductID=" . $data->getProductID() . ">" . "Update" . "</a></td>";
	 echo "<td>" . $data->getProductID() . "</td>";
	 echo "<td>" . $data->getType() . "</td>";
	 echo "<td>" . $data->getPrice() . "</td>";
	 
	echo "</tr>";
}
	echo "</table>";

} 
?>
<?php
   function getProduct ($productD) {
  	
   $mysqli = connectdb();
   
   
		$Query = "Select ProductID, Type, Price from Products 
		         where ProductID = ?";	         
	        //Prepared statement  
		$stmt = $mysqli->prepare($Query);

$stmt->bind_param("s", $productD);
$result = $stmt->execute();

 $stmt->bind_result($productID,$type,$price);
 

    $stmt->fetch();
  $productData = new Productclass($productID,$type,$price);
				
	$stmt->close();   
   $mysqli->close();
   return $productData;
  }
  function updateIt($productD) {
  	
  	
	$product = getProduct($productD);
	$productID = $product->getProductID();
	$type = $product->getType();
	$price = $product->getPrice();

	?>
	<p></p>
	
	<form name="updateProduct" method="POST" action="UpdateProduct.php">	
	<table border="1" width="100%" cellpadding="0">			
			<tr>
				<td width="157">Product Stock ID#:</td>
				<td><input type="text" name="ProductID" value='<?php echo $productID ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Type:</td>
				<td><input type="text" name="Type" value='<?php echo $type ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Price:</td>
				<td><input type="int" name="Price" value='<?php echo $price ?>' size="30"></td>
			</tr>
			<tr>
			
				<td width="157"><input type="submit" value="Update" name="UpdateMe"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>
		  	
  <?php	
  }
  function selectProducts()
  {
		
   $mysqli = connectdb();
		
	 
		$Query = "Select ProductID, Type, Price from Products";	         
	          
		$result = $mysqli->query($Query);
		$myProducts = array();
if ($result->num_rows > 0) {    
    while($row = $result->fetch_assoc()) {
    	
    	$productID = $row["ProductID"];
    	$type = $row["Type"];
    	$price = $row["Price"];
    	   	
        
       $productData = new Productclass($productID,$type,$price);
       $myProducts[] = $productData;         
      }    
 } 

	$mysqli->close();
	
	return $myProducts;		
		
	}


function getDbparms()
	 {
	 	$trimmed = file('parms/dbparms.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$key = array();
	$vals = array();
	foreach($trimmed as $line)
	{
		  $pairs = explode("=",$line);    
	    $key[] = $pairs[0];
	    $vals[] = $pairs[1]; 
	}
	
	$mypairs = array_combine($key,$vals);
	
	
	$myDbparms = new DbparmsClass($mypairs['username'],$mypairs['password'],
	                $mypairs['host'],$mypairs['db']);
	
	return $myDbparms;
	 }
	 
  function connectdb() {      		
		
	  $mydbparms = getDbparms();
	  
	  
	  $mysqli = new mysqli($mydbparms->getHost(), $mydbparms->getUsername(), 
	                        $mydbparms->getPassword(),$mydbparms->getDb());
	
	   if ($mysqli->connect_error) {
	      die('Connect Error (' . $mysqli->connect_errno . ') '
	            . $mysqli->connect_error);      
	   }
	  return $mysqli;
	}
 
 class DBparmsClass
	{
	     
	    private $username="";
	    private $password="";
	    private $host="";
	    private $db="";
	   
	    
	    public function __construct($myusername,$mypassword,$myhost,$mydb)
	    {
	      $this->username = $myusername;
	      $this->password = $mypassword;
			  $this->host = $myhost;
				$this->db = $mydb;
	    }
	    
	     
		  public function getUsername ()
	    {
	    	return $this->username;
	    } 
		  public function getPassword ()
	    {
	    	return $this->password;
	    } 
		  public function getHost ()
	    {
	    	return $this->host;
	    } 
		  public function getDb ()
	    {
	    	return $this->db;
	    } 	 
	
	 
	    public function setUsername ($myusername)
	    {
	    	$this->username = $myusername;    	
	    }
	    public function setPassword ($mypassword)
	    {
	    	$this->password = $mypassword;    	
	    }
	    public function setHost ($myhost)
	    {
	    	$this->host = $myhost;    	
	    }
	    public function setDb ($mydb)
	    {
	    	$this->db = $mydb;    	
	    }    
	    
	}

  class ProductClass
{
    
    private $productID="";
    private $type="";
    private $price="";
    
   
    public function __construct($productID,$type,$price)
    {
      $this->productID = $productID;
      $this->type = $type;
      $this->price = $price;
         
    }
    
	  public function getProductID ()
    {
    	return $this->productID;
    } 
	  public function getType ()
    {
    	return $this->type;
    } 
	  public function getPrice ()
    {
    	return $this->price;
    } 

	  
    public function setProductID ($value)
    {
    	$this->productID = $value;    	
    }
    public function setType ($value)
    {
    	$this->type = $value;    	
    }
    public function setPrice ($value)
    {
    	$this->price = $value;    	
    }
    
    
} 
function FinalUpdate($product) {
	
  $productID = $product->getProductID();
  $type = $product->getType();
  $price = $product->getPrice();
	

   $mysqli = connectdb();		
	 		
		$Query = "Update Products set ProductID = ?,
		         Type = ?, Price = ?  
		         where ProductID = ?";
		         
	                    
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("ssss", $productID, $type, $price, $productID);
$stmt->execute();

 				
	$stmt->close();   
   $mysqli->close();

}

?>


</body>
</html>