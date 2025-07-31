<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['update'])) {

   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = 'Product updated successfully!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/' . $old_image);
         $message[] = 'Image updated successfully!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>
   <link rel="icon" href="images/LYgjKqzpQb.ico" type="image/x-icon">

   <!-- font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   
   <!-- custom styles -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/admin-update-product.css">
   <link rel="stylesheet" href="../css/admin-header.css">
</head>
<body style="background-image: url('images/food-1024x683.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">

      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="<?= $fetch_products['name']; ?>">

      <label class="form-label">Product Name</label>
      <input type="text" name="name" required maxlength="100" class="box"
             value="<?= $fetch_products['name']; ?>">

      <label class="form-label">Price (Rs.)</label>
      <input type="number" name="price" min="0" max="9999999999" required 
             onkeypress="if(this.value.length == 10) return false;" 
             class="box" value="<?= $fetch_products['price']; ?>">

      <label class="form-label">Category</label>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
         <option value="main dish">Main Dish</option>
         <option value="fast food">Fast Food</option>
         <option value="drinks">Drinks</option>
         <option value="desserts">Desserts</option>
      </select>

      <label class="form-label">Change Image</label>
      <input type="file" name="image" class="box" 
             accept="image/jpg, image/jpeg, image/png, image/webp">

      <div class="flex-btn">
         <input type="submit" value="Update" class="btn" name="update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">No product found!</p>';
      }
   ?>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
