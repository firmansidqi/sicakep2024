<div class="clearfix">
<div class="row">
  <div class="col-lg-12">
	
	<div class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<span class="navbar-brand">Master List Kegiatan</span>
			</div>
		<div class="navbar-collapse collapse navbar-inverse-collapse" style="margin-right: -20px">
			
		</div><!-- /.nav-collapse -->
		</div><!-- /.container -->
	</div><!-- /.navbar -->
	
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/listkegiatan/add" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah List Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/listkegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>

  </div>
</div>

<?php echo $this->session->flashdata("k");?>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="5%">No.</th>
			<th width="30%">Unit Kerja Kegiatan</th>
			<th width="40%">Nama Kegiatan</th>
			<th width="10%">Satuan</th>
			<th width="20%">Action</th>
		</tr>
	</thead>
	
	<tbody>
		<?php 
		if (empty($data)) {
			echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
		} else {
			$no 	= ($this->uri->segment(4) + 1);
			$number=1;
			foreach ($data as $b) {
		?>
		<tr>
			<td class="ctr"><?php echo $b->id;?></td>
			<td><?php echo $b->unitkerja;?></td>
			<td><?php echo $b->nama_kegiatan; ?></td>
			<td><?php echo $b->nama_satuan; ?></td>
			
			<td class="ctr">
				<div class="btn-group">
					<a href="<?php echo base_URL(); ?>index.php/admin/listkegiatan/edt/<?php echo $b->id; ?>" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
					<a href="<?php echo base_URL()?>index.php/admin/listkegiatan/del/<?php echo $b->id;?>" class="btn btn-warning btn-sm" title="Hapus Data" onclick="return confirm('Anda Yakin Akan Menghapus Data Ini?')"><i class="icon-trash icon-remove">  </i> Del</a>	
					<!--<a href="<?php echo base_URL(); ?>admin/manage_admin/del/<?php echo $b->id?>" class="btn btn-warning btn-sm" title="Hapus Data" onclick="return confirm('Anda Yakin..?')"><i class="icon-trash icon-remove">  </i> Del</a>-->			
				</div>					
			</td>
		</tr>
		<?php 
			$no++;
			$number++;
			}
		}
		?>
	</tbody>
</table>
<center><ul class="pagination"><?php echo $pagi; ?></ul></center>
</div>
