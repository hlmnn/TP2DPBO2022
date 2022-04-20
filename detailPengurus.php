<?php
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Pengurus.class.php");
include("includes/Divisi.class.php");
include("includes/BidangDivisi.class.php");

// membuka database
$pengurus = new Pengurus($db_host, $db_user, $db_pass, $db_name);
$pengurus->open();
$divisi = new Divisi($db_host, $db_user, $db_pass, $db_name);
$divisi->open();
$bidangDivisi = new BidangDivisi($db_host, $db_user, $db_pass, $db_name);
$bidangDivisi->open();

$title = null;
$data = null;
$button = null;

$pengurus->getPengurus();

$title="Detail Pengurus";

if(isset($_GET['id_delete'])){ // jika user menekan tombol delete
    $pengurus->delete_Pengurus($_GET['id_delete']);
    header('location: index.php');
}

if(isset($_GET['id_pengurus'])) { // jika user menekan profile dari pengurus nya
    $pengurus->get_Pengurus($_GET['id_pengurus']);
    (list($id_pengurus, $nim, $nama, $semester, $img, $id_bidang) = $pengurus->getResult());
    $bidangDivisi->get_BidangDivisi($id_bidang);
    $dataBidangDivisi = $bidangDivisi->getResult();
    $divisi->get_Divisi($dataBidangDivisi['id_divisi']);
    $dataDivisi = $divisi->getResult();
    // menampilkan data
    $data .= "
        <div class='col-md-4 mb-3 d-flex justify-content-center border shadow p-2'>
            <div class='card border w-70'>
                <a href='detailPengurus.php?id_pengurus=".$id_pengurus."' style='text-decoration: none; color: black;'>
                    <img src='img/".$img."' class='card-img-top' alt='Foto'>
                    <div class='card-body' >
                        <table>
                            <thead>
                                <td><input type='hidden' name='key' value=''></td>
                                <td><input type='hidden' name='key' value=''></td>
                            </thead>
                            <tr>
                                <td><p class='card-text fw-bold my-0'>Nama</p></td>
                                <td>: $nama</td>
                            </tr>
                            <tr>
                                <td><p class='card-text fw-bold my-0'>NIM</p></td>
                                <td>: $nim</td>
                            </tr>
                            <tr>
                                <td><p class='card-text fw-bold my-0'>Semester</p></td>
                                <td>: $semester</td>
                            </tr>
                            <tr>
                                <td><p class='card-text fw-bold my-0'>Jabatan</p></td>
                                <td>: ".$dataBidangDivisi['jabatan']."</td>
                            </tr>
                            <tr>
                                <td><p class='card-text fw-bold my-0'>Divisi</p></td>
                                <td>: ".$dataDivisi['nama_divisi']."</td>
                            </tr>
                        <table>
                        <br>
                        <div>
                            BUTTON
                        </div>
                    </div>
                </a>
            </div>
        </div>
    ";
}

$button .= "
    <a href='add_update_Pengurus.php?id_update=". $id_pengurus ."' class='btn btn-primary' name='btn-update' id='btn-update'>Edit</a>
    <a href='detailPengurus.php?id_delete=". $id_pengurus ."' class='btn btn-danger' name='btn-delete' id='btn-delete'>Delete</a>
";

// menutup database
$pengurus->close();
$divisi->close();
$bidangDivisi->close();

$tpl = new Template("templates/detailPengurus.html");
$tpl->replace("DATA_TITLE", $title);
$tpl->replace("DATA_TABEL", $data);
$tpl->replace("BUTTON", $button);
$tpl->write();
?>