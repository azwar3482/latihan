 <h3 class="page-header page-header-top"> <?php echo $title;?> <small><?php echo $desc;?></small> </h3>
 
 <script src="<?php echo base_url()?>assets/js/jquery.min.js">
</script>
<script>
$(document).ready(function(){
    loadkonsentrasi();
});
</script>


<script>
$(document).ready(function(){
  $("#prodi").change(function(){
      loadkonsentrasi();
  });
});
</script>

<script type="text/javascript">
function loadkonsentrasi()
{
    var prodi=$("#prodi").val();
    $.ajax({
    url:"<?php echo base_url();?>matakuliah/tampilkonsentrasi",
    data:"prodi=" + prodi ,
    success: function(html)
    { 
       $("#konsentrasi").html(html);
       loadsemester();
    }
          });
}

</script>

<?php
echo form_open_multipart($this->uri->segment(1).'/post');
?>
<div class="col-sm-3">
    <table class="table table-bordered">
    <tr><td>Tahun Akademik <?php echo buatcombo('tahun_akademik', 'akademik_tahun_akademik', '', 'keterangan', 'tahun_akademik_id', '', array('id'=>'tahun_akademik_id'))?></td></tr>
    <tr><td>Program Studi <?php echo buatcombo('prodi', 'akademik_prodi', '', 'nama_prodi', 'prodi_id', '', array('id'=>'prodi'))?></td></tr>
    <tr><td>Konsentrasi <?php echo combodumy('konsentrasi', 'konsentrasi')?></td></tr>
    
    <tr><td><?php echo anchor('jadwalkuliah','<span class="glyphicon glyphicon-plus"></span> Kembali',array('class'=>'btn btn-primary  btn-sm'));?> 
        </td></tr>
</table>
</div>

<div class="col-sm-9">
    <table class="table table-bordered" id="makul">
        <!-- <tr><td width="50">Tahun akademik</td></td>
              <?php
                echo buatcombo('tahun_akademik','akademik_tahun_akademik','clo-sm-6','tahun_akademik','tahun_akademik_id','','');
                ?>
      </td></tr>
      <tr><td width="50">Konsenterasi</td></td>
            <?php
                echo buatcombo('konsentrasi','akademik_konsentrasi','clo-sm-6','konsentrasi','konsentrasi_id','','');
            ?>
          </td></tr> -->
        <tr><td width="150">Matakuliah</td><td>
                 <?php
                    echo buatcombo('matakuliah','makul_matakuliah','clo-sm-6','nama_makul','makul_id','','');
                ?>
        </td></tr>
         <tr><td>Dosen Pengapu</td><td>
                <?php 
                    echo buatcombo('dosen','app_dosen','col-sm-17','nama_lengkap','dosen_id','','');   
                ?>
        </td></tr>
        <tr><td>Semester | Hari</td><td>
        <?php
                      echo buatcombo('semester','app_semester','col-sm-3','semester','semester_id','','');
                ?>
                <?php echo buatcombo('hari','app_hari','col-sm-3','hari','hari_id','',''); ?>
        </td></tr>
        
        <tr><td>Ruangan</td><td>
                <?php echo buatcombo('ruangan','app_ruangan','col-sm-3','nama_ruangan','ruangan_id','',''); ?>
        </td></tr>
        <tr><td colspan="2"><input type="submit" name="submit" value="simpan" class="btn-danger btn-sm"><?php echo anchor($this->uri->segment(1),'kembali', array('class'=>'btn btn-danger btn-sm')); ?>
        </td></tr>
       <!--  <tr><td colspan="2"><button id="simpan" class="btn btn-primary  btn-sm">Simpan Data</button></td></tr> -->
    </table>
</div>
