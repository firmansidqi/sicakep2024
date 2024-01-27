<?php 
if($this->uri->segment(4)== NULL)
{
    $hariini=date("d-m-Y");
    $bulanini = substr($hariini,3,2);
    $uri4=$bulanini;
}else{
    $uri4 = $this->uri->segment(4);
}
$uri5 = $this->uri->segment(5);
?>

<script>
    function OnSelectionChange(){
        var pilihbulan  = document.getElementById('pilih_bulan');
        var id_bulan = pilihbulan.value;
        var base_url='<?php echo base_url();?>';
        var loc = base_url+"index.php/admin/unitkerjaprov/pilih_kegiatan/"+id_bulan;
        window.location.assign(loc); 
    }
</script>

<div class="row col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading"> DAFTAR SEMUA KEGIATAN BERDASARKAN BAGIAN/FUNGSI
            <div class="btn-group pull-right">
                <a href="<?php echo base_URL()?>index.php/admin/unitkerjaprov/" class="btn btn-info btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Provinsi</a>
                <a href="<?php echo base_URL()?>index.php/admin/unitkerjakabkota/"  class="btn btn-danger btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Kabupaten/Kota</a>
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
                
                echo form_dropdown("pilih_bulan", $options_bulan, $uri4, "id='pilih_bulan' class='form-control' onchange='OnSelectionChange()'")."";
                ?>
                <br>
                <?php
                for($i = 0; $i < getMinggu($uri4)[0]; $i++){
                    ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading"><?php echo "Minggu ".($i+1)." ( ".tgl_jam_sql(getMinggu($uri4)[1][$i]).")"; ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="4%">No.</th>
                                        <th width="17%">Tim Kerja</th>
                                        <th width="30%">Nama Kegiatan</th>
                                        <th width="10%">Batas Waktu</th>
                                        <th width="10%">Satuan</th>
                                        <th width="7%">Target</th>
                                        <th width="7%">Realisasi</th>
                                        <th width="15%">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $targetKum  = 0;
                                    $realKum    = 0;
                                    $no         = 1;
                                    $persen     = 0.00;
                                    foreach($data as $dat){
                                        if($dat->batas_minggu == getMinggu($uri4)[1][$i]){
                                            if($dat->id_kab == "6571"){
                                                $targetKum  += $dat->target;
                                                $realKum    += $dat->realisasi;
                                                ?>
                                                <tr>
                                                    <td align="center"><?php echo $no;?></td>
                                                    <td><?php echo $dat->unitkerja;?></td>
                                                    <td data-container="body" data-toggle="tooltip" data-placement="bottom" title="<?php echo 'Dasar :'.$dat->dasar_surat ;?>"><a href="<?php echo base_URL()?>index.php/admin/unitkerjaprov/view/<?php echo $dat->id_jeniskegiatan; ?>"><?php echo $dat->nama_kegiatan;?></a></td>
                                                    <td align="center"><?php echo tgl_jam_sql($dat->batas_waktu);?></td>
                                                    <td align="center"><?php echo $dat->satuan;?></td>
                                                    <td align="center"><?php echo $targetKum;?></td>
                                                    <td align="center"><?php echo $realKum;?></td>
                                                    <td align="center">
                                                        <?php 
                                                        if($targetKum == 0){
                                                            if($realKum == 0){
                                                            }else{
                                                                $persen = 100.00;
                                                            }
                                                        }else{
                                                            $persen = round($realKum/$targetKum*100.00,2);
                                                        }
                                                        if($persen >= 0 && $persen < 50){
                                                        ?>
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $persen; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: '.$persen."%"; ?>">
                                                                    <?php echo $persen." %"; ?>
                                                                </div>
                                                            </div>
                    									<?php
                    									}elseif($persen >= 50 && $persen < 90){
                                                        ?>
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $persen; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: '.$persen."%"; ?>">
                                                                    <?php echo $persen." %"; ?>
                                                                </div>
                                                            </div>
                    									<?php
                    									}else{
                                                        ?>
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $persen; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: '.$persen."%"; ?>">
                                                                    <?php echo $persen." %"; ?>
                                                                </div>
                                                            </div>
                    									<?php
                    									}
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $targetKum  = 0;
                                                $realKum    = 0;
                                                $no++;
                                            }else{
                                                $targetKum  += $dat->target;
                                                $realKum    += $dat->realisasi;
                                            }
                                            
                                        }
                                    };
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