


<?php
include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:admin_login.php');
}
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>
   <link rel="icon" href="images/logo.png" type="image/x-icon">
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/admin_messages.css">
   <link rel="stylesheet" href="../css/admin-header.css">
   
   <style>
   /* Orange and White Theme Overrides */
   
   </style>
</head>
<body style="background-image: url('images/food-1024x683.jpg'); background-size: cover; height:100vh; background-position: center; background-repeat: no-repeat;">

<?php include '../components/admin_header.php' ?>

<!-- messages section starts  -->
<section class="admin-messages">

   <h1 class="admin-messages__title">Messages</h1>

   <div class="admin-messages__grid">

   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="admin-messages__card">
      <p><strong>Name:</strong> <span><?= $fetch_messages['name']; ?></span></p>
      <p><strong>Number:</strong> <span><?= $fetch_messages['number']; ?></span></p>
      <p><strong>Email:</strong> <span><?= $fetch_messages['email']; ?></span></p>
      <p><strong>Message:</strong> <span><?= $fetch_messages['message']; ?></span></p>
      <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" 
         class="admin-messages__delete" 
         onclick="return confirm('Delete this message?');">Delete</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="admin-messages__empty">You have no messages</p>';
      }
   ?>

   </div>

</section>
<!-- messages section ends -->

<!-- messages section ends -->

         
<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>