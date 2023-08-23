<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
        </script>
        <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

        <!-- font awesome cdn link  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <!-- custom css file link  -->
        <link rel="stylesheet" href="css/style.css">

    </head>

    <body>

        <?php include 'components/user_header.php'; ?>

        <!-- Home-carousel -->

        <section id="carousel">
            <div id="Home-carousel" class="carousel slide" data-bs-ride="carousel" data-interval="100">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="carousel-image1" src="images/HomeImage.jpg" alt="Model">
                        <h2 class="carousel-text1">"People will stare. <br> Make it worth their while."
                            <br>
                            <span>—Harry Winston</span>
                        </h2>
                    </div>
                    <div class="carousel-item">
                        <img class="carousel-image2" src="images/Mens_Offer.jpg" alt="lady-profile">
                        <h2 class="carousel-text2"> <span>20%</span><br>OFF<br>on Men's Fashion. </h2>
                    </div>
                    <div class="carousel-item">
                        <img class="carousel-image3" src="images/New-arrivals.jpg" alt="lady-profile">
                        <h2 class="carousel-text3">New <br> Collections.</h2>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#Home-carousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#Home-carousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section class="category">

            <h1 class="heading">Explore!</h1>

            <div class="swiper category-slider">

                <div class="swiper-wrapper">

                    <a href="category.php?category=Men" class="swiper-slide slide">
                        <img src="images\mens-outfits.webp" alt="men.jpg">
                        <h3>Men</h3>
                    </a>

                    <a href="category.php?category=ladies" class="swiper-slide slide">
                        <img src="images\womens-outfits.webp" alt="women.jpg">
                        <h3>Women</h3>
                    </a>

                    <a href="category.php?category=Kids" class="swiper-slide slide">
                        <img src="images\kids-outfits.webp" alt="kids">
                        <h3>Kids</h3>
                    </a>

                    <a href="category.php?category=Shoes" class="swiper-slide slide">
                        <img src="images\shoes-outfit.webp" alt="shoes.jpg">
                        <h3>Shoes</h3>
                    </a>

                </div>
            </div>

        </section>

        <section class="home-products">

            <h1 class="heading">featured products</h1>

            <div class="swiper products-slider">

                <div class="swiper-wrapper">

                    <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
                    <form action="" method="post" class="swiper-slide slide">
                        <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                        <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                        <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <div class="name"><?= $fetch_product['name']; ?></div>
                        <div class="flex">
                            <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div>
                            <input type="number" name="qty" class="qty" min="1" max="99"
                                onkeypress="if(this.value.length == 2) return false;" value="1">
                        </div>
                        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                    </form>
                    <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

                </div>

                <div class="swiper-pagination"></div>

            </div>

        </section>









        <?php include 'components/footer.php'; ?>

        <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

        <script src="js/script.js"></script>

        <script>
        var swiper = new Swiper(".category-slider", {
            loop: false,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                750: {
                    slidesPerView: 3,
                },
                868: {
                    slidesPerView: 4,
                },
                1024: {
                    slidesPerView: 5,
                },
            },
        });

        var swiper = new Swiper(".products-slider", {
            loop: false,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                550: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
        </script>

    </body>

</html>