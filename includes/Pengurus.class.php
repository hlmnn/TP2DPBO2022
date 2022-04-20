<?php
class Pengurus extends DB
{
    function getPengurus(){
        $query = "SELECT * from pengurus";
        return $this->execute($query);
    }

    function get_Pengurus($id_pengurus){
        $query = "SELECT * from pengurus where id_pengurus = $id_pengurus";
        return $this->execute($query);
    }

    function add_Pengurus($nim, $nama, $semester, $img, $idbidang){
        $query = "INSERT INTO pengurus VALUES (NULL, $nim, '$nama', $semester, '$img', $idbidang)";
        return $this->execute($query);
    }

    function update_Pengurus($nim, $nama, $semester, $img, $idbidang, $id_pengurus){
        $query = "UPDATE pengurus SET nim=$nim, nama='$nama', semester=$semester, image='$img', id_bidang=$idbidang where id_pengurus='$id_pengurus'";
        return $this->execute($query);
    }

    function delete_Pengurus($id_pengurus){
        $query = "DELETE FROM pengurus WHERE id_pengurus='$id_pengurus'";
        return $this->execute($query);
    }
}
?>