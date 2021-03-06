<?php 
session_start();
require_once('include/db.php'); 
include('admin_navigation_bar.php');
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
}
$managerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); 
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]); 
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); 
 

$sql = "SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1"; 
$run_sql = mysqli_query($conn,$sql);

if (mysqli_num_rows($run_sql) == 0) { 
	 echo "Your login session data is not on record in the database.";
     exit();
}
?>
<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
function GetImageExtension($imagetype)
   	 {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }
	 
if (isset($_POST['product_name'])) {
	
	$pid = $_POST['thisID'];
    $product_name = $_POST['product_name'];
	$price =$_POST['price'];
	$category = $_POST['category'];
	$subcategory = $_POST['subcategory'];
	$details = $_POST['details'];

	
	$sql = "UPDATE products SET product_name='$product_name', price='$price', details='$details', category='$category', subcategory='$subcategory' WHERE id='$pid'";
	$run_sql = mysqli_query($conn,$sql);
	
	if ($_FILES['fileField']['tmp_name'] != "") 
	{
	$file_name=$_FILES["fileField"]["name"];
	$temp_name=$_FILES["fileField"]["tmp_name"];
	$imgtype=$_FILES["fileField"]["type"];
	$ext= GetImageExtension($imgtype);
	$imagename=date("d-m-Y")."-".time().$ext;
	$target_path = "../prod-img/".$imagename;
	$sql_img =  "prod-img/".$imagename;
	if(move_uploaded_file($temp_name, $target_path)) 
	{
		$sql = "UPDATE products SET thumb='$sql_img' WHERE id='$pid'";
	$run_sql = mysqli_query($conn,$sql);
	}
	 
	}
	header("location: inventory_list.php"); 
    exit();
}
?>
<?php 

if (isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
    $sql = "SELECT * FROM products WHERE id='$targetID' LIMIT 1";
	$run_sql = mysqli_query($conn,$sql);
    
    if (mysqli_num_rows($run_sql) > 0) {
	    while($row = mysqli_fetch_array($run_sql)){ 
             
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $category = $row["category"];
			 $subcategory = $row["subcategory"];
			 $details = $row["details"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
        }
    } else {
	    echo "Sorry dude that crap dont exist.";
		exit();
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inventory List</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />
<script type="text/javascript" language="javascript"> 
<!--
function validateMyForm ( ) { 
    var isValid = true;
    if ( document.myForm.product_name.value == "" ) { 
	    alert ( "Please type Product Name" ); 
	    isValid = false;
    } else if ( document.myForm.price.value == "" ) { 
            alert ( "Please enter price" ); 
            isValid = false;
    } else if ( document.myForm.details.value == "" ) { 
            alert ( "Please provide details" ); 
            isValid = false;
    }
    return isValid;
}
//-->
</script>
</head>

<body>
<div align="center" id="mainWrapper">

  <div id="pageContent"><br />
    <div align="right" style="margin-right:32px;"><a href="http://localhost/InorbitMall/storeadmin/logout.php">Logout</a></div>
<div align="left" style="margin-left:24px;">
      <h2>Inventory list</h2>
    </div>
    <hr />
    <a name="inventoryForm" id="inventoryForm"></a>
    <h3>
    &darr; Edit Inventory Item Form &darr;
    </h3>
    <form action="inventory_edit.php" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
    <table width="90%" border="0" cellspacing="0" cellpadding="6">
      <tr>
        <td width="20%" align="right">Product Name</td>
        <td width="80%"><label>
          <input name="product_name" type="text" id="product_name" size="64" value="<?php echo $product_name; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Product Price</td>
        <td><label>
          RS
          <input name="price" type="text" id="price" size="12" value="<?php echo $price; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Category</td>
        <td><label>
          <select name="category" id="category">
          <option value="Clothing">Clothing</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Subcategory</td>
        <td><select name="subcategory" id="subcategory">
          <option value="<?php echo $subcategory; ?>"><?php echo $subcategory; ?></option>
          <option value="Hats">Hats</option>
          <option value="Pants">Pants</option>
          <option value="Shirts">Shirts</option>
          </select></td>
      </tr>
      <tr>
        <td align="right">Product Details</td>
        <td><label>
          <textarea name="details" id="details" cols="64" rows="5"><?php echo $details; ?></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right">Product Image</td>
        <td><label>
          <input type="file" name="fileField" id="fileField" />
        </label></td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input name="thisID" type="hidden" value="<?php echo $targetID; ?>" />
          <input type="submit" name="button" id="button" value="Make Changes" onclick="javascript:return validateMyForm();"/>
        </label></td>
      </tr>
    </table>
    </form>
    <br />
  <br />
  </div>
  <?php include_once("admin_footer.php");?>
</div>
</body>
</html>