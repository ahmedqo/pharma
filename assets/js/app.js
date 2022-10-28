var PRODUCTS = [],
    BRANDS = [];

Array.prototype.sample = function(n = 1) {
    const v = n;
    var result = new Array(n),
        len = this.length,
        taken = new Array(len);
    if (n > len) {
        n = len;
        result = new Array(n);
    }
    while (n--) {
        var x = Math.floor(Math.random() * len);
        result[n] = this[x in taken ? taken[x] : x];
        taken[x] = --len in taken ? taken[len] : len;
    }
    return v > 1 ? result : result[0];
};

String.prototype.clean = function() {
    var str = this.replaceAll("", "");
    str = str.replaceAll(/’/g, '');
    str = str.replaceAll(/‘/g, '');
    str = str.replaceAll(/"/g, '');
    str = str.replaceAll(/'/g, '');
    str = str.replaceAll(/\//g, '');
    str = str.replaceAll(/[-]/g, ' ');
    str = str.replaceAll(/:/g, '-');
    str = str.replaceAll(/  */g, '-');
    str = str.replaceAll(/\\s+/g, '-');
    return str;
}

const CARDS = {
        Product: (product, box = false) => `
            <div data-js-mounted="true" class="${box ? "box w-56": "w-033 w-025"} product relative overflow-hidden rounded-2xl shadow-md flex flex-col border border-gray-100 bg-white">
                <div class="w-full prod flex items-center justify-center overflow-hidden p-2">
                    <img src="${product.image}" alt="${product.title}" class="block max-w-full max-h-full object-cover" />
                </div>
                <div class="flex w-full flex-1 flex-col items-center p-4 pt-0 justify-between gap-4">
                    <h1 data-translate-id="product.pro-${product.id}.title" class="text-gray-800 text-sm lg:text-lg text-center w-full ${box ? "line": ""}">
                        ${product.title}
                    </h1>
                    <a data-translate-id="extra.detail" href="${product.path}" class="text-white block text-md px-4 py-2 bg-grade rounded-md">DETAIL</a>
                </div>
            </div>
        `,
        Brand: {
            WithName: (name) => `
                <a data-js-mounted="true" href="/brands/${name.clean()}.html" class="flex flex-col gap-2 w-40 lg:w-56">
                    <div class="w-40 h-40 lg:w-56 lg:h-56 overflow-hidden border border-gray-100 shadow-lg rounded-2xl flex items-center justify-center bg-white p-2 lg:p-4">
                        <img src="/assets/brands/${name.clean()}.png" class="block max-w-full max-w-full" />
                    </div>
                    <h1 class="text-md text-center text-break">
                        ${name}
                    </h1>
                </a>
            `,
            Blank: (name) => `
                <a
                    data-js-mounted="true"
                    href="/brands/${name.clean()}.html"
                    class="box w-40 h-40 lg:w-56 lg:h-56 border border-gray-100 shadow-lg rounded-2xl p-4 flex items-center justify-center bg-white"
                >
                    <img src="/assets/brands/${name.clean()}.png" class="block max-h-full max-w-full" />
                </a>
            `
        }
    },
    Element = {
        Header: (active) => {
            const html = `
                <header data-js-mounted="true" id="header">
                    <div class="w-full back lg:hidden mb-4 back" style="background-image: url(/assets/image-1.gif);">
                        <div class="w-full p-4 flex gap-8 items-center container mx-auto justify-between flex-col">
                            <a href="/index.html" class="w-56 logo__header">
                                <img src="/assets/logo.png" alt="logo" class="w-full block" />
                            </a>
                            <div class="flex w-full mx-auto gap-4 lg:hidden">
                                <div class="w-full text-center">
                                    <h1 class="w-max mx-auto text-4xl md:text-7xl font-black text-grade text__h1">
                                        PROMED PLANET
                                    </h1>
                                    <h2 data-translate-id="title" class="text-xl md:text-3xl text-white font-semibold text__h2">
                                        Votre materiel medical et para medical professionel
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative px-4 mx-auto flex-col lg:gap-4 lg:flex-row container flex items-center justify-between">
                        <a href="/index.html" class="hidden lg:block w-20 lg:w-48">
                            <img src="/assets/logo.png" alt="logo" class="w-full block" />
                        </a>
                        <div class="flex w-full lg:w-max flex-row lg:flex-col lg:gap-4 items-start lg:items-end">
                            <div class="w-full lg:w-max gap-2 flex flex-wrap items-stretch">
                                <div class="bg-gray-100 flex gap-2 px-2 h-10 rounded-md items-center flex-1 w-0 lg:w-max">
                                    <span class="block text-prime">
                                            <svg class="block w-4 h-4 lg:h-6 lg:w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                                <path
                                                    d="M39.7 43.2 26.3 29.8q-1.5 1.15-3.425 1.8-1.925.65-4.225.65-5.75 0-9.675-3.925Q5.05 24.4 5.05 18.75q0-5.65 3.925-9.575Q12.9 5.25 18.55 5.25q5.65 0 9.575 3.925Q32.05 13.1 32.05 18.75q0 2.3-.65 4.175T29.5 26.5L43 39.9ZM18.55 27.7q3.8 0 6.375-2.575T27.5 18.75q0-3.75-2.575-6.35Q22.35 9.8 18.6 9.8q-3.85 0-6.425 2.6Q9.6 15 9.6 18.75q0 3.8 2.575 6.375T18.55 27.7Z"
                                                ></path>
                                            </svg>
                                        </span>
                                    <input data-translate-id="extra.search" onkeydown="search(event)" type="text" class="h-full text-sm lg:text-xl bg-transparent flex-1 outline-none" placeholder="RECHERCHER" />
                                </div>
        
                                <button id="nav-btn" onclick="(() => {show('#tar');show('#nav-btn path');})()" class="block p-1 bg-grade text-white rounded-md lg:hidden">
                                        <svg class="block h-8 w-8 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path d="M6 36V33H42V36ZM6 25.5V22.5H42V25.5ZM6 15V12H42V15Z"></path>
                                            <path
                                                class="hidden"
                                                d="M12.45 37.65 10.35 35.55 21.9 24 10.35 12.45 12.45 10.35 24 21.9 35.55 10.35 37.65 12.45 26.1 24 37.65 35.55 35.55 37.65 24 26.1Z"
                                            ></path>
                                        </svg>
                                    </button>
                            </div>
                            <nav class="container mx-auto w-0 lg:w-max">
                                <ul id="tar" class="hidden items-center justify-end gap-6 p-4 lg:p-0 shadow-lg lg:shadow-none bg-white lg:bg-transparent flex lg:flex flex-col lg:flex-row absolute lg:relative left-0 right-0 top-full lg:top-0 mt-1 lg:mt-0 z-100">
                                    <li>
                                        <a href="/index.html" class="${active === 1 ? "active" : "" } link w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                                            <span data-translate-id="menu.home">ACCUEIL</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/products.html" class="${active === 2 ? "active" : "" } link w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                                            <span data-translate-id="menu.product">NOS PRODUITS</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/brands.html" class="${active === 3 ? "active" : "" } link w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                                            <span data-translate-id="menu.brand">MARQUES</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/news.html" class="${active === 4 ? "active" : "" } link w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                                            <span data-translate-id="menu.news">NOUVEAUTES</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/contact.html" class="${active === 5 ? "active" : "" } link w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                                            <span data-translate-id="menu.contact">CONTACT</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/catalog.html" class="w-max flex items-center gap-2 text-sm font-semibold text-white bg-prime rounded-md px-4 py-2 bg-prime bg-grade">
                                            <span data-translate-id="menu.download">TELECHARGER CATALOGUE</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </header>
            `;
            const div = document.createElement("div");
            div.innerHTML = html;
            document.querySelector("#header").replaceWith(div.querySelector("*"));
        },
        Slider: async(text = false) => {
                const req = await fetch("/data/images.php");
                const images = await req.json() || [];
                const html = /*html*/ `
                    <section data-js-mounted="true" id="slider" class="px-4 mx-auto container mt-4">
                        <div class="relative w-full rounded-2xl bg-gray-200 flex flex-col gap-10 lg:gap-16 h-56 lg:h-96 overflow-hidden shadow-md border border-gray-200">
                            <div class="slider w-full h-full relative overflow-hidden">
                                ${images.length > 1 ?
                                    images.map(img => ` 
                                        <div class="slide w-full h-full p-2 active bg-white flex items-center justify-center">
                                            <img class="block max-w-full max-h-full" src="${img}" />
                                        </div>
                                        `
                    ).join("")
                    : ` 
                        <div class="w-full h-full p-2 active bg-white flex items-center justify-center">
                            <img class="block max-w-full max-h-full" src="${images[0]}" />
                        </div>
                    `
                                }
                                <div class="controls">
                                    <a href="javascript:void(0)" id="click-left"></a>
                                    <a href="javascript:void(0)" id="click-right"></a>
                                </div>
                            </div>
                            ${text ? `
                                <div data-translate-id="text.slide" class="absolute bottom-0 left-0 w-full bg-green-600 bg-opacity-80 text-center text-white text-md lg:text-xl px-4 py-2 z-100">
                                    Ensemble pour un Médical Meilleur
                                </div>
                            `: ""}
                        </div>
                    </section>
            `;
            const div = document.createElement("div");
            div.innerHTML = html;
            document.querySelector("#slider").replaceWith(div.querySelector("*"));
            slide();
        },
        Footer: (text = false) => {
            const html = /*html*/ `
                <footer data-js-mounted="true" id="footer" class="">
                    <section class="flex flex-col gap-10 lg:gap-20 2xl:px-4">
                        ${!text ? `
                            <div class="w-full flex flex-col gap-2 items-center">
                                <h1 data-translate-id="header.cords" class="text-3xl lg:text-6xl text-center container mx-auto px-4">
                                    NOS COORDONNEES
                                </h1>
                                <span class="w-16 lg:w-24 h-1 bg-grade rounded-full"></span>
                            </div>
                        ` : ""}
                        <div class="overflow-hidden bg-gray-500 2xl:container 2xl:mx-auto 2xl:rounded-xl">
                            <iframe class="h-96 w-full" frameborder="0" src="https://maps.google.com/maps?q=32.33048629760742,-6.370458602905273&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                loading="lazy"></iframe>
                        </div>
                    </section>
                    <section class="container mx-auto mt-4 lg:mt-8 flex flex-col lg:flex-row lg:flex-wrap gap-4 lg:gap-8 lg:items-start p-4">
                        <div class="w-full flex flex-col gap-6 justify-center lg:flex-1">
                            <div>
                                <h1 data-translate-id="header.comercial" class="text-2xl">Direction Commerciale</h1>
                                <div class="border-b-2 border-prime w-20"></div>
                            </div>
                            <div class="flex flex-col gap-4">
                                <a href="" class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path d="M24 37.05q-7.3-5.45-10.775-10.325Q9.75 21.85 9.75 17q0-3.6 1.3-6.325t3.3-4.55q2-1.825 4.55-2.8 2.55-.975 5.1-.975 2.5 0 5.075.975t4.575 2.8q2 1.825 3.325 4.55Q38.3 13.4 38.3 17q0 4.85-3.5 9.725T24 37.05Zm.05-16.1q1.8 0 3.025-1.275Q28.3
                        18.4 28.3 16.65q0-1.8-1.25-3.05-1.25-1.25-3-1.25t-3.025 1.275Q19.75 14.9 19.75 16.65q0 1.8 1.275 3.05 1.275 1.25 3.025 1.25Zm-14.3 24.6V41H38.3v4.55Z"></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <span>ETG 1 AV MED V° HAY CHARAF AIT EL KORCHI PRES DE LA GENDARMERIE ROYALE BENI MELLAL</span>
                                    </div>
                                </a>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path d="M7.5 41.1q-1.85 0-3.2-1.375T2.95 36.55v-25.1q0-1.8 1.35-3.2 1.35-1.4 3.2-1.4h33q1.85 0 3.225 1.4t1.375 3.2v25.1q0 1.8-1.375 3.175Q42.35 41.1 40.5 41.1ZM24 26.35 40.5 15.2v-3.75L24 22.35 7.5 11.45v3.75Z"></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="mailto:commerciale@promedplanet.com">
                                            commerciale@promedplanet.com
                                        </a>
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path d="M39.6 42.95q-6.25 0-12.45-3.075-6.2-3.075-11.125-7.975-4.925-4.9-8-11.15T4.95 8.35q0-1.4 1-2.425T8.4 4.9h6.75q1.6 0 2.575.8.975.8 1.275 2.35l1.35 5.3q.2 1.4-.1 2.375-.3.975-1.1 1.675L14 22.1q2.5 3.95 5.45 6.825T26 33.85l4.95-4.95q.85-.9
                        1.9-1.25 1.05-.35 2.35-.05l4.7 1.15q1.55.4 2.35 1.425t.8 2.525v6.75q0 1.5-1 2.5t-2.45 1Z"></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="tel:+212661472534">
                                                +212661472534
                                            </a>
                                        <a href="tel:+212523483595">
                                            +212523483595
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col gap-6 justify-center lg:flex-1">
                            <div>
                                <h1 data-translate-id="header.direction" class="text-2xl">Direction General</h1>
                                <div class="border-b-2 border-prime w-20"></div>
                            </div>
                            <div class="flex flex-col gap-4">
                                <a href="" class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path
                                                d="M24 37.05q-7.3-5.45-10.775-10.325Q9.75 21.85 9.75 17q0-3.6 1.3-6.325t3.3-4.55q2-1.825 4.55-2.8 2.55-.975 5.1-.975 2.5 0 5.075.975t4.575 2.8q2 1.825 3.325 4.55Q38.3 13.4 38.3 17q0 4.85-3.5 9.725T24 37.05Zm.05-16.1q1.8 0 3.025-1.275Q28.3
                        18.4 28.3 16.65q0-1.8-1.25-3.05-1.25-1.25-3-1.25t-3.025 1.275Q19.75 14.9 19.75 16.65q0 1.8 1.275 3.05 1.275 1.25 3.025 1.25Zm-14.3 24.6V41H38.3v4.55Z"
                                            ></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <span>ETG 1 AV MED V° HAY CHARAF AIT EL KORCHI PRES DE LA GENDARMERIE ROYALE BENI MELLAL</span>
                                    </div>
                                </a>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path
                                                d="M7.5 41.1q-1.85 0-3.2-1.375T2.95 36.55v-25.1q0-1.8 1.35-3.2 1.35-1.4 3.2-1.4h33q1.85 0 3.225 1.4t1.375 3.2v25.1q0 1.8-1.375 3.175Q42.35 41.1 40.5 41.1ZM24 26.35 40.5 15.2v-3.75L24 22.35 7.5 11.45v3.75Z"
                                            ></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="mailto:import@promedplanet.com">
                                                import@promedplanet.com
                                            </a>
                                        <a href="mailto:direction@promedplanet.com">
                                            direction@promedplanet.com
                                        </a>
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path
                                                d="M39.6 42.95q-6.25 0-12.45-3.075-6.2-3.075-11.125-7.975-4.925-4.9-8-11.15T4.95 8.35q0-1.4 1-2.425T8.4 4.9h6.75q1.6 0 2.575.8.975.8 1.275 2.35l1.35 5.3q.2 1.4-.1 2.375-.3.975-1.1 1.675L14 22.1q2.5 3.95 5.45 6.825T26 33.85l4.95-4.95q.85-.9
                        1.9-1.25 1.05-.35 2.35-.05l4.7 1.15q1.55.4 2.35 1.425t.8 2.525v6.75q0 1.5-1 2.5t-2.45 1Z"
                                            ></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="tel:+212661472534">
                                                +212661472534
                                            </a>
                                        <a href="tel:+212523483595">
                                            +212523483595
                                        </a>
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 308 308">
                                            <path
                                                d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156
                                        c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687
                                        c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887
                                        c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153
                                        c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348
                                        c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802
                                        c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922
                                        c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0
                                        c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458
                                        C233.168,179.508,230.845,178.393,227.904,176.981z"
                                            />
                                            <path
                                                d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716
                                        c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396
                                        c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z
                                        M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188
                                        l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677
                                        c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867
                                        C276.546,215.678,222.799,268.994,156.734,268.994z"
                                            />
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="https://api.whatsapp.com/send?phone=212661472534">
                                                +212661472534
                                            </a>
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <a href="https://www.facebook.com/promedplanet" class="block">
                                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 30 31">
                                                <path
                                                    d="M3.33333 0.250977H26.6667C27.5507 0.250977 28.3986 0.602166 29.0237 1.22729C29.6488 1.85241 30 2.70025 30 3.58431V26.9176C30 27.8017 29.6488 28.6495 29.0237 29.2747C28.3986 29.8998 27.5507 30.251 26.6667 30.251H3.33333C2.44928 30.251 1.60143 29.8998 0.976311 29.2747C0.351189 28.6495 0 27.8017 0 26.9176V3.58431C0 2.70025 0.351189 1.85241 0.976311 1.22729C1.60143 0.602166 2.44928 0.250977 3.33333 0.250977ZM25 3.58431H20.8333C19.2862 3.58431 17.8025 4.19889 16.7085 5.29285C15.6146 6.38682 15 7.87055 15 9.41764V13.5843H11.6667V18.5843H15V30.251H20V18.5843H25V13.5843H20V10.251C20 9.80895 20.1756 9.38503 20.4882 9.07246C20.8007 8.7599 21.2246 8.58431 21.6667 8.58431H25V3.58431Z"
                                                ></path>
                                            </svg>
                                    </a>
                                    <a href="https://www.instagram.com/p/ChUQWzDjMID/?igshid=MDJmNzVkMjY=" class="block">
                                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 30 31">
                                                <path
                                                    d="M8.7 0.250977H21.3C26.1 0.250977 30 4.15098 30 8.95098V21.551C30 23.8584 29.0834 26.0712 27.4518 27.7028C25.8203 29.3344 23.6074 30.251 21.3 30.251H8.7C3.9 30.251 0 26.351 0 21.551V8.95098C0 6.64359 0.916605 4.43071 2.54817 2.79915C4.17974 1.16758 6.39262 0.250977 8.7 0.250977ZM8.4 3.25098C6.96783 3.25098 5.59432 3.8199 4.58162 4.8326C3.56893 5.8453 3 7.21881 3 8.65098V21.851C3 24.836 5.415 27.251 8.4 27.251H21.6C23.0322 27.251 24.4057 26.6821 25.4184 25.6694C26.4311 24.6567 27 23.2831 27 21.851V8.65098C27 5.66598 24.585 3.25098 21.6 3.25098H8.4ZM22.875 5.50098C23.3723 5.50098 23.8492 5.69852 24.2008 6.05015C24.5525 6.40178 24.75 6.8787 24.75 7.37598C24.75 7.87326 24.5525 8.35017 24.2008 8.7018C23.8492 9.05343 23.3723 9.25098 22.875 9.25098C22.3777 9.25098 21.9008 9.05343 21.5492 8.7018C21.1975 8.35017 21 7.87326 21 7.37598C21 6.8787 21.1975 6.40178 21.5492 6.05015C21.9008 5.69852 22.3777 5.50098 22.875 5.50098ZM15 7.75098C16.9891 7.75098 18.8968 8.54115 20.3033 9.94768C21.7098 11.3542 22.5 13.2619 22.5 15.251C22.5 17.2401 21.7098 19.1478 20.3033 20.5543C18.8968 21.9608 16.9891 22.751 15 22.751C13.0109 22.751 11.1032 21.9608 9.6967 20.5543C8.29018 19.1478 7.5 17.2401 7.5 15.251C7.5 13.2619 8.29018 11.3542 9.6967 9.94768C11.1032 8.54115 13.0109 7.75098 15 7.75098ZM15 10.751C13.8065 10.751 12.6619 11.2251 11.818 12.069C10.9741 12.9129 10.5 14.0575 10.5 15.251C10.5 16.4445 10.9741 17.589 11.818 18.433C12.6619 19.2769 13.8065 19.751 15 19.751C16.1935 19.751 17.3381 19.2769 18.182 18.433C19.0259 17.589 19.5 16.4445 19.5 15.251C19.5 14.0575 19.0259 12.9129 18.182 12.069C17.3381 11.2251 16.1935 10.751 15 10.751Z"
                                                ></path>
                                            </svg>
                                    </a>
                                    <a href="https://twitter.com/promedplanet?t=Drmu5dGXWR_G_aDnhV_ysA&s=09" class="block">
                                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 310 310">
                                                <path
                                                    d="M302.973,57.388c-4.87,2.16-9.877,3.983-14.993,5.463c6.057-6.85,10.675-14.91,13.494-23.73
                                            c0.632-1.977-0.023-4.141-1.648-5.434c-1.623-1.294-3.878-1.449-5.665-0.39c-10.865,6.444-22.587,11.075-34.878,13.783
                                            c-12.381-12.098-29.197-18.983-46.581-18.983c-36.695,0-66.549,29.853-66.549,66.547c0,2.89,0.183,5.764,0.545,8.598
                                            C101.163,99.244,58.83,76.863,29.76,41.204c-1.036-1.271-2.632-1.956-4.266-1.825c-1.635,0.128-3.104,1.05-3.93,2.467
                                            c-5.896,10.117-9.013,21.688-9.013,33.461c0,16.035,5.725,31.249,15.838,43.137c-3.075-1.065-6.059-2.396-8.907-3.977
                                            c-1.529-0.851-3.395-0.838-4.914,0.033c-1.52,0.871-2.473,2.473-2.513,4.224c-0.007,0.295-0.007,0.59-0.007,0.889
                                            c0,23.935,12.882,45.484,32.577,57.229c-1.692-0.169-3.383-0.414-5.063-0.735c-1.732-0.331-3.513,0.276-4.681,1.597
                                            c-1.17,1.32-1.557,3.16-1.018,4.84c7.29,22.76,26.059,39.501,48.749,44.605c-18.819,11.787-40.34,17.961-62.932,17.961
                                            c-4.714,0-9.455-0.277-14.095-0.826c-2.305-0.274-4.509,1.087-5.294,3.279c-0.785,2.193,0.047,4.638,2.008,5.895
                                            c29.023,18.609,62.582,28.445,97.047,28.445c67.754,0,110.139-31.95,133.764-58.753c29.46-33.421,46.356-77.658,46.356-121.367
                                            c0-1.826-0.028-3.67-0.084-5.508c11.623-8.757,21.63-19.355,29.773-31.536c1.237-1.85,1.103-4.295-0.33-5.998
                                            C307.394,57.037,305.009,56.486,302.973,57.388z"
                                                />
                                            </svg>
                                    </a>
                                    <a href="https://t.me/promedplanet" class="block">
                                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10,0 C15.5228475,0 20,4.4771525 20,10 C20,15.5228475 15.5228475,20 10,20 C4.4771525,20 0,15.5228475 0,10 C0,4.4771525 4.4771525,0 10,0 Z M14.4415206,6 C14.060553,6.00676048 13.476055,6.20741135 10.663148,7.36249773 C9.67796175,7.7670526 7.70897661,8.60437935 4.75619264,9.87447795 C4.27670659,10.0627254 4.02553067,10.2468857 4.00266485,10.4269588 C3.95876487,10.7726802 4.46291296,10.8803081 5.09723696,11.0838761 C5.61440201,11.2498453 6.31007997,11.4440124 6.67173438,11.4517262 C6.99978943,11.4587234 7.36593635,11.3251987 7.77017511,11.051152 C10.5290529,9.21254679 11.9531977,8.28322679 12.0426094,8.26319203 C12.1056879,8.24905787 12.1930992,8.23128593 12.2523244,8.28325656 C12.3115496,8.33522719 12.3057275,8.43364956 12.299454,8.46005377 C12.2492926,8.67117474 9.65764825,10.998457 9.50849738,11.1513987 L9.43697409,11.2233057 C8.88741493,11.7661123 8.33196049,12.1205055 9.290333,12.7440164 C10.155665,13.3069957 10.6592923,13.6661378 11.5507686,14.2430701 C12.1204738,14.6117635 12.5671299,15.0489784 13.1553348,14.9955401 C13.4259939,14.9709508 13.705567,14.7196888 13.8475521,13.9703127 C14.1831052,12.1993135 14.8426779,8.36209709 14.9951103,6.78087197 C15.0084653,6.64233621 14.9916649,6.46503787 14.9781732,6.38720805 C14.9646815,6.30937823 14.9364876,6.19848702 14.8340164,6.11639754 C14.7126597,6.01917896 14.5253109,5.99867765 14.4415206,6 Z"
                                                />
                                            </svg>
                                    </a>
                                    <a href="https://www.tiktok.com/@promedplanet?_t=8VMGvDfLeOD&_r=1" class="block">
                                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 32 32">
                                                <path
                                                    d="M16.708 0.027c1.745-0.027 3.48-0.011 5.213-0.027 0.105 2.041 0.839 4.12 2.333 5.563 1.491 1.479 3.6 2.156 5.652 2.385v5.369c-1.923-0.063-3.855-0.463-5.6-1.291-0.76-0.344-1.468-0.787-2.161-1.24-0.009 3.896 0.016 7.787-0.025 11.667-0.104 1.864-0.719 3.719-1.803 5.255-1.744 2.557-4.771 4.224-7.88 4.276-1.907 0.109-3.812-0.411-5.437-1.369-2.693-1.588-4.588-4.495-4.864-7.615-0.032-0.667-0.043-1.333-0.016-1.984 0.24-2.537 1.495-4.964 3.443-6.615 2.208-1.923 5.301-2.839 8.197-2.297 0.027 1.975-0.052 3.948-0.052 5.923-1.323-0.428-2.869-0.308-4.025 0.495-0.844 0.547-1.485 1.385-1.819 2.333-0.276 0.676-0.197 1.427-0.181 2.145 0.317 2.188 2.421 4.027 4.667 3.828 1.489-0.016 2.916-0.88 3.692-2.145 0.251-0.443 0.532-0.896 0.547-1.417 0.131-2.385 0.079-4.76 0.095-7.145 0.011-5.375-0.016-10.735 0.025-16.093z"
                                                />
                                            </svg>
                                    </a>
                                    <a href="https://youtube.com/channel/UCeCqRY24hOKxx3DZbDLaK3g" class="block">
                                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 310 310">
                                                <path
                                                    d="M297.917,64.645c-11.19-13.302-31.85-18.728-71.306-18.728H83.386c-40.359,0-61.369,5.776-72.517,19.938
                                            C0,79.663,0,100.008,0,128.166v53.669c0,54.551,12.896,82.248,83.386,82.248h143.226c34.216,0,53.176-4.788,65.442-16.527
                                            C304.633,235.518,310,215.863,310,181.835v-53.669C310,98.471,309.159,78.006,297.917,64.645z M199.021,162.41l-65.038,33.991
                                            c-1.454,0.76-3.044,1.137-4.632,1.137c-1.798,0-3.592-0.484-5.181-1.446c-2.992-1.813-4.819-5.056-4.819-8.554v-67.764
                                            c0-3.492,1.822-6.732,4.808-8.546c2.987-1.814,6.702-1.938,9.801-0.328l65.038,33.772c3.309,1.718,5.387,5.134,5.392,8.861
                                            C204.394,157.263,202.325,160.684,199.021,162.41z"
                                                />
                                            </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col gap-6 justify-center lg:flex-1">
                            <div>
                                <h1 data-translate-id="header.partner" class="text-2xl">Nos Partenaire</h1>
                                <div class="border-b-2 border-prime w-20"></div>
                            </div>
                            <div class="flex flex-col gap-4">
                                <h1 class="text-2xl text-grade text-stroke">
                                    PROMED PLANET COMPANY
                                </h1>
                                <h2 class="text-xl">
                                    FERDOUS Chorouk
                                </h2>
                                <a href="https://www.google.com/maps/place/Via+Falcone+e+Borsellino,+3,+20090+Segrate+MI,+Italy/@45.5034384,9.2779016,17z/data=!3m1!4b1!4m5!3m4!1s0x4786c7dc6dd5ca3b:0x8b87b94ffc366824!8m2!3d45.5034347!4d9.2821288" class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path
                                                d="M24 37.05q-7.3-5.45-10.775-10.325Q9.75 21.85 9.75 17q0-3.6 1.3-6.325t3.3-4.55q2-1.825 4.55-2.8 2.55-.975 5.1-.975 2.5 0 5.075.975t4.575 2.8q2 1.825 3.325 4.55Q38.3 13.4 38.3 17q0 4.85-3.5 9.725T24 37.05Zm.05-16.1q1.8 0 3.025-1.275Q28.3
                        18.4 28.3 16.65q0-1.8-1.25-3.05-1.25-1.25-3-1.25t-3.025 1.275Q19.75 14.9 19.75 16.65q0 1.8 1.275 3.05 1.275 1.25 3.025 1.25Zm-14.3 24.6V41H38.3v4.55Z"
                                            ></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <span>Via Falcone e Borsellino N3 segrate Milano Italy</span>
                                    </div>
                                </a>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 308 308">
                                            <path
                                                d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156
                                    c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687
                                    c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887
                                    c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153
                                    c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348
                                    c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802
                                    c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922
                                    c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0
                                    c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458
                                    C233.168,179.508,230.845,178.393,227.904,176.981z"
                                            />
                                            <path
                                                d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716
                                    c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396
                                    c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z
                                    M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188
                                    l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677
                                    c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867
                                    C276.546,215.678,222.799,268.994,156.734,268.994z"
                                            />
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="https://api.whatsapp.com/send?phone=393881269567">
                                                +393881269567
                                            </a>
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-wrap items-center">
                                    <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                            <path
                                                d="M7.5 41.1q-1.85 0-3.2-1.375T2.95 36.55v-25.1q0-1.8 1.35-3.2 1.35-1.4 3.2-1.4h33q1.85 0 3.225 1.4t1.375 3.2v25.1q0 1.8-1.375 3.175Q42.35 41.1 40.5 41.1ZM24 26.35 40.5 15.2v-3.75L24 22.35 7.5 11.45v3.75Z"
                                            ></path>
                                        </svg>
                                    <div class="flex flex-col flex-1">
                                        <a href="mailto:f.chorouk@promedplanet.com">
                                                f.chorouk@promedplanet.com
                                            </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div onclick="show('#vid')" class="cursor-pointer w-full lg:w-32 flex flex-col gap-6 justify-center items-center">
                            <img src="/assets/eco.webp" class="block max-w-full" />
                            <img src="/assets/ship.png" class="block max-w-full" />
                        </div>
                    </section>
                    <section class="fixed w-max bottom-4 right-4 z-100">
                        <div id="lang" style="display: none;" class="w-40 bg-white absolute right-0 bottom-full mb-2 rounded-2xl shadow-lg flex flex-col overflow-hidden border border-gray-200">
                            <button onclick="Translate.run(event, '#lang-btn')" data-translate-id="extra.english" data-lang="en" class="block text-gray-800 cursor-pointer border-b border-gray-200 py-2 px-4 text-sm font-black hover:bg-gray-200">
                                    Englais
                                </button>
                            <button onclick="Translate.run(event, '#lang-btn')" data-translate-id="extra.french" data-lang="fr" class="block text-gray-800 cursor-pointer border-b border-gray-200 py-2 px-4 text-sm font-black hover:bg-gray-200">
                                    Francais
                                </button>
                            <button onclick="Translate.run(event, '#lang-btn')" data-translate-id="extra.italy" data-lang="it" class="block text-gray-800 cursor-pointer border-b border-gray-200 py-2 px-4 text-sm font-black hover:bg-gray-200">
                                    Italy
                                </button>
                            <button onclick="Translate.run(event, '#lang-btn')" data-translate-id="extra.arabic" data-lang="ar" class="block text-gray-800 cursor-pointer py-2 px-4 text-sm font-black hover:bg-gray-200">
                                    Arabic
                                </button>
                        </div>
                        <button id="lang-btn" onclick="Translate.toggle('#lang')" class="block text-white cursor-pointer bg-grade py-2 px-4 text-sm font-black rounded-2xl">
                                Francais
                            </button>
                    </section>
                    <section id="vid" class="fixed hidden w-full h-full inset-0 p-4 lg:p-10 bg-gray-900 bg-opacity-50 z-100 flex items-center justify-center">
                        <button onclick="show('#vid')" class="block text-white absolute top-4 right-4 cursor-pointer z-100">
                                <svg class="block h-10 w-10 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                                    <path
                                        d="M12.45 37.65 10.35 35.55 21.9 24 10.35 12.45 12.45 10.35 24 21.9 35.55 10.35 37.65 12.45 26.1 24 37.65 35.55 35.55 37.65 24 26.1Z"
                                    ></path>
                                </svg>
                            </button>
                        <video src="/assets/video.mp4" class="w-full rounded-2xl" controls></video>
                    </section>
                </footer>
            `;
            const div = document.createElement("div");
            div.innerHTML = html;
            document.querySelector("#footer").replaceWith(div.querySelector("*"));
        },
        Aside: () => {
            const html = `
                <aside data-js-mounted="true" id="aside" class="hidden flex lg:flex flex-col bg-gray-200 rounded-2xl p-4">
                    <ul class="w-full flex flex-col gap-2">
                        <li class="flex flex-col gap-2">
                            <a href="/categories/mobilier-medical.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-1">MOBILIER MEDICAL</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/etirement-&-evacuation.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-2">ETIREMENT & EVACUATION</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/confort-et-prevention-des-pressions.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-3">CONFORT ET PREVENTION DES PRESSIONS</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/diagnostic-&-equipement.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-4">DIAGNOSTIC & EQUIPEMENT</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/radiologie.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-5">RADIOLOGIE</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/aspiration-doxygenotherapie.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-6">ASPIRATION D'OXYGENOTHERAPIE</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/aide-a-la-marche.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-7">AIDE A LA MARCHE</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/bandages-coussinets-pansements-sparadraps.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-8">BANDAGES - COUSSINETS - PANSEMENTS - SPARADRAPS</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/consommable-danesthesie-chirurgie-reanimation.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-9">CONSOMMABLE D'ANESTHESIE CHIRURGIE REANIMATION</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/hygiene-et-protection-des-consommables-paramediques.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-10">HYGIENE ET PROTECTION DES CONSOMMABLES PARAMEDIQUES</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/equipement-de-sterilisation-en-acier-inoxydable.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-11">EQUIPEMENT DE STERILISATION EN ACIER INOXYDABLE</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/anatomie-humaine.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-12">ANATOMIE HUMAINE</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/instrumentation.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-13">INSTRUMENTATION</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/traumatologie-&-orthopedie.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-14">TRAUMATOLOGIE & ORTHOPEDIE</span>
                            </a>
                            <div class="w-full border-t border-gray-400"></div>
                        </li>
                        <li class="flex flex-col gap-2">
                            <a href="/categories/vetements-medicaux.html" class="flex gap-2 items-center text-sm">
                                <span data-translate-id="category.item-15">VETEMENTS MEDICAUX<</span>
                            </a>
                        </li>
                    </ul>
                </aside>
            `;
            const div = document.createElement("div");
            div.innerHTML = html;
            document.querySelector("#aside").replaceWith(div.querySelector("*"));
        }
    }

async function _print(e) {
    e.target.classList.add("hidden");
    var divContents = document.getElementById("print").innerHTML;
    e.target.classList.remove("hidden");
    var home = document.body.innerHTML;
    var code = `
            <main class="container mx-auto p-4">
                <img src="/assets/logo.png" alt="logo" width="200" class="mx-auto block" />
                <div class="h-10"></div>
                <section class="w-full flex flex-col lg:flex-row lg:flex-wrap gap-4">
                    ${divContents}
                </section>
            </main>
    `
    document.body.innerHTML = code;
    await window.print();
    setTimeout(() => {
        document.body.innerHTML = home;
    }, 500)
}

function slide() {
    let activeSlide = 0;
    const slides = document.querySelectorAll(".slide");

    let left = document.querySelector("#click-left");

    left.onclick = function () {
        updatePrevSlide();
    };

    let right = document.querySelector("#click-right");

    right.onclick = function () {
        updateNextSlide();
    };

    function updateNextSlide() {
        slides[activeSlide].classList.add("prev");
        let nextSlide = activeSlide < slides.length - 1 ? activeSlide + 1 : 0;
        slides[nextSlide].classList.remove("prev");
        slides[nextSlide].classList.remove("next");
        slides[nextSlide].classList.add("active");

        if (nextSlide < slides.length - 1) {
            slides[nextSlide + 1].classList.add("next");
            slides[nextSlide + 1].classList.remove("prev");
        } else {
            slides[0].classList.remove("prev");
            slides[0].classList.add("next");
        }
        activeSlide = nextSlide;
    }

    function updatePrevSlide() {
        slides[activeSlide].classList.add("next");
        let prevSlide = activeSlide > 0 ? activeSlide - 1 : slides.length - 1;
        slides[prevSlide].classList.remove("next");
        slides[prevSlide].classList.remove("prev");
        slides[prevSlide].classList.add("active");

        if (prevSlide > 0) {
            slides[prevSlide - 1].classList.add("prev");
            slides[prevSlide - 1].classList.remove("next");
        } else {
            slides[slides.length - 1].classList.remove("next");
            slides[slides.length - 1].classList.add("prev");
        }

        activeSlide = prevSlide;
    }

    setInterval(() => {
        updateNextSlide();
    }, 7000);
}

function navigate(selector, pos = "next") {
    const slideContainerEl = document.querySelector(selector);
    const slideEl = document.querySelector(selector + " .box");
    let slideWidth = slideEl.offsetWidth;
    slideContainerEl.scrollLeft = getNewScrollPosition(pos, slideContainerEl, slideWidth);
}

function getNewScrollPosition(pos, slideContainerEl, slideWidth) {
    const maxScrollLeft = slideContainerEl.scrollWidth - slideWidth * (window.innerWidth >= 1024 ? 5 : 2);
    if (pos === "next") {
        const x = slideContainerEl.scrollLeft + slideWidth;
        return x <= maxScrollLeft ? x : 0;
    } else {
        const x = slideContainerEl.scrollLeft - slideWidth;
        return x >= 0 ? x : maxScrollLeft;
    }
}

function show(selector) {
    const targets = document.querySelectorAll(selector);
    Array.from(targets).forEach((target) => {
        target.classList.toggle("hidden");
    });
}

function preview(target, selector) {
    const picture = document.querySelector(selector);
    const src = target.querySelector("img").src;
    picture.src = src;
}

function search(event) {
    if (event.keyCode === 13) location.href = "/search.html?" + event.target.value;
}

function style() {
    const css = document.querySelector("#css");
    if (css) {
        const scs = css.cloneNode(true);
        css.remove();
        document.head.appendChild(scs)
    }
}

async function filter(fil, type) {
    await getProd();
    return PRODUCTS.filter(e => e[type] === fil)
}

function animate() {
    const play = () => {
        navigate("#scroller");
        setTimeout(play, 2500);
    };
    const plays = () => {
        navigate("#scroll");
        setTimeout(plays, 3000);
    };
    setTimeout(play, 2500);
    setTimeout(plays, 3000);
}

async function random() {
    await getProd();
    document.querySelector("#display").innerHTML = PRODUCTS.sample(10).map(el => CARDS.Product(el, true)).join("");
    document.querySelector("#display-b").innerHTML = BRANDS.sample(20).map(el => CARDS.Brand.Blank(el)).join("");
}

const populate = {
    Product: async (array) => {
        array = await array;
        document.querySelector("#display").innerHTML = array.map(el => CARDS.Product(el)).join("");
    },
    Brand: async (array) => {
        array = await array;
        document.querySelector("#display").innerHTML = array.map(el => CARDS.Brand.WithName(el)).join("");
    }
}

const Starter = {
    funcs: [],
    add(...functions) {
        for (const func of functions) {
            var exsits;
            for (const fn of this.funcs) {
                if (fn.toString() === func.toString()) {
                    exsits = true;
                    break;
                }
            }
            if (!exsits) this.funcs.push(func);
        }
        return this;
    },
    init() {
        window.addEventListener("DOMContentLoaded", () => {
            for (const func of this.funcs)
                try {
                    func()
                } catch (err) {
                    console.log(err)
                };
        });
    },
};

async function getProd() {
    const req1 = await fetch("/json/products.json");
    const req2 = await fetch("/json/brands.json");
    PRODUCTS = await req1.json();
    BRANDS = await req2.json();
}

window.addEventListener("DOMContentLoaded", () => {
    document.querySelector("#overlay").remove();
});