<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated successfully!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>
   <link rel="icon" href="images/logo.png" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css files -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/admin-orders.css">
   <link rel="stylesheet" href="../css/admin-header.css">
</head>
<body style="background-image: url('images/food-1024x683.jpg'); background-size: cover; height:100vh; background-position: center; background-repeat: no-repeat;">

<?php include '../components/admin_header.php' ?>

<section class="orders-panel">
   <h1 class="panel-heading">Placed Orders</h1>

   <div class="orders-wrapper">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY id DESC");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="order-box">
         <div class="order-details">
            <p><span>User ID:</span> <?= $fetch_orders['user_id']; ?></p>
            <p><span>Placed On:</span> <?= $fetch_orders['placed_on']; ?></p>
            <p><span>Name:</span> <?= $fetch_orders['name']; ?></p>
            <p><span>Email:</span> <?= $fetch_orders['email']; ?></p>
            <p><span>Phone:</span> <?= $fetch_orders['number']; ?></p>
            <p><span>Address:</span> <?= $fetch_orders['address']; ?></p>
            <p><span>Products:</span> <?= $fetch_orders['total_products']; ?></p>
            <p><span>Total:</span> Rs.<?= $fetch_orders['total_price']; ?>/-</p>
            <p><span>Payment Method:</span> <?= $fetch_orders['method']; ?></p>
         </div>

         <form action="" method="POST" class="order-controls">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="payment_status" class="status-dropdown">
               <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="pending">Pending</option>
               <option value="completed">Completed</option>
            </select>

            <div class="order-buttons">
               <input type="submit" value="Update" class="btn" name="update_payment">
               <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" 
                  class="delete-btn" 
                  onclick="return confirm('Delete this order?');">Delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No orders have been placed yet!</p>';
      }
      ?>
   </div>
</section>


<script src="../js/admin_script.js"></script>
</body>
</html>
