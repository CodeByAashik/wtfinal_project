<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quickerr</title>

   <link rel="icon" href="images/logo.png" type="image/x-icon">

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/footer.css">
   <link rel="stylesheet" href="css/header.css">
   <link rel="stylesheet" href="css/customer-rev.css">
   <link rel="stylesheet" href="css/delivery-info.css">
   <link rel="stylesheet" href="css/hero.css">
   <link rel="stylesheet" href="css/category.css">
   <link rel="stylesheet" href="css/hero-products.css">

   <style>
      .box-container {
         display: flex;
         justify-content: space-between;
      }

      .box {
         position: relative;
         overflow: hidden;
         transition: background-color 0.3s ease;
      }

      .box:hover {
         background-color: rgba(240, 222, 222, 1);
      }
   </style>

</head>


<body>

<?php include 'components/user_header.php'; ?>


<?php include 'components/hero.php'; ?>
<section class="hero">

   <div class="swiper hero-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>delicious pizza</h3>
               <a href="menu.php" class="btn">see menus</a>
            </div>
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>chezzy hamburger</h3>
               <a href="menu.php" class="btn">see menus</a>
            </div>
            <div class="image">
               <img src="images/home-img-2.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>rosted chicken</h3>
               <a href="menu.php" class="btn">see menus</a>
            </div>
            <div class="image">
               <img src="images/home-img-3.png" alt="">
            </div>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>
</section>



<section class="category">
  <div class="category-overlay"></div>

  <h1 class="title">Food Category</h1>

  <div class="category-grid">
    <a href="category.php?category=fast food" class="cat-card">
      <img src="images/cat-1.png" alt="Fast Food">
      <h3>Fast Food</h3>
    </a>

    <a href="category.php?category=main dish" class="cat-card">
      <img src="images/cat-2.png" alt="Main Dishes">
      <h3>Main Dishes</h3>
    </a>

    <a href="category.php?category=drinks" class="cat-card">
      <img src="images/cat-3.png" alt="Drinks">
      <h3>Drinks</h3>
    </a>

    <a href="category.php?category=desserts" class="cat-card">
      <img src="images/cat-4.png" alt="Desserts">
      <h3>Desserts</h3>
    </a>
  </div>
</section>





<section class="products">

   <h1 class="title">Menu</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="10" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="menu.php" class="btnv">veiw all</a>
   </div>

</section>


















<?php include 'components/delivery-info.php'; ?>
<?php include 'components/customer-rev.php'; ?>

<!-- Location section -->
 <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
 <div class="loc-container">
    <section class="location">
       <!-- Floating Info Box -->
       <div class="info-card">
          <h2 id="m-nor">Quickerr Restaurant</h2>
          <h3 id="m-aor">Kathmandu</h3>
          <p id="">Balkumari, Lalitpur, <br>Nepal</p>
          <p><strong>Phone number</strong><br>9819893465</p>
          <p><strong>Website</strong><br><a href="https://www.aashikthakur.com.np" target="_blank">https://www.aashikthakur.com.np</a></p>
         </div>
         
         <!-- Map Container -->
         <div id="map"></div>
      </section>
   </div>

<script>
   
   const centerCoords = [27.7172, 85.3240];  // Kathmandu
   
   const map = L.map('map').setView(centerCoords, 15);
   
   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
   }).addTo(map);
   
   // 📍 Add marker at center location
   const marker = L.marker(centerCoords).addTo(map)
   .bindPopup("<b>Quickerr</b><br>Balkumari")
   .openPopup();
</script>



   <?php include 'components/footer.php'; ?>



<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".hero-slider", {
   loop:true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
});

</script>

</body>
</html>