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
$dataDiv = null;
$dataForm = null;
$jabatan = null;
$button = null;

$bidangDivisi->getBidangDivisi();
if (isset($_GET['id_update'])) { // jika user menekan tombol update
    $key = $_GET['id_update'];

    $bidangDivisi->get_BidangDivisi($key);
    list($id_bidang, $jabatan, $id_divisi) = $bidangDivisi->getResult();

    $divisi->getDivisi();
    while (list($id_divisi2, $nama_divisi) = $divisi->getResult()) {
        $dataDiv .= "<option value='".$id_divisi2."'"; 
        if($id_divisi == $id_divisi2){ // jika id nya sama
            $dataDiv .= " selected='selected'";
        }
        $dataDiv .= ">". $nama_divisi. "</option>";
    }

    if (isset($_POST['submit'])){ // jika user menekan tombol submit
        $jabatan_post = $_POST['jabatan'];
        $id_divisi_post = $_POST['id_divisi'];
        $bidangDivisi->update_BidangDivisi($key, $jabatan_post, $id_divisi_post); // query update
        header('location: detailBidangDivisi.php');
    }
}
else if (isset($_GET['id_divisi'])) { // jika user menekan tombol add record
    $divisi->getDivisi();
    while (list($id_divisi2, $nama_divisi) = $divisi->getResult()) {
        $dataDiv .= "<option value='".$id_divisi2."'>". $nama_divisi. "</option>"; 
    }

    if (isset($_POST['submit'])){ // jika user menekan tombol submit
        $jabatan_post = $_POST['jabatan'];
        $id_divisi_post = $_POST['id_divisi'];
        $bidangDivisi->add_BidangDivisi($jabatan_post, $id_divisi_post); // query add
        header('location: detailBidangDivisi.php');
    }
}

$title = "Add/Update Bidang Divisi";

$dataForm .="
    <div class='form-group'>
        <label for='exampleInputEmail1'>Jabatan</label>
        <input type='text' class='form-control' name='jabatan' placeholder='Masukan jabatan' value='$jabatan' required>
    </div>
    <div class='form-group'>
        <label for='exampleInputEmail1'>ID Divisi</label>
        <select class='form-control' id='id_divisi' name='id_divisi'>
            DATA_DIVISI
        </select>
    </div>
";

$button .="
    <button type='Submit' class='btn btn-primary' name='submit' id='submit'>Submit</button>
    <a href='detailBidangDivisi.php'><button type='button' class='btn btn-danger' name='cancel'>Cancel</button></a>
";

// menutup database
$pengurus->close();
$divisi->close();
$bidangDivisi->close();

$tpl = new Template("templates/add_update.html");
$tpl->replace("FORM_TITLE", $title);
$tpl->replace("FORM_DATA", $dataForm);
$tpl->replace("DATA_DIVISI", $dataDiv);
$tpl->replace("FORM_BUTTON", $button);
$tpl->write();
?>