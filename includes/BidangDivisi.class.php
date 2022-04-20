<?php
class BidangDivisi extends DB
{
    function getBidangDivisi()
    {
        $query = "SELECT * FROM bidang_divisi";
        return $this->execute($query);
    }
    function get_BidangDivisi($id_bidang){
        $query = "SELECT * FROM bidang_divisi where id_bidang = $id_bidang";
        return $this->execute($query);
    }
    
    function add_BidangDivisi($jabatan, $id_divisi){
        $query = "INSERT INTO bidang_divisi VALUES (NULL,'$jabatan',$id_divisi)";
        return $this->execute($query);
    }   

    function update_BidangDivisi($id_bidang, $jabatan, $id_divisi){
        $query = "UPDATE bidang_divisi SET jabatan='$jabatan', id_divisi=$id_divisi where id_bidang = $id_bidang";
        return $this->execute($query);
    }
    
    function delete_BidangDivisi($id_bidang){
        $query = "DELETE FROM bidang_divisi where id_bidang = $id_bidang";
        return $this->execute($query);
    }
}
?>