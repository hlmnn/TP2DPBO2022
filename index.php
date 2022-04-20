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

$pengurus->getPengurus();
$data = null;
    
$pengurus->getPengurus(); // query tampil
// menampilkan data
while (list($id_pengurus, $nim, $nama, $semester, $img, $id_bidang) = $pengurus->getResult()) {
    $bidangDivisi->get_BidangDivisi($id_bidang);
    $dataBidangDivisi = $bidangDivisi->getResult();

    $divisi->get_Divisi($dataBidangDivisi['id_divisi']);
    $dataDivisi = $divisi->getResult();

    $data .= "
        <div class='col-md-4 mb-3 d-flex justify-content-center'>
            <div class='card w-60 border'>
                <a href='detailPengurus.php?id_pengurus=".$id_pengurus."' style='text-decoration: none; color: black;'>
                    <img src='img/".$img."' class='card-img-top' alt='Foto'>
                    <div class='card-body' >
                        <p class='card-text fw-bold my-0'>". $nama ."</p>
                        <p class='card-text my-0'>". $dataBidangDivisi['jabatan'] ."</p>
                        <p class='card-text my-2'>". $dataDivisi['nama_divisi'] ."</p>
                    </div>
                </a>
            </div>
        </div>
    ";
}

// menutup database
$pengurus->close();
$divisi->close();
$bidangDivisi->close();

$tpl = new Template("templates/index.html");
$tpl->replace("DATA_TABEL", $data);
$tpl->write();
?>