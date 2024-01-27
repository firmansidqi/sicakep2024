<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<head>
		<title>.:: CAKEP (Capaian Kegiatan Pegawai) by BPS Provinsi Kalimantan Utara ::.</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<style type="text/css">
			@font-face {
				font-family: 'Cabin';
				font-style: normal;
				font-weight: 400;
				src: local('Cabin Regular'), local('Cabin-Regular'), url(<?php echo base_url(); ?>aset/font/satu.woff) format('woff');
			}
			@font-face {
				font-family: 'Cabin';
				font-style: normal;
				font-weight: 700;
				src: local('Cabin Bold'), local('Cabin-Bold'), url(<?php echo base_url(); ?>aset/font/dua.woff) format('woff');
			}
			@font-face {
				font-family: 'Lobster';
				font-style: normal;
				font-weight: 400;
				src: local('Lobster'), url(<?php echo base_url(); ?>aset/font/tiga.woff) format('woff');
			}	
			
		</style>
		<link rel="stylesheet" href="<?php echo base_url(); ?>aset/css/bootstrap.css" media="screen">
		<link rel="stylesheet" href="<?php echo base_url(); ?>aset/js/jquery/jquery-ui.css" />
		<link href="<?php echo base_url(); ?>aset/css/datatables.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>aset/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link rel="shortcut icon" href="<?php echo base_url('aset/img/bps.ico');?>" type="image/ico">
		
		<script src="<?php echo base_url(); ?>aset/js/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/jquery.sortElements.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/bootswatch.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/jquery/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/datatables.min.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/app.js"></script>
		<script type="text/javascript">
	// <![CDATA[
			$(document).ready(function () {

				$(function() {
					$( "#batas_waktu" ).datepicker({
						changeMonth: true,
						changeYear: true,
						dateFormat: 'yy-mm-dd'
					});
				});
				
				$(function() {
					$( "#tgl_entri" ).datepicker({
						changeMonth: true,
						changeYear: true,
						dateFormat: 'yy-mm-dd'
					});
				});
				
			});
	// ]]>
		</script>

		
		<header>
			<div class="navbar navbar-inverse navbar-fixed-top navbar" style="z-index:999">
				<div class="container">
					<div class="navbar-header">
						<img src="<?php echo base_url(); ?>upload/logo2.jpg" class="thumbnail span3" style="display: inline; float: left; margin-right: 10px; width:60px; height: 50px">
						<span class="navbar-brand"><strong style="font-family: verdana; vertical-align: baseline;">BPS KALTARA</strong></span>
						<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="navbar-collapse collapse" id="navbar-main">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="<?php echo base_url(); ?>index.php/admin"><i class="icon-home icon-white"> </i> Beranda</a></li>
							<?php
								if ($this->session->userdata('admin_level') == "Super Admin" || $this->session->userdata('admin_level') == "userprov") 
								{
								?>
								<li><a href="<?php echo base_url(); ?>index.php/admin/kegiatan/"><i class="icon-th icon-white"> </i> Kegiatan </a></li>
								<?php 
								}
								if ($this->session->userdata('admin_level') == "userkabkota") 
								{
								?>
								<li><a href="<?php echo base_url(); ?>index.php/admin/unitkerjakabkotadetail/"><i class="icon-film icon-white"> </i> Progress </a></li>
								<?php
								}
								else
								{
								?>
								<li><a href="<?php echo base_url(); ?>index.php/admin/unitkerjaprov/"><i class="icon-film icon-white"> </i> Progress </a></li>	
								<?php
								}
							?>
							<li><a href="<?php echo base_url(); ?>index.php/admin/laporan_kab/"><i class="icon-file icon-white"> </i> Laporan </a></li>
							<?php
								if ($this->session->userdata('admin_level') == "Super Admin" || $this->session->userdata('admin_level') == "userprov") 
								{
								?>
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes"><i class="icon-th-list icon-white"> </i> Master <span class="caret"></span></a>
									<ul class="dropdown-menu" aria-labelledby="themes">
										<!--<li><a href="<?php echo base_url(); ?>index.php/admin/kelolakegiatan/"><i class="icon-list icon-black"> </i> Kegiatan</a></li>-->
										<li><a href="<?php echo base_url(); ?>index.php/admin/satuan/">Satuan</a></li>
										<li><a href="<?php echo base_url(); ?>index.php/admin/duplikasikegiatan/">Duplikasi Kegiatan</a></li>
										<?php
											if ($this->session->userdata('admin_user') == "admin" || $this->session->userdata('admin_user') == "6500" ) 
											{
											?>
											<li><a href="<?php echo base_url(); ?>index.php/admin/listkegiatan/">List Kegiatan</a></li>
											<?php
											}
										?>
									</ul>
								</li>
								<!--<li><a href="<?php echo base_url(); ?>index.php/admin/konfirmasi"><i class="icon-retweet icon-white"> </i> Konfirmasi Realisasi Kegiatan</a></li>-->
								<?php 
								}
								if ($this->session->userdata('admin_user') == "admin" || $this->session->userdata('admin_user') == "6500" ) 
								{
								?>
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes"><i class="icon-wrench icon-white"> </i> Pengaturan<span class="caret"></span></a>
									<ul class="dropdown-menu" aria-labelledby="themes">
										<li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/admin/pengguna">Instansi Pengguna</a></li>
										<li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/admin/manage_admin">Manajemen Admin</a></li>
									</ul>
								</li>
								<?php 
								}
							?>
							
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes"><i class="icon-user icon-white"></i> <?php echo $this->session->userdata('admin_nama');?><span class="caret"></span></a>
								<ul class="dropdown-menu" aria-labelledby="themes">
									<li><a tabindex="-1" href="https://bpskaltara.id">Kembali ke SiApik</a></li>
									<li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/admin/set_tahun/2021">Tahun 2021</a></li>
									<li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/admin/passwod">Ubah Password</a></li>
									<li><a tabindex="-1" href="<?php echo base_url(); ?>index.php/admin/logout">Logout Cakep</a></li>
									<?php 
										if($this->session->userdata('logout_url')) { 
										?>
										<li><a tabindex="-1" href="<?php echo $this->session->userdata('logout_url'); ?>">Logout SSO</a></li>
										<?php 
										} 
									?>
								</ul>
							</li>
						</ul>

					</div>
				</div>
			</div>
			<br>
			<?php 
				$q_instansi	= $this->db->query("SELECT * FROM tr_instansi LIMIT 1")->row();
				//echo $this->session->userdata('admin_level');
			?>
			<br>
			<div class="container">

				<div class="page-header" id="banner">
					<div class="row">
						<div class="" style="padding: 5px 5px 0 5px;">

						</div>
					</div>
				</div>


			</header>
		</head>

		<body>
			<div class="container">
				<div class="row">
					<?php $this->load->view('admin/'.$page); ?>
				</div>
			</div>
		</body>
		<br>
		<footer class="main-footer" >
			<div class="span12 well well-sm" style=" bottom: 0px; text-align:center; width: 100%; position: fixed; margin-bottom: 0px;">
				<h5 style="font-weight: bold; align:center; ">CAKEP (Capaian Kegiatan Pegawai) by BPS Provinsi Kalimantan Utara direplikasi dari EVITA by BPS Provinsi Jawa Tengah</h5>
				<!--<h6>&copy;  2017. Waktu Eksekusi : {elapsed_time}, Penggunaan Memori : {memory_usage}</h6>-->
			</div>
		</footer>
	</div>
	</html>