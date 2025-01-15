<!DOCTYPE html>
<html>
<head>
    <title>Test Swiper</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <style>
        .swiper {
            width: 100%;
            height: 500px;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php
            $images = [
                'pexels-pixabay-275484.jpg',
                'pexels-john-tekeridis-1428348.jpg',
                'pexels-vecislavas-popa-1571468.jpg'
            ];
            
            foreach ($images as $image) {
                echo '<div class="swiper-slide">';
                echo '<img src="img/' . $image . '" alt="Image">';
                echo '</div>';
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    </script>
</body>
</html>
