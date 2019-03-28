<?php

header("Content-Type: application/json; charset=UTF-8");
include './config/koneksi.php';

// Membuat nama folder upload
$upload_path = 'uploads/';

// Mengambil IP server
$server_ip = gethostbyname(gethostname());

// Membuat url upload
$upload_url = 'http://'.$server_ip.'/makanan/'.$upload_path;

// Membuat folder upload apabila folder tidak ada
if (!is_dir($upload_url)){
    // Membuat folder
    mkdir("uploads", 0775, true);
}

// Membuat response array
$response = array();

// Cek method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari parameter
    $iduser = $_POST['iduser'];
    $idkategori = $_POST['idkategori'];
    $namamakanan = $_POST['namamakanan'];
    $descmakanan = $_POST['descmakanan'];
    $timeinsert = $_POST['timeinsert'];

    // Membuat try and catch agar mencoba menyimpan file ke directory dengan aman
    try {
        // Mengambil nama extension file
        $temp = explode(".", $_FILES["image"]["name"]);
        // Menggabungkan nama baru dengan extension 
        $newfilename = round(microtime(true)) . '.' . end($temp);

        // Memasukkan file ke dalam folder
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_path . $newfilename);

        $query = "INSERT INTO tb_makanan(
                    id_user,
                    id_kategori,
                    nama_makanan,
                    desc_makanan,
                    foto_makanan,
                    insert_time
                    )
                    VALUES (
                    '$iduser',
                    '$idkategori',
                    '$namamakanan',
                    '$descmakanan',
                    '$newfilename',
                    '$timeinsert'
                    )";

    // Mengeksekusi query dan langsung mengecek apakah berhasil atau tidak
    if (mysqli_query($connection, $query)) {
        // Menampilkan pesan success upload
        $response['result'] = 1;
        $response['message'] = "Upload Berhasil";
        $response['url'] = $upload_url . $newfilename;
        $response['name'] = $namamakanan;
    }else {
        // menampilkan pesan error upload
        $response['result'] = 0;
        $response['message'] = "Upload Gagal";
    }                

    } catch (Exception $e) {
        $response['result'] = 0;
        $response['message'] = $e->getMessage();
    }

    // displaying the response JSON
    echo json_encode($response);

    // closing the connection
    mysqli_close($connection);
}

?>