<?php 
$uri4 = $this->uri->segment(4);
?>

<div class="row col-md-12">
  <div class="panel panel-info">
    <div class="panel-heading">DETAIL KEGIATAN
     <div class="btn-group pull-right">
	
                          <a href="<?php echo base_URL()?>index.php/admin/kegiatan/" class="btn btn-info btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Semua Kegiatan</a>
                          <a href="<?php echo base_URL()?>index.php/admin/kegiatan_bidang/"  class="btn btn-danger btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Bagian/Fungsi</a>
							<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/add" class="btn btn-success btn-sm"><i class="icon-plus-sign icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Tambah Kegiatan</a>
       </div>
    </div>
    <div class="panel-body">

      <div class="col-lg-12 alert alert-warning" style="margin-bottom: 20px">
        <div class="col-md-12">
            <table class="table table-bordered" style="margin-bottom: 0px">
              <tr><td>ID Kegiatan</td><td><?php echo $datview->id_jeniskegiatan; ?></td></tr>
              <tr><td>Nama Kegiatan</td><td><?php echo $datview->nama_kegiatan; ?></td></tr>
              <tr><td width="30%">Satuan</td><td width="70%"><?php echo $datview->nama_satuan; ?></td></tr>
              <tr><td>Target</td><td><?php echo $datview->target; ?></td></tr>
			  <tr><td>Batas Waktu</td><td><?php echo tgl_jam_sql($datview->batas_waktu); ?> </td></tr>
			  <tr><td colspan="2" align="right">
				<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/edt/<?php echo $datview->id_jeniskegiatan; ?>/1" class="btn btn-success btn-xs" title="Edit Data"><i class="icon-edit icon-white"> </i></a>
								<?php
								$id_delete =$datview->id_jeniskegiatan ;
								?>
								<a href="#" class="open_modal btn btn-danger btn-xs" id="<?php echo $id_delete ;?>"><i class="icon-trash icon-white"></i></a>		
				</div>
			  
			  </td></tr>
            </table>
        </div>
      </div>


      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="5%">No</th>
            <th width="35%">Kabupaten/Kota</th>
            <th width="15%">Target</th>
            <th width="15%" colspan="2">Realisasi</th>
            <th width="15%" colspan="2">Persentase</th>
			<th width="15%">Tanggal Update</th>
          </tr>
        </thead>

        <tbody>
          <?php 
            if (!empty($datprogress)) {
              $no = 1;
              foreach ($datprogress as $d) {
                				  ?>
                <tr>
                      <td class="ctr"><?php echo $no ; ?></td>
                      <td><?php echo $d->nama_kab ; ?></td>
                      <td class="ctr"><?php echo $d->target ; ?></td>
                      <td class="ctr"><?php echo $d->realisasi ; ?></td>
					  <td class="ctr">
					  <?php
					  if($this->session->userdata('admin_user') == $d->id_kab || $this->session->userdata('admin_user') == '6500' || $this->session->userdata('admin_user') == '92100' || $this->session->userdata('admin_user') == '92200' || $this->session->userdata('admin_user') == '92300' || $this->session->userdata('admin_user') == '92400' || $this->session->userdata('admin_user') == '92500' || $this->session->userdata('admin_user') == '92600')
					  {
					  ?>
							<div class="btn-group">
								<a href="<?php echo base_URL()?>index.php/admin/entry_unitkerja/edt/<?php echo $d->id_jeniskegiatan; ?>/<?php echo $d->id_kab; ?>" class="btn btn-danger btn-xs" title="Update Data"><i class="icon-plus icon-white"> </i></a>
							</div>
					<?php
					  }
					  ?>
					  </td>
                      <td class="ctr"><?php echo $d->persen ; ?></td>
					  <td class="ctr">
					  <?php
					  if($this->session->userdata('admin_level') == 'userprov' && $d->realisasi != 0 && $d->flag_konfirm =='2')
					  {
						$id_konfirm=$d->id_jeniskegiatan.''.$d->id_kab;
					  ?>
							<div class="btn-group">
								<a href="#<?=$no;?>" class="open_modal_konfirm btn btn-success btn-xs"  id="<?php echo $id_konfirm;?>" title="Konfirmasi Data"><i class="icon-check icon-white"> </i> </a>
							</div>
						<?php
					  }
					  ?>
					  </td>
					   <td class="ctr"><?php echo tgl_jam_sql($d->tgl_entri) ; ?></td>
                      </tr>
			 <?php
              $no++;
              }
            } else {
              echo '<tr><td colspan="5">Belum ada progress</td></tr>';
            }
          ?>
        </tbody>
      </table>
    
      </div>
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

<!-- Modal Popup untuk Edit--> 
<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
<!-- Javascript untuk popup modal Edit--> 
<script type="text/javascript">
   $(document).ready(function () {
   $(".open_modal_konfirm").click(function(e) {
//       e.preventDefault();
      var m = $(this).attr("id");
		   $.ajax({
    			   url: "<?php echo base_url(); ?>index.php/admin/konfirmasi/edt/",
    			   type: "GET",
    			   data : {konfirmasi_id: m,},
    			   success: function (ajaxData){
      			   $("#ModalEdit").html(ajaxData);
      			   $("#ModalEdit").modal('show',{backdrop: 'true'});
      		   }
    		   });
        });
//        return;
      });
</script>	