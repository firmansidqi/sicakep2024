<?php
class M_getfunction extends CI_Model{
    //private $table="pemirsa_m_target";
    
    function getsatuan($nama_kegiatan){
		$hasil=$this->db->query("SELECT s.id_satuan,s.satuan from m_satuan s left join m_listkegiatan k on s.id_satuan=k.satuan where k.nama_kegiatan='$nama_kegiatan' ");
        return $hasil->result();
    }
}