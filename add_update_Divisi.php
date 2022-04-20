<?php
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Divisi.class.php");
include("includes/BidangDivisi.class.php");
include("includes/pengurus.class.php");

// membuka database
$pengurus = new Pengurus($db_host, $db_user, $db_pass, $db_name);
$pengurus->open();
$divisi = new Divisi($db_host, $db_user, $db_pass, $db_name);
$divisi->open();
$bidangDivisi = new BidangDivisi($db_host, $db_user, $db_pass, $db_name);
$bidangDivisi->open();

$title = null;
$dataForm = null;
$button = null;
$nama_divisi = null;

$divisi->getDivisi();
if(isset($_GET['id_update'])){ // jika user menekan tombol update
    $key = $_GET['id_update'];
    $divisi->get_Divisi($key);
    list($id_bidang, $nama_divisi) = $divisi->getResult();

    if(isset($_POST['submit'])){ // jika user menekan tombol submit
        $nama_divisi = $_POST['nama_divisi'];
        $divisi->update_Divisi($key, $nama_divisi); // query update
        header('location: detailDivisi.php');
    }
} else { // jika user menekan tombol add record
    if(isset($_POST['submit'])){ 
        $nama_divisi = $_POST['nama_divisi'];
        $divisi->add_Divisi($nama_divisi); // query add
        header('location: detailDivisi.php');
    }
}

$title = "Add/Update Divisi";

$dataForm .="
    <div class='form-group'>
        <label for='nama_divisi'>Nama Divisi</label>
        <input type='text' class='form-control' name='nama_divisi' placeholder='Masukan nama divisi' value='$nama_divisi' required>
    </div>
";

$button .="
    <button type='Submit' class='btn btn-primary' name='submit' id='submit'>Submit</button>
    <a href='detailDivisi.php'><button type='button' class='btn btn-danger' name='cancel'>Cancel</button></a>
";

// menutup database
$pengurus->close();
$divisi->close();
$bidangDivisi->close();

$tpl = new Template("templates/add_update.html");
$tpl->replace("FORM_TITLE", $title);
$tpl->replace("FORM_DATA", $dataForm);
$tpl->replace("FORM_BUTTON", $button);
$tpl->write();
?>