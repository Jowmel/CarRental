<?php

@include 'VehicleCon.php';

if(isset($_POST['add'])){

   $product_name = $_POST['name'];
   $product_price = $_POST['type'];
   $product_image = $_FILES['image']['name'];
   $product_image_tmp_name = $_FILES['image']['tmp_name'];
   $product_image_folder = 'uploaded_img/'.$product_image; 
   $price = $_POST['price'];

   if(empty($product_name) || empty($product_price) ||  empty($product_image) || empty($price)){
      $message[] = 'please fill out all';
   }else{
      $insert = "INSERT INTO vehicle(name, type, image, price) VALUES('$product_name', '$product_price', '$product_image', '$price')";
      $upload = mysqli_query($conn,$insert);
      if($upload){
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         $message[] = 'new product added successfully';
      }else{
         $message[] = 'could not add the product';
      }
   }

};


if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM vehicle WHERE id = $id");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM vehicle WHERE id = $id");
    session_start();
    header('location: AdminPage2.php');
	$message[] = 'Delete successfully.';
    exit;
}

$search = '';
if (isset($_POST['search'])) {
   $search = $_POST['search'];
}
if (isset($_GET['refresh'])) {
   $search = '';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/adminL.jpeg" type="image/x-icon">
    <link rel="shortcut icon" href="static/adminL.jpeg" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');

:root{
   --green:#27ae60;
   --black:#333;
   --white:#fff;
   --bg-color:#eee;
   --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
   --border:.1rem solid var(--black);
}

*{
   font-family: 'Poppins', sans-serif;
   margin:0; padding:0;
   box-sizing: border-box;
   outline: none; border:none;
   text-decoration: none;
   text-transform: capitalize;
}

html{
   font-size: 62.5%;
   overflow-x: hidden;
}

.btn{
   display: block;
   width: 100%;
   cursor: pointer;
   border-radius: .5rem;
   margin-top: 1rem;
   font-size: 1.7rem;
   padding:1rem 3rem;
   background: #08f;
   color:var(--white);
   text-align: center;
}

.btn:hover{
   background: var(--black);
}

.message{
   display: block;
   background: var(--bg-color);
   padding:1.5rem 1rem;
   font-size: 2rem;
   color:var(--black);
   margin-bottom: 1rem;
   text-align: center;
   position: absolute;
   margin-top: 52px;
   width: 100%;
}

.container{
   max-width: 1200px;
   padding:2rem;
   margin:0 auto;
   margin-top: 3%;
}

.admin-product-form-container.centered{
   display: flex;
   align-items: center;
   justify-content: center;
   min-height: 100vh;
   
}

.admin-product-form-container form{
   max-width: 50rem;
   margin:0 auto;
   padding:2rem;
   border-radius: .5rem;
   background: var(--bg-color);
}

.admin-product-form-container form h3{
   text-transform: uppercase;
   color:var(--black);
   margin-bottom: 1rem;
   text-align: center;
   font-size: 2.5rem;
}

.admin-product-form-container form .box{
   width: 100%;
   border-radius: .5rem;
   padding:1.2rem 1.5rem;
   font-size: 1.7rem;
   margin:1rem 0;
   background: var(--white);
   text-transform: none;
}

.product-display{
   margin:2rem 0;
}

.product-display .product-display-table{
   width: 100%;
   text-align: center;
}

.product-display .product-display-table thead{
   background: var(--bg-color);
}

.product-display .product-display-table th{
   padding:1rem;
   font-size: 2rem;
}


.product-display .product-display-table td{
   padding:1rem;
   font-size: 2rem;
   border-bottom: var(--border);
}

.product-display .product-display-table .btn:first-child{
   margin-top: 0;
}

.product-display .product-display-table .btn:last-child{
   background: crimson;
}

.product-display .product-display-table .btn:last-child:hover{
   background: var(--black);
} 

@media (max-width:991px){

   html{
      font-size: 55%;
   }

}

@media (max-width:768px){

   .product-display{
      overflow-y:scroll;
   }

   .product-display .product-display-table{
      width: 80rem;
   }

}

@media (max-width:450px){

   html{
      font-size: 50%;
   }

}
header{
	width: 100%;
	background: #111;
	height: 8vh;
}
header h1{
	padding: 10px 16px;
	color: #fff;
	font-size: 2.5em;
}
header a{
	right: 0;
	padding: 0 10px;
	position: absolute;
	top: 2%;
	color: #fff;
	font-size: 1.8em;
	transition: 0.2s ease;
}
header a:hover{
	color: red;
}
header .srch #search{
	border-radius: 15px;
	text-indent: 5px;
	padding: 5px 7px 5px;
}
header .srch{
	position: absolute;
	top: 1.5%;
	left: 65%;
}
.sub{
	background-image:url('static/bx-search-alt-2.svg');
	background-size: cover;
	height: 20px;
	width: 20px;
	border-radius: 50px;
	cursor: pointer;
	position: absolute;
	top: 5px;
	left: 142px;
	border: none;
	outline: none;
	transition: 0.2s ease;
}
.sub:hover{
	transform: scale(0.9);
}
   </style>
</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '<span class="message">'.$message.'</span>';
   }
}

?>
   
<header>
	<h1>ADMIN PANEL</h1><a href="adminP.php">Back</a>
	<div class="srch">
		<form method="post" action="">
			<input type="text" id="search" placeholder="Enter vehicle name" name="search" value="<?php echo $search; ?>">
			<input type="submit" class="sub" value="">
		</form>
	</div>
</header>


<div class="container">

   <div class="admin-product-form-container">

      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
         <h3>add a new vehicle type</h3>
         <input type="text" placeholder="enter vehicle's name" name="name" class="box">
		 <input type="text" placeholder="Price (24hrs)" name="price" class="box">
		 <select id="type" name="type" class="box">
			<option>4 Sitter</option>
			<option>8 Sitter</option>
		 </select>
         <input type="file" accept="image/png, image/jpeg, image/jpg" name="image" class="box">
         <input type="submit" class="btn" name="add" value="add">
      </form>

   </div>
<?php

   $select = mysqli_query($conn, "SELECT * FROM vehicle WHERE name LIKE '%$search%'");

   ?>
   <div class="product-display">
      <table class="product-display-table">
         <thead>
         <tr>
            <th>Vehicle Image</th>
            <th>Vehicle Name</th>
			<th>Price</th>
            <th>Sitter Type</th>
            <th>Action</th>
         </tr>
         </thead>
         <?php while ($row = mysqli_fetch_assoc($select)) { ?>
            <tr>
               <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" width="100" style="border-radius: 50px" alt=""></td>
               <td><?php echo $row['name']; ?></td>
			   <td>/ ₱ <?php echo $row['price']; ?> /</td>
               <td><?php echo $row['type']; ?></td>
               <td>
                  <a href="AdminPage2_update.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> Edit </a>
                  <a href="AdminPage2.php?delete=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-trash"></i> Delete </a>
               </td>
            </tr>
         <?php } ?>
      </table>
   </div>
</div>


</body>
</html>