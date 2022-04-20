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

$divisi->getDivisi();
$no=1;

$title="Detail Divisi";

$dataHeader.="
            <td>No</td>
            <td>Nama Divisi</td>
            <td>Jumlah Pengurus</td>
            <td>Opsi</td>
";

if(isset($_GET['id_delete'])){ // jika user menekan tombol delete
    $hapus_divisi = $divisi->getResult();
    $bidangDivisi->getBidangDivisi();
    while(list($id_bidang, $jabatan, $id_divisi2) = $bidangDivisi->getResult()){
        if($id_divisi2 == $_GET['id_delete']){
            $pengurus->getPengurus();
            while(list($id_pengurus, $nim, $nama, $semester, $img, $id_bidang2) = $pengurus->getResult()){
                if($id_bidang == $id_bidang2){
                    $pengurus->delete_Pengurus($id_pengurus);
                }
            }
            $bidangDivisi->delete_BidangDivisi($id_bidang);
        }
    }
    $divisi->delete_Divisi($_GET['id_delete']);
    header('location: detailDivisi.php');
}

// membuka database
$divisi2 = new Divisi($db_host, $db_user, $db_pass, $db_name);
$divisi2->open();

while(list($id_divisi, $nama_divisi) = $divisi->getResult()){
    $divisi2->get_JumlahPengurus($id_divisi); // menghitung banyak pengurusnya
    $jumlahPengurus = $divisi2->getResult(); // mengambil hasilnya

    // menampilkan data
    $data .= 
    "<tr>
        <td>".$no." </td> 
        <td>".$nama_divisi."</td>
        <td>".$jumlahPengurus['jumlahPengurus']."</td>
        <td>
            <a href='add_update_Divisi.php?id_update=". $id_divisi ."' class='btn btn-primary' name='btn-update' id='btn-update'>Edit</a>
            <a href='detailDivisi.php?id_delete=". $id_divisi ."' class='btn btn-danger' name='btn-delete' id='btn-delete'>Delete</a>
        </td>
    </tr>";
    $no++; // iterasi
}

$add_button .= "<a href='add_update_Divisi.php'><button class='btn btn-primary'>Add Record</button></a>";

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