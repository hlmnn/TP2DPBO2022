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
$dataHeader = null;
$data = null;
$add_button = null;

$bidangDivisi->getBidangDivisi();
$no = 1;

$title="Detail Bidang Divisi";

$dataHeader.="
            <td>No</td>
            <td>Jabatan</td>
            <td>Divisi</td>
            <td>Opsi</td>
";

if(isset($_GET['id_delete'])) { // jika user menekan tombol delete
    $hapus_bidang_divisi = $bidangDivisi->getResult();
    $pengurus->getPengurus();
    while(list($id_pengurus, $nim, $nama, $semester, $img, $id_bidang) = $pengurus->getResult()){
        if($id_bidang == $_GET['id_delete']){
            $pengurus->delete_Pengurus($id_pengurus);
        }
    }
    $bidangDivisi->delete_BidangDivisi($_GET['id_delete']);
    header('location: detailBidangDivisi.php');
}

// menampilkan data
while(list($id_bidang, $jabatan, $id_divisi) = $bidangDivisi->getResult()) {
    $divisi->get_Divisi($id_divisi);
    $nama_divisi = $divisi->getResult();
    $data .= "
    <tr>
        <td>". $no ." </td> 
        <td>". $jabatan."</td>
        <td>". $nama_divisi['nama_divisi']."</td>
        <td>
            <a href='add_update_BidangDivisi.php?id_update=". $id_bidang ."' class='btn btn-primary' name='btn-update' id='btn-update'>Edit</a>
            <a href='detailBidangDivisi.php?id_delete=". $id_bidang ."' class='btn btn-danger' name='btn-delete' id='btn-delete'>Delete</a>
        </td>
    </tr>";
    $no++; // iterasi
}

$add_button .= "<a href='add_update_BidangDivisi.php?id_divisi=1'><button class='btn btn-primary'>Add Record</button></a>";

// menutup database
$pengurus->close();
$divisi->close();
$bidangDivisi->close();

$tpl = new Template("templates/detail.html");
$tpl->replace("DATA_TITLE", $title);
$tpl->replace("NAMA_TABEL", $title);
$tpl->replace("DATA_THEAD", $dataHeader);
$tpl->replace("DATA_TABEL", $data);
$tpl->replace("ADD_BUTTON", $add_button);
$tpl->write();
?>