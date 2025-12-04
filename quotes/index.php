<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AJAX Slider With Themes</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
:root {
    --bg: #191919;
    --fg: #fff;
}

.light {
    --bg: #fff;
    --fg: #191919;
}

body {
    margin: 0;
    background: var(--bg);
    transition: background 0.3s;
}

.swiper {
    width: 100vw;
    height: 100vh;
}

.swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg);
}

.swiper-slide img {
    width: 90%;
    height: 90%;
    object-fit: contain;
    border-radius: 12px;
}

/* убираем точки */
.swiper-pagination { display: none !important; }

/* стрелки swiper */
.swiper-button-next,
.swiper-button-prev {
    color: var(--fg) !important;
    fill: var(--fg) !important;
    stroke: var(--fg) !important;
    z-index: 5;
}

/* переключатель темы */
#themeToggle {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--fg);
    color: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
    z-index: 99999;
    transition: 0.2s;
}
</style>

</head>
<body>

<div class="swiper">
    <div class="swiper-wrapper" id="slider-wrapper"></div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<div id="themeToggle">🌓</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// ----------------------
// ТЕМА ПО УМОЛЧАНИЮ
// ----------------------
if (window.matchMedia("(prefers-color-scheme: light)").matches) {
    document.body.classList.add("light");
}

document.getElementById("themeToggle").onclick = () => {
    document.body.classList.toggle("light");
};

// ----------------------
// AJAX ЗАГРУЗКА КАРТИНОК
// ----------------------
fetch("load_images.php")
    .then(r => r.json())
    .then(files => {
        const wrapper = document.getElementById("slider-wrapper");

        files.forEach(file => {
            wrapper.innerHTML += `
                <div class="swiper-slide">
                    <img src="images/${file}">
                </div>
            `;
        });

        new Swiper(".swiper", {
            loop: true,
            autoplay: {
                delay: 15000, // 15 секунд
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
