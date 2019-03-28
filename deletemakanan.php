<?php

// Agar output nya selalu JSON 
header("Content-Type: application/json; charset=UTF-8");
// Memasukkan koneksi untuk menggunakan connection
include './config/koneksi.php';

// kita cek method post atau bukan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pengecekan parameter inputan user
    if (isset($_POST["idmakanan"]) &&
        isset($_POST["fotomakanan"])){

            // Memasukkan data yang sudah dikirim oleh user di dalam parameter ke variable penampung baru
            $idmakanan = $_POST["idmakanan"];
            $fotomakanan = $_POST["fotomakanan"];

             // Membuat query untuk delete data
             $query = "DELETE FROM tb_makanan WHERE id_makanan = '$idmakanan'";
             // Mengeksekusi query delete dan langsung mengecek apakah berhasil atau tidak
             if (mysqli_query($connection, $query)) {
                // Menghapus image sebelumnya
             unlink("./uploads/" . $fotomakanan);

                 // Mengisi response dengan pesan berhasil delete
                 $response['result'] = 1;
                 $response['message'] = "Data makanan berhasil di hapus";
             }else {
                 // Apabila gagal melakukan query maka tampilkan pesan gagal delete
                 $response['result'] = 0;
                 $response['message'] = "Menghapus data gagal";
             }
    }else {
        // Apabila gagal melakukan query maka tampilkan pesan gagal delete
        $response['reslut'] = 0;
        $response['message'] = "Maaf!, Data kurang";
    }

    // Merubah response menjadi JSON
    echo json_encode($response);

    // Mematikan koneksi
    mysqli_close($connection);
}        


?>