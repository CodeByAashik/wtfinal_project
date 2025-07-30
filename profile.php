<?php  
include 'components/connect.php';  
session_start();  
if(isset($_SESSION['user_id'])){    
   $user_id = $_SESSION['user_id']; 
}else{    
   $user_id = '';    
   header('location:home.php'); 
};  
?>  

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>
   <link rel="icon" href="images/LYgjKqzpQb.ico" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">     
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/footer.css">
   <link rel="stylesheet" href="css/header.css">
   
   <style>
   /* Profile Page Specific Styles */
   .profile-container {
      max-width: 80rem;
      margin: 3rem auto;
      padding: 0 2rem;
   }

   .profile-header {
      text-align: center;
      margin-bottom: 4rem;
   }

   .profile-header h1 {
      font-size: 3.5rem;
      color: #2c3e50;
      margin-bottom: 1rem;
      font-weight: 700;
   }

   .profile-header .subtitle {
      font-size: 1.6rem;
      color: #7f8c8d;
      font-weight: 300;
   }

   .profile-card {
      border-radius: 2rem;
      padding: 4rem;
      box-shadow: 0 0 4rem rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
   }

   .profile-content {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 1.5rem;
      padding: 3rem;
      position: relative;
      z-index: 2;
   }

   .profile-avatar {
      text-align: center;
      margin-bottom: 3rem;
   }

   .profile-avatar img {
      width: 12rem;
      height: 12rem;
      border-radius: 50%;
      border: 0.5rem solid #fff;
      box-shadow: 0 1rem 2rem rgba(0,0,0,0.2);
      object-fit: cover;
   }

   .profile-info {
      display: grid;
      gap: 2rem;
      margin-bottom: 3rem;
   }

   .info-item {
      display: flex;
      align-items: center;
      padding: 1.5rem;
      background: #f8f9fa;
      border-radius: 1rem;
      border-left: 0.4rem solid #667eea;
      transition: all 0.3s ease;
   }

   .info-item:hover {
      background: #e9ecef;
      transform: translateX(0.5rem);
   }

   .info-item i {
      font-size: 2rem;
      color: #667eea;
      margin-right: 1.5rem;
      width: 3rem;
      text-align: center;
   }

   .info-item .info-content {
      flex: 1;
   }

   .info-item .info-label {
      font-size: 1.2rem;
      color: #6c757d;
      font-weight: 500;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.1rem;
   }

   .info-item .info-value {
      font-size: 1.6rem;
      color: #2c3e50;
      font-weight: 600;
   }

   .address-item {
      border-left-color: #e74c3c;
   }

   .address-item i {
      color: #e74c3c;
   }

   .address-placeholder {
      color: #95a5a6;
      font-style: italic;
   }

   .profile-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      margin-top: 3rem;
   }

   .action-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem 2rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      text-decoration: none;
      border-radius: 1rem;
      font-size: 1.4rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
   }

   .action-btn:hover {
      transform: translateY(-0.3rem);
      box-shadow: 0 1rem 2rem rgba(102, 126, 234, 0.4);
   }

   .action-btn.secondary {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      box-shadow: 0 0.5rem 1rem rgba(231, 76, 60, 0.3);
   }

   .action-btn.secondary:hover {
      box-shadow: 0 1rem 2rem rgba(231, 76, 60, 0.4);
   }

   .action-btn i {
      margin-right: 1rem;
      font-size: 1.6rem;
   }

   /* Responsive Design */
   @media (max-width: 768px) {
      .profile-container {
         padding: 0 1rem;
         margin: 2rem auto;
      }

      .profile-card {
         padding: 2rem;
      }

      .profile-content {
         padding: 2rem;
      }

      .profile-header h1 {
         font-size: 2.8rem;
      }

      .profile-avatar img {
         width: 10rem;
         height: 10rem;
      }

      .profile-actions {
         grid-template-columns: 1fr;
      }

      .info-item {
         flex-direction: column;
         text-align: center;
      }

      .info-item i {
         margin-right: 0;
         margin-bottom: 1rem;
      }
   }

   @media (max-width: 480px) {
      .profile-header h1 {
         font-size: 2.4rem;
      }

      .profile-header .subtitle {
         font-size: 1.4rem;
      }

      .action-btn {
         padding: 1.2rem 1.5rem;
         font-size: 1.2rem;
      }
   }

   /* Animation */
   .profile-card {
      animation: slideUp 0.6s ease-out;
   }

   @keyframes slideUp {
      from {
         opacity: 0;
         transform: translateY(3rem);
      }
      to {
         opacity: 1;
         transform: translateY(0);
      }
   }

   .info-item {
      animation: fadeInLeft 0.6s ease-out;
      animation-fill-mode: both;
   }

   .info-item:nth-child(1) { animation-delay: 0.1s; }
   .info-item:nth-child(2) { animation-delay: 0.2s; }
   .info-item:nth-child(3) { animation-delay: 0.3s; }
   .info-item:nth-child(4) { animation-delay: 0.4s; }

   @keyframes fadeInLeft {
      from {
         opacity: 0;
         transform: translateX(-2rem);
      }
      to {
         opacity: 1;
         transform: translateX(0);
      }
   }
   </style>
</head>

<body>     
   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="profile-container">
      <div class="profile-header">
         <h1>My Profile</h1>
         <p class="subtitle">Manage your personal information and preferences</p>
      </div>

      <div class="profile-card">
         <div class="profile-content">
            <div class="profile-avatar">
               <img src="images/user-icon.png" alt="Profile Picture">
            </div>

            <div class="profile-info">
               <div class="info-item">
                  <i class="fas fa-user"></i>
                  <div class="info-content">
                     <div class="info-label">Full Name</div>
                     <div class="info-value"><?= $fetch_profile['name']; ?></div>
                  </div>
               </div>

               <div class="info-item">
                  <i class="fas fa-phone"></i>
                  <div class="info-content">
                     <div class="info-label">Phone Number</div>
                     <div class="info-value"><?= $fetch_profile['number']; ?></div>
                  </div>
               </div>

               <div class="info-item">
                  <i class="fas fa-envelope"></i>
                  <div class="info-content">
                     <div class="info-label">Email Address</div>
                     <div class="info-value"><?= $fetch_profile['email']; ?></div>
                  </div>
               </div>

               <div class="info-item address-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <div class="info-content">
                     <div class="info-label">Address</div>
                     <div class="info-value <?php if($fetch_profile['address'] == '') echo 'address-placeholder'; ?>">
                        <?php if($fetch_profile['address'] == ''){echo 'Please enter your address';}else{echo $fetch_profile['address'];} ?>
                     </div>
                  </div>
               </div>
            </div>

            <div class="profile-actions">
               <a href="update_profile.php" class="action-btn">
                  <i class="fas fa-edit"></i>
                  Update Info
               </a>
               <a href="update_address.php" class="action-btn secondary">
                  <i class="fas fa-map-marker-alt"></i>
                  Update Address
               </a>
            </div>
         </div>
      </div>
   </div>
           
   <?php include 'components/footer.php'; ?>        
   
   <!-- custom js file link  --> 
   <script src="js/script.js"></script>  
</body>
</html>