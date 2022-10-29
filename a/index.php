<?php

$categories = json_decode(file_get_contents("../json/categories.json"), true);
$products = json_decode(file_get_contents("../json/products.json"), true);
$brands = json_decode(file_get_contents("../json/brands.json"), true);
$fr = json_decode(file_get_contents("../translate/data-fr.json"), true);
$en = json_decode(file_get_contents("../translate/data-en.json"), true);
$it = json_decode(file_get_contents("../translate/data-it.json"), true);
$ar = json_decode(file_get_contents("../translate/data-ar.json"), true);

$id = count($products) + 100;
$HEADER = '<span id="header"></span>';
$FOOTER = '<span id="footer"></span>';
$SLIDE = '<span id="slider"></span>';
$SIDE = '<span id="aside"></span>';

function clean($str)
{
    $str = strip_tags($str);

    $str = preg_replace('/’/', '', $str);
    $str = preg_replace('/‘/', '', $str);

    $str = preg_replace('/"/', '', $str);
    $str = preg_replace("/'/", '', $str);

    $str = preg_replace("/\//", '', $str);

    $str = preg_replace('/[-]/', ' ', $str);
    $str = preg_replace("/:/", '-', $str);

    $str = preg_replace('/  */', '-', $str);
    $str = preg_replace('/\\s+/', '-', $str);
    return $str;
}

function translate($id, $fr, $en, $it, $ar)
{
    $fr["product"]["pro-" . $id] = [
        "title" => $_POST["title_fr"],
        "desc" => $_POST["desc_fr"],
        "description" => $_POST["description_fr"]
    ];
    $en["product"]["pro-" . $id] = [
        "title" => $_POST["title_en"],
        "desc" => $_POST["desc_en"],
        "description" => $_POST["description_en"]
    ];
    $it["product"]["pro-" . $id] = [
        "title" => $_POST["title_it"],
        "desc" => $_POST["desc_it"],
        "description" => $_POST["description_it"]
    ];
    $ar["product"]["pro-" . $id] = [
        "title" => $_POST["title_ar"],
        "desc" => $_POST["desc_ar"],
        "description" => $_POST["description_ar"]
    ];
    file_put_contents("../translate/data-fr.json", json_encode($fr), true);
    file_put_contents("../translate/data-en.json", json_encode($en), true);
    file_put_contents("../translate/data-it.json", json_encode($it), true);
    file_put_contents("../translate/data-ar.json", json_encode($ar), true);
}

function product($id, $img, $products)
{
    array_push($products, [
        "path" => "/products/" . clean($_POST["title_fr"]) . ".html",
        "image" => $img,
        "title" => $_POST["title_fr"],
        "category" => $_POST["category"],
        "brand" => $_POST["brand"],
        "id" => $id
    ]);
    file_put_contents("../json/products.json", json_encode($products), true);
}

function upload($file)
{
    // Exit if is not a valid image file
    $image_type = exif_imagetype($file["tmp_name"]);
    // Get file extension based on file type, to prepend a dot we pass true as the second parameter
    $image_extension = image_type_to_extension($image_type, true);
    // Create a unique image name
    $image_name = bin2hex(random_bytes(16)) . $image_extension;
    // Move the temp image file to the images directory
    move_uploaded_file(
        // Temp image location
        $file["tmp_name"],
        // New image location
        "../assets/products/" . $image_name
    );
    return "/assets/products/" . $image_name;
}

function uploads($file)
{
    $images = [];
    for ($i = 0; $i < count($file['name']); $i++) {
        // Exit if is not a valid image file
        $image_type = exif_imagetype($file["tmp_name"][$i]);
        // Get file extension based on file type, to prepend a dot we pass true as the second parameter
        $image_extension = image_type_to_extension($image_type, true);
        // Create a unique image name
        $image_name = bin2hex(random_bytes(16)) . $image_extension;
        // Move the temp image file to the images directory
        move_uploaded_file(
            // Temp image location
            $file["tmp_name"][$i],
            // New image location
            "../assets/products/" . $image_name
        );
        array_push($images, "/assets/products/" . $image_name);
    }
    return $images;
}

if (isset($_POST['send'])) {
    $image = upload($_FILES['primary']);
    $images = uploads($_FILES['extra']);
    translate($id, $fr, $en, $it, $ar);
    product($id, $image, $products);
    $myfile = fopen("../products/" . clean($_POST['title_fr']) . ".html", "w+") or die("Unable to open file!");
    $IMG = '';
    foreach ($images as $img) {
        $IMG .= '<button onclick="preview(this, `#preview`)" class="w-24 h-24 border border-gray-200 shadow-sm rounded-lg bg-white p-2 flex items-center justify-center cursor-pointer">
                <img src="' . $img . '" class="block max-w-full max-h-full pointer-events-none" />
            </button>';
    }
    $info = '';
    if (strlen($_POST["description_fr"]) > 0) {
        $info = '
            <div class="flex flex-col gap-4 lg:gap-8 container mx-auto my-6 lg:my-10">
                <h1 data-translate-id="extra.desc" class="text-2xl lg:text-4xl text-center">
                        Caractéristiques
                </h1>
                <div data-translate-id="product.pro-' . $id . '.description" class="block w-full reset">
                    ' . $_POST["description_fr"] . '
                </div>
            </div>
        ';
    }
    $code = '
    <!DOCTYPE html>
    <html lang="fr" class="w-full overflow-x-hidden">
    
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://cdn.tailwindcss.com/"></script>
        <script src="/assets/js/app.js"></script>
        <link rel="stylesheet" href="/assets/css/style.css" />
        <title data-translate-id="product.pro-' . $id . '.title">' . $_POST["title_fr"] . '</title>
    </head>
    
    <body class="text-gray-800 pb-4 lg:pt-4">
       ' . $HEADER . '
        ' . $SLIDE . '
        <section class="flex flex-wrap gap-2 items-center mx-auto container p-4">
            <a href="/index.html" data-translate-id="menu.home" class="block">ACCUEIL</a>
            <span class="block border border-gray-900 w-2"></span>
            <a href="/products.html" data-translate-id="header.category" class="block">NOS GAMMES</a>
            <span class="block border border-gray-900 w-2"></span>
            <a href="/categories/' . clean($_POST['category']) . '.html" data-translate-id="' . array_search($_POST['category'], $categories) . '" class="block">' . $_POST["category"] . '</a>
            <span class="block border border-gray-900 w-2"></span>
            <a href="/products/' . clean($_POST['title_fr']) . '.html" class="block text-prime" data-translate-id="product.pro-' . $id . '.title">' . $_POST["title_fr"] . '</a>
        </section>
        <main class="overflow-x-hidden flex flex-col lg:flex-row lg:flex-wrap gap-4 p-4 my-10 container mx-auto items-start">
            <div class="w-full lg:w-1/4 flex flex-col gap-4">
                <div class="w-full text-white items-center justify-between flex gap-4 lg:hidden rounded-2xl bg-grade px-4 py-2">
                    <h1 data-translate-id="' . array_search($_POST['category'], $categories) . '" class="font-semibold text-xl">' . $_POST["category"] . '</h1>
                    <button onclick="show(`#aside`)" class="block">
                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                            <path d="M21.05 43V30.75h4v4.2h17.7v4h-17.7V43Zm-15.8-4.05v-4h13.3v4Zm9.3-8.9v-4.1h-9.3V22h9.3v-4.15h4v12.2Zm6.5-4.1V22h21.7v3.95Zm8.4-8.75V4.95h4v4.1h9.3v4h-9.3v4.15Zm-24.2-4.15v-4h21.7v4Z" />
                        </svg>
                    </button>
                </div>
                ' . $SIDE . '
            </div>
            <section id="print" class="flex flex-col lg:flex-row lg:flex-wrap gap-4 w-full lg:w-0 lg:flex-1">
                <div class="w-full lg:w-96 flex flex-col gap-2">
                    <picture class="w-full h-96 border border-gray-200 shadow-sm rounded-2xl bg-white p-4 flex items-center justify-center">
                        <img id="preview" src="' . $image . '" class="block max-w-full max-h-full" />
                    </picture>
                    <div class="w-full overflow-x-auto scroll">
                        <div class="flex flex-wrap w-max gap-2">'  . $IMG . '</div>
                    </div>
                </div>
                <div class="w-full lg:flex-1 flex flex-col gap-2">
                    <span class="text-lg font-bold text-gray-500">' . $_POST["ref"] . '</span>
                    <h1 class="text-4xl font-semibold mb-2" data-translate-id="product.pro-' . $id . '.title">
                        ' . $_POST["title_fr"] . '
                    </h1>
                    <div class="text-md text-gray-500 border-b border-gray-300 pb-2 mb-2" data-translate-id="product.pro-' . $id . '.desc">
                        ' . $_POST["desc_fr"] . '
                    </div>
                    <div class="flex justify-between lg:items-center flex-col lg:flex-row gap-4">
                        <div class="flex flex-col">
                            <h1 data-translate-id="extra.brand" class="text-md text-gray-900">Marque</h1>
                            <img src="/assets/brands/' . $_POST["brand"] . '.png" class="block max-60" />
                        </div>
                        <button data-translate-id="extra.file" onclick="_print(event)" class="text-sm bg-prime px-4 py-2 w-max rounded-2xl text-white">Télécharger la fiche produit</button>
                    </div>
                </div>
               ' . $info . '
            </section>
        </main>
        ' . $FOOTER . '
        <script defer>
            Starter.add(
                () => {
                    Element.Aside();
                    Element.Header();
                    Element.Slider(true);
                    Element.Footer(true);
                }
            ).init();
        </script>
        <script src="/assets/js/translate.js" defer></script>
        <div id="overlay" class="fixed inset-0 bg-white" style="z-index: 99999;"></div>
    </body>
    
    </html>
    ';
    fwrite($myfile, $code);
    fclose($myfile);
}

?>

<!DOCTYPE html>
<html lang="fr" class="w-full overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com/"></script>
    <title>PROMED PLANET</title>
</head>

<body class="text-gray-800 pb-4 lg:pt-4">
    <form class="flex flex-col gap-4 container mx-auto px-4 items-end" method="post" action="<?= $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <div class="w-full">
            <div class="flex flex-1 flex-col">
                <label for="ref" class="text-gray-900 font-black text-md">Reference</label>
                <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                    <input type="text" id="ref" name="ref" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Reference" required />
                </div>
            </div>
            <div class="flex flex-1 flex-col">
                <label for="category" class="text-gray-900 font-black text-md">Categorie</label>
                <select name="category" id="category" class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                    <?php foreach ($categories as $key => $value) { ?>
                        <option value="<?= $value ?>"><?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="flex flex-1 flex-col">
                <label for="brand" class="text-gray-900 font-black text-md">Marque</label>
                <select name="brand" id="brand" class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                    <option value="BRAND-LESS">BRANDLESS</option>
                    <?php foreach ($brands as $value) { ?>
                        <option value="<?= clean($value) ?>"><?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="w-full">
            <h1 class="font-black text-2xl">Images</h1>
            <div class="flex flex-1 flex-col">
                <label for="primary" class="text-gray-900 font-black text-md">Principale</label>
                <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                    <input type="file" id="primary" name="primary" class="text-gray-900 text-lg flex-1 bg-transparent focus:outline focus:outline-2 outline-green-500" placeholder="Principale" required />
                </div>
            </div>
            <div class="flex flex-1 flex-col">
                <label for="extra" class="text-gray-900 font-black text-md">Extra</label>
                <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                    <input type="file" id="extra" name="extra[]" class="text-gray-900 text-lg flex-1 bg-transparent focus:outline focus:outline-2 outline-green-500" placeholder="Extra" multiple />
                </div>
            </div>
        </div>
        <div class="w-full">
            <h1 class="font-black text-2xl">Titre</h1>
            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="flex flex-1 flex-col">
                    <label for="title.fr" class="text-gray-900 font-black text-md">Francais</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="title.fr" name="title_fr" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais" required />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="title.en" class="text-gray-900 font-black text-md">Anglais</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="title.en" name="title_en" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Anglais" required />
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="flex flex-1 flex-col">
                    <label for="title.it" class="text-gray-900 font-black text-md">Italian</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="title.it" name="title_it" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Italian" required />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="title.ar" class="text-gray-900 font-black text-md">Arabe</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="title.ar" name="title_ar" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Arabe" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <h1 class="font-black text-2xl">Mini Description</h1>
            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="flex flex-1 flex-col">
                    <label for="desc.fr" class="text-gray-900 font-black text-md">Francais</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="desc.fr" name="desc_fr" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais" />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="desc.en" class="text-gray-900 font-black text-md">Anglais</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="desc.en" name="desc_en" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Anglais" />
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="flex flex-1 flex-col">
                    <label for="desc.it" class="text-gray-900 font-black text-md">Italian</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="desc.it" name="desc_it" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Italian" />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="desc.ar" class="text-gray-900 font-black text-md">Arabe</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="desc.ar" name="desc_ar" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Arabe" />
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <h1 class="font-black text-2xl">Description</h1>
            <div class="w-full flex flex-col">
                <label for="description.fr" class="text-gray-900 font-black text-md">Francais</label>
                <div class="w-full h-32 flex rounded-md bg-gray-100 overflow-hidden">
                    <textarea id="description.fr" name="description_fr" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais"></textarea>
                </div>
            </div>
            <div class="w-full flex flex-col">
                <label for="description.en" class="text-gray-900 font-black text-md">Anglais</label>
                <div class="w-full h-32 flex rounded-md bg-gray-100 overflow-hidden">
                    <textarea id="description.en" name="description_en" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais"></textarea>
                </div>
            </div>
            <div class="w-full flex flex-col">
                <label for="description.it" class="text-gray-900 font-black text-md">Italian</label>
                <div class="w-full h-32 flex rounded-md bg-gray-100 overflow-hidden">
                    <textarea id="description.it" name="description_it" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais"></textarea>
                </div>
            </div>
            <div class="w-full flex flex-col">
                <label for="description.ar" class="text-gray-900 font-black text-md">Arabe</label>
                <div class="w-full h-32 flex rounded-md bg-gray-100 overflow-hidden">
                    <textarea id="description.ar" name="description_ar" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais"></textarea>
                </div>
            </div>
        </div>
        <button name="send" type="submit" class="w-full h-12 flex items-center justify-center gap-2 p-4 rounded-md bg-green-500 focus:outline-none focus:bg-green-300 hover:bg-green-300">
            <span class="text-white font-black text-lg">ENREGISTRER</span>
        </button>
    </form>
</body>

</html>