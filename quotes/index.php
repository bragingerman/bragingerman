<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<title>Slider</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
:root {
    --dark-bg: #191919;
    --light-bg: #ffffff;

    --dark-color: #ffffff;
    --light-color: #191919;
}

/* Тёмная тема */
html[data-theme="dark"] {
    --bg: var(--dark-bg);
    --color: var(--dark-color);
}

/* Светлая тема */
html[data-theme="light"] {
    --bg: var(--light-bg);
    --color: var(--light-color);
}

body {
    margin: 0;
    background: var(--bg);
    color: var(--color);
    transition: background 0.3s, color 0.3s;
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

/* Отключаем стандартные кнопки Swiper */
.swiper-button-next,
.swiper-button-prev {
    background: none !important;
    width: 60px !important;
    height: 60px !important;
}

/* SVG стрелки */
.arrow {
    width: 60px;
    height: 60px;
    fill: var(--color);
    transition: fill 0.3s;
}

/* Переключатель темы */
#theme-toggle {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 45px;
    height: 45px;
    background: var(--bg);
    border: 2px solid var(--color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s, border 0.3s;
}

#theme-toggle svg {
    width: 25px;
    height: 25px;
    fill: var(--color);
    transition: fill 0.3s;
}

.swiper-pagination { display: none !important; }
</style>

</head>
<body>

<!-- Переключатель темы -->
<div id="theme-toggle">
    <svg id="theme-icon" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="5"></circle>
    </svg>
</div>

<!-- Слайдер -->
<div class="swiper">
    <div class="swiper-wrapper" id="slider-wrapper"></div>

    <div class="swiper-button-next">
        <svg class="arrow" viewBox="0 0 24 24">
            <path d="M8 4l8 8-8 8"></path>
        </svg>
    </div>

    <div class="swiper-button-prev">
        <svg class="arrow" viewBox="0 0 24 24">
            <path d="M16 4L8 12l8 8"></path>
        </svg>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Определяем системную тему
const prefers = window.matchMedia("(prefers-color-scheme: light)").matches ? "light" : "dark";
document.documentElement.setAttribute("data-theme", prefers);

// Переключатель темы
document.getElementById("theme-toggle").onclick = () => {
    const current = document.documentElement.getAttribute("data-theme");
    document.documentElement.setAttribute("data-theme", current === "dark" ? "light" : "dark");
};

// Загрузка списка изображений
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
