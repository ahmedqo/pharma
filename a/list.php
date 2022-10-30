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
    unlink("../". array_values($pro)[0]['path']);
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
            <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                <input type="search" id="search" name="search" onkeyup="myFunction()" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Search" />
            </div>
            <div class="w-full bg-white overflow-hidden">
                <div class="w-full rounded-md overflow-x-auto">
                    <table class="w-full table-auto">
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
                                        <img src="<?= $value['image'] ?>" class="w-20 block aspect-square rounded-md" />
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <?= $value['title'] ?>
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
                </div>
            </div>
        </section>
    </main>
    <script>
        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.querySelector("input");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>