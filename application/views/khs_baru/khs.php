<h2 style="font-weight: normal;"><?php echo $title;?></h2>
<div class="push">
    <ol class="breadcrumb">
        <li><i class='fa fa-home'></i> <a href="javascript:void(0)">Home</a></li>
        <li><?php echo anchor($this->uri->segment(1),$title);?></li>
        <li class="active">Data</li>
    </ol>
</div>
 
 <script src="<?php echo base_url()?>assets/js/jquery.min.js">
</script>



<script type="text/javascript">
function loaddata(mahasiswa_id)
{
        $.ajax({
	url:"<?php echo base_url();?>khs/loaddata",
	data:"id_mahasiswa=" + mahasiswa_id ,
	success: function(html)
	{
            $("#daftarkhs").html(html);
	}
	});
}


function loadtablemapel(id, konsentrasi)
{
    //var konsentrasi=$("#konsentrasi").val();
    $.ajax({
	url:"<?php echo base_url();?>khs/loadmapel",
	data:"konsentrasi=" + konsentrasi +"&mahasiswa_id="+id,
	success: function(html)
	{
            $("#daftarkhs").html(html); 
	}
	});
}


function ambil(jadwal_id,mahasiswa_id)
{
    $.ajax({
	url:"<?php echo base_url();?>khs/post",
	data:"jadwal_id=" + jadwal_id+"&mahasiswa_id="+mahasiswa_id ,
	success: function(html)
	{
		if (html == '') {
			$("#hide"+jadwal_id).hide(300);
		} else {
			alert(html);
		}
	}
	});
   
}


function hapus(khs_id)
{
        $.ajax({
	url:"<?php echo base_url();?>khs/delete",
	data:"khs_id=" + khs_id ,
	success: function(html)
	{
            $("#khshide"+khs_id).hide(300);   
	}
	});
}
</script>
<?php
if($this->session->userdata('level')==1)
{
    $param="";
}
else
{
    $param=array('prodi_id'=>$this->session->userdata('keterangan'));
}
?>


<div class="col-sm-8">
    <div id="daftarkhs"></div>
</div>




