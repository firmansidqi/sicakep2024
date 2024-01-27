<html>
	<head>
		<meta charset="utf-8">
		<?php
		$hariini=date("d-m-Y");
		$bulanini = substr($hariini,3,2);
		$query_tu=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3351' and substring(batas_waktu,6,2) = '$bulanini' LIMIT 1")->row();
		$query_sos=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3352' and substring(batas_waktu,6,2) = '$bulanini' LIMIT 1")->row();
		$query_prod=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3353' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$query_dist=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3354' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$query_ner=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3355' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$query_ipds=$this->db->query("SELECT *,round((sum(realisasi)/sum(target)*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,4)='3356' and substring(batas_waktu,6,2) = '$bulanini'  LIMIT 1")->row();
		$capaian_tu =$query_tu->persen;
		$capaian_sos =$query_sos->persen;
		$capaian_prod =$query_prod->persen;
		$capaian_dist =$query_dist->persen;
		$capaian_ner =$query_ner->persen;
		$capaian_ipds =$query_ipds->persen;
		
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
		<!--Pie Chart starts here-->
		<script type="text/javascript">
			$(function (){
				Highcharts.theme = {
				   colors: ['#8085e9','#f45b5b','#8d4654', '#7798BF', '#aaeeee', '#ff0066', '#eeaaee',
					  '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
				   chart: {
					  backgroundColor: null,
					  style: {
						 fontFamily: 'Signika, serif'
					  }
				   },
				   title: {
					  style: {
						 color: 'black',
						 fontSize: '16px',
						 fontWeight: 'bold'
					  }
				   },
				   subtitle: {
					  style: {
						 color: 'black'
					  }
				   },
				   tooltip: {
					  borderWidth: 0
				   },
				   legend: {
					  itemStyle: {
						 fontWeight: 'bold',
						 fontSize: '13px'
					  }
				   },
				   xAxis: {
					  labels: {
						 style: {
							color: '#6e6e70'
						 }
					  }
				   },
				   yAxis: {
					  labels: {
						 style: {
							color: '#6e6e70'
						 }
					  }
				   },
				   plotOptions: {
					  series: {
						 shadow: true
					  },
					  candlestick: {
						 lineColor: '#404048'
					  },
					  map: {
						 shadow: false
					  }
				   },

				   // Highstock specific
				   navigator: {
					  xAxis: {
						 gridLineColor: '#D0D0D8'
					  }
				   },
				   rangeSelector: {
					  buttonTheme: {
						 fill: 'white',
						 stroke: '#C0C0C8',
						 'stroke-width': 1,
						 states: {
							select: {
							   fill: '#D0D0D8'
							}
						 }
					  }
				   },
				   scrollbar: {
					  trackBorderColor: '#C0C0C8'
				   },

				   // General
				   background2: '#E0E0E8'

				};

				// Apply the theme
				Highcharts.setOptions(Highcharts.theme);

		
						var pieChart;
						$(document).ready(function(){
							$('#tu').highcharts({
							chart:  {
									type: 'pie',
									options3d: {
										enabled: true,
										alpha: 45
									}
								},
								title: {
									text: 'Bagian Umum'
								},
								exporting: {
									enabled: false
								},
								plotOptions: {
									pie: {
										innerSize: 100,
										depth: 45,
										dataLabels: {
												enabled: false,
											}
									}
								},
								series: [{
									name: ' ',
									data: [
										['Sudah Selesai', <?php echo $capaian_tu;?>],
										['Belum Selesai', <?php echo 100 - $capaian_tu;?>]

									]
								}]
								});
							});	
					//<!--Pie chart ends here-->
					
					//<!--bar stacked chart start here-->
					$('#sos').highcharts({
					chart:  {
							type: 'pie',
							options3d: {
								enabled: true,
								alpha: 45
							}
						},
						title: {
							text: 'Fungsi Statistik Sosial'
						},
						exporting: {
									enabled: false
						},
						plotOptions: {
							pie: {
								innerSize: 100,
								depth: 45,
								dataLabels: {
												enabled: false,
											}
							}
						},
						series: [{
							name: ' ',
							data: [
								['Sudah Selesai', <?php echo $capaian_sos;?>],
								['Belum Selesai', <?php echo 100-$capaian_sos;?>]

							]
						}]
				});
					//<!--bar stacked chart end here  -->
					
					//<!--bar chart start here-->
				$('#prod').highcharts({
					chart:  {
							type: 'pie',
							options3d: {
								enabled: true,
								alpha: 45
							}
						},
						title: {
							text: 'Fungsi Statistik Produksi'
						},
						exporting: {
									enabled: false
						},
						plotOptions: {
							pie: {
								innerSize: 100,
								depth: 45,
								dataLabels: {
												enabled: false,
											}
							}
						},
						series: [{
							name: ' ',
							data: [
								['Sudah Selesai', <?php echo $capaian_prod;?>],
								['Belum Selesai', <?php echo 100 - $capaian_prod;?>]

							]
						}]
				});
				
					//<!--bar chart start here-->
					
					//<!--Line chart start here-->
					$('#dist').highcharts({
					chart:  {
							type: 'pie',
							options3d: {
								enabled: true,
								alpha: 45
							}
						},
						title: {
							text: 'Fungsi Statistik Distribusi'
						},
						exporting: {
									enabled: false
						},
						plotOptions: {
							pie: {
								innerSize: 100,
								depth: 45,
								dataLabels: {
												enabled: false,
											}
							}
						},
						series: [{
							name: ' ',
							data: [
								['Sudah Selesai', <?php echo $capaian_dist;?>],
								['Belum Selesai', <?php echo 100 - $capaian_dist;?>]

							]
						}]
				});
				
				//<!--Line chart ends here-->
					//<!--bar stacked chart start here-->
					$('#ner').highcharts({
					chart:  {
							type: 'pie',
							options3d: {
								enabled: true,
								alpha: 45
							}
						},
						title: {
							text: 'Fungsi Nerwilis'
						},
						exporting: {
									enabled: false
						},
						plotOptions: {
							pie: {
								innerSize: 100,
								depth: 45,
								dataLabels: {
												enabled: false,
											}
							}
						},
						series: [{
							name: ' ',
							data: [
								['Sudah Selesai', <?php echo $capaian_ner;?>],
								['Belum Selesai', <?php echo 100 - $capaian_ner;?>]

							]
						}]
				});
					//<!--bar stacked chart end here  -->	
				
				//<!--bar stacked chart start here-->
					$('#ipds').highcharts({
					chart:  {
							type: 'pie',
							options3d: {
								enabled: true,
								alpha: 45
							}
						},
						title: {
							text: 'Fungsi IPDS'
						},
						exporting: {
									enabled: false
						},
						plotOptions: {
							pie: {
								innerSize: 100,
								depth: 45,
								dataLabels: {
												enabled: false,
											}
							}
						},
						series: [{
							name: ' ',
							data: [
								['Sudah Selesai', <?php echo $capaian_ipds;?>],
								['Belum Selesai', <?php echo 100 - $capaian_ipds;?>]

							]
						}]

				});
					//<!--bar stacked chart end here  -->				

			});///<!--This is for the main function-->
		</script>
<!--Charts end here-->
</head>
	
<body>
	<div class="container">
		<div class="alert alert-dismissable alert-success">
			Progress Kegiatan Bulan <strong><?php echo $bulannama; ?></strong>  sampai dengan tanggal <strong><?php echo  date('d').' '.$bulannama.' '.date('Y'); ?> </strong> 
		</div>
			<!--First chart-->
				<div class="col-md-4">		
					<div id="tu" style="min-width: 250px; height: 250px; margin:30 auto"></div>	
				</div>
			<!--Second chart-->
				<div class="col-md-4">
					<div id="sos" style="min-width: 250px; height: 250px; margin: 30 auto"></div>
				</div>
			<!--Third chart-->
				<div class="col-md-4">
					<div id="prod" style="min-width: 250px; height: 250px; margin: 30 auto"></div>
				</div>
				<br><br>
			<!--Fourth chart-->
				<div class="col-md-4">
					<div id="dist" style="min-width: 250px; height: 250px; margin: 30 auto"></div>
				</div>
			<!--Fifth chart-->
				<div class="col-md-4">
					<div id="ner" style="min-width: 250px; height: 250px; margin: 30 auto"></div>
				</div>
			<!--SIxth chart-->
				<div class="col-md-4">
					<div id="ipds" style="min-width: 250px; height: 250px; margin: 30 auto"></div>
				</div>
			<!--End of charts-->
	</div> <!--for container div-->

</body>
</html>