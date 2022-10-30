<?php
require_once "library.php";
$array = [];

if ($handle = opendir('./products/')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $page = str_get_html(file_get_contents_curl("http://localhost/site/products/" . $entry));
            $images = [];
            foreach ($page->find('.scroll button') as $image) {
                array_push($images, $image->find('img', 0)->src);
            }
            array_push($array, [
                "path" => "/products/" . clean($entry),
                "images" => implode("|", $images),
                "image" => $page->find("#preview", 0)->src,
                "title" => trim($page->find("h1.text-4xl", 0)->plaintext),
                "category" => $page->find("section.flex.flex-wrap.gap-2.items-center.mx-auto.container.p-4 a", 2)->plaintext,
                "brand" => explode(".", basename($page->find("div.flex.flex-col img.block.max-60", 0)->src))[0],
                "id" => (int)explode("-", explode(".", $page->find("title", 0)->attr["data-translate-id"])[1])[1]
            ]);
        }
    }
    closedir($handle);
}

$idx = array_column($array, 'id');
array_multisort($idx, SORT_ASC, $array);

$myfile = fopen("./data.json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($array));
fclose($myfile);


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



function file_get_contents_curl($url)
{
    if (!function_exists('curl_version')) {
        exit("Enable cURL in PHP");
    }
    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

    $ch = curl_init();
    $timeout = 0; // 100; // set to zero for no timeout
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    if (curl_errno($ch)) {
        echo curl_error($ch);
        curl_close($ch);
        exit();
    }
    curl_close($ch);
    return $file_contents;
}