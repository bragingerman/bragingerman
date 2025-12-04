<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Working AJAX Slider</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
body {
    margin: 0;
    background: #1a1a1a;
}

.swiper {
    width: 100vw;
    height: 100vh;
}

.swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000;
}

.swiper-slide img {
    width: 90%;
    height: 90%;
    object-fit: contain;
    border-radius: 12px;
}

.swiper-pagination { display: none !important; }
</style>

</head>
<body>

<div class="swiper">
    <div class="swiper-wrapper" id="slider-wrapper"></div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// ЗАГРУЖАЕМ СПИСОК КАРТИНОК AJAX-ОМ
fetch("load_images.php")
    .then(r => r.json())
    .then(files => {
        const wrapper = document.getElementById("slider-wrapper");

        files.forEach(file => {
            wrapper.innerHTML += `
                <div class="swiper-slide">
                    <img src="images/${file}"/>
                </div>
            `;
        });

        // ↓↓↓ СТАВИМ SWIPER ТОЛЬКО ПОСЛЕ ЗАГРУЗКИ СЛАЙДОВ
        new Swiper(".swiper", {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            speed: 500,

            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },

            keyboard: true
        });
    })
    .catch(err => console.error("AJAX error:", err));
</script>

</body>
</html>
