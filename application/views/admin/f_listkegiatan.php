<?php
$mode		= $this->uri->segment(3);

if ($mode == "edt" || $mode == "act_edt") {
	$act					= "act_edt";
	$id_listkegiatan		= $datpil->id;
	$nama_kegiatan			= $datpil->nama_kegiatan;
	$unitkerja				= $datpil->id_unitkerja;
	$satuan					= $datpil->satuan;
	
	$query_satuan=$this->db->query("SELECT * from m_satuan where id_satuan='$satuan' LIMIT 1")->row();
	$idsatuan_terpilih =$query_satuan->id_satuan;
	$satuan_terpilih =$query_satuan->satuan;
	
	$query_unitkerja =$this->db->query("SELECT * from m_unitkerja where id_unitkerja='$unitkerja' LIMIT 1")->row();
	$idunitkerja_terpilih =$query_unitkerja->id_unitkerja;
	$unitkerja_terpilih =$query_unitkerja->unitkerja;
	
} else {
	$act					= "act_add";
	$id_listkegiatan		= "";
	$nama_kegiatan			= "";
	$unitkerja				= "";
	$satuan					= "";
}
?>

<div class="navbar navbar-inverse">
	<div class="container z0">
		<div class="navbar-header">
			<span class="navbar-brand" href="#">Tambah/Edit List Kegiatan</span>
		</div>
	</div><!-- /.container -->
</div><!-- /.navbar -->

<?php echo $this->session->flashdata("k");?>
	
<form action="<?php echo base_URL()?>index.php/admin/listkegiatan/<?php echo $act; ?>" method="post" accept-charset="utf-8" role="form" enctype="multipart/form-data">
	
	<!--<input type="hidden" name="idp" value="<?php echo $idp; ?>">-->
	
	<div class="row-fluid well" style="overflow: hidden">
	
	<div class="col-lg-8">
		<table width="100%" class="table-form">
		<tr>
			<!--<td width="20%">ID Kegiatan</td>-->
			<td><b><input type="hidden" tabindex="1"  name="id_listkegiatan" id="id_listkegiatan" required value="<?php echo $id_listkegiatan; ?>" style="width: 400px" class="form-control" readonly></b></td>
		</tr>
		<tr>
		<td width="20%">Asal Kegiatan</td>
		<td>
				<b>
				<?php
				if ($mode == "edt" || $mode == "act_edt") 
				{
				?>
					<select name="unitkerja" id="unitkerja"  class="form-control" tabindex="5" style="width: 200px">
                    <option selected value="<?php echo $idunitkerja_terpilih; ?>"><?php echo $unitkerja_terpilih; ?></option>
                        <?php
						//mengambil nama-nama unitkerja yang ada di database
						if($this->session->userdata('admin_level')=='userprov' and $this->session->userdata('admin_user') != '6500')
						{
						$bidangku = $this->session->userdata('admin_user');
                        $unitkerja = $this->db->query("select * from m_unitkerja where substring(id_unitkerja,1,4)='$bidangku'")->result();
						}
						else
						{
						 $unitkerja = $this->db->query("select * from m_unitkerja where id_unitkerja <> '6500'")->result();
						}
                        foreach($unitkerja as $p){
							if ($p->id_unitkerja != $idunitkerja_terpilih) 
							{
							echo "<option value='".$p->id_unitkerja."'>".$p->unitkerja."</option>";
							}
						}
                        ?>
                    </select>  
				<?php
				}
				else
				{
				?>
					<select name="unitkerja" id="unitkerja"  class="form-control" tabindex="6" style="width: 200px">
                    <option selected value="Kosong">- Pilih Unit Kerja -</option>
                        <?php
						//mengambil nama-nama unitkerja yang ada di database
                        if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_user') != '6500')
						{
						$bidangku = $this->session->userdata('admin_user');
                        $unitkerja = $this->db->query("select * from m_unitkerja where substring(id_unitkerja,1,4)='$bidangku'")->result();
						}
						else
						{
						 $unitkerja = $this->db->query("select * from m_unitkerja where id_unitkerja <> '6500'")->result();
						}
                        foreach($unitkerja as $p){
							echo "<option value='".$p->id_unitkerja."'>".$p->unitkerja."</option>\n";
                        }
				}
				?>
				
					<!--<input type="text" tabindex="5" name="satuan" required value="<?php echo $satuan; ?>" id="satuan" style="width: 100px" class="form-control">-->
				</b>
		</td>
		</tr>
		
		<tr>
			<td width="20%">Nama Kegiatan</td>
			<td><b><input type="text" tabindex="1"  name="nama_kegiatan" id="nama_kegiatan" required value="<?php echo $nama_kegiatan; ?>" style="width: 400px" class="form-control"></b></td>
		</tr>
			
		<tr>
			<td width="20%">Satuan</td>
			<td>
				<b>
				<?php
				if ($mode == "edt" || $mode == "act_edt") 
				{
				?>
					<select name="satuan" id="satuan"  class="form-control" tabindex="5" style="width: 200px">
                    <option selected value="<?php echo $idsatuan_terpilih; ?>"><?php echo $satuan_terpilih; ?></option>
                        <?php
						//mengambil nama-nama satuan yang ada di database
                        $satuan = $this->db->query("select * from m_satuan")->result();
                        foreach($satuan as $p){
							if ($p->id_satuan != $idsatuan_terpilih) 
							{
							echo "<option value='".$p->id_satuan."'>".$p->satuan."</option>";
							}
						}
                        ?>
                    </select>  
				<?php
				}
				else
				{
				?>
					<select name="satuan" id="satuan"  class="form-control" tabindex="6" style="width: 200px">
                    <option selected value="Kosong">- Pilih Satuan -</option>
                        <?php
						//mengambil nama-nama satuan yang ada di database
                        $satuan = $this->db->query("select * from m_satuan")->result();
                        foreach($satuan as $p){
							echo "<option value='".$p->id_satuan."'>".$p->satuan."</option>\n";
                        }
				}
				?>
				
					<!--<input type="text" tabindex="5" name="satuan" required value="<?php echo $satuan; ?>" id="satuan" style="width: 100px" class="form-control">-->
				</b>
			</td>
		</tr>		
		<tr><td colspan="2">
		<br><a href="javascript:history.back()" tabindex="10" class="btn btn-success"><i class="icon icon-arrow-left icon-white"></i> Batal</a>
		<button type="submit" class="btn btn-primary" tabindex="9" ><i class="icon icon-folder-close icon-white"></i> Simpan</button>
		
		</td></tr>
		
		</table>
	</div>
	
	</div>
	
	</form>
