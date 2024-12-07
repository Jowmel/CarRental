<?php

@include 'VehicleCon.php';

$id = $_GET['edit'];

if(isset($_POST['update'])){
   $product_name = $_POST['name'];
   $product_price = $_POST['type'];
   $product_image = $_FILES['image']['name'];
   $product_image_tmp_name = $_FILES['image']['tmp_name'];
   $product_image_folder = 'uploaded_img/'.$product_image;
   $price = $_POST['price'];

   // Check if the image file is provided
   if (!empty($product_image)) {
      $upload = move_uploaded_file($product_image_tmp_name, $product_image_folder);

      if (!$upload) {
         $message[] = 'Error uploading image.';
      }
   }

   if (empty($product_name) || empty($product_price) || empty($price)) {
      $message[] = 'Please fill out all fields!';
   } else {
      // If the image file is provided, include it in the update query
      $imageUpdate = !empty($product_image) ? ", image='$product_image'" : "";

      $update_data = "UPDATE vehicle SET name='$product_name', type='$product_price' $imageUpdate , price='$price' WHERE id = '$id'";
      $upload = mysqli_query($conn, $update_data);

      if ($upload) {
         header('location: AdminPage2.php');
      } else {
         $message[] = 'Error updating product.';
      }
   }
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- This is for the logo seen above site-->
	<link rel="icon" href="static/adminL.jpeg" type="image/x-icon">
    <link rel="shortcut icon" href="static/adminL.jpeg" type="image/x-icon">
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
   overflow: hidden;
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
   margin-bottom: 2rem;
   text-align: center;
   position: absolute;
   width: 100%;
}

.container{
   max-width: 1200px;
   padding:2rem;
   margin:0 auto;
   position: absolute;
   top: 5%;
   left: 30%;
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
.img_veh:hover{
	transform: scale(1.1);
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

<div class="container">
   <div class="admin-product-form-container centered">
      <?php
         $select = mysqli_query($conn, "SELECT * FROM vehicle WHERE id = '$id'");
         while($row = mysqli_fetch_assoc($select)){
      ?>
      <form action="" method="post" enctype="multipart/form-data">
         <img src="uploaded_img/<?php echo $row['image']; ?>" id="image-preview" height="100" class="img_veh" width="100" style="border-radius: 50px; cursor: pointer; transition: 0.2s ease; position: absolute; top: 3%; left: 40%;" alt="">
         <h3 class="title">update the product</h3>
         <input type="text" class="box" name="name" value="<?php echo $row['name']; ?>" placeholder="enter the product name">
		 <input type="text" class="box" name="price" value="<?php echo $row['price']; ?>" placeholder="Price (24hrs)">
         <select id="type" name="type" class="box">
            <option value="4 Sitter" <?php echo ($row['type'] == '4 Sitter') ? 'selected' : ''; ?>>4 Sitter</option>
            <option value="8 Sitter" <?php echo ($row['type'] == '8 Sitter') ? 'selected' : ''; ?>>8 Sitter</option>
         </select>
         <input type="file" class="box" name="image" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this)">
         <input type="submit" value="update" name="update" class="btn">
         <a href="AdminPage2.php" class="btn">go back!</a>
      </form>
      <?php }; ?>
   </div>
</div>

<script>
      function previewImage(input) {
         const preview = document.getElementById('image-preview');
         const file = input.files[0];

         if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
               preview.src = e.target.result;
            };

            reader.readAsDataURL(file);
         }
      }
   </script>
</body>
</html>