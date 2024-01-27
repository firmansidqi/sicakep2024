<?php
	if($this->uri->segment(3) == null )
	{
		$tab=1;
	}
	else 
	{
		$tab = $this->uri->segment(3);
	}
		
?>

<div id="ModalDelete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>


<div class="clearfix">
<div class="row">
  <div class="col-lg-12">
	<div class="navbar navbar-inverse">
	
		<div class="navbar-header">
			<span class="navbar-brand" href="#">Entri Kegiatan - Rincian-</span>
		</div>

</div><!-- /.navbar -->
  
<?php echo $this->session->flashdata("k");?>  
	  
	<div class="container">
	<div class="row">
    <div class="col-sm-12 blog-main">
	<div class="tabs">
	
        <!-- content blog anda isikan disini-->
		<ul class="nav nav-tabs" id="prodTabs">
			<li <?php echo $tab==1?"class='active'":"" ?> ><a href="#all" data-toggle="tab">All</a></li>
			<li <?php echo $tab==2?"class='active'":"" ?> ><a href="#tu" data-toggle="tab">Bagian Umum</a></li>
			<li <?php echo $tab==3?"class='active'":"" ?> ><a href="#sos" data-toggle="tab">Fungsi Statistik Sosial</a></li>
			<li <?php echo $tab==4?"class='active'":"" ?> ><a href="#prod" data-toggle="tab">Fungsi Statistik Produksi</a></li>
			<li <?php echo $tab==5?"class='active'":"" ?> ><a href="#dist" data-toggle="tab">Fungsi Statistik Distribusi</a></li>
			<li <?php echo $tab==6?"class='active'":"" ?> ><a href="#ner" data-toggle="tab">Fungsi Nerwilis</a></li>
			<li <?php echo $tab==7?"class='active'":"" ?> ><a href="#ipds" data-toggle="tab">Fungsi IPDS</a></li>
	  </ul>
      <!-- Tab panes content dari tab di atas -->
      <div class="tab-content">
      <div class="scroll tab-pane <?php echo $tab==1?"active":"" ?>" id="all"><br>
				<?php
				$tab=1;
				?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/1" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
	   		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($dataall)) {
						echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($dataall as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/1"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
						<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/1" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
								<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-remove"></i> Del</a>		
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>

	    </div>
        <div class="scroll tab-pane <?php echo $tab==2?"active":"" ?> " id="tu"><br>
				<?php
				$tab=2;
				?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/2" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($datatu)) {
						echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($datatu as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/2"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
						<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/2" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
							<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete; ?>"><i class="icon-trash icon-remove"></i> Del</a>			
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>
		
		</div>
        <div class="scroll tab-pane <?php echo $tab==3?"active":"" ?>" id="sos"><br>
				<?php
				$tab=3;
				?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/3" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($datasos)) {
						echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($datasos as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/3"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
							<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/3" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
								<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-remove"></i> Del</a>			
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>
				
		
		</div>
        <div class="scroll tab-pane <?php echo $tab==4?"active":"" ?>" id="prod"><br>
				<?php
				$tab=4;
				?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/4" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($dataprod)) {
						echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($dataprod as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/4"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
						<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/4" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
								<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-remove"></i> Del</a>			
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>
				
		</div>
		<div class="scroll tab-pane <?php echo $tab==5?"active":"" ?>" id="dist"><br>
				<?php
				$tab=5;
				?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/5" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($datadist)) {
						echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($datadist as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/5"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
						<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/5" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
								<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-remove"></i> Del</a>		
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>
	
		
		</div>
		<div class="scroll tab-pane <?php echo $tab==6?"active":"" ?>" id="ner"><br>
			<?php
				$tab=6;
			?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/6" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($datanerwil)) {
						echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($datanerwil as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/6"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
						<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/6" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
								<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-remove"></i> Del</a>	
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>
				
		</div>
		<div class="scroll tab-pane <?php echo $tab==7?"active":"" ?>" id="ipds"><br>
				<?php
				$tab=7;
				?>
				<ul class="nav navbar-nav">
					<a href="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/add/7" class="btn btn-danger"><i class="icon-plus-sign icon-white"> </i> Tambah Kegiatan</a>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" method="post" action="<?php echo base_URL(); ?>index.php/admin/kelolakegiatan/cari">
						<input type="text" class="form-control" name="q" style="width: 200px" placeholder="Kata kunci pencarian ..." required>
						<button type="submit" class="btn btn-danger"><i class="icon-search icon-white"> </i> Cari</button>
					</form>
				</ul>
			<br>
		<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="10%">ID Kegiatan</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Target</th>
						<th width="10%">Realisasi</th>
						<th width="10%">Satuan</th>
						<th width="10%">Batas Waktu</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (empty($dataipds)) {
						echo "<tr><td colspan='5' style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
						$no 	= 1;
						foreach ($dataipds as $b) {
					?>
					<tr>
						<td  align="center"><?php echo $no;?></td>
						<td><?php echo $b->id_jeniskegiatan;?></td>
						<td><a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/7"><?php echo $b->nama_kegiatan;?></a></td>
						<td  align="center"><?php echo $b->target; ?></td>
						<td  align="center"><?php echo $b->realisasi;?></td>
						<td  align="center"><?php echo $b->satuan;?></td>
						<td  align="center"><?php echo tgl_jam_sql($b->batas_waktu);?></td>
						<td class="ctr">
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $b->id_jeniskegiatan; ?>/7" class="btn btn-success btn-sm" title="Edit Data"><i class="icon-edit icon-white"> </i> Edt</a>
								<?php
								$id_delete =$b->id_jeniskegiatan.''.$tab ;
								?>
								<a href="#" class="open_modal btn btn-warning btn-sm" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-remove"></i> Del</a>			
							</div>	
						</td>
					</tr>
					<?php 
						$no++;
						}
					}
					?>
				</tbody>
			</table>
		
		</div>
	   </div>
	</div>
    </div><!-- /.blog-main -->
	</div>
	</div><!-- /.container -->
  
  
	
	</div>
  </div>
</div>

<!-- Modal Popup untuk Delete--> 
<div id="ModalDelete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
<!-- Javascript untuk popup modal Edit--> 
<script type="text/javascript">
   $(document).ready(function () {
   $(".open_modal").click(function(e) {
      var m = $(this).attr("id");
		   $.ajax({
    			   url: "<?php echo base_url(); ?>index.php/admin/kelolakegiatan/del/",
    			   type: "GET",
    			   data : {delete_id: m,},
    			   success: function (ajaxData){
      			   $("#ModalDelete").html(ajaxData);
      			   $("#ModalDelete").modal('show',{backdrop: 'true'});
      		   }
    		   });
        });
      });
</script>	




