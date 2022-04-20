<?php
class Divisi extends DB
{
    function getDivisi()
    {
        $query = "SELECT * FROM divisi";
        return $this->execute($query);
    }

    function get_Divisi($id_divisi){
        $query = "SELECT * FROM divisi where id_divisi = $id_divisi";
        return $this->execute($query);
    }

    function add_Divisi($nama_divisi){
        $query = "INSERT INTO divisi VALUES(NULL, '$nama_divisi')";
        return $this->execute($query);
    }
    
    function update_Divisi($id_divisi, $nama_divisi){
        $query = "UPDATE divisi set nama_divisi='$nama_divisi' where id_divisi=$id_divisi";
        return $this->execute($query);
    }

    function delete_Divisi($id_divisi){
        $query = "DELETE FROM divisi where id_divisi = $id_divisi";
        return $this->execute($query);
    }

    function get_JumlahPengurus($id_divisi) {
        // create the query
        $query = "SELECT COUNT(id_pengurus) AS jumlahPengurus FROM pengurus WHERE id_bidang IN (SELECT id_bidang FROM bidang_divisi WHERE id_divisi='$id_divisi')";
        
        // executing query
        return $this->execute($query);
    }
}
?>