<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('M_kelolakegiatan');
		$this->load->model('M_getfunction');
		$this->load->library('encryption'); //in controller
	}
	
	public function index() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		//variabel untuk select dropdown
		$kab = $this->input->get('pilih_kab');
		$bulan = $this->input->get('bln');
		$tahun = $this->input->get('Tahun');
		
		//tes highchart
		$a['report']                = $this->M_kelolakegiatan->reportDashboard();
		$a['report2']               = $this->M_kelolakegiatan->cobaJumlahKegiatanPerbidang();
		$a['report3']               = $this->M_kelolakegiatan->cobaJumlahKegiatanPerbulan();
		$a['report4']               = $this->M_kelolakegiatan->cobaReportKegiatanPerbidang();
		$a['report5']               = $this->M_kelolakegiatan->cobaJumlahKegiatanPerkabkot();
		$a['target']                = $this->M_kelolakegiatan->gettargetkabkota();
		$a['realisasi']             = $this->M_kelolakegiatan->getrealisasikabkota();
		$a['targetkumulatif']       = $this->M_kelolakegiatan->gettargetkabkotakumulatif();
		$a['realisasikumulatif']    = $this->M_kelolakegiatan->getrealisasikabkotakumulatif();
		//$this->load->view('dashboard', $a);

		//$this->load->model('M_kelolakegiatan');
		$hasil                  = $this->M_kelolakegiatan->cobaLaporanDashboard();
		$hasil2                 = $this->M_kelolakegiatan->jumlahKegiatanPerbidang();
		$hasil3                 = $this->M_kelolakegiatan->jumlahKegiatanPerbulan();
		$hasil4                 = $this->M_kelolakegiatan->ReportKegiatanPerbidang();
		$hasil5                 = $this->M_kelolakegiatan->jumlahKegiatanPerkabkot();
		
		$a['hasil']             = $hasil;
		$a['hasil2']            = $hasil2;
		$a['hasil3']            = $hasil3;
		$a['hasil4']            = $hasil4;
		$a['hasil5']            = $hasil5;
		$a['page']	            = "dashboard_komplit";
		$a['kab']               = $kab;
		$a['bulan']             = $bulan;
		$a['tahun']             = $tahun;
		
		$this->load->view('admin/index', $a);

		$this->input->get();
		/*
		$data = $this->mymodel->cobaLaporan();
		foreach ($data as $tabel) {
			echo "ID Kegiatan".$tabel['id_jeniskegiatan']."<br/>";
		}*/
	}
	
	public function kalender() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		$a['page']	= "f_kalender";
		$this->load->view('admin/index', $a);
	}

	public function pengguna() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}		
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		
		//ambil variabel Postingan
		$idp					= addslashes($this->input->post('idp'));
		$nama					= addslashes($this->input->post('nama'));
		$alamat					= addslashes($this->input->post('alamat'));
		$kepsek					= addslashes($this->input->post('kepsek'));
		$nip_kepsek				= addslashes($this->input->post('nip_kepsek'));
		
		$cari					= addslashes($this->input->post('q'));

		//upload config 
		$config['upload_path'] 		= './upload';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if ($mau_ke == "act_edt") {
			if ($this->upload->do_upload('logo')) {
				$up_data	 	= $this->upload->data();
				
				$this->db->query("UPDATE tr_instansi SET nama = '$nama', alamat = '$alamat', kepsek = '$kepsek', nip_kepsek = '$nip_kepsek', logo = '".$up_data['file_name']."' WHERE id = '$idp'");

			} else {
				$this->db->query("UPDATE tr_instansi SET nama = '$nama', alamat = '$alamat', kepsek = '$kepsek', nip_kepsek = '$nip_kepsek' WHERE id = '$idp'");
			}		

			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated</div>");			
			redirect('index.php/admin/pengguna');
		} else {
			$a['data']		= $this->db->query("SELECT * FROM tr_instansi WHERE id = '1' LIMIT 1")->row();
			$a['page']		= "f_pengguna";
		}
		
		$this->load->view('admin/index', $a);	
	}
	
	public function manage_admin() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM t_admin")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/manage_admin/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel Postingan
		$idp					= addslashes($this->input->post('idp'));
		$username				= addslashes($this->input->post('username'));
		$password				= md5(addslashes($this->input->post('password')));
		$nama					= addslashes($this->input->post('nama'));
		$nip					= addslashes($this->input->post('nip'));
		$level					= addslashes($this->input->post('level'));
		
		$cari					= addslashes($this->input->post('q'));

		
		if ($mau_ke == "del") {
			$this->db->query("DELETE FROM t_admin WHERE id = '$idu'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/manage_admin');
		} else if ($mau_ke == "cari") {
			$a['data']		= $this->db->query("SELECT * FROM t_admin WHERE nama LIKE '%$cari%' ORDER BY id DESC")->result();
			$a['page']		= "l_manage_admin";
		} else if ($mau_ke == "add") {
			$a['page']		= "f_manage_admin";
		} else if ($mau_ke == "edt") {
			$a['datpil']	= $this->db->query("SELECT * FROM t_admin WHERE id = '$idu'")->row();	
			$a['page']		= "f_manage_admin";
		} else if ($mau_ke == "act_add") {	
			$cek_user_exist = $this->db->query("SELECT username FROM t_admin WHERE username = '$username'")->num_rows();

			if (strlen($username) < 4) {
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Username minimal 4 huruf</div>");
				redirect('index.php/admin/manage_admin');
			} else if ($cek_user_exist > 0) {
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Username telah dipakai. Ganti yang lain..!</div>");
				redirect('index.php/admin/manage_admin');	
			} else {
				$this->db->query("INSERT INTO t_admin VALUES (NULL, '$username', '$password', '$nama', '$nip', '$level')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			}
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			redirect('index.php/admin/manage_admin');
		} else if ($mau_ke == "act_edt") {
			if ($password == md5("-")) {
				$this->db->query("UPDATE t_admin SET username = '$username', nama = '$nama', nip = '$nip', level = '$level' WHERE id = '$idp'");
			} else {
				$this->db->query("UPDATE t_admin SET username = '$username', password = '$password', nama = '$nama', nip = '$nip', level = '$level' WHERE id = '$idp'");
			}
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated </div>");			
			redirect('index.php/admin/manage_admin');
		} else {
			$a['data']		= $this->db->query("SELECT * FROM t_admin LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_manage_admin";
		}
		
		$this->load->view('admin/index', $a);
	}

	public function passwod() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ke				= $this->uri->segment(3);
		$id_user		= $this->session->userdata('admin_id');
		
		//var post
		$p1				= md5($this->input->post('p1'));
		$p2				= md5($this->input->post('p2'));
		$p3				= md5($this->input->post('p3'));
		
		if ($ke == "simpan") {
			$cek_password_lama	= $this->db->query("SELECT password FROM t_admin WHERE id = $id_user")->row();
			//echo 
			
			if ($cek_password_lama->password != $p1) {
				$this->session->set_flashdata('k_passwod', '<div id="alert" class="alert alert-error">Password Lama tidak sama</div>');
				redirect('index.php/admin/passwod');
			} else if ($p2 != $p3) {
				$this->session->set_flashdata('k_passwod', '<div id="alert" class="alert alert-error">Password Baru 1 dan 2 tidak cocok</div>');
				redirect('index.php/admin/passwod');
			} else {
				$this->db->query("UPDATE t_admin SET password = '$p3' WHERE id = ".$id_user."");
				$this->session->set_flashdata('k_passwod', '<div id="alert" class="alert alert-success">Password berhasil diperbaharui</div>');
				redirect('index.php/admin/passwod');
			}
		} else {
			$a['page']	= "f_passwod";
		}
		
		$this->load->view('admin/index', $a);
	}
	
	//login
	public function login() {
		if($this->session->userdata('admin_valid'))
			redirect('index.php/admin');

		$this->load->view('admin/login');
	}
	
	public function do_login() {
		$u 		= $this->security->xss_clean($this->input->post('u'));
		$ta 	= $this->security->xss_clean($this->input->post('ta'));
        $p 		= md5($this->security->xss_clean($this->input->post('p')));
         
		$q_cek	= $this->db->query("SELECT * FROM t_admin WHERE username = '".$u."' AND password = '".$p."'");
		$j_cek	= $q_cek->num_rows();
		$d_cek	= $q_cek->row();
		//echo $this->db->last_query();
		
        if($j_cek == 1) {
            $data = array(
                    'admin_id' => $d_cek->id,
                    'admin_user' => $d_cek->username,
                    'admin_nama' => $d_cek->nama,
                    'admin_ta' => $ta,
                    'admin_level' => $d_cek->level,
					'admin_valid' => true,
					'admin_nip' => $d_cek->nip
                    );
            $this->session->set_userdata($data);
            $username = $this->session->userdata('admin_user');
			date_default_timezone_set("Asia/Bangkok");
			$logindate = date('Y-m-d H:i:s');
			$this->db->query("INSERT INTO evita_userlog VALUES (NULL,'$username','login','$logindate','')");
            redirect('index.php/admin');
        } else {	
			$this->session->set_flashdata("k", "<div id=\"alert\" class=\"alert alert-error\">username or password is not valid</div>");
			redirect('index.php/admin/login');
		}
	}
	
	public function logout(){
        $this->session->sess_destroy();
		redirect('index.php/admin/login');
    }
	
	public function progress() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		
		/* pagination 	
		$total_row_all		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan")->num_rows();
		$total_row_tu		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921'")->num_rows();
		$total_row_sos		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922'")->num_rows();
		$total_row_prod		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923'")->num_rows();
		$total_row_dist		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924'")->num_rows();
		$total_row_ner		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925'")->num_rows();
		$total_row_ipds		= $this->db->query("SELECT *,(realisasi/target*100) as persen FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926'")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagiall']	= _page($total_row_all, $per_page, 4, base_url()."index.php/admin/progress/p");
		$a['pagitu']	= _page($total_row_tu, $per_page, 4, base_url()."index.php/admin/progress/p");
		$a['pagisos']	= _page($total_row_sos, $per_page, 4, base_url()."index.php/admin/progress/p");
		$a['pagiprod']	= _page($total_row_prod, $per_page, 4, base_url()."index.php/admin/progress/p");
		$a['pagidist']	= _page($total_row_dist, $per_page, 4, base_url()."index.php/admin/progress/p");
		$a['paginer']	= _page($total_row_ner, $per_page, 4, base_url()."index.php/admin/progress/p");
		$a['pagiipds']	= _page($total_row_ipds, $per_page, 4, base_url()."index.php/admin/progress/p");
		*/
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel post
		$idp					= addslashes($this->input->post('idp'));
		$cari					= addslashes($this->input->post('q'));

		if ($mau_ke == "detail") 
		{
			//echo $idu;
			$a['dataall']		= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'")->result();
			$a['datatu']		= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu' and substring(k.id_jeniskegiatan,1,3)='921'")->result();
			$a['datasos']		= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu' and substring(k.id_jeniskegiatan,1,3)='922'")->result();
			$a['dataprod']		= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu' and substring(k.id_jeniskegiatan,1,3)='923'")->result();
			$a['datadist']		= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu' and substring(k.id_jeniskegiatan,1,3)='924'")->result();
			$a['datanerwil']	= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu' and substring(k.id_jeniskegiatan,1,3)='925'")->result();
			$a['dataipds']		= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu' and substring(k.id_jeniskegiatan,1,3)='926'")->result();
			$a['page']			= "l_progress_kab";
		} 
		else {
			$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen FROM m_jeniskegiatan order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['page']		= "l_progress";
		}
		
		$this->load->view('admin/index', $a);
	}

	public function get_kegiatan() {
		$unitkerja=$this->input->post('unitkerja');
		$bidang = substr($unitkerja,1,3);
		$query 	=  $this->db->query("SELECT * FROM m_jeniskegiatan WHERE substring(id_jeniskegiatan,1,3)='$bidang'");
		?>
		<option value="Kosong">-- Pilih Nama Kegiatan--<?php echo $bidang;?></option>
		<?php
        foreach($query->result() as $row)
        { 
             echo "<option value='".$row->id_jeniskegiatan."'>".$row->nama_kegiatan."</option>";
        }
	}
	
public function entry() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		$kabkota=$this->session->userdata('admin_user') ;
		/* pagination */	
		$total_row		= $this->db->query("SELECT k.*,(k.realisasi/k.target*100) as persen,m.nama_kegiatan FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where id_kab='$kabkota'")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/entry/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel post
		$id_jeniskegiatan		= addslashes($this->input->post('id_jeniskegiatan'));
		$nama_kegiatan			= addslashes($this->input->post('nama_kegiatan'));
		$target					= addslashes($this->input->post('target'));
		$realisasi				= addslashes($this->input->post('realisasi'));
		$bukti					= addslashes($this->input->post('bukti'));
		$newrealisasi			= addslashes($this->input->post('newrealisasi'));

		$tgl_entri				= date('Y-m-d');

		
		$cari					= addslashes($this->input->post('q'));

		//upload config 
		$config['upload_path'] 		= './upload/surat_masuk';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if ($mau_ke == "edt") {
			$kabkota =$this->uri->segment(5);
			$a['datpil']	= $this->db->query("SELECT k.*,m.nama_kegiatan,w.nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan = m.id_jeniskegiatan inner join m_kab w on  k.id_kab=w.id_kab WHERE k.id_jeniskegiatan = '$idu' and k.id_kab='$kabkota'")->row();	
			$a['page']		= "f_entry";
		} 
		else if ($mau_ke == "act_edt")
		{
			if($newrealisasi > $target)
			{
			$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Realisasi Tidak Boleh Melebihi Target</div>");	
			redirect('index.php/admin/entry/edt/'.$id_jeniskegiatan);
			}
			else
			{	
				$tab				= addslashes($this->input->post('tab'));
				if($this->session->userdata('admin_user') != "6500")
				{
				$queryrealisasisekarang =$this->db->query("select realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' ")->result();
				//$datarealisasisekarang = mysql_fetch_array($queryrealisasisekarang);
				$realisasisekarang = $queryrealisasisekarang->realisasi;
				$realisasiterbaru = $queryrealisasisekarang->realisasi + $newrealisasi ;
				
				
				$this->db->query("UPDATE t_kegiatan SET realisasi = '$newrealisasi', tgl_entri = '$tgl_entri', flag_konfirm='2', bukti='$bukti' WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$kabkota'");
				if($realisasiterbaru < $target)
				{
				$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$realisasiterbaru' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
				}
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated. ".$this->upload->display_errors()."</div>");			
				redirect('index.php/admin/entry/');
				}
				else
				{
				$kabkota			= addslashes($this->input->post('kabkota'));
				
				$this->db->query("UPDATE t_kegiatan SET realisasi = '$newrealisasi', tgl_entri = '$tgl_entri' , flag_konfirm='2' WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$kabkota'");
				
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated. ".$this->upload->display_errors()."</div>");			
				redirect('index.php/admin/entry/');
				}
			}
		} else {
		if($this->session->userdata('admin_user') == "6500")
			{
			$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' ")->result();
			$a['page']			= "l_entry";
			}
		else
		{
			$a['dataall']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where id_kab='$kabkota' and k.target <> 0 order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='921' and id_kab='$kabkota' and k.target <> 0  order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='922' and id_kab='$kabkota' and k.target <> 0   order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='923' and id_kab='$kabkota'  and k.target <> 0  order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where substring(k.id_jeniskegiatan,1,3)='924' and id_kab='$kabkota'  and k.target <> 0  order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='925' and id_kab='$kabkota'  and k.target <> 0  order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc  ")->result();
			$a['dataipds']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='926' and id_kab='$kabkota'  and k.target <> 0  order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc  ")->result();
			$a['page']		= "l_entry";
		
		
		}
		}
		
		$this->load->view('admin/index', $a);
	}
	
	
public function kelolakegiatan() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT *,(realisasi/target*100) as persen  FROM m_jeniskegiatan")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/kelolakegiatan/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$cari					= addslashes($this->input->post('q'));
	
		//ambil variabel post
		
		$idp					= addslashes($this->input->post('idp'));
		$unitkerja				= addslashes($this->input->post('unitkerja'));
		$tahun					= addslashes($this->input->post('tahun'));
		//ambil max id
		
		$batas_waktu			= addslashes($this->input->post('batas_waktu'));
		$nama_kegiatan			= addslashes($this->input->post('nama_kegiatan'));
		$dasar_surat			= addslashes($this->input->post('dasar_surat'));
		//$targetprop				= addslashes($this->input->post('targetprop'));
		$tab					= addslashes($this->input->post('tab'));
		//$realisasiprop			= addslashes($this->input->post('realisasiprop'));
		$satuan					= addslashes($this->input->post('satuan'));
		$_6501 					=$this->input->post('_6501');
		$_6502 					=$this->input->post('_6502');
		$_6503					=$this->input->post('_6503');
		$_6504					=$this->input->post('_6504');
		$_6571					=$this->input->post('_6571');
		//Perubahan April-Mei 2023
		$pj_prov				=addslashes($this->input->post('pj_prov'));
		$mulai  				=addslashes($this->input->post('mulai'));
		$pj_6501 				=addslashes($this->input->post('pj_6501'));
		$pj_6502 				=addslashes($this->input->post('pj_6502'));
		$pj_6503				=addslashes($this->input->post('pj_6503'));
		$pj_6504				=addslashes($this->input->post('pj_6504'));
		$pj_6571				=addslashes($this->input->post('pj_6571'));
		
		$cari					= addslashes($this->input->post('q'));

		//upload config 
		$config['upload_path'] 		= './upload/bukti_kirim';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if($mau_ke == "del")
		{
			$id_delete = $this->input->get('delete_id');;
			?>
			<script>
			//window.alert('<?php echo $id_delete;?>');</script>
			<?php
			$a['datpil']	= $this->db->query("select m.* from m_jeniskegiatan as m where m.id_jeniskegiatan='$id_delete'")->row();	
			$a['page']		= "f_del";
		
		}
		else if ($mau_ke == "act_del") {
			$id_jeniskegiatan = addslashes($this->input->post('id_jeniskegiatan'));
			$tab = addslashes($this->input->post('tab'));
			$this->db->query("DELETE FROM m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
			$this->db->query("DELETE FROM t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil dihapus </div>");
			redirect('index.php/admin/kegiatan/');
		} else if ($mau_ke == "cari") {
			if($this->session->userdata('admin_user') == "6500")
			{
			$a['dataall']		= $this->db->query("SELECT *,round((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where m.nama_kegiatan  LIKE '%$cari%' ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' and nama_kegiatan like '%$cari%'")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' and nama_kegiatan like '%$cari%' ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' and nama_kegiatan like '%$cari%'")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' and nama_kegiatan like '%$cari%'")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' and nama_kegiatan like '%$cari%'")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' and nama_kegiatan like '%$cari%'")->result();
			$a['page']		= "l_kelolakegiatan";
			}
		else
		{
			$kabkota=$this->session->userdata('admin_user') ;
			$a['dataall']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where id_kab='$kabkota' and m.nama_kegiatan like '%$cari%' ")->result();
			$a['datatu']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='921' and id_kab='$kabkota' and m.nama_kegiatan like '%$cari%'")->result();
			$a['datasos']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='922' and id_kab='$kabkota'  and m.nama_kegiatan like '%$cari%'")->result();
			$a['dataprod']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='923' and id_kab='$kabkota' and m.nama_kegiatan like '%$cari%'")->result();
			$a['datadist']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where substring(k.id_jeniskegiatan,1,3)='924' and id_kab='$kabkota'  and m.nama_kegiatan like '%$cari%'")->result();
			$a['datanerwil']	= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='925' and id_kab='$kabkota' and m.nama_kegiatan like '%$cari%'")->result();
			$a['dataipds']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='926' and id_kab='$kabkota'  and m.nama_kegiatan like '%$cari%'")->result();
			$a['page']		= "l_kelolakegiatan";
		}
			$a['page']		= "l_kelolakegiatan";
		} else if ($mau_ke == "add") {
			$tab			= $this->uri->segment(4);
			$a['page']		= "f_tambahkegiatan";
		} else if ($mau_ke == "edt") {
			$tab			= $this->uri->segment(5);
			$a['datpil']	= $this->db->query("SELECT k.*,m.nama_kegiatan,m.satuan,m.batas_waktu,m.pj_prov, m.mulai, m.dasar_surat, w.nama_kab,m.target as targetprop, m.realisasi as realisasiprop,m.batas_waktu as batas_waktu FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan = m.id_jeniskegiatan inner join m_kab w on  k.id_kab=w.id_kab WHERE k.id_jeniskegiatan = '$idu'")->row();	
			$a['page']		= "f_kelolakegiatan";
		} else if ($mau_ke == "act_add") {
		    
		    
			$query = $this->db->query("select max(convert((substring(id_jeniskegiatan,10,length(id_jeniskegiatan-9))),SIGNED INTEGER)) as  maxID from m_jeniskegiatan where substring(id_jeniskegiatan,6,4)='$tahun' and substring(id_jeniskegiatan,1,5)='$unitkerja'")->row();
			//$data = mysql_fetch_array($query);
			
			
			$idMax = $query->maxID;
			$noUrut = (int)$idMax;
			$noUrut++;
			$id_jeniskegiatan = $unitkerja.''.$tahun.''.sprintf("%03s",$noUrut);
		
			$targetkabkumulatif=0; 
			$wilayah=$this->db->query("select * from m_kab")->result();
			foreach ($wilayah as $row)
			{
				$kode_kab = $row->id_kab;
				$data_kab = $this->input->post('_'.$kode_kab);
				for($i = 0; $i < count($data_kab); $i++){
				    $targetkabkumulatif+=$data_kab[$i];
				};
			}
			
			/*if($targetkabkumulatif > $targetprop)
			{
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Jumlah Target Kabkota Tidak Boleh Melebihi Target Propinsi</div>");	
				redirect('index.php/admin/kegiatan/add/');
			}
			else
			{*/
				$wilayah=$this->db->query("select * from m_kab")->result();
				$bataswaktuplussatu = new DateTime($batas_waktu);
				date_modify($bataswaktuplussatu, '+1 day');
				$bataslewat = date_format($bataswaktuplussatu, 'Y-m-d');
				foreach ($wilayah as $row)
				{
					$kode_kab   = $row->id_kab;
					//Perubahan April-Mei 2023
					$data_kab   = $this->input->post('_'.$kode_kab);
					//$this->db->query("INSERT INTO t_kegiatan VALUES (NULL,'$id_jeniskegiatan', '$kode_kab','$data_kab',0,'$bataslewat','1','-','0','-','-1','0','0','','','0','')");
					$pj_kab     = addslashes($this->input->post('pj_'.$kode_kab));
					$friday[0]  = date("Y-m-d", strtotime("this Friday".$mulai));
					$m_ke       =  1;
					if(count($data_kab) == 1){
                        if(date("N", strtotime($batas_waktu)) == 6 || date("N", strtotime($batas_waktu)) == 7){
                            $friday[0]  = date("Y-m-d", strtotime("previous Friday".$friday[0]));
                        };
					}
					for($i = 0; $i < count($data_kab); $i++){
					    $this->db->query("INSERT INTO t_kegiatan VALUES (NULL,'$id_jeniskegiatan', '$kode_kab', '$m_ke','$pj_kab','$data_kab[$i]','$friday[$i]',0,'$bataslewat','1','-','0','-','-','-1','0','0','','','0','')");
					    $friday[$i+1] = date("Y-m-d", strtotime('next friday'.$friday[$i]));
					    $m_ke++;
					};
				}
				//Perubahan April-Mei 2023
				//$this->db->query("INSERT INTO m_jeniskegiatan VALUES (NULL,'$id_jeniskegiatan', '$nama_kegiatan', $targetkabkumulatif,0, '$satuan', '$batas_waktu','$dasar_surat')");
				$this->db->query("INSERT INTO m_jeniskegiatan VALUES (NULL,'$id_jeniskegiatan', '$nama_kegiatan', '$pj_prov', $targetkabkumulatif,0, '$satuan', '$mulai', '$batas_waktu','$dasar_surat')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added. ".$this->upload->display_errors()."</div>");
				redirect('index.php/admin/kegiatan/');
			//}
		} 
		else if ($mau_ke == "act_edt") {

            $id_jeniskegiatan =$this->uri->segment(4);
			$realisasiprop = 0;
			$targetkabkumulatif=0; 
			$wilayah=$this->db->query("select * from m_kab")->result();
			
			$query = $this->db->query("select max(convert((substring(id_jeniskegiatan,10,length(id_jeniskegiatan-9))),SIGNED INTEGER)) as  maxID from m_jeniskegiatan where substring(id_jeniskegiatan,6,4)='$tahun' and substring(id_jeniskegiatan,1,5)='$unitkerja'")->row();
			//$data = mysql_fetch_array($query);
			$idMax = $query->maxID;
			$noUrut = (int)$idMax;
			$noUrut++;
			$id_jeniskegiatannew = $unitkerja.''.$tahun.''.sprintf("%03s",$noUrut);
			$fday       = date("d-m-Y", strtotime($mulai));
    	    $lday       = date("d-m-Y", strtotime($batas_waktu));
            $ffriday    = date("d-m-Y", strtotime("this Friday".$fday));
            $lfriday    = date("d-m-Y", strtotime("this Friday".$lday));
            $sfriday    = floor((strtotime($lfriday)-strtotime($ffriday)) / (60 * 60 * 24));
            $jfriday    = 1;
            $friday[0]  = date("Y-m-d", strtotime($ffriday));
            if($sfriday == 0){
                if(date("N", strtotime($lday)) == 6 || date("N", strtotime($lday)) == 7){
                    $friday[0]  = date("Y-m-d", strtotime("previous Friday".$friday[0]));
                };
            }else{
                $jfriday = $sfriday/7+1;
                if(($lfriday-$lday) >= 5){
                    $jfriday--;
                }
            };
            if($jfriday > 1){
                for($i = 1; $i < $jfriday; $i++){
                    $friday[$i] = date("Y-m-d", strtotime('next friday'.$friday[$i-1]));
                };
            };
			
			foreach ($wilayah as $row)
			{
				$kode_kab = $row->id_kab;
				$data_kab   = $this->input->post('_'.$kode_kab);
				$pj_kab     = addslashes($this->input->post('pj_'.$kode_kab));
				$data       = $this->db->query("select realisasi,tgl_entri from t_kegiatan  where id_jeniskegiatan='$id_jeniskegiatan' and id_kab='$kode_kab' ORDER BY minggu_ke")->result();
				$bataswaktuplussatu = new DateTime($batas_waktu);
				date_modify($bataswaktuplussatu, '+1 day');
				$bataslewat = date_format($bataswaktuplussatu, 'Y-m-d');
				$minggu = 1;
				if(count($data) > count($data_kab)){
				  for($i = count($data_kab); $i < count($data); $i++){
				      $this->db->query("DELETE FROM t_kegiatan WHERE id_jeniskegiatan='$id_jeniskegiatan' and id_kab='$kode_kab' and minggu_ke='$i+1'");
				  }  
				};
				for($i = 0; $i < count($data_kab); $i++){
				    $targetkabkumulatif+=$data_kab[$i];
				    if($i < count($data)){
				        $realisasi_sekarang     = $data[$i]->realisasi;
				        $tgl_entri_sekarang     = $data[$i]->tgl_entri;
				        $target_new             = $data_kab[$i];
				        $batas_waktu_new	    = new DateTime($friday[$i]);
				        $tglentriconvert		= new DateTime($tgl_entri_sekarang);
						$newformatbatas_waktu	= date_format($batas_waktu_new,"d-m-Y");
						$newformattgl_entri		= date_format($tglentriconvert,"d-m-Y");
						
						$datetime1 					= new DateTime($newformattgl_entri);
						$datetime2 					= new DateTime($newformatbatas_waktu);
						$difference 				= $datetime2->diff($datetime1);
						$selisih_pengiriman		 	= $difference->d ;
						
						if($target_new != '0')
						{
						$realisasiterbaru 			= $realisasi_sekarang/$target_new;
						$persen_realisasi			= $realisasi_sekarang/$target_new*100;
						}else{
						    	$realisasiterbaru  = $realisasi_sekarang;
						    	$persen_realisasi = '0';
						}
						
						if($persen_realisasi >= '95')
						{
							$nilai_volume = '4';
						}else if($persen_realisasi >= '80' && $persen_realisasi <= '94')
						{
							$nilai_volume = '3';
						}else if($persen_realisasi >= '60' && $persen_realisasi <= '79')
						{
							$nilai_volume = '2';
						}else 
						{
							$nilai_volume = '1';
						};
						if($datetime1 <= $datetime2)
						{
						    $nilai_deadline ="4";
						}else
						{
						    $nilai_deadline ="3";
						};
						$nilai_total    = $realisasiterbaru*$nilai_deadline;
						if(substr($id_jeniskegiatan,0,5) == $unitkerja)
						{
						    $this->db->query("UPDATE t_kegiatan SET pj_kab = '$pj_kab', target = '$data_kab[$i]', batas_minggu = '$friday[$i]', persen_realisasi='$persen_realisasi', selisih_pengiriman='$selisih_pengiriman', nilai_volume='$nilai_volume', nilai_deadline='$nilai_deadline', nilai_total='$nilai_total' where id_jeniskegiatan='$id_jeniskegiatan' and id_kab='$kode_kab' and minggu_ke = '$minggu'" );
						}else
						{
						    $this->db->query("UPDATE t_kegiatan SET id_jeniskegiatan='$id_jeniskegiatannew', pj_kab = '$pj_kab', target = '$data_kab[$i]', batas_minggu = '$friday[$i]', persen_realisasi='$persen_realisasi', selisih_pengiriman='$selisih_pengiriman', nilai_volume='$nilai_volume', nilai_deadline='$nilai_deadline', nilai_total='$nilai_total'  where id_jeniskegiatan='$id_jeniskegiatan' and id_kab='$kode_kab' and minggu_ke='$minggu'" );
					    }
					}else{
					    $this->db->query("INSERT INTO t_kegiatan VALUES (NULL,'$id_jeniskegiatan', '$kode_kab', '$minggu','$pj_kab','$data_kab[$i]','$friday[$i]',0,'$bataslewat','1','-','0','-','-1','0','0','','','0','')");
					};
					$minggu++;
				}
			}
			if(substr($id_jeniskegiatan,0,5) == $unitkerja)
			{
				$this->db->query("UPDATE m_jeniskegiatan SET nama_kegiatan ='$nama_kegiatan', pj_prov ='$pj_prov', target= $targetkabkumulatif, mulai = '$mulai', batas_waktu = '$batas_waktu', dasar_surat='$dasar_surat' where id_jeniskegiatan='$id_jeniskegiatan'");
			}else
			{
				$this->db->query("UPDATE m_jeniskegiatan SET id_jeniskegiatan='$id_jeniskegiatannew', nama_kegiatan ='$nama_kegiatan', pj_prov ='$pj_prov', target= $targetkabkumulatif, mulai = '$mulai', batas_waktu = '$batas_waktu', dasar_surat='$dasar_surat' where id_jeniskegiatan='$id_jeniskegiatan'");
			}
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil diubah. ".$this->upload->display_errors()."</div>");
			redirect('index.php/admin/kegiatan/');
		} 
		else {
		if($this->session->userdata('admin_user') == "6500")
			{
			$a['dataall']		= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='921' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='922' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='923' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='924' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='925' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round ((m.realisasi/m.target*100),2) as persen  FROM m_jeniskegiatan m inner join m_satuan s on m.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='926' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['page']			= "l_kelolakegiatan";
			}
		else
		{
			$kabkota=$this->session->userdata('admin_user') ;
			$a['dataall']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where id_kab='$kabkota' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc")->result();
			$a['datatu']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='921' and id_kab='$kabkota'")->result();
			$a['datasos']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='922' and id_kab='$kabkota' ")->result();
			$a['dataprod']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='923' and id_kab='$kabkota' ")->result();
			$a['datadist']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where substring(k.id_jeniskegiatan,1,3)='924' and id_kab='$kabkota' ")->result();
			$a['datanerwil']	= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan  FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='925' and id_kab='$kabkota' ")->result();
			$a['dataipds']		= $this->db->query("SELECT k.*,round((k.realisasi/k.target*100),2) as persen,m.nama_kegiatan FROM t_kegiatan  k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan  where substring(k.id_jeniskegiatan,1,3)='926' and id_kab='$kabkota'  ")->result();
			$a['page']		= "l_kelolakegiatan";

		}
		}
		
		$this->load->view('admin/index', $a);
	}


public function satuan() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM m_satuan")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/satuan/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel Postingan
		$idp					= addslashes($this->input->post('idp'));
		$satuan				= addslashes($this->input->post('satuan'));
	
		
		$cari					= addslashes($this->input->post('q'));

		
		if ($mau_ke == "del") {
			$this->db->query("DELETE FROM m_satuan WHERE id_satuan = '$idu'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been deleted </div>");
			redirect('index.php/admin/satuan');
		} else if ($mau_ke == "cari") {
			$a['data']		= $this->db->query("SELECT * FROM m_satuan WHERE satuan LIKE '%$cari%' ORDER BY satuan ASC, id_satuan DESC")->result();
			$a['page']		= "l_satuan";
		} else if ($mau_ke == "add") {
			$a['page']		= "f_satuan";
		} else if ($mau_ke == "edt") {
			$a['datpil']	= $this->db->query("SELECT * FROM m_satuan WHERE id_satuan = '$idu'")->row();	
			$a['page']		= "f_satuan";
		} else if ($mau_ke == "act_add") {	
			
				$this->db->query("INSERT INTO m_satuan VALUES (NULL, '$satuan')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been added</div>");
			redirect('index.php/admin/satuan');
		} else if ($mau_ke == "act_edt") {
			
				$this->db->query("UPDATE m_satuan SET satuan = '$satuan' where id_satuan = '$idp'");
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated </div>");			
			redirect('index.php/admin/satuan');
		} else {
			$a['data']		= $this->db->query("SELECT * FROM m_satuan ORDER BY satuan LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_satuan";
		}
		
		$this->load->view('admin/index', $a);
	}

public function konfirmasi() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		parse_str($_SERVER['QUERY_STRING'], $_GET);  
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT distinct(m.nama_kegiatan),m.*,count(k.id_kab) as jumlah_dikonfirm FROM m_jeniskegiatan m inner join t_kegiatan k on k.id_jeniskegiatan=m.id_jeniskegiatan where k.flag_konfirm='2' and k.realisasi <> '0' group by k.id_jeniskegiatan")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/konfirmasi/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel post
		$idp					= addslashes($this->input->post('idp'));
		$cari					= addslashes($this->input->post('q'));
		
		if ($mau_ke == "edt") {
            $id_edit          = $this->input->get('konfirmasi_id');
            $id_jeniskegiatan = substr($id_edit,0,strlen($id_edit)-5);

            $id_kab         =substr($id_edit,strlen($id_edit)-5,4);
            $minggu         =substr($id_edit,strlen($id_edit)-1,1);
            $a['datpil']    = $this->db->query("select k.id_jeniskegiatan,m.nama_kegiatan,k.id_kab,w.nama_kab,k.target,k.realisasi,k.bukti,k.link_pengiriman from t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$id_jeniskegiatan' and k.id_kab='$id_kab' and k.minggu_ke='$minggu'")->row();    
            $a['page']      = "f_konfirmasi";
		}
		else if ($mau_ke == "act_edt")
		{
				$tab_aktif=addslashes($this->input->post('tab_aktif'));
				$id_jeniskegiatan = addslashes($this->input->post('id_jeniskegiatan'));
				$id_kab = addslashes($this->input->post('id_kab'));
				$this->db->query("update t_kegiatan set flag_konfirm='1' where id_jeniskegiatan='$id_jeniskegiatan' and id_kab='$id_kab'");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data sudah dikonfirmasi</div>");
				//echo "<script>  window.location.reload(); </script>";
				redirect('index.php/admin/kegiatan/view/'.$id_jeniskegiatan);
		
		}
		$this->load->view('admin/index', $a);
	}

public function kegiatan()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($idu == '00')
			{
				if($this->session->userdata('admin_nip') != '6500')
				{	
				$bidangku = $this->session->userdata('admin_nip');
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan where YEAR(k.batas_waktu)='$tahun'  and substring(u.id_unitkerja,1,3) = '$bidangku' order by k.batas_waktu asc")->result();
				}
				else
				{
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan where YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu asc")->result();
				}
				$a['page']		= "l_kegiatan";	
			}
			else
			{
				if($this->session->userdata('admin_nip') != '6500')
				{	
				$bidangku = $this->session->userdata('admin_nip');
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where (MONTH(k.mulai)='$idu' or MONTH(k.batas_waktu)='$idu') and YEAR(k.batas_waktu)='$tahun' and substring(u.id_unitkerja,1,3) = '$bidangku' order by k.batas_waktu asc")->result();
				}
				else
				{
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where (MONTH(k.mulai)='$idu' or MONTH(k.batas_waktu)='$idu') and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu asc")->result();
				}
				$a['page']		= "l_kegiatan";	
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']	= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$idu'")->row();	
			$a['datprogress'] = $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'  ")->result();	
			$a['wilayah']   = $this->db->query("SELECT * FROM m_kab")->result();	
			$a['page']		= "v_kegiatan_detail";
		}
		else
		{
			$hariini=date("d-m-Y");
			$bulanini = substr($hariini,3,2);
			if($this->session->userdata('admin_nip') != '6500')
			{
			$bidangku = $this->session->userdata('admin_nip');
			$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where (MONTH(k.mulai)='$bulanini' or MONTH(k.batas_waktu)='$bulanini' ) and YEAR(k.batas_waktu)='$tahun' and substring(u.id_unitkerja,1,3) = '$bidangku' order by k.batas_waktu asc")->result();
			}
			else
			{
			$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where (MONTH(k.mulai)='$bulanini' or MONTH(k.batas_waktu)='$bulanini' ) and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu asc")->result();
			}
			$a['page']		= "l_kegiatan";	
		}
		
		$this->load->view('admin/index', $a);
	}

public function kegiatan2()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($idu == '00')
			{
				if($this->session->userdata('admin_nip') != '6500')
				{	
				$bidangku = $this->session->userdata('admin_nip');
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan where YEAR(k.batas_waktu)='$tahun'  and substring(u.id_unitkerja,1,3) = '$bidangku' order by k.batas_waktu asc")->result();
				}
				else
				{
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan where YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu asc")->result();
				}
				$a['page']		= "l_kegiatan2";	
			}
			else
			{
				if($this->session->userdata('admin_nip') != '6500')
				{	
				$bidangku = $this->session->userdata('admin_nip');
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where (MONTH(k.mulai)='$idu' or MONTH(k.batas_waktu)='$idu') and YEAR(k.batas_waktu)='$tahun' and substring(u.id_unitkerja,1,3) = '$bidangku' order by k.batas_waktu asc")->result();
				}
				else
				{
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where (MONTH(k.mulai)='$idu' or MONTH(k.batas_waktu)='$idu') and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu asc")->result();
				}
				$a['page']		= "l_kegiatan2";	
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']	= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$idu'")->row();	
			$a['datprogress'] = $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'  ")->result();	
			$a['wilayah']   = $this->db->query("SELECT * FROM m_kab")->result();	
			$a['page']		= "v_kegiatan_detail";
		}
		else
		{
			$hariini=date("d-m-Y");
			$bulanini = substr($hariini,3,2);
			if($this->session->userdata('admin_nip') != '6500')
			{
			$bidangku = $this->session->userdata('admin_nip');
			$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where (MONTH(k.mulai)='$bulanini' or MONTH(k.batas_waktu)='$bulanini' ) and YEAR(k.batas_waktu)='$tahun' and substring(u.id_unitkerja,1,3) = '$bidangku' order by k.batas_waktu asc")->result();
			}
			else
			{
			$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where (MONTH(k.mulai)='$bulanini' or MONTH(k.batas_waktu)='$bulanini' ) and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu asc")->result();
			}
			$a['page']		= "l_kegiatan2";	
		}
		
		$this->load->view('admin/index', $a);
	}

public function kegiatan_bidang()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
		redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan") {
			if($idu == '00')
			{
			$a['data92110']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92110'")->result();
			$a['data92120']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92120'")->result();
			$a['data92130']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92130'")->result();
			$a['data92140']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92140'")->result();
			$a['data92150']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92150'")->result();
			$a['data92210']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92210'")->result();
			$a['data92220']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92220'")->result();
			$a['data92230']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92230'")->result();
			$a['data92310']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92310'")->result();
			$a['data92320']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92320'")->result();
			$a['data92330']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92330'")->result();
			$a['data92410']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92410'")->result();
			$a['data92420']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92420'")->result();
			$a['data92430']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92430'")->result();
			$a['data92510']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92510'")->result();
			$a['data92520']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92520'")->result();
			$a['data92530']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92530'")->result();
			$a['data92610']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92610'")->result();
			$a['data92620']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92620'")->result();
			$a['data92630']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92630'")->result();
			$a['page']		= "l_kegiatan_bidang";
			}
			else
			{
			$a['data92110']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92110'")->result();
			$a['data92120']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92120'")->result();
			$a['data92130']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92130'")->result();
			$a['data92140']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92140'")->result();
			$a['data92150']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92150'")->result();
			$a['data92210']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92210'")->result();
			$a['data92220']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92220'")->result();
			$a['data92230']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92230'")->result();
			$a['data92310']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92310'")->result();
			$a['data92320']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92320'")->result();
			$a['data92330']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92330'")->result();
			$a['data92410']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92410'")->result();
			$a['data92420']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92420'")->result();
			$a['data92430']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92430'")->result();
			$a['data92510']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92510'")->result();
			$a['data92520']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92520'")->result();
			$a['data92530']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92530'")->result();
			$a['data92610']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92610'")->result();
			$a['data92620']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92620'")->result();
			$a['data92630']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92630'")->result();
			$a['page']		= "l_kegiatan_bidang";
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']	= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$idu'")->row();	
			$a['datprogress'] = $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'  and k.target <> '0'")->result();		
			$a['page']		= "v_kegiatan_detail";
		}
		else{
			$hariini=date("d-m-Y");
			$bulanini = substr($hariini,3,2);
			$a['data92110']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92110'")->result();
			$a['data92120']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92120'")->result();
			$a['data92130']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92130'")->result();
			$a['data92140']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92140'")->result();
			$a['data92150']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92150'")->result();
			$a['data92210']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92210'")->result();
			$a['data92220']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92220'")->result();
			$a['data92230']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92230'")->result();
			$a['data92310']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92310'")->result();
			$a['data92320']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92320'")->result();
			$a['data92330']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92330'")->result();
			$a['data92410']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92410'")->result();
			$a['data92420']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92420'")->result();
			$a['data92430']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92430'")->result();
			$a['data92510']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92510'")->result();
			$a['data92520']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92520'")->result();
			$a['data92530']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92530'")->result();
			$a['data92610']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92610'")->result();
			$a['data92620']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92620'")->result();
			$a['data92630']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan  FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan  where MONTH(k.batas_waktu)='$bulanini' and YEAR(k.batas_waktu)='$tahun' and substring(k.id_jeniskegiatan,1,5)='92630'")->result();
			$a['page']		= "l_kegiatan_bidang";
		}
		
		
	
		$this->load->view('admin/index', $a);
	}
	
	public function unitkerjaprov()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($idu == '00')
			{
			$hariini    = date("d-m-Y");
			//$bulanini = substr($hariini,3,2);
			$tFriday    = date("Y-m-d", strtotime("this friday ".$hariini));
			/*$a['dataall']		= $this->db->query("SELECT *, round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='921' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='922' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='923' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='924' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='925' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='926' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();*/
			$a['data']          = $this->db->query("SELECT t.*, m.nama_kegiatan, m.batas_waktu, m.dasar_surat, u.unitkerja FROM t_kegiatan t LEFT JOIN m_jeniskegiatan m ON t.id_jeniskegiatan=m.id_jeniskegiatan LEFT JOIN m_unitkerja u ON substring(t.id_jeniskegiatan,1,5)=u.id_unitkerja WHERE AND YEAR(batas_waktu)='$tahun' ORDER BY t.id_jeniskegiatan ASC, t.batas_minggu ASC, t.id_kab ASC")->result();
			$a['page']			= "l_kegiatanunitkerja";	
			}
			else
			{
			/*$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen FROM m_jeniskegiatan where MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun'  order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' and MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' and MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();*/
			$a['data']          = $this->db->query("SELECT t.*, m.nama_kegiatan, m.batas_waktu, m.dasar_surat, u.unitkerja FROM t_kegiatan t LEFT JOIN m_jeniskegiatan m ON t.id_jeniskegiatan=m.id_jeniskegiatan LEFT JOIN m_unitkerja u ON substring(t.id_jeniskegiatan,1,5)=u.id_unitkerja WHERE MONTH(t.batas_minggu)>=('$idu'-1) AND MONTH(t.batas_minggu)<=('$idu'+1) AND YEAR(batas_waktu)='$tahun' ORDER BY t.id_jeniskegiatan ASC, t.batas_minggu ASC, t.id_kab ASC")->result();
			$a['page']			= "l_kegiatanunitkerja";	
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']	= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$idu'")->row();	
			$a['datprogress'] = $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'  and k.target <> '0'")->result();		
			$a['wilayah']   = $this->db->query("SELECT * FROM m_kab")->result();	
			$a['page']		= "v_kegiatan_unitkerja_detail";
		}
		else
		{
			$hariini    = date("d-m-Y");
			$bulanini = substr($hariini,3,2);
			$tFriday    = date("Y-m-d", strtotime("this friday ".$hariini));
			/*$a['dataall']		= $this->db->query("SELECT *, round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='921' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='922' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='923' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='924' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='925' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT DISTINCT m.*, round((m.realisasi/m.target*100),2) as persen, t.batas_minggu  FROM m_jeniskegiatan m LEFT JOIN t_kegiatan t ON m.id_jeniskegiatan=t.id_jeniskegiatan where substring(m.id_jeniskegiatan,1,3)='926' and t.batas_minggu='$tFriday' order by substring(m.id_jeniskegiatan,1,5) asc , substring(m.id_jeniskegiatan,10,2) asc ")->result();
			$a['page']			= "l_kegiatanunitkerja";
			$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen FROM m_jeniskegiatan where MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun'  order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' and MONTH(batas_waktu)='$bulanini' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' and MONTH(batas_waktu)='$bulanini' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' and MONTH(batas_waktu)='$bulanini' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' and MONTH(batas_waktu)='$bulanini' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' and MONTH(batas_waktu)='$bulanini' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' and MONTH(batas_waktu)='$bulanini' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();*/
			$a['data']          = $this->db->query("SELECT t.*, m.nama_kegiatan, m.batas_waktu, m.dasar_surat, u.unitkerja, s.satuan FROM t_kegiatan t LEFT JOIN m_jeniskegiatan m ON t.id_jeniskegiatan=m.id_jeniskegiatan LEFT JOIN m_unitkerja u ON substring(t.id_jeniskegiatan,1,5)=u.id_unitkerja LEFT JOIN m_satuan s ON m.satuan=s.id_satuan WHERE MONTH(t.batas_minggu)>=('$bulanini'-1) AND MONTH(t.batas_minggu)<=('$bulanini'+1) AND YEAR(batas_waktu)='$tahun' ORDER BY t.id_jeniskegiatan ASC, t.batas_minggu ASC, t.id_kab ASC")->result();
			$a['page']			= "l_kegiatanunitkerja";
		}
		
		$this->load->view('admin/index', $a);
	}
	
	public function unitkerjakabkota()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$id_bulan				= $this->uri->segment(4);
		$id_kabkota			= $this->uri->segment(5);
		//$id_kabkota				= $this->session->userdata('admin_nip');
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($id_bulan == '00')
			{
				if($id_kabkota == '6500')
				{
					$a['data']			= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
					$a['page']			= "l_kegiatanunitkerjakabkota";	
				}
				else
				{
					//$id_kabkota 		= $this->session->userdata('admin_user');
					$a['data']			= $this->db->query("select t.id_kab,t.id_jeniskegiatan, m.nama_kegiatan,u.unitkerja, m.batas_waktu, t.target, t.realisasi, t.batas_minggu, s.satuan,t.tgl_entri,t.flag_konfirm from t_kegiatan t left join m_jeniskegiatan m on t.id_jeniskegiatan=m.id_jeniskegiatan left join m_unitkerja u on substring(m.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on m.satuan=s.id_satuan where t.id_kab='$id_kabkota' and and YEAR(batas_waktu)='$tahun' t.target <> '0' order by t.id_jeniskegiatan asc, m.batas_waktu asc")->result();
					$a['page']			= "l_kegiatanunitkerjakabkotafilter";
					//$a['data']			= $this->db->query("SELECT k.*,u.unitkerja,t.id_kab,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join t_kegiatan t on k.id_jeniskegiatan=t.id_jeniskegiatan  left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and t.id_kab='$id_kabkota' and t.target <> '0' order by k.batas_waktu desc")->result();
					//$a['page']			= "l_kegiatanunitkerjakabkota";	
				}
		
			}
			else
			{
				if($id_kabkota == '6500')
				{
				$a['data']			= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan   where YEAR(k.batas_waktu)='$tahun' and MONTH(k.batas_waktu)='$id_bulan' order by k.batas_waktu desc")->result();
				$a['page']			= "l_kegiatanunitkerjakabkota";	
				}
				else
				{
					$a['data']			= $this->db->query("select t.id_kab,t.id_jeniskegiatan, m.nama_kegiatan,u.unitkerja, m.batas_waktu, t.target, t.realisasi, s.satuan,t.tgl_entri,t.flag_konfirm, t.batas_minggu from t_kegiatan t left join m_jeniskegiatan m on t.id_jeniskegiatan=m.id_jeniskegiatan left join m_unitkerja u on substring(m.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on m.satuan=s.id_satuan where t.id_kab='$id_kabkota' and MONTH(t.batas_minggu)>=('$id_bulan'-1) and MONTH(t.batas_minggu)<=('$id_bulan'+1) and YEAR(batas_waktu)='$tahun' and t.target <> '0' order by t.id_jeniskegiatan asc, m.batas_waktu asc, t.batas_minggu asc")->result();
					$a['page']			= "l_kegiatanunitkerjakabkotafilter";
					//$a['data']			= $this->db->query("SELECT k.*,u.unitkerja,t.id_kab,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join t_kegiatan t on k.id_jeniskegiatan=t.id_jeniskegiatan  left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and t.id_kab='$id_kabkota' and MONTH(k.batas_waktu)='$id_bulan'  and t.target <> '0' order by k.batas_waktu desc")->result();
					//$a['page']			= "l_kegiatanunitkerjakabkota";	
				}
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']		= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$id_bulan'")->row();	
			$a['datprogress']	= $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$id_bulan' and k.target <> '0'")->result();		
			$a['wilayah']   = $this->db->query("SELECT * FROM m_kab")->result();
			$a['page']			= "v_kegiatan_unitkerja_detail";
		}
		else
		{
			$hariini=date("d-m-Y");
			$bulanini = substr($hariini,3,2);
			$a['data']			= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and MONTH(k.batas_waktu)='$bulanini' order by substring(k.id_jeniskegiatan,1,5) asc")->result();
			$a['page']			= "l_kegiatanunitkerjakabkota";	
		}
		
		$this->load->view('admin/index', $a);
	}
	
	public function unitkerjakabkotadetail()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$id_bulan				= $this->uri->segment(4);
		//$id_kabkota				= $this->uri->segment(5);
		$id_kabkota				= $this->session->userdata('admin_nip');
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($id_bulan == '00')
			{
				//$id_kabkota 		= $this->session->userdata('admin_user');
				$a['data']			= $this->db->query("select t.id_kab,t.id_jeniskegiatan, m.nama_kegiatan,u.unitkerja, m.mulai, m.batas_waktu, t.target, t.realisasi, s.satuan,t.tgl_entri,t.flag_konfirm from t_kegiatan t left join m_jeniskegiatan m on t.id_jeniskegiatan=m.id_jeniskegiatan left join m_unitkerja u on substring(m.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on m.satuan=s.id_satuan where t.id_kab='$id_kabkota' and YEAR(m.batas_waktu) = '$tahun' order by t.id_jeniskegiatan asc, t.minggu_ke asc, m.batas_waktu asc")->result();
				$a['page']			= "l_unitkerjakabkotadetail";	
			}
			else
			{
				//$id_kabkota 		= $this->session->userdata('admin_user');
				$a['data']          =$this->db->query("SELECT t.*, m.nama_kegiatan, m.batas_waktu, u.unitkerja, s.satuan FROM t_kegiatan t LEFT JOIN m_jeniskegiatan m ON t.id_jeniskegiatan=m.id_jeniskegiatan LEFT JOIN m_unitkerja u ON substring(t.id_jeniskegiatan,1,5)=u.id_unitkerja LEFT JOIN m_satuan s ON m.satuan=s.id_satuan WHERE t.id_kab=$id_kabkota AND MONTH(t.batas_minggu)>=('$id_bulan'-1) AND MONTH(t.batas_minggu)<=('$id_bulan'+1) AND YEAR(t.batas_minggu)='$tahun' ORDER BY t.id_jeniskegiatan ASC ")->result();
				$a['page']			= "l_unitkerjakabkotadetail";	
			}
		}

		else
		{
			$hariini			= date("d-m-Y");
			$bulanini 			= substr($hariini,3,2);
			//$id_kabkota 		= $this->session->userdata('admin_user');
			$a['data']          =$this->db->query("SELECT t.*, m.nama_kegiatan, m.batas_waktu, u.unitkerja, s.satuan FROM t_kegiatan t LEFT JOIN m_jeniskegiatan m ON t.id_jeniskegiatan=m.id_jeniskegiatan LEFT JOIN m_unitkerja u ON substring(t.id_jeniskegiatan,1,5)=u.id_unitkerja LEFT JOIN m_satuan s ON m.satuan=s.id_satuan WHERE t.id_kab=$id_kabkota AND MONTH(t.batas_minggu)>=('$bulanini'-1) AND MONTH(t.batas_minggu)<=('$bulanini'+1) AND YEAR(t.batas_minggu)='$tahun' ORDER BY t.id_jeniskegiatan ASC ")->result();
			$a['page']			= "l_unitkerjakabkotadetail";	
		}
		
		$this->load->view('admin/index', $a);
	}
	
	public function unitkerjaprovuntukkab()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($idu == '00')
			{
			$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen FROM m_jeniskegiatan where YEAR(batas_waktu)='$tahun'  order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['page']			= "l_kegiatanunitkerjauntukkab";	
			}
			else
			{
			$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen FROM m_jeniskegiatan where MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun'  order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' and MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' and MONTH(batas_waktu)='$idu' and YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' and MONTH(batas_waktu)='$idu' and  YEAR(batas_waktu)='$tahun' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['page']			= "l_kegiatanunitkerjauntukkab";	
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']	= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$idu'")->row();	
			$a['datprogress'] = $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'  and k.target <> '0'")->result();		
			$a['page']		= "v_kegiatan_unitkerja_detailkab";
		}
		else
		{
			$hariini=date("d-m-Y");
			$bulanini = substr($hariini,3,2);
			$a['dataall']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen FROM m_jeniskegiatan where YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datatu']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='921' and YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datasos']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='922' and YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataprod']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='923' and YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datadist']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='924' and YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['datanerwil']	= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='925' and YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['dataipds']		= $this->db->query("SELECT *,round((realisasi/target*100),2) as persen  FROM m_jeniskegiatan where substring(id_jeniskegiatan,1,3)='926' and YEAR(batas_waktu)='$tahun' and MONTH(batas_waktu)='$bulanini' order by substring(id_jeniskegiatan,1,5) asc , substring(id_jeniskegiatan,10,2) asc ")->result();
			$a['page']			= "l_kegiatanunitkerjauntukkab";	
		}
		
		$this->load->view('admin/index', $a);
	}
	
	
public function entry_unitkerja() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		//$kabkota=$this->session->userdata('admin_user') ;
		$kabkota=$this->uri->segment(5);
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT k.*,(k.realisasi/k.target*100) as persen,m.nama_kegiatan FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where id_kab='$kabkota'")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/entry/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel post
		$id_jeniskegiatan		= addslashes($this->input->post('id_jeniskegiatan'));
		$nama_kegiatan			= addslashes($this->input->post('nama_kegiatan'));
		$target					= addslashes($this->input->post('target'));
		$realisasi				= addslashes($this->input->post('realisasi'));
		$bukti					= addslashes($this->input->post('bukti'));
		$newrealisasi			= addslashes($this->input->post('newrealisasi'));
		$link_pengiriman		= addslashes($this->input->post('link_pengiriman'));
		$tgl_entri				= addslashes($this->input->post('tgl_entri'));
		//$tgl_entri				= date('Y-m-d');
		
		
		$cari					= addslashes($this->input->post('q'));

		//upload config 
		$config['upload_path'] 		= './upload/surat_masuk';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if ($mau_ke == "edt") {
			$kabkota =$this->uri->segment(5);
			$a['datpil']	= $this->db->query("SELECT k.*,m.nama_kegiatan,w.nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan = m.id_jeniskegiatan inner join m_kab w on  k.id_kab=w.id_kab WHERE k.id_jeniskegiatan = '$idu' and k.id_kab='$kabkota'")->row();	
			$a['page']		= "f_entry_unitkerja";
		} 
		else if ($mau_ke == "act_edt")
		{
			if($newrealisasi > $target)
			{
				$kabkota					= addslashes($this->input->post('kabkota'));
				$id_kabkota 				= substr($kabkota,0,4);	
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Realisasi Tidak Boleh Melebihi Target</div>");	
				redirect('index.php/admin/entry_unitkerja/edt/'.$id_jeniskegiatan.'/'.$id_kabkota);
			}
			else
			{	
				if($this->session->userdata('admin_user') == "6500"|| $this->session->userdata('admin_nip') == '921' || $this->session->userdata('admin_nip') == '922' || $this->session->userdata('admin_nip') == '923' || $this->session->userdata('admin_nip') == '924' || $this->session->userdata('admin_nip') == '925' || $this->session->userdata('admin_nip') == '926')
						{
						$kabkota			= addslashes($this->input->post('kabkota'));
						$id_kabkota 				= substr($kabkota,0,4);
						
						//$queryrealisasisekarang 	= mysql_query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' ");
						//$datarealisasisekarang 		= mysql_fetch_array($queryrealisasisekarang);
						
						$datarealisasisekarang 	= $this->db->query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan'")->row();
						
						//$queryrealisasikabsekarang 	= mysql_query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ");
						//$datarealisasikabsekarang 	= mysql_fetch_array($queryrealisasikabsekarang);
						$queryrealisasikabsekarang 	= $this->db->query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ")->row();
						$realisasikabsekarang		= $queryrealisasikabsekarang->realisasi;
						
						
						$targetprov		 			= $datarealisasisekarang->target;
						$realisasisekarang 			= $datarealisasisekarang->realisasi;
						$realisasiterbaru 			= $datarealisasisekarang->realisasi - $realisasikabsekarang + $newrealisasi ;
						$persen_realisasi			= $newrealisasi/$target*100;
						
						$query_batas_waktu			= $this->db->query("select batas_waktu from m_jeniskegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();
						$batas_waktu				= new DateTime($query_batas_waktu->batas_waktu);
						
						$tglentriconvert			=new DateTime($tgl_entri);
						$newformatbatas_waktu		=date_format($batas_waktu,"d-m-Y");
						$newformattgl_entri			=date_format($tglentriconvert,"d-m-Y");
							
						$datetime1 					= new DateTime($newformattgl_entri);
						$datetime2 					= new DateTime($newformatbatas_waktu);
						
						$difference 				= $datetime1->diff($datetime2);
						$selisih_pengiriman 	= $difference->d ;
						//$selisih_pengiriman			= $selisih_pengirimanhitung + 1;
						
						//perubahan 2 Februari 2023
						//sebelum 2 Februari 2023
						/*if($persen_realisasi >= '95' && $persen_realisasi <= '100')
						{
							$nilai_volume = '4';
						}*/
						//setelah 2 Februari 2023
						if($persen_realisasi >= '95')
						{
							$nilai_volume = '4';
						}
						//end of perubahan 2 Februari 2023
						
						else if($persen_realisasi >= '80' && $persen_realisasi <= '94')
						{
							$nilai_volume = '3';
						}
						else if($persen_realisasi >= '60' && $persen_realisasi <= '79')
						{
							$nilai_volume = '2';
						}		
						else 
						{
							$nilai_volume = '1';
						}
						
						//Sebelum 22 Maret 2022	
						/*if($selisih_pengiriman >= '2' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="4";
						}
						else if($selisih_pengiriman == '1' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="3";
						}
						else if($selisih_pengiriman == '0' && $datetime1 == $datetime2)
						{
							$nilai_deadline ="2";
						}
						else
						{
							$nilai_deadline ="1";
						}*/

						//--Perubahan 22 Maret 2022
						if($datetime1 <= $datetime2)
						{
						    $nilai_deadline ="4";
						}else
						{
						    $nilai_deadline ="3";
						}
						//--End of Perubahan 22 Maret 2022
						
						$nilai_total 				= 0.7 * $nilai_volume + 0.3 * $nilai_deadline ;
						
						$this->db->query("UPDATE t_kegiatan SET realisasi = '$newrealisasi', tgl_entri = '$tgl_entri', flag_konfirm='1', bukti='$bukti', persen_realisasi='$persen_realisasi', link_pengiriman='$link_pengiriman', selisih_pengiriman='$selisih_pengiriman', nilai_volume='$nilai_volume', nilai_deadline='$nilai_deadline', nilai_total='$nilai_total' WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota'");
						if($realisasiterbaru <= $targetprov)
						{
						$querykumulatifrealisasi=$this->db->query("select sum(realisasi) as jumlah_realisasi from t_kegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();	
						$kumulatifrealisasi=$querykumulatifrealisasi->jumlah_realisasi;
						
						$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$kumulatifrealisasi' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
						
						//$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$realisasiterbaru' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
						}
										
						$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated. ".$this->upload->display_errors()."</div>");			
						
						//redirect('index.php/admin/entry_unitkerja/edt/'.$id_jeniskegiatan.'/'.$id_kabkota);
						//redirect('index.php/admin/unitkerjakabkota/');
						redirect('index.php/admin/kegiatan/view/'.$id_jeniskegiatan);
						}
				else
						{
						$kabkota					= addslashes($this->input->post('kabkota'));
						$id_kabkota 				= substr($kabkota,0,4);	
							
						/*$queryrealisasisekarang 	= mysql_query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' ");
						$datarealisasisekarang 		= mysql_fetch_array($queryrealisasisekarang);
						
						$queryrealisasikabsekarang 	= mysql_query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ");
						$datarealisasikabsekarang 	= mysql_fetch_array($queryrealisasikabsekarang);
						$realisasikabsekarang		= $datarealisasikabsekarang['realisasi'];
						
						
						$targetprov		 			= $datarealisasisekarang['target'];
						$realisasisekarang 			= $datarealisasisekarang['realisasi'];
						$realisasiterbaru 			= $datarealisasisekarang['realisasi'] - $realisasikabsekarang + $newrealisasi ;
						$persen_realisasi			= $newrealisasi/$target*100;*/
						
						$datarealisasisekarang 	= $this->db->query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan'")->row();
						$queryrealisasikabsekarang 	= $this->db->query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ")->row();
						$realisasikabsekarang		= $queryrealisasikabsekarang->realisasi;
						
						
						$targetprov		 			= $datarealisasisekarang->target;
						$realisasisekarang 			= $datarealisasisekarang->realisasi;
						$realisasiterbaru 			= $datarealisasisekarang->realisasi - $realisasikabsekarang + $newrealisasi ;
						$persen_realisasi			= $newrealisasi/$target*100;
						
						$query_batas_waktu			= $this->db->query("select batas_waktu from m_jeniskegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();
						$batas_waktu				= new DateTime($query_batas_waktu->batas_waktu);
						
						$tglentriconvert			=new DateTime($tgl_entri);
						$newformatbatas_waktu		=date_format($batas_waktu,"d-m-Y");
						$newformattgl_entri			=date_format($tglentriconvert,"d-m-Y");
							
						$datetime1 					= new DateTime($newformattgl_entri);
						$datetime2 					= new DateTime($newformatbatas_waktu);
						$difference 				= $datetime2->diff($datetime1);
						$selisih_pengiriman		 	= $difference->d ;
						//$selisih_pengiriman			= $selisih_pengirimanhitung + 1;
						
						//perubahan 2 Februari 2023
						//sebelum 2 Februari 2023
						/*if($persen_realisasi >= '95' && $persen_realisasi <= '100')
						{
							$nilai_volume = '4';
						}*/
						//setelah 2 Februari 2023
						if($persen_realisasi >= '95')
						{
							$nilai_volume = '4';
						}
						//end of perubahan 2 Februari 2023
						
						else if($persen_realisasi >= '80' && $persen_realisasi <= '94')
						{
							$nilai_volume = '3';
						}
						else if($persen_realisasi >= '60' && $persen_realisasi <= '79')
						{
							$nilai_volume = '2';
						}		
						else 
						{
							$nilai_volume = '1';
						}
						
						//Sebelum 22 Maret 2022	
						/*if($selisih_pengiriman >= '2' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="4";
						}
						else if($selisih_pengiriman == '1' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="3";
						}
						else if($selisih_pengiriman == '0' && $datetime1 == $datetime2)
						{
							$nilai_deadline ="2";
						}
						else
						{
							$nilai_deadline ="1";
						}*/
						
						//--Perubahan 22 Maret 2022
						if($datetime1 <= $datetime2)
						{
						    $nilai_deadline ="4";
						}else
						{
						    $nilai_deadline ="3";
						}
						//--End of Perubahan 22 Maret 2022
						
						$nilai_total 				= 0.7 * $nilai_volume + 0.3 * $nilai_deadline ;

						$this->db->query("UPDATE t_kegiatan SET realisasi = '$newrealisasi', tgl_entri = '$tgl_entri', flag_konfirm='2', bukti='$bukti', persen_realisasi='$persen_realisasi', link_pengiriman='$link_pengiriman', selisih_pengiriman='$selisih_pengiriman', nilai_volume='$nilai_volume', nilai_deadline='$nilai_deadline', nilai_total='$nilai_total' WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota'");
						if($realisasiterbaru <= $targetprov)
						{
						$querykumulatifrealisasi=$this->db->query("select sum(realisasi) as jumlah_realisasi from t_kegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();	
						$kumulatifrealisasi=$querykumulatifrealisasi->jumlah_realisasi;
						
						$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$kumulatifrealisasi' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
						}
						$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated. ".$this->upload->display_errors()."</div>");			
						
						//redirect('index.php/admin/entry_unitkerja/edt/'.$id_jeniskegiatan.'/'.$id_kabkota);
						redirect('index.php/admin/kegiatan/view/'.$id_jeniskegiatan);
						}
			}
		} 
		
		$this->load->view('admin/index', $a);
	}
	
	
	public function entry_unitkerjakab() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$ta = $this->session->userdata('admin_ta');
		//$kabkota=$this->session->userdata('admin_user') ;
		$kabkota=$this->uri->segment(5);
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT k.*,(k.realisasi/k.target*100) as persen,m.nama_kegiatan FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan where id_kab='$kabkota'")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/entry/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$minggu                 = $this->uri->segment(6);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel post
		$id_jeniskegiatan		= addslashes($this->input->post('id_jeniskegiatan'));
		$nama_kegiatan			= addslashes($this->input->post('nama_kegiatan'));
		$target					= addslashes($this->input->post('target'));
		$realisasi				= addslashes($this->input->post('realisasi'));
		$bukti					= addslashes($this->input->post('bukti'));
		$newrealisasi			= addslashes($this->input->post('newrealisasi'));
		$link_pengiriman		= addslashes($this->input->post('link_pengiriman'));
		$keterangan             = addslashes($this->input->post('keterangan'));
		$tgl_entri				= addslashes($this->input->post('tgl_entri'));
		//$tgl_entri				= date('Y-m-d');
		
		
		$cari					= addslashes($this->input->post('q'));

		//upload config 
		$config['upload_path'] 		= './upload/surat_masuk';
		$config['allowed_types'] 	= 'gif|jpg|png|pdf|doc|docx';
		$config['max_size']			= '2000';
		$config['max_width']  		= '3000';
		$config['max_height'] 		= '3000';

		$this->load->library('upload', $config);
		
		if ($mau_ke == "edt") {
			$kabkota =$this->uri->segment(5);
			$a['datpil']	= $this->db->query("SELECT k.*,m.nama_kegiatan,w.nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan = m.id_jeniskegiatan inner join m_kab w on  k.id_kab=w.id_kab WHERE k.id_jeniskegiatan = '$idu' and k.id_kab='$kabkota' and k.minggu_ke = '$minggu'")->row();	
			$a['page']		= "f_entry_unitkerjakab";
		} 
		else if ($mau_ke == "act_edt")
		{
			if($newrealisasi > $target)
			{
				$kabkota					= addslashes($this->input->post('kabkota'));
				$id_kabkota 				= substr($kabkota,0,4);	
				$this->session->set_flashdata("k", "<div class=\"alert alert-danger\" id=\"alert\">Realisasi Tidak Boleh Melebihi Target</div>");	
				redirect('index.php/admin/entry_unitkerjakab/edt/'.$id_jeniskegiatan.'/'.$id_kabkota);
			}
			else
			{	
				if($this->session->userdata('admin_user') == "6500"|| $this->session->userdata('admin_nip') == '921' || $this->session->userdata('admin_nip') == '922' || $this->session->userdata('admin_nip') == '923' || $this->session->userdata('admin_nip') == '924' || $this->session->userdata('admin_nip') == '925' || $this->session->userdata('admin_nip') == '926')
						{
						$kabkota			= addslashes($this->input->post('kabkota'));
						$id_kabkota 				= substr($kabkota,0,4);
						
						//$queryrealisasisekarang 	= mysql_query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' ");
						//$datarealisasisekarang 		= mysql_fetch_array($queryrealisasisekarang);
						
						$datarealisasisekarang 	= $this->db->query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan'")->row();
						
						//$queryrealisasikabsekarang 	= mysql_query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ");
						//$datarealisasikabsekarang 	= mysql_fetch_array($queryrealisasikabsekarang);
						$queryrealisasikabsekarang 	= $this->db->query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' and minggu_ke='$minggu' ")->row();
						$realisasikabsekarang		= $queryrealisasikabsekarang->realisasi;
						
						
						$targetprov		 			= $datarealisasisekarang->target;
						$realisasisekarang 			= $datarealisasisekarang->realisasi;
						$realisasiterbaru 			= $datarealisasisekarang->realisasi - $realisasikabsekarang + $newrealisasi ;
						$persen_realisasi			= $newrealisasi/$target*100;
						
						$query_batas_waktu			= $this->db->query("select batas_waktu from m_jeniskegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();
						$batas_waktu				= new DateTime($query_batas_waktu->batas_waktu);
						
						$tglentriconvert			=new DateTime($tgl_entri);
						$newformatbatas_waktu		=date_format($batas_waktu,"d-m-Y");
						$newformattgl_entri			=date_format($tglentriconvert,"d-m-Y");
							
						$datetime1 					= new DateTime($newformattgl_entri);
						$datetime2 					= new DateTime($newformatbatas_waktu);
						
						$difference 				= $datetime1->diff($datetime2);
						$selisih_pengiriman 	= $difference->d ;
						//$selisih_pengiriman			= $selisih_pengirimanhitung + 1;
						
						//perubahan 2 Februari 2023
						//sebelum 2 Februari 2023
						/*if($persen_realisasi >= '95' && $persen_realisasi <= '100')
						{
							$nilai_volume = '4';
						}*/
						//setelah 2 Februari 2023
						if($persen_realisasi >= '95')
						{
							$nilai_volume = '4';
						}
						//end of perubahan 2 Februari 2023
						
						else if($persen_realisasi >= '80' && $persen_realisasi <= '94')
						{
							$nilai_volume = '3';
						}
						else if($persen_realisasi >= '60' && $persen_realisasi <= '79')
						{
							$nilai_volume = '2';
						}		
						else 
						{
							$nilai_volume = '1';
						}
						
						//Sebelum 22 Maret 2022	
						/*if($selisih_pengiriman >= '2' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="4";
						}
						else if($selisih_pengiriman == '1' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="3";
						}
						else if($selisih_pengiriman == '0' && $datetime1 == $datetime2)
						{
							$nilai_deadline ="2";
						}
						else
						{
							$nilai_deadline ="1";
						}*/
						
						//--Perubahan 22 Maret 2022
						if($datetime1 <= $datetime2)
						{
						    $nilai_deadline ="4";
						}else
						{
						    $nilai_deadline ="3";
						}
						//--End of Perubahan 22 Maret 2022
						
						$nilai_total 				= 0.7 * $nilai_volume + 0.3 * $nilai_deadline ;
						
						$this->db->query("UPDATE t_kegiatan SET realisasi = '$newrealisasi', tgl_entri = '$tgl_entri', flag_konfirm='1', bukti='$bukti', persen_realisasi='$persen_realisasi', link_pengiriman='$link_pengiriman', keterangan='$keterangan',selisih_pengiriman='$selisih_pengiriman', nilai_volume='$nilai_volume', nilai_deadline='$nilai_deadline', nilai_total='$nilai_total' WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' minggu_ke='$minggu' ");
						if($realisasiterbaru <= $targetprov)
						{
						$querykumulatifrealisasi=$this->db->query("select sum(realisasi) as jumlah_realisasi from t_kegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();	
						$kumulatifrealisasi=$querykumulatifrealisasi->jumlah_realisasi;
						
						$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$kumulatifrealisasi' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
						
						//$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$realisasiterbaru' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
						}
										
						$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated. ".$this->upload->display_errors()."</div>");			
						
						//redirect('index.php/admin/entry_unitkerja/edt/'.$id_jeniskegiatan.'/'.$id_kabkota);
						redirect('index.php/admin/unitkerjakabkotadetail/');
						//redirect('index.php/admin/unitkerjaprov/view/'.$id_jeniskegiatan);
						}
				else
						{
						$kabkota					= addslashes($this->input->post('kabkota'));
						$id_kabkota 				= substr($kabkota,0,4);	
							
						/*$queryrealisasisekarang 	= mysql_query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' ");
						$datarealisasisekarang 		= mysql_fetch_array($queryrealisasisekarang);
						
						$queryrealisasikabsekarang 	= mysql_query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ");
						$datarealisasikabsekarang 	= mysql_fetch_array($queryrealisasikabsekarang);
						$realisasikabsekarang		= $datarealisasikabsekarang['realisasi'];
						
						
						$targetprov		 			= $datarealisasisekarang['target'];
						$realisasisekarang 			= $datarealisasisekarang['realisasi'];
						$realisasiterbaru 			= $datarealisasisekarang['realisasi'] - $realisasikabsekarang + $newrealisasi ;
						$persen_realisasi			= $newrealisasi/$target*100;*/
						
						$datarealisasisekarang 	= $this->db->query("select target, realisasi from m_jeniskegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan'")->row();
						$queryrealisasikabsekarang 	= $this->db->query("select realisasi from t_kegiatan WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota' ")->row();
						$realisasikabsekarang		= $queryrealisasikabsekarang->realisasi;
						
						
						$targetprov		 			= $datarealisasisekarang->target;
						$realisasisekarang 			= $datarealisasisekarang->realisasi;
						$realisasiterbaru 			= $datarealisasisekarang->realisasi - $realisasikabsekarang + $newrealisasi ;
						$persen_realisasi			= $newrealisasi/$target*100;
						
						$query_batas_waktu			= $this->db->query("select batas_waktu from m_jeniskegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();
						$batas_waktu				= new DateTime($query_batas_waktu->batas_waktu);
						
						$tglentriconvert			=new DateTime($tgl_entri);
						$newformatbatas_waktu		=date_format($batas_waktu,"d-m-Y");
						$newformattgl_entri			=date_format($tglentriconvert,"d-m-Y");
							
						$datetime1 					= new DateTime($newformattgl_entri);
						$datetime2 					= new DateTime($newformatbatas_waktu);
						$difference 				= $datetime2->diff($datetime1);
						$selisih_pengiriman		 	= $difference->d ;
						//$selisih_pengiriman			= $selisih_pengirimanhitung + 1;
						
						//perubahan 2 Februari 2023
						//sebelum 2 Februari 2023
						/*if($persen_realisasi >= '95' && $persen_realisasi <= '100')
						{
							$nilai_volume = '4';
						}*/
						//setelah 2 Februari 2023
						if($persen_realisasi >= '95')
						{
							$nilai_volume = '4';
						}
						//end of perubahan 2 Februari 2023
						
						else if($persen_realisasi >= '80' && $persen_realisasi <= '94')
						{
							$nilai_volume = '3';
						}
						else if($persen_realisasi >= '60' && $persen_realisasi <= '79')
						{
							$nilai_volume = '2';
						}		
						else 
						{
							$nilai_volume = '1';
						}
						
						//Sebelum 22 Maret 2022	
						/*if($selisih_pengiriman >= '2' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="4";
						}
						else if($selisih_pengiriman == '1' && $datetime1 < $datetime2)
						{
							$nilai_deadline ="3";
						}
						else if($selisih_pengiriman == '0' && $datetime1 == $datetime2)
						{
							$nilai_deadline ="2";
						}
						else
						{
							$nilai_deadline ="1";
						}*/
						
						//--Perubahan 22 Maret 2022
						if($datetime1 <= $datetime2)
						{
						    $nilai_deadline ="4";
						}else
						{
						    $nilai_deadline ="3";
						}
						//--End of Perubahan 22 Maret 2022
						
						$nilai_total 				= 0.7 * $nilai_volume + 0.3 * $nilai_deadline ;

						$this->db->query("UPDATE t_kegiatan SET realisasi = '$newrealisasi', tgl_entri = '$tgl_entri', flag_konfirm='2', bukti='$bukti', persen_realisasi='$persen_realisasi', link_pengiriman='$link_pengiriman', selisih_pengiriman='$selisih_pengiriman', nilai_volume='$nilai_volume', nilai_deadline='$nilai_deadline', nilai_total='$nilai_total' WHERE id_jeniskegiatan = '$id_jeniskegiatan' and id_kab='$id_kabkota'");
						if($realisasiterbaru <= $targetprov)
						{
						$querykumulatifrealisasi=$this->db->query("select sum(realisasi) as jumlah_realisasi from t_kegiatan where id_jeniskegiatan='$id_jeniskegiatan'")->row();	
						$kumulatifrealisasi=$querykumulatifrealisasi->jumlah_realisasi;
						
						$this->db->query("UPDATE m_jeniskegiatan SET realisasi = '$kumulatifrealisasi' WHERE id_jeniskegiatan = '$id_jeniskegiatan'");
						}
						$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data has been updated. ".$this->upload->display_errors()."</div>");			
						
						//redirect('index.php/admin/entry_unitkerja/edt/'.$id_jeniskegiatan.'/'.$id_kabkota);
						//redirect('index.php/admin/unitkerjaprov/view/'.$id_jeniskegiatan);
						redirect('index.php/admin/unitkerjakabkotadetail/');
						}
			}
		} 
		
		$this->load->view('admin/index', $a);
	}
	
	
	
	public function laporan_kab()
	{
	    //variabel untuk select dropdown
	//	$kab = $this->input->get('pilih_kab');
		$bulan = $this->input->get('bln');
	//	$tahun = $this->input->get('Tahun');
		
		//tes highchart
		//$a['report'] = $this->M_kelolakegiatan->report($kab, $bulan, $tahun);
		$a['report'] = $this->M_kelolakegiatan->report($bulan);
		//$this->load->view('dashboard', $a);

		//$this->load->model('M_kelolakegiatan');
		//$hasil = $this->M_kelolakegiatan->cobaLaporan($kab, $bulan, $tahun);
		$hasil = $this->M_kelolakegiatan->cobaLaporan($bulan);
		$a['hasil'] = $hasil;
		$a['page']	= "laporan_kab";
	//	$a['kab'] = $kab;
		$a['bulan'] = $bulan;
	//	$a['tahun'] = $tahun;
		
		$this->load->view('admin/index', $a);

		$this->input->get();
	
	
		//variabel untuk select dropdown
		/*$kab = $this->input->get('pilih_kab');
		$bulan = $this->input->get('bln');
		$tahun = $this->input->get('Tahun');
		
		//tes highchart
		$a['report'] = $this->M_kelolakegiatan->report($kab, $bulan, $tahun);
		//$this->load->view('dashboard', $a);

		//$this->load->model('M_kelolakegiatan');
		$hasil = $this->M_kelolakegiatan->cobaLaporan($kab, $bulan, $tahun);
		$a['hasil'] = $hasil;
		$a['page']	= "laporan_kab";
		$a['kab'] = $kab;
		$a['bulan'] = $bulan;
		$a['tahun'] = $tahun;
		
		$this->load->view('admin/index', $a);

		$this->input->get();
		*/
	}
	
	public function laporan_pengguna()
	{
	    //variabel untuk select dropdown
		$bulan = $this->input->get('bln');
		$a['report'] = $this->M_kelolakegiatan->reportuser($bulan);
		$hasiluser = $this->M_kelolakegiatan->cobaLaporanUser($bulan);
		$a['hasiluser'] = $hasiluser;
		$a['page']	= "laporan_pengguna";
		$a['bulan'] = $bulan;
	
		$this->load->view('admin/index', $a);

		$this->input->get();
	}

	public function laporan_prov()
	{
		//variabel untuk select dropdown
		$kab = $this->input->get('pilih_kab');
		$bulan = $this->input->get('bln');
		$tahun = $this->input->get('Tahun');
		
		//tes highchart
		$a['report'] = $this->M_kelolakegiatan->reportProv($kab, $bulan, $tahun);
		//$this->load->view('dashboard', $a);

		//$this->load->model('M_kelolakegiatan');
		$hasil = $this->M_kelolakegiatan->cobaLaporanProv($kab, $bulan, $tahun);
		$a['hasil'] = $hasil;
		$a['page']	= "laporan_prov";
		$a['kab'] = $kab;
		$a['bulan'] = $bulan;
		$a['tahun'] = $tahun;
		
		$this->load->view('admin/index', $a);

		$this->input->get();
		
	}
	
	public function kegiatankabkota()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}

		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		$tahun					= $this->session->userdata('admin_ta');
	
		if ($mau_ke == "pilih_kegiatan")
		{
			if($idu == '00')
			{
				if($this->session->userdata('admin_user') != '6500')
				{	
				$bidangku = $this->session->userdata('admin_user');
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan where YEAR(k.batas_waktu)='$tahun'  and substring(u.id_unitkerja,1,4) = '$bidangku' order by k.batas_waktu desc")->result();
				}
				else
				{
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja left join m_satuan s on k.satuan=s.id_satuan where YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
				}
				$a['page']		= "l_kegiatankabkota";	
			}
			else
			{
				if($this->session->userdata('admin_user') != '6500')
				{	
				$bidangku = $this->session->userdata('admin_user');
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' and substring(u.id_unitkerja,1,4) = '$bidangku' order by k.batas_waktu desc")->result();
				}
				else
				{
				$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where MONTH(k.batas_waktu)='$idu' and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
				}
				$a['page']		= "l_kegiatankabkota";	
			}
		}
		else if($mau_ke == "view")
		{
			$a['datview']	= $this->db->query("select m.*,s.satuan as nama_satuan from m_jeniskegiatan as m left join m_satuan s on m.satuan=s.id_satuan where m.id_jeniskegiatan='$idu'")->row();	
			$a['datprogress'] = $this->db->query("select k.*,m.nama_kegiatan,round((k.realisasi/k.target*100),2) as persen,w.nama_kab as nama_kab FROM t_kegiatan k inner join m_jeniskegiatan m on k.id_jeniskegiatan=m.id_jeniskegiatan inner join m_kab w on k.id_kab=w.id_kab where k.id_jeniskegiatan='$idu'  and k.target <> '0'")->result();	
			$a['page']		= "v_kegiatan_detail";
		}
		else
		{
			if($this->session->userdata('admin_user') != '6500')
			{
			$bidangku = $this->session->userdata('admin_user');
			$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' and substring(u.id_unitkerja,1,4) = '$bidangku' order by k.batas_waktu desc")->result();
			}
			else
			{
			$a['data']		= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan  where YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
			}
			$a['page']		= "l_kegiatankabkota";	
		}
		
		$this->load->view('admin/index', $a);
	}
	
	
	
	public function duplikasikegiatan()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		$act					= $this->uri->segment(3);
		
		$user  = $this->session->userdata('admin_nip');
		$tahun = $this->session->userdata('admin_ta');
		$hariini=date("d-m-Y");
		$bulanini = substr($hariini,3,2);
		
        if ($this->session->userdata('admin_user') == "rizchi" || $this->session->userdata('admin_user') == "6500" ) {
            if($this->uri->segment(3)) $user = $this->uri->segment(3);
            if($this->uri->segment(4)) $bulanini = $this->uri->segment(4);
            
            if($user && $bulanini)
        		$a['datakegiatan']	= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u 
	        	on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='$user' and MONTH(k.batas_waktu) = '$bulanini' and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
		} else {
    		$a['datakegiatan']	= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u 
	    	on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='$user' and MONTH(k.batas_waktu) = '$bulanini' and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
		}
		
		if($act == 'duplikat' )
		{
			if( $this->input->post('duplikat') != false )
			{
				$duplikat=$this->input->post('duplikat');
				foreach($duplikat as $id_jeniskegiatan)
				{
					$datakegiatanduplikasi =$this->db->query("Select * from t_kegiatan where id_jeniskegiatan ='$id_jeniskegiatan'")->result();	
					
					$masterkegiatanduplikasi=$this->db->query("Select * from m_jeniskegiatan where id_jeniskegiatan ='$id_jeniskegiatan'")->row();
					$tglBatassekarang=$masterkegiatanduplikasi->batas_waktu;
					
					$tanggal			= substr($tglBatassekarang,0,2);
					$bulan				= addslashes($this->input->post('bulan_duplikasi'));
					$tahun				= addslashes($this->input->post('tahun_duplikasi'));
					//$tahun				= $this->session->userdata('admin_ta');
					$tglbatasgabungan 	= $tahun.'-'.$bulan.'-'.$tanggal;
					$tglbatasbaru		= new DateTime($tglbatasgabungan);
					
					$bataswaktuplussatu = new DateTime($tglbatasgabungan);
					date_modify($bataswaktuplussatu, '+1 day');
					$bataslewat = date_format($bataswaktuplussatu, 'Y-m-d');
					
					$nama_kegiatan = $masterkegiatanduplikasi->nama_kegiatan;
					$satuan = $masterkegiatanduplikasi->satuan;
					$dasar_surat = $masterkegiatanduplikasi->dasar_surat;
					
					
					$tglBataswaktu = date('Y-m-d', strtotime('+1 months', strtotime($tglBatassekarang)));
					//$bataswaktuplussatu = new DateTime($tglBataswaktu);
					//date_modify($bataswaktuplussatu, '+1 day');
					//$bataslewat = date_format($bataswaktuplussatu, 'Y-m-d');
					
					
					$unitkerja = substr($id_jeniskegiatan,0,5);
					$query = $this->db->query("select max(convert((substring(id_jeniskegiatan,10,length(id_jeniskegiatan-9))),SIGNED INTEGER)) as maxID from m_jeniskegiatan where substring(id_jeniskegiatan,6,4)='$tahun' and substring(id_jeniskegiatan,1,5)='$unitkerja'")->row();

					$idMax = $query->maxID;
					$noUrut = (int)$idMax;
					$noUrut++;
					$id_jeniskegiatannew = $unitkerja.''.$tahun.''.sprintf("%03s",$noUrut);
					$targetkabkumulatif=0;
					foreach ($datakegiatanduplikasi as $d) 
					{
						$targetkabkumulatif+=$d->target;
						$this->db->query("INSERT INTO t_kegiatan VALUES (NULL,'$id_jeniskegiatannew','$d->id_kab','$d->target',0,'$bataslewat','1','-','0','-','-1','0','0','','','0','')");
					}
					//$this->db->query("INSERT INTO m_jeniskegiatan VALUES (NULL,'$id_jeniskegiatannew', '$nama_kegiatan', $targetkabkumulatif,0, '$satuan', '$tglBataswaktu','$dasar_surat')");
					$this->db->query("INSERT INTO m_jeniskegiatan VALUES (NULL,'$id_jeniskegiatannew', '$nama_kegiatan', $targetkabkumulatif,0, '$satuan', '$tglbatasgabungan','$dasar_surat')");
				}
			}
		}			
		else if($act == 'pilih_kegiatanduplikasi')
		{
		$id_bulan				= $this->uri->segment(4);
		$id_tahun				= $this->uri->segment(5);
		$a['datakegiatan']	= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u 
		on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='$user' and MONTH(k.batas_waktu) = '$id_bulan' and YEAR(k.batas_waktu)='$id_tahun' order by k.batas_waktu desc")->result();
		}
		else
		{
		$a['datakegiatan']	= $this->db->query("SELECT k.*,u.unitkerja,s.satuan as nama_satuan FROM m_jeniskegiatan k left join m_unitkerja u 
		on substring(k.id_jeniskegiatan,1,5) = u.id_unitkerja  left join m_satuan s on k.satuan=s.id_satuan where substring(id_jeniskegiatan,1,3)='$user' and MONTH(k.batas_waktu) = '$bulanini' and YEAR(k.batas_waktu)='$tahun' order by k.batas_waktu desc")->result();
		}
		$a['page']	= "l_duplikasikegiatan";
		$this->load->view('admin/index', $a);
	}
	
	public function listkegiatan()
	{
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('admin_id') == "") {
			redirect("index.php/admin/login");
		}
		
		/* pagination */	
		$total_row		= $this->db->query("SELECT * FROM m_listkegiatan")->num_rows();
		$per_page		= 10;
		
		$awal	= $this->uri->segment(4); 
		$awal	= (empty($awal) || $awal == 1) ? 0 : $awal;
		
		//if (empty($awal) || $awal == 1) { $awal = 0; } { $awal = $awal; }
		$akhir	= $per_page;
		
		$a['pagi']	= _page($total_row, $per_page, 4, base_url()."index.php/admin/listkegiatan/p");
		
		//ambil variabel URL
		$mau_ke					= $this->uri->segment(3);
		$idu					= $this->uri->segment(4);
		
		$cari					= addslashes($this->input->post('q'));

		//ambil variabel Postingan
		$idp					= addslashes($this->input->post('id_listkegiatan'));
		$nama_kegiatan			= addslashes($this->input->post('nama_kegiatan'));
		$unitkerja				= addslashes($this->input->post('unitkerja'));
		$satuan					= addslashes($this->input->post('satuan'));
				
		$cari					= addslashes($this->input->post('q'));

		
		if ($mau_ke == "del") {
			$this->db->query("DELETE FROM m_listkegiatan WHERE id = '$idu'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data telah dihapus </div>");
			redirect('index.php/admin/listkegiatan');
		} else if ($mau_ke == "cari") {
			$a['data']		= $this->db->query("SELECT l.id, l.nama_kegiatan, l.id_unitkerja, u.unitkerja, l.satuan,s.satuan as nama_satuan FROM m_listkegiatan l left join m_satuan s on l.satuan=s.id_satuan left join m_unitkerja u on l.id_unitkerja=u.id_unitkerja where l.nama_kegiatan like '%$cari%' order by l.id")->result();
			$a['page']		= "l_listkegiatan";
		} else if ($mau_ke == "add") {
			$a['page']		= "f_listkegiatan";
		} else if ($mau_ke == "edt") {
			$a['datpil']	= $this->db->query("SELECT * FROM m_listkegiatan WHERE id = '$idu'")->row();	
			$a['page']		= "f_listkegiatan";
		} else if ($mau_ke == "act_add") {	
			
				$this->db->query("INSERT INTO m_listkegiatan VALUES (NULL, '$unitkerja','$nama_kegiatan','$satuan')");
				$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil ditambahkan</div>");
			
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil ditambahkan</div>");
			redirect('index.php/admin/listkegiatan');
		} else if ($mau_ke == "act_edt") {
			
			$this->db->query("UPDATE m_listkegiatan SET nama_kegiatan = '$nama_kegiatan', id_unitkerja='$unitkerja', satuan='$satuan' where id = '$idp'");
			$this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data berhasil ditambahkan </div>");			
			redirect('index.php/admin/listkegiatan');
		} else {
			$a['data']		= $this->db->query("SELECT l.id, l.nama_kegiatan, l.id_unitkerja, u.unitkerja, l.satuan,s.satuan as nama_satuan FROM m_listkegiatan l left join m_satuan s on l.satuan=s.id_satuan left join m_unitkerja u on l.id_unitkerja=u.id_unitkerja order by l.id LIMIT $awal, $akhir ")->result();
			$a['page']		= "l_listkegiatan";
		}
		
		$this->load->view('admin/index', $a);
	}

	public function login_sso()
	{
		$provider = new JKD\SSO\Client\Provider\Keycloak([
		    'authServerUrl'         => 'https://sso.bps.go.id',
		    'realm'                 => 'pegawai-bps',
		    'clientId'              => '13300-evita-4ed',
		    'clientSecret'          => 'ef75e3c8-88d8-4c57-a2a7-7732a924144f',
		    'redirectUri'           => 'https://webapps.bps.go.id/jateng/evita/index.php/admin/login_sso'
		]);

		if (!isset($_GET['code'])) {

		    // Untuk mendapatkan authorization code
		    $authUrl = $provider->getAuthorizationUrl();
		    $_SESSION['oauth2state'] = $provider->getState();
		    header('Location: '.$authUrl);
		    exit;

		// Mengecek state yang disimpan saat ini untuk memitigasi serangan CSRF
		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

		    unset($_SESSION['oauth2state']);
		    exit('Invalid state');

		} else {

		    try {
		        $token = $provider->getAccessToken('authorization_code', [
		            'code' => $_GET['code']
		        ]);
		    } catch (Exception $e) {
		        exit('Gagal mendapatkan akses token : '.$e->getMessage());
		    }

		    // Opsional: Setelah mendapatkan token, anda dapat melihat data profil pengguna
		    try {

		        $user 			= $provider->getResourceOwner($token);
		        $organisasi 	= $user->getKodeOrganisasi();
		        
		      if(substr($organisasi,0,2)=='65'){  
		        $admin_level 	= substr($organisasi,2,2)=='00' ? 'userprov' : 'userkabkota'; 
		        //$admin_user 	= substr($organisasi,2,2)=='00' ? '335'.substr($organisasi,9,1) : substr($organisasi,0,4);
		        $data = array(
		                'admin_id'		=> $user->getNip(),
		                'admin_user' 	=> $admin_user,
		                'admin_nama' 	=> $user->getName(),
		                'admin_ta' 		=> date('Y'),
		                'admin_level' 	=> $admin_level,
						'admin_valid' 	=> true,
						'username'		=> $user->getUsername(),
						'access_token'	=> $token->getToken(),
						'logout_url'    => $provider->getLogoutUrl(),
		                );
		        $this->session->set_userdata($data);

                $username = $this->session->userdata('username');
    			date_default_timezone_set("Asia/Bangkok");
    			$logindate = date('Y-m-d H:i:s');
    			$this->db->query("INSERT INTO evita_userlog VALUES (NULL,'$username','login','$logindate','')");

		        redirect('index.php/admin');
		      } else {
		      	echo 'Anda login SSO sebagai <b>'.$user->getName().'</b> ['.substr($organisasi,0,4).'] .<br>';
		        echo 'Wilayah Anda tidak terdaftar dalam sistem ini.<br>';
		        echo 'Silakan '.anchor($provider->getLogoutUrl(),'logout SSO').' terlebih dahulu';
                //$this->session->sess_destroy();
		        exit();
		      }

/*		        $userdata = array(
			            'nama'          => $user->getName(),
			            'niplama'       => $user->getNip(),
			            'nipbaru'       => $user->getNipBaru(),
			            'id_wilayah'    => substr($organisasi, 0, 4),
			            'id_unitkerja'  => substr($organisasi, 7, 4),
			            'url_foto'      => $user->getUrlFoto(),
			            'url_logout'    => $provider->getLogoutUrl(),
			        );
		        $_SESSION['userdata'] = $userdata;
*/		        
		    } catch (Exception $e) {
		        exit('Gagal Mendapatkan Data Pengguna: '.$e->getMessage());
		    }

		    // Gunakan token ini untuk berinteraksi dengan API di sisi pengguna
//		    $access_token   = $token->getToken();
		}
//		echo '<pre>'; print_r($_SESSION); echo '</pre>';

	}

	public function info_sso()
	{
		echo '<pre>'; print_r($_SESSION); echo '</pre>';
	}
	
	function getsatuan(){
	 //$idgoal=$this->input->get('idgoal');
		$nama_kegiatan_asli = $this->uri->segment(3);
		$nama_kegiatan=str_replace("%20"," ",$nama_kegiatan_asli);
        $datasatuan=$this->M_getfunction->getsatuan($nama_kegiatan);
		echo json_encode($datasatuan);
    }
    
    public function set_tahun($tahun=null)
    {
        if(!$tahun)
            $tahun=date('Y');
        
        $this->session->set_userdata('admin_ta',$tahun);
        redirect('index.php/admin/index');
    }
    
    //tambahan 22 Januari 2021
	public function laporan_bagbid()
	{
		//variabel untuk select dropdown
		$bulan = $this->input->get('bln');
		
		//tes highchart
		$a['reportlaporanbagbid'] = $this->M_kelolakegiatan->laporanbagbid($bulan);
		//$this->load->view('dashboard', $a);

		//$this->load->model('M_kelolakegiatan');
		//$hasil = $this->M_kelolakegiatan->cobaLaporan($kab, $bulan, $tahun);
		$hasillaporanbagbid = $this->M_kelolakegiatan->cobalaporanbagbid($bulan);
		$a['hasillaporanbagbid'] = $hasillaporanbagbid;
		$a['page']	= "laporan_bagbid";
		$a['bulan'] = $bulan;
		
		$this->load->view('admin/index', $a);

		$this->input->get();

	}
	
	function getPegawaiTerpilih($nip){
	    $db2 = $this->load->database('db2',TRUE);
	    $pegawai = $db2->query("SELECT gelar_depan, nama, gelar_belakang FROM master_pegawai WHERE niplama='$pj_kab' LIMIT 1");
	    return $pegawai->row();
	}
	
	function getMingguKgt(){
	    $fday       = date("d-m-Y", strtotime($this->uri->segment(3)));
	    $lday       = date("d-m-Y", strtotime($this->uri->segment(4)));
        $ffriday    = date("d-m-Y", strtotime("this Friday".$fday));
        $lfriday    = date("d-m-Y", strtotime("this Friday".$lday));
        $sfriday    = floor((strtotime($lfriday)-strtotime($ffriday)) / (60 * 60 * 24));
        $jfriday    = 1;
        $friday[0]  = date("Y-m-d", strtotime($ffriday));
        if($sfriday == 0){
            if(date("N", strtotime($lday)) == 6 || date("N", strtotime($lday)) == 7){
                $friday[0]  = date("Y-m-d", strtotime("previous Friday".$friday[0]));
            };
        }else{
            $jfriday = $sfriday/7+1;
            if(($lfriday-$lday) >= 5){
                $jfriday--;
            }
        };
        if($jfriday > 1){
            for($i = 1; $i < $jfriday; $i++){
                $friday[$i] = date("Y-m-d", strtotime('next friday'.$friday[$i-1]));
            };
        };
        $data   =  array("jfriday" => $jfriday,
                        "friday" => $friday,);
        echo json_encode($data);
    }
}
