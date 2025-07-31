<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      if($address == ''){
         $message[] = 'please add your address!';
      }else{
         
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'order placed successfully!';
      }
      
   }else{
      $message[] = 'your cart is empty';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout - Quickerr</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->
   <link rel="stylesheet" href="css/header.css">
   <link rel="stylesheet" href="css/footer.css">
   <link rel="stylesheet" href="css/checkout.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Secure Checkout</h3>
   <p><a href="home.php">home</a> <span> / checkout</span></p>
</div>

<section class="checkout">

   <h1 class="title">Complete Your Order</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>Order Summary</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">Your cart is empty! Add some delicious items to continue.</p>';
         }
      ?>
      <p class="grand-total"><span class="name">Total Amount:</span><span class="price">Rs. <?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">View Cart Details</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
   <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

   <div class="user-info">
      <h3>Customer Information</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <a href="update_profile.php" class="btn">Update Information</a>
      
      <h3>Delivery Address</h3>
      <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'Please enter your delivery address';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">Update Address</a>
      
      <select name="method" class="box" required>
         <option value="" disabled selected>Choose Payment Method</option>
         <option value="cash on delivery">💵 Cash on Delivery</option>
         <option value="credit card">💳 Credit Card</option>
         <option value="paytm">📱 Paytm</option>
         <option value="paypal">🌐 PayPal</option>
      </select>
      
      <input type="submit" value="🚀 Place Order Now" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--gradient); color:var(--white); font-size: 1.1rem; padding: 1rem 2rem;" name="submit">
   </div>

</form>
   
</section>

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
   // Add smooth animations
   document.addEventListener('DOMContentLoaded', function() {
      // Animate cart items
      const cartItems = document.querySelectorAll('.cart-items p');
      cartItems.forEach((item, index) => {
         item.style.opacity = '0';
         item.style.transform = 'translateX(-20px)';
         item.style.transition = 'all 0.5s ease';
         
         setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
         }, index * 100);
      });

      // Add hover effect to payment options
      const selectBox = document.querySelector('select[name="method"]');
      if (selectBox) {
         selectBox.addEventListener('change', function() {
            this.style.borderColor = 'var(--accent-color)';
            this.style.boxShadow = '0 0 0 3px rgba(39,174,96,0.1)';
         });
      }

      // Add pulse animation to place order button
      const submitBtn = document.querySelector('input[type="submit"]');
      if (submitBtn && !submitBtn.classList.contains('disabled')) {
         submitBtn.addEventListener('mouseenter', function() {
            this.style.animation = 'pulse 1s infinite';
         });
         
         submitBtn.addEventListener('mouseleave', function() {
            this.style.animation = 'none';
         });
      }
   });

   // Add CSS animation for pulse effect
   const style = document.createElement('style');
   style.textContent = `
      @keyframes pulse {
         0% { transform: scale(1); }
         50% { transform: scale(1.05); }
         100% { transform: scale(1); }
      }
   `;
   document.head.appendChild(style);
</script>

</body>
</html>