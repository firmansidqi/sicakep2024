<?php
$mode		= $this->uri->segment(3);
$wilayah	= $this->db->query("select * from m_kab where id_kab <> '6500'")->result();
$db2        = $this->load->database('db2',TRUE);
$this->load->model('M_kelolakegiatan');

$act		= "act_add";
$judul		= "Entri Kegiatan Baru";
$idp		= "";
$unitkerja  = "";
$tahun		= "";
$id_jeniskegiatan		= "";
$nama_kegiatan			= "";
$dasar_surat			= "";
$target					= "";
$realisasi				= "";
$targetprop				= "";
$realisasiprop			= "";
$kabkota				= "";
$batas_waktu			= "";
$satuan					= "";
//Perubahan April-Mei 2023
$mulai                  = "";
$pj_prov                = "";
$pj_6501="";
$pj_6502="";
$pj_6503="";
$pj_6504="";
$pj_6571="";
//End of Perubahan April-Mei 2023

$_6501="";
$_6502="";
$_6503="";
$_6504="";
$_6571="";

?>

<?php echo $this->session->flashdata("k");?>
<div class="row col-md-12">
  <div class="panel panel-info">
      
      <div class="panel-heading"> TAMBAH KEGIATAN
          <div class="btn-group pull-right">
           
              <a href="<?php echo base_URL()?>index.php/admin/kegiatan/" class="btn btn-info btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Semua Kegiatan</a>
              <a href="<?php echo base_URL()?>index.php/admin/kegiatan_bidang/"  class="btn btn-danger btn-sm"><i class="icon-list icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Bidang/Fungsi</a>
              <a href="<?php echo base_URL()?>index.php/admin/kelolakegiatan/add" class="btn btn-success btn-sm"><i class="icon-plus-sign icon-white" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Tambah Kegiatan</a>
          </div>
      </div>
      
      
      
      <div class="scroll" style="height: 800px;">
       <form action="<?php echo base_URL()?>index.php/admin/kelolakegiatan/<?php echo $act; ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
           
           
           
           <div class="row-fluid well" style="overflow: hidden">
               
               <!-- Perubahan April-Mei 2023 -->
               <!--div class="col-lg-6"-->
               <div class="row col-md-12">
                  <table width="100%" class="table-form">
                      
                      <tr><td width="20%">Asal Kegiatan</td>
                          <td>
                            <b>
                                <?php
                                if ($mode == "edt" || $mode == "act_edt") 
                                {
                                    ?>
                                    <select name="unitkerja" id="unitkerja"  class="form-control" tabindex="5" style="width: 300px" required>
                                        <option selected value="<?php echo $idunitkerja_terpilih; ?>"><?php echo $unitkerja_terpilih; ?></option>
                                        <?php
                                        //mengambil nama-nama satuan yang ada di database
                                        if($this->session->userdata('admin_level')=='userprov' and $this->session->userdata('admin_nip') != '6500')
                                        {
                                          $bidangku = $this->session->userdata('admin_nip');
                                          $unitkerja = $this->db->query("select * from m_unitkerja where substring(id_unitkerja,1,3)='$bidangku'")->result();
                                      }
                                      else
                                      {
                                         $unitkerja = $this->db->query("select * from m_unitkerja where id_unitkerja <> '92000'")->result();
                                     }
                                     foreach($unitkerja as $p){
                                         if ($p->id_unitkerja != $idunitkerja_terpilih) 
                                         {
                                             echo "<option value='".$p->id_unitkerja."'>".$p->unitkerja."</option>";
                                         }
                                     }
                                     ?>
                                 </select>  
                                 <?php
                             }
                             else
                             {
                                ?>
                                <select name="unitkerja" id="unitkerja"  class="form-control" tabindex="1" style="width: 300px" required>
                                    <option selected value="Kosong">- Pilih Unit Kerja -</option>
                                    <?php
                                    //mengambil nama-nama unitkerja yang ada di database
                                    if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_nip') != '6500')
                                    {
                                      $bidangku = $this->session->userdata('admin_nip');
                                      $unitkerja = $this->db->query("select * from m_unitkerja where substring(id_unitkerja,1,3)='$bidangku'")->result();
                                  }
                                  else
                                  {
                                     $unitkerja = $this->db->query("select * from m_unitkerja where id_unitkerja <> '92000'")->result();
                                 }
                                 foreach($unitkerja as $p){
                                     echo "<option value='".$p->id_unitkerja."'>".$p->unitkerja."</option>\n";
    							//echo $this->session->userdata();
                                 }
                                 ?>
                             </select>  
                             <?php
                         }
                         ?>
                         
                         <!--<input type="text" tabindex="5" name="satuan" required value="<?php echo $satuan; ?>" id="satuan" style="width: 100px" class="form-control">-->
                     </b>
                 </td>
             </tr>
             <tr>
                 <td width="20%">Tahun Kegiatan</td>
                 <td><b><input type="text" tabindex="2" name="tahun" required value="<?php echo $tahun; ?>" id="tahun" style="width: 100px" class="form-control"></b>
                 </td>
             </tr>
             <tr><td width="20%">Nama Kegiatan</td>
              <td><b>
                 <b>
                    <?php
                    if ($mode == "edt" || $mode == "act_edt") 
                    {
                        ?>
                        <select name="nama_kegiatan" id="nama_kegiatan"  class="form-control" tabindex="5" style="width: 300px" >
                            <option selected value="<?php echo $nama_kegiatanterpilih; ?>"><?php echo $nama_kegiatanterpilih; ?></option>
                            <?php
        						//mengambil nama-nama satuan yang ada di database
                            if($this->session->userdata('admin_level')=='userprov' and $this->session->userdata('admin_nip') != '6500')
                            {
                              $bidangku = $this->session->userdata('admin_nip');
                              $unitkerja = $this->db->query("select * from m_listkegiatan where substring(id_unitkerja,1,3)='$bidangku' ORDER BY nama_kegiatan")->result();
                          }
                          else
                          {
                             $unitkerja = $this->db->query("select * from m_listkegiatan ")->result();
                         }
                         foreach($unitkerja as $p){
        							//if ($p->id_unitkerja != $idunitkerja_terpilih) 
        							//{
                             echo "<option value='".$p->nama_kegiatan."'>".$p->nama_kegiatan."</option>";
        							//}
                         }
                         ?>
                     </select>  
                     <?php
                 }
                 else
                 {
                    ?>
                    <select name="nama_kegiatan" id="nama_kegiatan"  class="form-control" tabindex="3" style="width: 300px" >
                        <option selected value="Kosong">- Pilih Nama Kegiatan -</option>
                        <?php
        						//mengambil nama-nama unitkerja yang ada di database
                        if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_nip') != '6500')
                        {
                          $bidangku = $this->session->userdata('admin_nip');
                          $unitkerja = $this->db->query("select * from m_listkegiatan where substring(id_unitkerja,1,3)='$bidangku'")->result();
                      }
                      else
                      {
                         $unitkerja = $this->db->query("select * from m_listkegiatan")->result();
                     }
                     foreach($unitkerja as $p){
                         echo "<option value='".$p->nama_kegiatan."'>".$p->nama_kegiatan."</option>\n";
                     }
                     ?>
                 </select>
                 <?php
             }
             ?>
             
             <!--<input type="text" tabindex="5" name="satuan" required value="<?php echo $satuan; ?>" id="satuan" style="width: 100px" class="form-control">-->
         </b>
         
         <!--<input type="text" tabindex="3" name="nama_kegiatan" required value="<?php echo $nama_kegiatan; ?>" id="nama_kegiatan" style="width: 400px" class="form-control">-->
     </td>
 </tr>
 <tr>
     <td width="20%">Satuan</td>
     <td>
         <b>
             <select name="satuan" id="satuan" style="width: 300px" class="form-control" readonly>
                <?php
                if ($mode == "edt" || $mode == "act_edt") 
                {
                    ?>
                    <option selected value="<?php echo $idsatuan_terpilih; ?>"><?php echo $satuan_terpilih; ?></option>
                    <?php
                }
                echo "<option> - Satuan - </option>";
                ?>
            </select>
        </b>
    </td>
</tr>

    		<!--<tr>
    			<td width="20%">Satuan</td>
    			<td>
    				<b>
    				<?php
    				if ($mode == "edt" || $mode == "act_edt") 
    				{
    				?>
    					<select name="satuan" id="satuan"  class="form-control" tabindex="5" style="width: 200px">
                        <option selected value="<?php echo $idsatuan_terpilih; ?>"><?php echo $satuan_terpilih; ?></option>
                            <?php
    						//mengambil nama-nama satuan yang ada di database
                            $satuan = $this->db->query("select * from m_satuan")->result();
                            foreach($satuan as $p){
    							if ($p->id_satuan != $idsatuan_terpilih) 
    							{
    							echo "<option value='".$p->id_satuan."'>".$p->satuan."</option>";
    							}
    						}
                            ?>
                        </select>  
    				<?php
    				}
    				else
    				{
    				?>
    					<select name="satuan" id="satuan"  class="form-control" tabindex="6" style="width: 200px">
                        <option selected value="Kosong">- Pilih Satuan -</option>
                            <?php
    						//mengambil nama-nama satuan yang ada di database
                            $satuan = $this->db->query("select * from m_satuan")->result();
                            foreach($satuan as $p){
    							echo "<option value='".$p->id_satuan."'>".$p->satuan."</option>\n";
                            }
    				}
    				?>
    				
    					<!--<input type="text" tabindex="5" name="satuan" required value="<?php echo $satuan; ?>" id="satuan" style="width: 100px" class="form-control">
    				</b>
    			</td>
    		</tr>-->
    		
    		<!-- Perubahan April-Mei 2023 -->
    		<tr>
    			<td width="20%">Penanggung Jawab</td>
    			<td><b>
                    <?php
                    if ($mode == "edt" || $mode == "act_edt")
                    {
                        $pegawaiterpilih = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang FROM master_pegawai WHERE niplama='$nama_pjprovterpilih' LIMIT 1")->row();
                        ?>
                        <select name="pj_prov" id="pj_prov"  class="form-control" tabindex="5" style="width: 300px" >
                            <option selected value="<?php echo $nama_pjprovterpilih; ?>"><?php echo $pegawaiterpilih->gelar_depan." ".$pegawaiterpilih->nama." ".$pegawaiterpilih->gelar_belakang; ?></option>
                            <?php
        						//mengambil nama-nama pegawai dari provinsi
                            if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_nip') != '6500')
                            {
                              $kodeprov = "6500";
                              $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodeprov' ORDER BY id_org ")->result();
                          }
                          else
                          {
                              $kodeprov = "6500";
                              $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodeprov' ORDER BY id_org ")->result();
                          }
                          foreach($pegawai as $p){
                             echo "<option value='".$p->niplama."'>".$p->gelar_depan." ".$p->nama." ".$p->gelar_belakang."</option>\n";
                         }
                         ?>
                     </select>  
                     <?php
                 }
                 else
                 {
                    ?>
                    <select name="pj_prov" id="pj_prov"  class="form-control" tabindex="4" style="width: 300px" >
                        <option selected value="Kosong">- Pilih Penanggung Jawab -</option>
                        <?php
        						//mengambil nama-nama pegawai dari provinsi
                        if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_nip') != '6500')
                        {
                          $kodeprov = "6500";
                          $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodeprov' ORDER BY id_org ")->result();
                      }
                      else
                      {
                          $kodeprov = "6500";
                          $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodeprov' ORDER BY id_org ")->result();
                      }
                      foreach($pegawai as $p){
                         echo "<option value='".$p->niplama."'>".$p->gelar_depan." ".$p->nama." ".$p->gelar_belakang."</option>\n";
                     }
                     ?>
                 </select>
                 <?php
             }
             ?>
         </b>
     </td>
 </tr>
 <tr>
     <td width="20%" >Mulai</td>
     <td><b><input type="date" name="mulai" tabindex="5" required value="<?php echo $mulai; ?>" id="mulai" style="width: 200px" class="form-control"></b>
     </td>
 </tr>
 
 <!-- End of Perubahan April-Mei 2023 -->
 
 <tr>
     <td width="20%">Batas Waktu</td>
     <td><b><input type="text" name="batas_waktu" tabindex="6" required value="<?php echo $batas_waktu; ?>" id="batas_waktu" style="width: 200px" class="form-control"></b>
     </td>
 </tr>
 <tr>
  <td width="20%">Dasar Kegiatan</td>
  <td><b><input type="text" tabindex="7" name="dasar_surat" required value="<?php echo $dasar_surat; ?>" id="dasar_surat" style="width: 300px" class="form-control"></td>
  </tr>
</table>
</div>
<div class="row col-md-12">
   <table width = 100% class="table table-form">
       <tr>
           <td width="20%"><b>Alokasi</b></td>
       </tr>
       <tr><b></b></tr>
       <table width="100%" class="table table-striped" id="targetTable">
         <th width="5%" style="text-align:center">No</th>
         <th width="7%" style="text-align:center">Kode Kab/Kota</th>
         <th width="8%" style="text-align:center">Kab/Kota</th>
         <!-- Perubahan April-Mei 2023 -->
         <th width="20%" style="text-align:center" id="jtabel">Penanggung Jawab</th>
         <!-- End of Perubahan April-Mei 2023 -->
     </tr>
     <?php
     $no=1;
     foreach($wilayah as $row) 
     {
       
       
       echo "<tr>";
       echo "<td>".$no." </td>";
       echo "<td>".$row->id_kab."</td>";
       echo "<td>".$row->nama_kab." </td>";
       $i = 1;
       ?>
       <!-- Perubahan April-Mei 2023 -->
       <td>
           <?php
           $querypj = $this->db->query("select * from t_kegiatan where id_kab='$row->id_kab' and id_jeniskegiatan='$id_jeniskegiatan'")->row();
           if(!empty($querypj)){
               $pj_kab = $querypj->pj_kab;
               $pegawaiterpilih = $db2->query("SELECT gelar_depan, nama, gelar_belakang FROM master_pegawai WHERE niplama='$pj_kab' LIMIT 1")->row();?>
               <select name="<?php echo 'pj_'.$row->id_kab; ?>" class="form-control" pjkab>
                   <option selected value="<?php echo $pj_kab; ?>"><?php echo $pegawaiterpilih->gelar_depan." ".$pegawaiterpilih->nama." ".$pegawaiterpilih->gelar_belakang; ?></option>
                   <?php
        					        //mengambil nama-nama pegawai dari kabkota
                   if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_nip') != '6500')
                   {
                      $kodekab = "$row->id_kab";
                      $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodekab' ORDER BY id_org ")->result();
                  }
                  else
                  {
                      $kodekab = "$row->id_kab";
                      $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodekab' ORDER BY id_org ")->result();
                  }
                  foreach($pegawai as $p){
                     echo "<option value='".$p->niplama."'>".$p->gelar_depan." ".$p->nama." ".$p->gelar_belakang."</option>\n";
                 }
                 ?>
                 </select><?php
             }else{?>
               <select name="<?php echo 'pj_'.$row->id_kab; ?>" class="form-control" pjkab>
                   <option selected value="Kosong">- Pilih Penanggung Jawab -</option>
                   <?php
    					            //mengambil nama-nama pegawai dari kabkota
                   if($this->session->userdata('admin_level') == 'userprov'  and  $this->session->userdata('admin_nip') != '6500')
                   {
                      $kodekab = "$row->id_kab";
                      $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodekab' ORDER BY id_org ")->result();
                  }
                  else
                  {
                      $kodekab = "$row->id_kab";
                      $pegawai = $db2->query("SELECT niplama, gelar_depan, nama, gelar_belakang, id_satker FROM master_pegawai WHERE id_satker='$kodekab' ORDER BY id_org ")->result();
                  }
                  foreach($pegawai as $p){
                     echo "<option value='".$p->niplama."'>".$p->gelar_depan." ".$p->nama." ".$p->gelar_belakang."</option>\n";
                 }?>
             </select>
             <?php
         }
         ?>
     </td>
     <!-- End of Perubahan April-Mei 2023 -->
     <?php
     
     echo "</tr>";
     
     $no++;
 }
 ?>
</td>
</tr>	
</table>
</table>



<a href="javascript:history.back()" tabindex="10" class="btn btn-primary"><i class="icon icon-arrow-left icon-white"></i> Batal</a>
<button type="submit" class="btn btn-success" tabindex="9" ><i class="icon icon-folder-close icon-white"></i> Simpan</button>



</div>


</div>

</form>
</div>
</div>
</div>

<script type="text/javascript">
	$("input[targetkab]").bind("paste", function(){
		var $this = $(this);
		setTimeout(function() {
			var columns = $this.val().split(/\s+/);
            var i;
            var input = $this;
            for (i = 0; i < columns.length; i++) {
                input.val(columns[i]);
                if( i % 3 !== 2){
                    input = input.parent().parent().next().find('input');
                } else{
                    input = input.parent().parent().next().find('input');
                }
            }
        }, 0);
	});
	//Perubahan April-Mei 2023
	$("input[pjkab]").bind("paste", function(){
		var $this = $(this);
		setTimeout(function() {
			var columns = $this.val().split(/\s+/);
            var i;
            var input = $this;
            for (i = 0; i < columns.length; i++) {
                input.val(columns[i]);
                if( i % 3 !== 2){
                    input = input.parent().parent().next().find('input');
                } else{
                    input = input.parent().parent().next().find('input');
                }
            }
        }, 0);
	});

	$(document).ready(function(){
        $('#mulai, #batas_waktu').change(function(){
            var tmulai  = $('#mulai').val();
            var tbatas  = $('#batas_waktu').val();
            var l       = document.getElementById("targetTable").rows[0].cells.length;
            var w       = document.getElementById("targetTable").rows.length;
            if(tmulai != '' && tbatas != ''){
                if(l > 4){
                    for(i = 4; i < l; i++){
                        for (j = 0; j < w; j++){
                            const element = document.getElementById("tgt");
                            element.remove();
                        }
                    }
                }
                $.ajax({
                    typ         : "GET",
                    url         : "<?php echo base_url();?>index.php/Admin/getMingguKgt/"+tmulai+"/"+tbatas,
                    dataType    : 'json',
                    success     : function(data){
                        for(i = 0; i < data.jfriday; i++){
                            var tr      = document.getElementById("targetTable").rows[0];
                            var th      = document.createElement("th");
                            var text    = "Minggu "+ (i+1) + "<br>(" + data.friday[i]+")";
                            th.setAttribute("style", "text-align:center");
                            th.setAttribute("id", "tgt");
                            tr.appendChild(th);
                            th.innerHTML    = text;
                            var kodekab     = ["", "6501", "6502", "6503", "6504", "6571"]
                            for(j = 1; j < w; j++){
                                tr          = document.getElementById("targetTable").rows[j];
                                var td      = document.createElement("td");
                                var input   = document.createElement("input");
                                td.setAttribute("id", "tgt");
                                input.setAttribute("type", "text");
                                input.setAttribute("name", "_"+kodekab[j]+"[]");
                                input.setAttribute("class", "form-control");
                                input.setAttribute("size", "60px");
                                tr.appendChild(td);
                                td.appendChild(input);
                            }
                        }
                    }
                });
            }
            
        });
    });

</script>

<script type="text/javascript">
	function selectkegiatan()
 {
  var unitkerja=$('#unitkerja').val();
  
  $.post('<?php echo base_url();?>index.php/admin/get_kegiatan/',
  {
    unitkerja:unitkerja
    
},
function(data) 
{
    $('#jeniskegiatan').html(data);
}); 
  
}
</script>
<script type="text/javascript">			
	function selecttarget()
 {
     window.alert ('Halooo');
     var kabkota=$('#kabkota').val();
     var jeniskegiatan=$('#jeniskegiatan').val();
     
     $.post('<?php echo base_url();?>index.php/admin/get_target/',
     {
        kabkota:kabkota,
        jeniskegiatan:jeniskegiatan
    },
    function(data) 
    {
        $('#target').html(data);
        $('#realisasi').html(data);
    }); 
     
 }
 
</script>

<script type="text/javascript">		
  function callTarget(){
     window.alert ('Halooo');
     var kabkota=$('#kabkota').val();
     var jeniskegiatan=$('#jeniskegiatan').val();
     if(kabkota&&jeniskegiatan){
         
         $.ajax({
             type: "POST",
             url: "<?php echo base_url();?>index.php/admin/get_target/",
             data: { kabkota:kabkota,
             jeniskegiatan:jeniskegiatan
         }
     }).done(function(data) {
         $("#target").val(data);
     });
 }
}
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#nama_kegiatan').change(function(){
            var nama_kegiatan=$(this).val();
            $.ajax({
                type : "GET",
                url : "<?php echo base_url();?>index.php/Admin/getsatuan/"+nama_kegiatan,
                //data : {idgoal: idgoal},
               // async : false,
                dataType : 'json',
                success: function(datasatuan){
                    var html = '';
                    var i;
                    for(i=0; i<datasatuan.length; i++){
                        html += '<option value="'+datasatuan[i].id_satuan+'">'+datasatuan[i].satuan+'</option>';
                    }
                    $('#satuan').html(html);
                }
            });
        });
    });
</script>
