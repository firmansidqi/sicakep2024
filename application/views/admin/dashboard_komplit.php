<html>
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-more.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<style type="text/css">
			.with-nav-tabs.panel-primary .nav-tabs > li > a,
			.with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
			.with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
				color: #fff;
			}
			.with-nav-tabs.panel-primary .nav-tabs > .open > a,
			.with-nav-tabs.panel-primary .nav-tabs > .open > a:hover,
			.with-nav-tabs.panel-primary .nav-tabs > .open > a:focus,
			.with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
			.with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
				color: #fff;
				background-color: #003366;
				border-color: transparent;
			}
			.with-nav-tabs.panel-primary .nav-tabs > li.active > a,
			.with-nav-tabs.panel-primary .nav-tabs > li.active > a:hover,
			.with-nav-tabs.panel-primary .nav-tabs > li.active > a:focus {
				color: #003366;
				background-color: #fff;
				border-color: #003366;
				border-bottom-color: transparent;
			}
		</style>

		<!--<script type="text/javascript">-->

		<?php
		$hariini=date("d-m-Y");
		$bulanini = substr($hariini,3,2);
		$tahunini =  $this->session->userdata('admin_ta');
		/*
		$query_tu=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3351' and substring(batas_waktu,6,2) = '$bulanini' LIMIT 1")->row();
		$query_sos=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3352' and substring(batas_waktu,6,2) = '$bulanini' LIMIT 1")->row();
		$query_prod=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3353' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$query_dist=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3354' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$query_ner=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3355' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$query_ipds=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3356' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		*/
		
		//isian tabel baru
		$query_nilai_volume=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen FROM m_jeniskegiatan WHERE substring(batas_waktu,6,2) = '$bulanini' LIMIT 1")->row();

		//nilai volume dan nilai waktu
		$nilai_volume=$query_nilai_volume->persen;
		
		/*
		$capaian_tu =$query_tu->persen;
		$capaian_sos =$query_sos->persen;
		$capaian_prod =$query_prod->persen;
		$capaian_dist =$query_dist->persen;
		$capaian_ner =$query_ner->persen;
		$capaian_ipds =$query_ipds->persen;
		*/
		
		if($bulanini == '01')
		{
			$bulannama='Januari';
		}
		else if($bulanini == '02')
		{
			$bulannama='Februari';
		}
		else if($bulanini == '03')
		{
			$bulannama='Maret';
		}
		else if($bulanini == '04')
		{
			$bulannama='April';
		}
		else if($bulanini == '05')
		{
			$bulannama='Mei';
		}
		else if($bulanini == '06')
		{
			$bulannama='Juni';
		}
		else if($bulanini == '07')
		{
			$bulannama='Juli';
		}
		else if($bulanini == '08')
		{
			$bulannama='Agustus';
		}
		else if($bulanini == '09')
		{
			$bulannama='September';
		}
		else if($bulanini == '10')
		{
			$bulannama='Oktober';
		}
		else if($bulanini == '11')
		{
			$bulannama='November';
		}
		else if($bulanini == '12')
		{
			$bulannama='Desember';
		}
		?>
		
		<script>
		function ShowHideCapaian() {
			var bulanini 		= document.getElementById("spiderbulanini");
			var sampaibulanini = document.getElementById("spiderkumulatifbulan");
			var sel_value		= document.getElementById("sel_jeniscapaian");
			if(sel_value.value == 1)
			{
				bulanini.style.display='block';
				sampaibulanini.style.display = 'none';
			}
			else
			{
				bulanini.style.display='none';
				sampaibulanini.style.display = 'block';
			}
		}
		
		</script>
	</head>
	
<body>
	<div class="container">
		<!--<div class="alert alert-dismissable alert-success">
			Progress Kegiatan Bulan <strong><?php echo $bulannama; ?></strong>  sampai dengan tanggal <strong><?php echo  date('d').' '.$bulannama.' '.date('Y'); ?> </strong> 
		</div>
		<div class="row col-md-12">
      		<div class="btn-group pull-left">
    			<a href="" class="btn btn-info btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Provinsi</a>
        		<a href=""  class="btn btn-danger btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Kabupaten</a>
       		</div>
		</div>
		<br>-->
		<div class="alert alert-dismissable alert-success">
			<strong>SELAMAT DATANG DI SISTEM MONITORING KEGIATAN BPS PROVINSI KALIMANTAN UTARA</strong> 
		</div>
		<div class="row">
			<div class="col-sm-12">
							<?php 
							//print_r($report);
							$nama_kab = [];
							$nilai = [];
							$color =[];
							if(empty($report))
							{
								echo '<b>Bulan Ini Belum Ada Kegiatan</b>';
							}
							else
							{
								foreach ($report as $result) {
									$nama_kab[] = $result->nama_kab;
									if($result -> rata2nilai >= 0 && $result -> rata2nilai <= 1)
									{
										$color = "'red'";
									}
									else if($result -> rata2nilai > 1 && $result -> rata2nilai <= 3)
									{
										$color = "'orange'";
									}
									else if($result -> rata2nilai > 3 && $result -> rata2nilai <= 4)
									{
										$color = "'green'";
									} 
								$nilai[] =  "{y :".(float) $result -> rata2nilai.",color:".$color."}";
								}
								$nilai_final = json_encode($nilai);
								$nilai_dipakai = str_replace('"','',$nilai_final);
								//print_r($nilai_final);
							}
							?>

									<div id="grafik"></div>
									<script>
										//var chart=null;
										//$(document).ready(function(){
										//$('#report').highcharts({
										Highcharts.chart('grafik', {
												chart: {
												type: 'column',
												margin: 75,
												//renderTo:'report',
													option3d:{
														enabled: false,
														alpha: 10,
														beta: 25,
														depth: 70
													}
												},
												title: {
													text: '<?php echo 'Rata-rata Nilai Kegiatan Bulan '.$bulannama.' '.$tahunini.' Berdasarkan Kabupaten/Kota';?>',
													style: {
														fontSize: '18px',
														fontFamily: 'Verdana, sans-serif'
													}
												},
												subtitle: {
													text: ' ',
													style: {
														fontSize: '18px',
														fontfamily: 'Verdana, sans-serif'
													}
												},
												plotOptions: {
													column: {
														depth: 25,
														color:'#52B2E5',
													}
												},
												credits : {
													enabled: false
												},
												xAxis: {
													categories: <?php echo json_encode($nama_kab); ?>,
													        labels: {
																style: {
																	fontSize: '9px'
																}
															}
												},
												exporting: {
													enabled: false
												},
												yAxis: {
												    max : 4,
													title: {
														text: 'Nilai'
													},
												},
												tooltip: {
													formatter: function() {
														return 'Nilai <b>'+ this.x + '</b> adalah <b>' + Highcharts.numberFormat(this.y,1);
													}
												},
												series: [{
													name: 'Rata-rata Nilai',
													data: <?php echo $nilai_dipakai; ?>,
													shadow: true,
													dataLabels: {
														enabled: true,
														color: '#045396',
														align: 'center',
														formatter: function() {
															return Highcharts.numberFormat(this.y, 1);
														},
													y: 0,
													style: {
														fontSize: '13px',
														fontFamily: 'Verdana, sans-serif'
													}
													}
												}]
											});
										//});
									</script>
	
			</div>
		</div>
		
	
	    <div class="row">
			<div class="col-sm-12">
							<?php 
							//print_r($report);
							$nama_kab = [];
							$jmlhkegiatan = [];
							$color =[];
							if(empty($report5))
							{
								echo '<b>Bulan Ini Belum Ada Kegiatan</b>';
							}
							else
							{
								foreach ($report5 as $result5) {
									$nama_kab[] = $result5->nama_kab;
									$jmlhkegiatan[] = (int) $result5 -> total_kegiatan;
								}
							}
							?>

									<div id="grafik5"></div>
									<script>
										//var chart=null;
										//$(document).ready(function(){
										//$('#report').highcharts({
										Highcharts.chart('grafik5', {
												chart: {
												type: 'column',
												margin: 75,
												//renderTo:'report',
													option3d:{
														enabled: false,
														alpha: 10,
														beta: 25,
														depth: 70
													}
												},
												title: {
													text: '<?php echo 'Jumlah Kegiatan Bulan '.$bulannama.' Berdasarkan Kabupaten/Kota';?>',
													style: {
														fontSize: '18px',
														fontFamily: 'Verdana, sans-serif'
													}
												},
												subtitle: {
													text: ' ',
													style: {
														fontSize: '18px',
														fontfamily: 'Verdana, sans-serif'
													}
												},
												plotOptions: {
													column: {
														depth: 25,
														color:'#18BC9C',
													}
												},
												credits : {
													enabled: false
												},
												xAxis: {
													categories: <?php echo json_encode($nama_kab); ?>,
													        labels: {
																style: {
																	fontSize: '9px'
																}
															}
												},
												exporting: {
													enabled: false
												},
												yAxis: {
														title: {
														text: 'Nilai'
													},
												},
												tooltip: {
													formatter: function() {
														return 'Jumlah Kegiatan <b>'+ this.x + '</b> adalah <b>' + Highcharts.numberFormat(this.y,0);
													}
												},
												series: [{
													name: 'Jumlah Kegiatan',
													data: <?php echo json_encode($jmlhkegiatan); ?>,
													shadow: true,
													dataLabels: {
														enabled: true,
														color: '#045396',
														align: 'center',
														formatter: function() {
															return Highcharts.numberFormat(this.y, 0);
														},
													y: 0,
													style: {
														fontSize: '13px',
														fontFamily: 'Verdana, sans-serif'
													}
													}
												}]
											});
										//});
									</script>
	
			</div>
		</div>
		
		
	
	
	
	
	
		<div class="row">
			<!--by bulan-->
			<div class="col-sm-6">
				&nbsp;
				<?php 
						//print_r($report);
								$nama_bidang = [];
								$id_bidang = [];
								$nilai = [];
								foreach ($report4 as $result) {
									$id_bidang[] = $result->id_bidang;
									
									if($result->id_bidang=='921')
									{
											$nama_bidang_toadd='Bagian Umum';
									}
									else if($result->id_bidang=='922')
									{
											$nama_bidang_toadd='Fungsi Statistik Sosial';
									}
									else if($result->id_bidang=='923')
									{
											$nama_bidang_toadd='Fungsi Statistik Produksi';
									}
									else if($result->id_bidang=='924')
									{
											$nama_bidang_toadd='Fungsi Statistik Distribusi';
									}
									else if($result->id_bidang=='925')
									{
											$nama_bidang_toadd='Fungsi Nerwilis';
									}
									else if($result->id_bidang=='926')
									{
											$nama_bidang_toadd='Fungsi IPDS';
									}
									array_push($nama_bidang,$nama_bidang_toadd);
									
									$nilai[] = (float) $result -> rata_rata;
								}
							?>
								<div id="grafik4"></div>
									<script>
										//var chart=null;
										//$(document).ready(function(){
										//$('#report').highcharts({
										Highcharts.chart('grafik4', {
												chart: {
												type: 'column',
												margin: 75,
												//renderTo:'report',
													option3d:{
														enabled: false,
														alpha: 10,
														beta: 25,
														depth: 70
													}
												},
												title: {
													text:  '<?php echo 'Rata-rata Nilai Kegiatan Bulan '.$bulannama.' '.$tahunini.' Berdasarkan Bagian/Fungsi';?>' ,
													style: {
														fontSize: '18px',
														fontFamily: 'Verdana, sans-serif'
													}
												},
												subtitle: {
													text: ' ',
													style: {
														fontSize: '14px',
														fontfamily: 'Verdana, sans-serif'
													}
												},
												plotOptions: {
													column: {
														depth: 25,
														color:'#DD4814',
													}
													
												},
												credits : {
													enabled: false
												},
												xAxis: {
													categories: <?php echo json_encode($nama_bidang); ?>,
													        labels: {
																style: {
																	fontSize: '9px'
																}
															}
												},
												exporting: {
													enabled: false
												},
												yAxis: {
													title: {
														text: 'Nilai'
													},
												},
												tooltip: {
													formatter: function() {
														return 'Nilai <b>'+ this.x + '</b> adalah <b>' + Highcharts.numberFormat(this.y,1);
													}
												},
												series: [{
													name: 'Rata-rata Nilai',
													data: <?php echo json_encode($nilai); ?>,
													shadow: true,
													dataLabels: {
														enabled: true,
														color: '#045396',
														align: 'center',
														formatter: function() {
															return Highcharts.numberFormat(this.y, 1);
														},
													y: 0,
													style: {
														fontSize: '12px',
														fontFamily: 'Verdana, sans-serif'
													}
													}
												}]
											});
										//});
									</script>	
			</div>
			<!--end of by bulan-->
		
			<div class="col-sm-6">
				&nbsp;
				<?php 
						//print_r($report);
								$nama_bidang = [];
								$id_bidang = [];
								$nilai = [];
								foreach ($report2 as $result) {
									$id_bidang[] = $result->bidang;
									
									if($result->bidang=='921')
									{
											$nama_bidang_toadd='Bagian Umum';
									}
									else if($result->bidang=='922')
									{
											$nama_bidang_toadd='Fungsi Statistik Sosial';
									}
									else if($result->bidang=='923')
									{
											$nama_bidang_toadd='Fungsi Statistik Produksi';
									}
									else if($result->bidang=='924')
									{
											$nama_bidang_toadd='Fungsi Statistik Distribusi';
									}
									else if($result->bidang=='925')
									{
											$nama_bidang_toadd='Fungsi Nerwilis';
									}
									else if($result->bidang=='926')
									{
											$nama_bidang_toadd='Fungsi IPDS';
									}
									array_push($nama_bidang,$nama_bidang_toadd);
									
									$nilai[] = (float) $result -> rata_rata;
								}
							?>
								<div id="grafik2"></div>
									<script>
										//var chart=null;
										//$(document).ready(function(){
										//$('#report').highcharts({
										Highcharts.chart('grafik2', {
												chart: {
												type: 'column',
												margin: 75,
												//renderTo:'report',
													option3d:{
														enabled: false,
														alpha: 10,
														beta: 25,
														depth: 70
													}
												},
												title: {
													text:  'Rata-rata Nilai Menurut Bagian/Fungsi selama Tahun Berjalan' ,
													style: {
														fontSize: '18px',
														fontFamily: 'Verdana, sans-serif'
													}
												},
												subtitle: {
													text: ' ',
													style: {
														fontSize: '14px',
														fontfamily: 'Verdana, sans-serif'
													}
												},
												plotOptions: {
													column: {
														depth: 25,
														color:'#00AAFF',
													}
													
												},
												credits : {
													enabled: false
												},
												xAxis: {
													categories: <?php echo json_encode($nama_bidang); ?>,
													        labels: {
																style: {
																	fontSize: '9px'
																}
															}
												},
												exporting: {
													enabled: false
												},
												yAxis: {
													title: {
														text: 'Nilai'
													},
												},
												tooltip: {
													formatter: function() {
														return 'Nilai <b>'+ this.x + '</b> adalah <b>' + Highcharts.numberFormat(this.y,1);
													}
												},
												series: [{
													name: 'Rata-rata Nilai',
													data: <?php echo json_encode($nilai); ?>,
													shadow: true,
													dataLabels: {
														enabled: true,
														color: '#045396',
														align: 'center',
														formatter: function() {
															return Highcharts.numberFormat(this.y, 1);
														},
													y: 0,
													style: {
														fontSize: '12px',
														fontFamily: 'Verdana, sans-serif'
													}
													}
												}]
											});
										//});
									</script>	
			</div>
			
			
			
			
			</div>
			<!--tambahan 9 Maret 2021 -->
			<br>
			<div id="select_spider" class="row">
				<div class="col-sm-12">
						  <select id="sel_jeniscapaian" name="sel_jeniscapaian" class="form-control" onchange="ShowHideCapaian()">
							<option value='1'> Realisasi dan Target Bulan Ini</option>
							<option value='2'> Realisasi dan Target Kumulatif sampai Bulan ini </option>
						  </select>
				</div>
			</div>
			
			<div id="spiderbulanini" class="row">
			<div class="col-sm-12">
			<?php 
						//print_r($report);
								$arrtarget		 = [];
								$arrrealisasi	 = [];
								foreach ($target as $resulttarget) {
									$arrtarget[] = (float) $resulttarget -> target;
								}
								
								foreach ($realisasi as $resultrealisasi) {
									$arrrealisasi[] = (float) $resultrealisasi -> realisasi;
								}
								
							?>
			<div id="spiderweb" style="height:600px"></div>
			<script>
					//spiderchart
				Highcharts.chart('spiderweb', {

				chart: {
					polar: true,
					type: 'line'
				},

				accessibility: {
					description: 'Deskripsi'
				},

				title: {
					text: 'Jumlah Realisasi dan Target Bulan Ini Berdasarkan Kabupaten/Kota',
					x: -80
				},

				pane: {
					size: '90%'
				},

				xAxis: {
					categories: ['Malinau','Bulungan','Tana Tidung','Nunukan','Kota Tarakan'],
					tickmarkPlacement: 'on',
					lineWidth: 0
				},

				yAxis: {
					gridLineInterpolation: 'polygon',
					lineWidth: 0,
					min: 0
				},

				tooltip: {
					shared: true,
					pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
				},

				legend: {
					align: 'right',
					verticalAlign: 'middle',
					layout: 'vertical'
				},

				series: [{
					name: 'Target',
					data: <?php echo json_encode($arrtarget); ?>,
					pointPlacement: 'on',
					color:'#19C3FF'
				}, {
					name: 'Realisasi',
					data: <?php echo json_encode($arrrealisasi); ?>,
					pointPlacement: 'on',
					color:'red'
				}],

				responsive: {
					rules: [{
						condition: {
							maxWidth: 800
						},
						chartOptions: {
							legend: {
								align: 'center',
								verticalAlign: 'bottom',
								layout: 'horizontal'
							},
							pane: {
								size: '100%'
							}
						}
					}]
				}

			});
			</script>
			</div>			
			</div>
			
			<div id="spiderkumulatifbulan" class="row"  style="display:none">
			<div class="col-sm-12">
			<?php 
						//print_r($report);
								$arrtargetkumulatif		 = [];
								$arrrealisasikumulatif	 = [];
								foreach ($targetkumulatif as $resulttargetkumulatif) {
									$arrtargetkumulatif[] = (float) $resulttargetkumulatif -> target;
								}
								
								foreach ($realisasikumulatif as $resultrealisasikumulatif) {
									$arrrealisasikumulatif[] = (float) $resultrealisasikumulatif -> realisasi;
								}
								
							?>
			<div id="spiderwebkumulatif" style="height:600px"></div>
			<script>
					//spiderchart
				Highcharts.chart('spiderwebkumulatif', {

				chart: {
					polar: true,
					type: 'line'
				},

				accessibility: {
					description: 'Deskripsi'
				},

				title: {
					text: 'Jumlah Realisasi dan Target Kumulatif Sampai Bulan ini Berdasarkan Kabupaten/Kota',
					x: -80
				},

				pane: {
					size: '90%'
				},

				xAxis: {
					categories: ['Malinau','Bulungan','Tana Tidung','Nunukan','Kota Tarakan'],
					tickmarkPlacement: 'on',
					lineWidth: 0
				},

				yAxis: {
					gridLineInterpolation: 'polygon',
					lineWidth: 0,
					min: 0
				},

				tooltip: {
					shared: true,
					pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
				},

				legend: {
					align: 'right',
					verticalAlign: 'middle',
					layout: 'vertical'
				},

				series: [{
					name: 'Target',
					data: <?php echo json_encode($arrtargetkumulatif); ?>,
					pointPlacement: 'on',
					color:'#19C3FF'
				}, {
					name: 'Realisasi',
					data: <?php echo json_encode($arrrealisasikumulatif); ?>,
					pointPlacement: 'on',
					color:'red'
				}],

				responsive: {
					rules: [{
						condition: {
							maxWidth: 800
						},
						chartOptions: {
							legend: {
								align: 'center',
								verticalAlign: 'bottom',
								layout: 'horizontal'
							},
							pane: {
								size: '100%'
							}
						}
					}]
				}

			});
			</script>
			</div>			
			</div>
			<!-- end of tambahan 9 maret 2021-->
			
		<!--	<div class="row">			
			<div class="col-sm-12">
				&nbsp;
				<?php 

							//print_r($report);
								$nama_bulan = [];
								$id_bulan = [];
								$nilai = [];
								foreach ($report3 as $result) {
									$id_bulan[] = $result->bulan;
									
									if($result->bulan == '1')
									{
										$bulannama='Januari';
									}
									else if($result->bulan == '2')
									{
										$bulannama='Februari';
									}
									else if($result->bulan == '3')
									{
										$bulannama='Maret';
									}
									else if($result->bulan == '4')
									{
										$bulannama='April';
									}
									else if($result->bulan == '5')
									{
										$bulannama='Mei';
									}
									else if($result->bulan == '6')
									{
										$bulannama='Juni';
									}
									else if($result->bulan == '7')
									{
										$bulannama='Juli';
									}
									else if($result->bulan == '8')
									{
										$bulannama='Agustus';
									}
									else if($result->bulan == '9')
									{
										$bulannama='September';
									}
									else if($result->bulan == '10')
									{
										$bulannama='Oktober';
									}
									else if($result->bulan == '11')
									{
										$bulannama='November';
									}
									else if($result->bulan == '12')
									{
										$bulannama='Desember';
									}
									array_push($nama_bulan,$bulannama);
									
									$nilai[] = (float) $result -> jumlah_kegiatan;
								}
							?>
								<div id="grafik3"></div>
									<script>
										//var chart=null;
										//$(document).ready(function(){
										//$('#report').highcharts({
										Highcharts.chart('grafik3', {
												chart: {
												type: 'column',
												margin: 75,
												//renderTo:'report',
													option3d:{
														enabled: false,
														alpha: 10,
														beta: 25,
														depth: 70
													}
												},
												title: {
													text:  'Grafik Jumlah Kegiatan Perbulan Tahun 2019' ,
													style: {
														fontSize: '18px',
														fontFamily: 'Verdana, sans-serif'
													}
												},
												subtitle: {
													text: ' ',
													style: {
														fontSize: '18px',
														fontfamily: 'Verdana, sans-serif'
													}
												},
												plotOptions: {
													column: {
														depth: 25,
														color:'#18BC9C',
													}
												},
												credits : {
													enabled: false
												},
												xAxis: {
													categories: <?php echo json_encode($nama_bulan); ?>
												},
												exporting: {
													enabled: false
												},
												yAxis: {
													title: {
														text: 'Nilai'
													},
												},
												tooltip: {
													formatter: function() {
														return 'Nilai <b>'+ this.x + '</b> adalah <b>' + Highcharts.numberFormat(this.y,1);
													}
												},
												series: [{
													name: 'Jumlah Kegiatan',
													data: <?php echo json_encode($nilai); ?>,
													shadow: true,
													dataLabels: {
														enabled: true,
														color: '#045396',
														align: 'center',
														formatter: function() {
															return Highcharts.numberFormat(this.y, 0);
														},
													y: 0,
													style: {
														fontSize: '13px',
														fontFamily: 'Verdana, sans-serif'
													}
													}
												}]
											});
										//});
									</script>	
			</div>
		</div>-->

	</div> <!--for container div-->

</body>
</html>