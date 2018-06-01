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
	
<?php   	
		if(isset($_POST["CreateSubmit"])) 
		{    	 
	 	 		 	 	
	   	validate_form();	   	     
		} 
		else 
		{			    
			$messages = array();
	    show_form($messages);  
  	} 
	?>
	

	
<?php
function show_form($messages){
		$productID="";
		$type="";
		$price="";
		
		if (isset($_POST["ProductID"]))
		  $productID=$_POST["ProductID"];
	  	if (isset($_POST["Type"]))
		  $type=$_POST["Type"];	  
		if (isset($_POST["Price"]))
		  $price=$_POST["Price"];  
	
		 	
?>
<br>
<h3>Insert new products</h3>
<form name="createproduct" method="POST" action="AddProduct.php">	
	<table border="1" width="100%" cellpadding="0">			
			<tr>
				<td width="157">Product Stock ID#:</td>
				<td><input type="text" name="ProductID" value='<?php echo $productID ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Product Type:</td>
				<td><input type="text" name="Type" value='<?php echo $type ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Price:</td>
				<td><input type="int" name="Price" value='<?php echo $price ?>' size="30"></td>
			</tr>
			<tr>

				<td width="157"><input type="submit" value="Submit" name="CreateSubmit"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>
	<?php
} 
?>

<?php
function validate_form()
{
		
	$messages = array();
  $redisplay = false;
  $productID = $_POST["ProductID"];
  $type = $_POST["Type"];
  $price = $_POST["Price"];
 
  
  $product = new ProductClass($productID,$type,$price);
  	$count = countProduct($product);    	  
 
 	
  	if ($count==0) 
  	{  		
  		$res = insertProduct($product);
  		echo "<p>Product has been added to the database.</p> ";
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
  	else 
  	{
  	echo "<p> Product already exists in databse</p>";	  		
  	}  	
  }
 function countProduct ($product)
  {  	  	 
  	
   $mysqli = connectdb();
   $productID = $product->getProductID();
   $type = $product->getType();
   $price = $product->getPrice();
 
	$mysqli = connectdb();
		
	 $Myquery = "SELECT count(*) as count from Products
		   where ProductID='$productID'";	 
		
	 if ($result = $mysqli->query($Myquery)) 
	 {
	   	     
	    while( $row = $result->fetch_assoc() )
	    {
	  	  $count=$row["count"];	    			   	     	  	     	  
	    }	 
	
 	   
	    $result->close();	      
   }
	
	$mysqli->close();   
	    
	return $count;
  	  	
  }
 function insertProduct ($product)
  {
		
   $mysqli = connectdb();

   $productID = $product->getProductID();
   $type = $product->getType();
   $price = $product->getPrice();
   
		
		// Add Prepared Statement
		$Query = "INSERT INTO Products 
	          (ProductID,Type,Price) 
	           VALUES (?,?,?)";
	           
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("sss", $productID, $type, $price);
$stmt->execute();
		
		
	
	$stmt->close();
	$mysqli->close();
		
		return true;
}

function selectProducts ()
  {
		
   $mysqli = connectdb();
		
	 
		$Query = "Select ProductID,Type,Price from Products";	         
	          
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

?>
</body>
</html>