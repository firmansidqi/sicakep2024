<?php
$this->load->model('M_kelolakegiatan');
$hariini=date("d-m-Y");
if($this->uri->segment(4)== NULL)
{
	$bulanini = substr($hariini,3,2);
	$uri4=$bulanini;
}
else
{	
	$uri4 = $this->uri->segment(4);
}
//$uri5 = $this->uri->segment(5);
?>

<style style type="text/css">
	table.dataTable thead .sorting { background: url('/evita/aset/img/sort_both.png') no-repeat center right; }
	table.dataTable thead .sorting_asc { background: url('/evita/aset/img/sort_asc.png') no-repeat center right; }
	table.dataTable thead .sorting_desc { background: url('/evita/aset/img/sort_desc.png') no-repeat center right; }
	table.dataTable thead .sorting_asc_disabled { background: url('/evita/aset/img/sort_asc_disabled.png') no-repeat center right; }
	table.dataTable thead .sorting_desc_disabled { background: url('/evita/aset/img/sort_desc_disabled.png') no-repeat center right; }
</style>

<script>
	$(document).ready(function () {
		$('#dtBasicExample').DataTable({
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false,
			"bAutoWidth": false,
			"searching": false
		});
		$('.dataTables_length').addClass('bs-select');
	});
</script>

<script>
	function OnSelectionChange()
	{
		var pilihbulan  = document.getElementById('pilih_bulan');
		var id_bulan = pilihbulan.value;
        //var pilihkabkota  = document.getElementById('pilih_kabkota');
        //var id_kabkota = pilihkabkota.value;
		var base_url='<?php echo base_url();?>';
		var loc = base_url+"index.php/admin/unitkerjakabkotadetail/pilih_kegiatan/"+id_bulan ;
		window.location.assign(loc); 
	}
</script>

<div class="row col-md-12">
	<div class="panel panel-info">
		
		<div class="panel-heading">KEGIATAN BERDASARKAN KABUPATEN/KOTA
			<div class="btn-group pull-right">
				<a href="<?php echo base_URL()?>index.php/admin/unitkerjaprovuntukkab/" class="btn btn-info btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Provinsi</a>
				<a href="<?php echo base_URL()?>index.php/admin/unitkerjakabkotadetail/"  class="btn btn-danger btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Kabupaten/Kota</a>
			</div>
		</div>
		
		<div class="panel-body">
			
			<!-- accordion -->
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php 
				$options_bulan = array(
					//'00'         => 'Semua Bulan',
					'01'         => 'Januari',
					'02'         => 'Februari',
					'03'         => 'Maret',
					'04'         => 'April',
					'05'         => 'Mei',
					'06'         => 'Juni',
					'07'         => 'Juli',
					'08'         => 'Agustus',
					'09'         => 'September',
					'10'         => 'Oktober',
					'11'         => 'November',
					'12'         => 'Desember',
				);
				/*$options_kabkota = array(
							'6500'         => 'Semua Kabkota',
							'6501'         => 'Kabupaten Malinau',
							'6502'         => 'Kabupaten Bulungan',
							'6503'         => 'Kabupaten Tana Tidung',
							'6504'         => 'Kabupaten Nunukan',
							'6571'         => 'Kota Tarakan',
							
						);*/
						echo form_dropdown("pilih_bulan", $options_bulan, $uri4, "id='pilih_bulan' class='form-control' onchange='OnSelectionChange()'")."";
				//echo form_dropdown("pilih_kabkota", $options_kabkota, $uri5, "id='pilih_kabkota' class='form-control' onchange='OnSelectionChange()'")."";
						?>
						<br>
						<?php
						for($i = 0; $i < getMinggu($uri4)[0]; $i++){
						    ?>
						    <div class="panel panel-warning">
						        <div class="panel-heading"><?php echo "Minggu ".($i+1)." ( ".tgl_jam_sql(getMinggu($uri4)[1][$i]).")"; ?></div>
						    </div>
						    <div class="panel panel-default">
						        <div class="panel-body">
						            <table class="table table-bordered table-hover">
						                <thead>
						                    <tr>
						                        <th width="4%">No.</th>
						                        <th width="17%">Asal Kegiatan</th>
						                        <th width="30%">Nama Kegiatan</th>
						                        <th width="10%">Satuan</th>
						                        <th width="5%">Target</th>
						                        <th width="10%" colspan="2">Realisasi</th>
						                        <th width="10%">Batas Waktu</th>
						                        <th width="14%">Keterangan</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    <?php
						                    $no = 1;
						                    //$konfirm = 1;
						                    foreach($data as $d){
						                        if($d->batas_minggu == getMinggu($uri4)[1][$i]){
						                            ?>
						                            <tr>
						                                <td align="center"><?php echo $no;?></td>
						                                <td><?php echo $d->unitkerja;?></td>
						                                <td><?php echo $d->nama_kegiatan;?></td>
						                                <td align="center"><?php echo $d->satuan;?></td>
						                                <td align="center"><?php echo $d->target;?></td>
						                                <td align="center"><?php echo $d->realisasi;?></td>
						                                <td align="center">
															<div class="btn-group">
																<a href="<?php echo base_URL()?>index.php/admin/entry_unitkerjakab/edt/<?php echo $d->id_jeniskegiatan; ?>/<?php echo $d->id_kab; ?>/<?php echo $d->minggu_ke; ?>" class="btn btn-danger btn-xs" title="Update Data"><i class="icon-plus icon-white"> </i></a>
															</div>
														</td>
														<?php
														/*if($konfirm != $d->flag_konfirm){
															$konfirm    = $d->flag_konfirm;
														};*/
														?>
														<td align="center"><?php echo tgl_jam_sql($d->batas_waktu);?></td>
														<?php
														if($d->flag_konfirm == '2'){
														    echo "<td>Belum dikonfirmasi provinsi</td>";
														}else{
														    echo "<td></td>";
														}
														?>
						                            </tr>
						                        <?php      
						                        }
						                    }
						                    ?>
						                </tbody>
						            </table>
						        </div>
						    </div>
						<?php
						}
						?>
						
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