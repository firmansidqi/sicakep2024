      <div class="clearfix">

		<div class="alert alert-dismissable alert-success">
			Selamat datang <strong><?php echo $this->session->userdata('admin_nama'); ?></strong> 
		</div>
		
		
		<div class="panel panel-flat">
		  <div class="panel-body">
			<div class="chart-container">
			<div class="col-md-4">
			  <center><i class="label label-info"><strong>Bagian Umum</strong></i></center>
			  <hr>
			   <center><div class="chart has-fixed-height" id="basic_bars"></div></center>
			</div>
			<div class="col-md-4">
				<center><i class="label label-default"><strong>Fungsi Statistik Sosial</strong></i></center>
				<hr>
				<center><div class="chart has-fixed-height" id="basic_line"></div></center>
			</div>
			<!--<br><br><br>-->
			<div class="col-md-4">
			  <center><i class="label label-primary"><strong>Fungsi Statistik Produksi</strong></i></center>
			  <hr>
			  <center><div class="chart has-fixed-height" id="basic_columns"></div></center>
			</div>
			<br><br><br>
			<div class="col-md-4">
			  <center><i class="label label-info"><strong>Fungsi Statistik Distribusi</strong></i></center>
			  <hr>
			  <center><div class="chart has-fixed-height" id="basic_bars"></div></center>
			</div>
			<div class="col-md-4">
				<center><i class="label label-default"><strong>Fungsi Nerwilis</strong></i></center>
				<hr>
				<center><div class="chart has-fixed-height" id="basic_line"></div></center>
			</div>
			<br><br><br>
			<div class="col-md-4">
			  <center><i class="label label-primary"><strong>Fungsi IPDS</strong></i></center>
			  <hr>
			  <center><div class="chart has-fixed-height" id="basic_columns"></div></center>
			</div>
			</div>
		  </div>
	</div>
	<script>
	$(function () {
			require.config({
				paths: {
					echarts: 'aset/chart/plugins/visualization/echarts'
				}
			});
			require(
				[
					'echarts',
					'echarts/theme/limitless',
					'echarts/chart/bar',
					'echarts/chart/line'
				],
				function (ec, limitless) {
					var tu = ec.init(document.getElementById('tu'), limitless);
					var sos = ec.init(document.getElementById('sos'), limitless);
					var prod = ec.init(document.getElementById('prod'), limitless);
					tu_options = {
						grid: {
							x: 40,
							x2: 40,
							y: 35,
							y2: 25
						},
						tooltip: {
							trigger: 'axis',
							axisPointer: {
								type: 'shadow'
							}
						},
						legend: {
							data: ['Laki-Laki', 'Perempuan']
						},
						calculable: true,
						xAxis: [{
							type: 'value',
							boundaryGap: [0, 1]
						}],
						yAxis: [{
							type: 'category',
							data: ['']
						}],
						series: [
							{
								name: 'Laki-Laki',
								type: 'bar',
								itemStyle: {
									normal: {
										color: '#EF5350'
									}
								},
								data: [<?php echo $this->db->query("select nik from penduduk where jk='Laki-laki'")->num_rows(); ?>]
							},
							{
								name: 'Perempuan',
								type: 'bar',
								itemStyle: {
									normal: {
										color: '#66BB6A'
									}
								},
								data: [<?php echo $this->db->query("select nik from penduduk where jk='Perempuan'")->num_rows(); ?>]
							}
						]
					};
					sos_options = {
						grid: {
							x: 40,
							x2: 40,
							y: 35,
							y2: 25
						},
						tooltip: {
							trigger: 'axis',
							axisPointer: {
								type: 'shadow'
							}
						},
						legend: {
							data: ['Meninggal', 'Hidup']
						},
						calculable: true,
						yAxis: [{
							type: 'value',
							boundaryGap: [0, 1]
						}],
						xAxis: [{
							type: 'category',
							data: ['']
						}],
						series: [
							{
								name: 'Meninggal',
								type: 'bar',
								itemStyle: {
									normal: {
										color: '#EF5350'
									}
								},
								data: [<?php echo getjumstatus(2); ?>]
							},
							{
								name: 'Hidup',
								type: 'bar',
								itemStyle: {
									normal: {
										color: '#66BB6A'
									}
								},
								data: [<?php echo getjumstatus(1); ?>]
							}
						]
					};
					prod_options = {

						// Setup grid
						grid: {
							x: 40,
							x2: 40,
							y: 35,
							y2: 25
						},

						// Add tooltip
						tooltip: {
							trigger: 'axis'
						},

						// Add legend
						legend: {
							data: ['Jumlah']
						},

						// Enable drag recalculate
						calculable: true,

						// Horizontal axis
						xAxis: [{
							type: 'category',
							data: [<?php $no=0; foreach($agama as $row): $no++;?>'<?php echo $row->agama; ?>',<?php endforeach;?>]
						}],

						// Vertical axis
						yAxis: [{
							type: 'value'
						}],

						// Add series
						series: [
							{
								name: 'Jumlah',
								type: 'line',
								data: [<?php $no=0; foreach($agama as $row): $no++;?>'<?php echo getjumagama($row->id_agama); ?>',<?php endforeach;?>],
								itemStyle: {
									normal: {
										label: {
											show: true,
											textStyle: {
												fontWeight: 500
											}
										}
									}
								},
							},
							
						]
					};



					// Apply options
					// ------------------------------

					basic_columns.setOption(basic_columns_options);
					basic_bars.setOption(basic_bars_options);
					basic_line.setOption(basic_line_options);
					window.onresize = function () {
						setTimeout(function (){
							basic_columns.resize();
							basic_bars.resize();
							basic_line.resize();
						}, 200);
					}
				}
			);
		});
		</script>
		
<div id="chart" style="width: 500px; height: 350px;"></div>
    	<script>
    		var chart = document.getElementById('chart');
    		var myChart = echarts.init(chart);
    		var option = {
    			title: { text: 'ECharts Sample' },
    			tooltip: { },
    			legend: { data: [ 'Sales' ] },
    			xAxis: { data: [ "shirt", "cardign", "chiffon shirt", "pants", "heels", "socks" ] },
    			yAxis: { },
    			series: [{
    				name: 'Sales',
    				type: 'bar',
    				data: [5, 20, 36, 10, 10, 20]
    			}]
    		};
    		myChart.setOption(option);
    	</script>
		
      </div>
