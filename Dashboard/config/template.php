<?php
$page = (isset($_GET['page'])) ? $_GET['page'] : '';

switch ($page) {
    case 'home':
        include "views/home/index.php";
        break;

    case 'dusun':
        include "views/dusun/index.php";
        break;
    case 'warga':
        include "views/warga/index.php";
        break;
    default: // Ini untuk set default jika isi dari $page tidak ada pada 3 kondisi diatas
        include "views/home/index.php";
}
