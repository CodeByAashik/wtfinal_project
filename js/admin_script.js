let profile = document.querySelector('.admin-header .flex .profile');
let navbar = document.querySelector('.admin-header .flex .navbar');

document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

window.onscroll = () => {
   profile.classList.remove('active');
   navbar.classList.remove('active');
}

// Image switching code below stays the same
subImages = document.querySelectorAll('.update-product .image-container .sub-images img');
mainImage = document.querySelector('.update-product .image-container .main-image img');

subImages.forEach(images => {
   images.onclick = () => {
      let src = images.getAttribute('src');
      mainImage.src = src;
   }
});
