<?php
session_start();
if (isset($_SESSION["logged_in"])) {
    header("location: /a/create.php");
    die();
}

if (isset($_POST['login'])) {
    if ($_POST['email'] === "admin@promedplanet.com" && $_POST['password'] === "Password12345") {
        $_SESSION["logged_in"] = true;
        header("location: /a/create.php");
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="fr" class="w-full overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <script src="https://cdn.tiny.cloud/1/5q3f1hswxbi1wr1hsf6r4che0wgz79s909nh2cl0vwv81983/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <title>CONNEXION</title>
</head>

<body class="text-gray-800">
    <main class="overflow-x-hidden min-h-screen flex items-center">
        <form class="w-full lg:w-1/3 flex flex-col gap-4 container mx-auto px-4 items-end" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
            <div class="w-full flex flex-col">
                <label for="email" class="text-gray-900 font-black text-md">Email</label>
                <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                    <input type="email" id="email" name="email" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Email" required />
                </div>
            </div>
            <div class="w-full flex flex-col">
                <label for="password" class="text-gray-900 font-black text-md">Password</label>
                <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                    <input type="password" id="password" name="password" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Password" required />
                </div>
            </div>
            <button name="login" type="submit" class="w-full h-12 flex items-center justify-center gap-2 p-4 rounded-md bg-green-500 focus:outline-none focus:bg-green-300 hover:bg-green-300">
                <span class="text-white font-black text-lg">CONNEXION</span>
            </button>
        </form>
    </main>
</body>

</html>