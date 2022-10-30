<?php
session_start();

if (!isset($_SESSION["logged_in"]) || isset($_GET['logout'])) {
    session_destroy();
    header("location: /a/index.php");
    die();
}

$products = json_decode(file_get_contents("../json/products.json"), true);
$fr = json_decode(file_get_contents("../translate/data-fr.json"), true);
$en = json_decode(file_get_contents("../translate/data-en.json"), true);
$it = json_decode(file_get_contents("../translate/data-it.json"), true);
$ar = json_decode(file_get_contents("../translate/data-ar.json"), true);


if (isset($_GET['remove'])) {
    $pro = array_filter($products, function ($v, $k) {
        return $v["id"] === (int)$_GET['remove'];
    }, ARRAY_FILTER_USE_BOTH);
    unlink("../" . array_values($pro)[0]['path']);
    foreach (explode("|", array_values($pro)[0]['images']) as $img) {
        unlink("../" . $img);
    }
    $prods = array_filter($products, function ($v, $k) {
        return $v["id"] !== (int)$_GET['remove'];
    }, ARRAY_FILTER_USE_BOTH);
    $fr["product"] = array_filter($fr["product"], function ($v, $k) {
        return $k !== "pro-" . $_GET['remove'];
    }, ARRAY_FILTER_USE_BOTH);
    $en["product"] = array_filter($en["product"], function ($v, $k) {
        return $k !== "pro-" . $_GET['remove'];
    }, ARRAY_FILTER_USE_BOTH);
    $it["product"] = array_filter($it["product"], function ($v, $k) {
        return $k !== "pro-" . $_GET['remove'];
    }, ARRAY_FILTER_USE_BOTH);
    $ar["product"] = array_filter($ar["product"], function ($v, $k) {
        return $k !== "pro-" . $_GET['remove'];
    }, ARRAY_FILTER_USE_BOTH);
    file_put_contents("../json/products.json", json_encode($prods), true);
    file_put_contents("../translate/data-fr.json", json_encode($fr), true);
    file_put_contents("../translate/data-en.json", json_encode($en), true);
    file_put_contents("../translate/data-it.json", json_encode($it), true);
    file_put_contents("../translate/data-ar.json", json_encode($ar), true);
    header("location: /a/list.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="fr" class="w-full overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="/assets/css/style.css" />
    <title>LISTE PRODUITS</title>
</head>

<body class="text-gray-800 py-4">
    <header class="w-full flex-col lg:flex-row gap-4 flex container mx-auto px-4 items-center justify-between">
        <a href="/index.html" class="w-32">
            <img src="/assets/logo.png" alt="logo" class="w-full block" />
        </a>
        <nav class="w-full lg:w-max">
            <ul class="flex flex-col w-full lg:flex-row gap-4 items-center">
                <li>
                    <a href="/a/list.php" class="w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                        <span>Liste</span>
                    </a>
                </li>
                <li>
                    <a href="/a/create.php" class="w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                        <span>Creer</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $_SERVER['PHP_SELF']; ?>?logout=true" class="w-max flex items-center gap-2 text-sm font-semibold text-white bg-prime rounded-md px-4 py-2 bg-prime bg-grade">
                        <span>Deconnexion</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="overflow-x-hidden">
        <section class="flex flex-col gap-4 container mx-auto px-4 items-end mt-10">
            <table data-format-table class="w-full table-auto">
                <thead>
                    <tr class="text-xs font-bold tracking-wide uppercase text-left border-b border-grey-darkest text-grey-900">
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Titre</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($products as $value) { ?>
                        <tr class="text-grey-900 even:bg-gray-50">
                            <td class="px-4 py-3 text-sm">
                                <img src="<?= $value['image'] ?>" class="w-16 block aspect-square rounded-md" />
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="<?= $value['path'] ?>"><?= $value['title'] ?></a>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2 items-center justify-center text-sm">
                                    <a href="<?= $_SERVER['PHP_SELF']; ?>?remove=<?= $value['id'] ?>" class="flex items-center justify-between cursor-pointer p-1 rounded-md focus:outline-none focus:bg-gray-100 hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="currentcolor" viewBox="0 0 48 48">
                                            <path d="M12.65 43.05Q10.85 43.05 9.45 41.7Q8.05 40.35 8.05 38.5V10.9H5.15V6.35H16.55V4H31.4V6.35H42.8V10.9H39.9V38.5Q39.9 40.35 38.55 41.7Q37.2 43.05 35.3 43.05ZM17.85 34.6H21.55V14.7H17.85ZM26.5 34.6H30.25V14.7H26.5Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <script>
        const Table = function() {
            const targets = document.querySelectorAll("[data-format-table]:not(.format-table)");
            targets.forEach((target) => {
                target.classList.add("format-table");
                const wrap = document.createElement("div");
                const cont = document.createElement("div");
                const full = document.createElement("div");
                const sear = document.createElement("input");
                const sele = document.createElement("select");
                const rows = Array.from(target.querySelectorAll("tbody tr"));
                full.className = "w-full overflow-auto bg-white rounded-md shadow-md";
                wrap.className = "w-full flex gap-4 justify-between items-center";
                cont.className = "w-full flex gap-2 justify-center items-center flex-wrap overflow-auto";
                sele.className = "w-max flex shadow-md rounded-md text-gray-900 p-2 px-3 text-sm md:text-md lg:text-lg 2xl:text-xl bg-white focus:outline focus:outline-2 outline-green-500";
                sear.className = "flex-1 w-0 md:w-96 shadow-md md:flex-none flex rounded-md text-gray-900 p-2 px-3 text-sm md:text-md lg:text-lg 2xl:text-xl bg-white focus:outline focus:outline-2 outline-green-500";
                sele.innerHTML = "<option value='10'>10</option><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option>";
                sear.placeholder = "Recherche...";
                sear.type = "search";
                target.insertAdjacentElement("beforebegin", full);
                full.insertAdjacentElement("beforebegin", wrap);
                full.insertAdjacentElement("afterend", cont);
                full.append(target);
                wrap.append(sear);
                wrap.append(sele);

                const chunck = function(n = 10) {
                    return items.reduce((resultArray, item, index) => {
                        const chunkIndex = Math.floor(index / n)
                        if (!resultArray[chunkIndex]) {
                            resultArray[chunkIndex] = [];
                        }
                        resultArray[chunkIndex].push(item);
                        return resultArray;
                    }, []);
                }
                const pop = function() {
                    target.querySelector("tbody").innerHTML = "";
                    if (pages.length === 0)
                        target.querySelector("tbody").innerHTML = "<tr><td class='py-4 text-center' colspan='" + rows[0].childNodes.length + "'>NO DATA FOUND</td></tr>"
                    else
                        pages[index].forEach(row => {
                            target.querySelector("tbody").append(row);
                        });
                }
                const btn = function() {
                    cont.innerHTML = ""
                    pages.forEach((_, i) => {
                        const btn = document.createElement("button");
                        btn.className = "table-btn shadow-md rounded-md flex items-center justify-center text-gray-900 font-black text-md w-8 h-8 bg-white focus:outline-none focus:bg-gray-300 hover:bg-gray-300";
                        btn.innerHTML = i + 1;
                        if (i === 0) {
                            btn.style.backgroundColor = "#22c55e";
                            btn.style.color = "#FFFFFF";
                        }
                        cont.append(btn);
                        btn.addEventListener("click", e => {
                            index = Array.from(btn.parentElement.childNodes).indexOf(btn);
                            clear(btn);
                            pop();
                        })
                    });
                }
                const clear = function($) {
                    Array.from(cont.querySelectorAll(".table-btn")).forEach(b => {
                        b.removeAttribute("style");
                    });
                    $.style.backgroundColor = "#22c55e";
                    $.style.color = "#FFFFFF";
                }

                var items = rows,
                    pages = chunck(),
                    index = 0;
                pop();
                btn();

                target.dispatchEvent(new CustomEvent('loaded', {
                    bubbles: true,
                    detail: {
                        select: sele,
                        search: sear,
                    }
                }))

                sele.addEventListener("change", e => {
                    pages = chunck(parseInt(e.target.value));
                    index = 0;
                    pop();
                    btn();
                });
                sear.addEventListener("input", e => {
                    const filter = e.target.value.toUpperCase();
                    if (filter.trim() === "") {
                        items = rows;
                    } else {
                        const __ = [];
                        rows.forEach(item => {
                            td = item.innerText;
                            if (td && td.toUpperCase().indexOf(filter) > -1) {
                                __.push(item);
                            }
                        });
                        items = __;
                    }
                    pages = chunck(parseInt(sele.value));
                    index = 0;
                    pop();
                    btn();
                });
            });
        }
        Table();
    </script>
</body>

</html>