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
$dataForm = null;
$dataJabatan = null;
$nim = null;
$nama = null;
$semester = null;
$button = null;

if(isset($_GET['id_delete'])){ // jika user menekan tombol delete
    $pengurus->delete_Pengurus($_GET['id_delete']); // query delete
    header('location: index.php');
} 
else if(isset($_GET['id_update'])) { // jika user menekan tombol update
    $key = $_GET['id_update'];
    $pengurus->get_Pengurus($_GET['id_update']);
    list($id_pengurus, $nim, $nama, $semester, $image, $id_bidang) = $pengurus->getResult();
    
    $bidangDivisi->getBidangDivisi();
    while(list($id_bidang2, $jabatan, $id_divisi) = $bidangDivisi->getResult()){
        $divisi->get_Divisi($id_divisi);
        $namadivisi = $divisi->getResult();
        
        $dataJabatan .= "<option value='".$id_bidang2."'"; 
        if($id_bidang == $id_bidang2){ // jika id nya sama
            $dataJabatan .= " selected='selected'";
        }
        $dataJabatan .= ">" . $jabatan. " ". $namadivisi['nama_divisi'] . "</option>";
    }

    if(isset($_POST['submit'])) { // jika user menekan tombol submit
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $semester = $_POST['semester'];
        $id_bidang = $_POST['jabatan_divisi'];

        $targetDir = "img/";
        $image = $_FILES['image']['name']; 
        $tmp = $_FILES['image']['tmp_name'];
        $fileTargetDir = $targetDir . $image; // menyatukan

        // jika file belum ada di direktori yg dituju
        if (!file_exists($fileTargetDir)) {
            // memindahkan file ke targetdir
            $moveUploadedFile = move_uploaded_file($tmp, $fileTargetDir);
        }

        $pengurus->update_Pengurus($nim, $nama, $semester, $image, $id_bidang, $key); // query update
        header('location: index.php');
    }

}
else { // jika user menekan tombol tambah pengurus
    $bidangDivisi->getBidangDivisi();

    while(list($id_bidang2, $jabatan, $id_divisi) = $bidangDivisi->getResult()){
        $divisi->get_Divisi($id_divisi);
        $namadivisi = $divisi->getResult();
        $dataJabatan .= "<option value='".$id_bidang2."'>" . $jabatan. " ". $namadivisi['nama_divisi'] ."</option>"; 
    }
    if(isset($_POST['submit'])) { // jika user menekan tombol submit
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $semester = $_POST['semester'];
        $id_bidang = $_POST['jabatan_divisi'];

        $targetDir = "img/";
        $image = $_FILES['image']['name'];    
        $tmp = $_FILES['image']['tmp_name'];
        $fileTargetDir = $targetDir . $image; // menyatukan

        // jika file belum ada di direktori yg dituju
        if (!file_exists($fileTargetDir)) {
            // memindahkan file ke filetargetdir
            $moveUploadedFile = move_uploaded_file($tmp, $fileTargetDir);
        }

        $pengurus->add_Pengurus($nim, $nama, $semester, $image, $id_bidang); // query add
        header('location: index.php');
    }
}

$title = "Add/Update Pengurus";

$dataForm .="
    <div class='form-group'>
        <label for='nim'>NIM</label>
        <input type='number' class='form-control' name='nim' placeholder='Contoh: 69069' value='$nim' required>
    </div>
    <div class='form-group'>
        <label for='nama'>Nama</label>
        <input type='text' class='form-control' name='nama' placeholder='Contoh: Hilman' value='$nama' required>
    </div>
    <div class='form-group'>
        <label for='semester'>Semester</label>
        <input type='number' class='form-control' name='semester' placeholder='Contoh: 1' value='$semester''>
    </div>
    <div class='form-group'>
        <label for='image'>Gambar</label>
        <input type='file' class='form-control' name='image' id='image' value='$image'>
    </div>
    <div class='form-group'>
        <label for='jabatan_divisi'>Jabatan</label>
        <select class='form-control' id='jabatan_divisi' name='jabatan_divisi'>
            DATA_JABATAN
        </select>
    </div>
";

$button .="
    <button type='Submit' class='btn btn-primary' name='submit' id='submit'>Submit</button>
    <a href='index.php'><button type='button' class='btn btn-danger' name='cancel'>Cancel</button></a>
";

// menutup database
$pengurus->close();
$divisi->close();
$bidangDivisi->close();

$tpl = new Template("templates/add_update.html");
$tpl->replace("FORM_TITLE", $title);
$tpl->replace("FORM_DATA", $dataForm);
$tpl->replace("DATA_JABATAN", $dataJabatan);
$tpl->replace("FORM_BUTTON", $button);
$tpl->write();
?>