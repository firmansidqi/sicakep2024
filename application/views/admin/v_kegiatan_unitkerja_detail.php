<?php 
$uri4 = $this->uri->segment(4);
$this->load->model('M_kelolakegiatan');
?>

<div class="row col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading">DETAIL KEGIATAN
            <div class="btn-group pull-right">
                <a href="<?php echo base_URL()?>index.php/admin/unitkerjaprov/" class="btn btn-info btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Provinsi</a>
                <a href="<?php echo base_URL()?>index.php/admin/unitkerjakabkota/"  class="btn btn-danger btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Kabupaten/Kota</a>
            </div>
        </div>
        
        <div class="panel-body">
            
            <div class="col-lg-12 alert alert-warning" style="margin-bottom: 20px">
                <div class="col-md-12">
                    <table class="table table-bordered" style="margin-bottom: 0px">
                        <tr><td width="30%">ID Kegiatan</td><td width="70%"><?php echo $datview->id_jeniskegiatan; ?></td></tr>
                        <tr><td>Nama Kegiatan</td><td><?php echo $datview->nama_kegiatan; ?></td></tr>
                        <tr>
                            <td>Penanggung Jawab</td>
                            <td><?php echo $this->M_kelolakegiatan->getPgwTerpilih($datview->pj_prov); ?></td>
                        </tr>
                        <tr><td>Target</td><td><?php echo $datview->target;?></td></tr>
                        <tr><td>Satuan</td><td><?php echo $datview->nama_satuan;?></td></tr>
                        <tr><td>Rentang Waktu</td><td><?php echo tgl_jam_sql($datview->mulai)." - ".tgl_jam_sql($datview->batas_waktu); ?> </td></tr>
                    </table>
                </div>
            </div>
            
            <table class="table table-bordered">
                <?php $friday = getFridayKgt($datview->mulai, $datview->batas_waktu);?>
                <thead>
                    <tr>
                        <th width="4%" rowspan = '2'>No.</th>
                        <th width="9%" rowspan = '2'>Kabupaten/Kota</th>
                        <th width="17%" rowspan = '2'>Penanggung Jawab</th>
                        <?php
                        for($i = 1; $i <= $friday[0]; $i++){
                            echo "<th width='10%' colspan = '2'>Minggu ".$i."<br>( ".tgl_jam_sql($friday[1][$i-1]).")</th>";
                        }
                        ?>
                        <th width="10%" colspan = '2'>Total</th>
                        <th width="10%" rowspan = '2'>Persentase</th>
                        <th width="10%" rowspan = '2'>Tanggal Update</th>
                    </tr>
                    <tr>
                        <?php
                        for($i = 1; $i <= $friday[0]; $i++){
                            echo "<th width = '5%'>T</th>";
                            echo "<th width = '5%'>R</th>";
                        }
                        ?>
                        <th>T</th>
                        <th>R</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    $no = 1;
                    foreach($wilayah as $wil){
                        $data = $this->M_kelolakegiatan->getProgresWil($uri4, $wil->id_kab);
                        if(!empty($data)){
                            ?>
                            <tr>
                                <td class="ctr"><?php echo $no ; ?></td>
                                <td><?php echo $wil->nama_kab; ?></td>
                                <td><?php echo $this->M_kelolakegiatan->getPgwTerpilih($data[0]->pj_kab); ?></td>
                                <?php
                                $target     = 0;
                                $realisasi  = 0;
                                $update     = $data[0]->tgl_entri;
                                foreach($data as $d){
                                    echo "<td class=\"ctr\">".$d->target."</td>";
                                    echo "<td class=\"ctr\" width='4%'>".$d->realisasi."</td>";
                                    $target += $d->target;
                                    $realisasi += $d->realisasi;
                                    if($d->realisasi <> 0){
                                        $update = $d->tgl_entri;
                                    }
                                };?>
                                <td class="ctr"><?php echo $target; ?></td>
                                <td class="ctr"><?php echo $realisasi; ?></td>
                                <?php
                                $persen = 0.00;
                                if($target == 0){
                                    if($realisasi == 0){
                                    }else{
                                        $persen = 100.00;
                                    }
                                }else{
                                    $persen = round($realisasi/$target*100.00,2);
                                }
                                ?>
                                <td>
									<?php
									if($persen >= 0 && $persen < 50){
									?>
										<div class="progress">
											<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $persen; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: '.$persen."%"; ?>">
												<?php echo $persen." %"; ?>
											</div>
										</div>
									<?php
									}elseif($persen >= 50 && $persen < 90  ){
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
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $persen; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: '.$persen."%"; ?>">
												<?php echo $persen." %"; ?>
											</div>
										</div>
									<?php
									}
									?>
								</td>
								<td class = "ctr"><?php echo tgl_jam_sql($update);?></td>
                            </tr>
                            <?php
                            $no++;
                        }
                    }?>
                </tbody>
            </table>
        
        </div>
    </div>
</div>

