<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'cart item deleted!';
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   // header('location:cart.php');
   $message[] = 'deleted all from cart!';
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart - Quickerr</title>
   <link rel="icon" href="images/LYgjKqzpQb.ico" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->
   <link rel="stylesheet" href="css/footer.css">
   <link rel="stylesheet" href="css/header.css">
   <link rel="stylesheet" href="css/cart.css">


</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="home.php">home</a> <span> / cart</span></p>
</div>

<!-- shopping cart section starts  -->

<section class="products">

   <h1 class="title">Your Cart Items</h1>

   <div class="box-container">

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"></button>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Rs. </span><?= $fetch_cart['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
         <div class="sub-total"> sub total : <span>Rs. <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">Your cart is empty! Start adding some delicious items to get started.</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>🛒 Cart Total : <span>Rs. <?= $grand_total; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">🚀 Proceed to Checkout</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('delete all from cart?');">🗑️ Clear Cart</button>
      </form>
      <a href="menu.php" class="btn">🍽️ Continue Shopping</a>
   </div>

</section>

<!-- shopping cart section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
   // Add smooth animations and interactions
   document.addEventListener('DOMContentLoaded', function() {
      // Animate cart items on load
      const cartItems = document.querySelectorAll('.box');
      cartItems.forEach((item, index) => {
         item.style.animationDelay = `${index * 0.1}s`;
      });

      // Add quantity change animation
      const qtyInputs = document.querySelectorAll('.qty');
      qtyInputs.forEach(input => {
         input.addEventListener('change', function() {
            this.style.transform = 'scale(1.1)';
            setTimeout(() => {
               this.style.transform = 'scale(1)';
            }, 200);
         });
      });

      // Add loading state to update buttons
      const updateBtns = document.querySelectorAll('.fa-edit');
      updateBtns.forEach(btn => {
         btn.addEventListener('click', function() {
            const originalContent = this.innerHTML;
            this.innerHTML = '<div class="loading"></div>';
            
            setTimeout(() => {
               this.innerHTML = originalContent;
            }, 1000);
         });
      });

      // Add hover effects to action buttons
      const actionBtns = document.querySelectorAll('.fa-eye, .fa-times');
      actionBtns.forEach(btn => {
         btn.addEventListener('mouseenter', function() {
            this.style.animation = 'pulse 0.5s ease-in-out';
         });
         
         btn.addEventListener('mouseleave', function() {
            this.style.animation = 'none';
         });
      });

      // Add checkout button pulse if cart has items
      const checkoutBtn = document.querySelector('.cart-total .btn:not(.disabled)');
      if (checkoutBtn) {
         setInterval(() => {
            checkoutBtn.style.animation = 'pulse 1s ease-in-out';
            setTimeout(() => {
               checkoutBtn.style.animation = 'none';
            }, 1000);
         }, 5000);
      }

      // Add smooth scroll to cart total when items are updated
      const cartTotal = document.querySelector('.cart-total');
      if (cartTotal) {
         const observer = new MutationObserver(() => {
            cartTotal.scrollIntoView({ behavior: 'smooth', block: 'center' });
         });
      }
   });

   const style = document.createElement('style');
   style.textContent = `
      .box {
         transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      .qty {
         transition: transform 0.2s ease;
      }
   `;
   document.head.appendChild(style);
</script>

</body>
</html>