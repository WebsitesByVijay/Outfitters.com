let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});

   // Function to update the total when quantity is changed
   function updateTotal(input) {
      var quantity = input.value;
      var price = input.dataset.price;
      var subTotal = price * quantity;
      var subTotalElement = input.parentNode.nextElementSibling.querySelector("span");
      subTotalElement.textContent = "$" + subTotal.toFixed(2) + "/-";

      // Calculate and update the grand total
      var grandTotal = 0;
      var subTotalElements = document.querySelectorAll(".sub-total span");
      subTotalElements.forEach(function(element) {
         grandTotal += parseFloat(element.textContent.slice(1));
      });
      var grandTotalElement = document.querySelector(".cart-total p span");
      grandTotalElement.textContent = "$" + grandTotal.toFixed(2) + "/-";

      // Enable or disable buttons based on grand total
      var deleteAllBtn = document.querySelector(".delete-btn");
      var checkoutBtn = document.querySelector(".btn");
      if (grandTotal > 1) {
         deleteAllBtn.classList.remove("disabled");
         checkoutBtn.classList.remove("disabled");
      } else {
         deleteAllBtn.classList.add("disabled");
         checkoutBtn.classList.add("disabled");
      }
   }

