<?php

header("Content-Type: application/json; charset=UTF-8");
include './config/koneksi.php';

// Membuat nama folder upload
$upload_path = 'uploads/';

// Mengambil IP server
$server_ip = gethostbyname(gethostname());

// Membuat url upload
$upload_url = 'http://'.$server_ip.'/makanan/'.$upload_path;

// Mengambil inputan user dari parameter 
$idmakanan = $_GET['idmakanan'];

// Membuat variable untuk querinya
$query = "SELECT tm.id_makanan, tm.id_user, tm.id_kategori, tm.nama_makanan, tm.desc_makanan, tm.foto_makanan, tm.insert_time, tm.view,
tu.nama_user, 
tk.nama_kategori FROM tb_user tu,tb_makanan tm, tb_kategori tk WHERE 
tu.id_user = tm.id_user  &&
tk.id_kategori = tm.id_kategori &&
tm.id_makanan = '$idmakanan' ";

// Membuat variable result/hasil dari eksekusi query
$result = mysqli_query($connection, $query) or die ("Error in selecting " . mysqli_error
    ($connection));

// Membuat variable array untuk data yang diambil
$temparray = array();
// Membuat  variable untuk response terakhir
$response = array();

// Membuat variable untuk mengecek isi data setelah di query
$check = mysqli_num_rows($result);

// Melakukan kondisi untuk mengecek apakah query tadi ada isinya
if ($check > 0){

    while ($row = mysqli_fetch_assoc($result)) {
        $row['url_makanan'] = $upload_url . $row['foto_kategori'];
    array_push($row['url_makanan']);
        // Mengambil data view dan increment +1
        $jumlahview = $row['view']+1;
        $temparray = $row;
    }
    
    // Melakukan update view di database
    $query = "UPDATE tb_makanan SET view = '$jumlahview' WHERE id_makanan = '$idmakanan'";

    // Mengeksekusi update untuk view
    mysqli_query($connection, $query);

    // Membuat tambahan item untuk menampilka pesan sukses
    $response['result'] = 1;
    $response['message'] = "Data berhasil di ambil";

    // Memasukkan data tadi ke dalam variable response
    $response['data'] = $temparray;
}else {
    // Menampilkan response data kosong
    $response['result'] = 0;
    $response['message'] = "Data kosong";
}

// Mengubah response menjadi JSON
echo json_encode($response);

// Close connection
mysqli_close($connection)

?>