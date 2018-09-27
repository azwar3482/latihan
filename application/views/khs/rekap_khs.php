<!DOCTYPE html>
<html>
<head>
	<title></title>
	<base href="<?php echo base_url() ?>">
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/img/logo1.png">
</head>
<body onload="print()">
		<?php $d = $data->row_array(); ?>
		<center>
		<table>
			<tr>
				<td>
					<img src="assets/images/logo.png" style="width: 100px; height: 100px">
				</td>
				<td>
					<center>
					<h3>Universitas Nurtanio Bandung</h3>
					<h4>Jl. Pajajaran No. 219, Lanud Husein Sastranegara, dan kampus II pada Jl Casa No. 2, Lanud Sulaiman</h4>
					<h4>(022) 6034484</h4>
					<h4>E-mail : http://www.unur.ac.id</h4>
					</center>
				</td>
			</tr>
		</table>
		<hr>
		<h4><u>DAFTAR NILAI AKADEMIK</u></h4>
		</center>

		<table class="table ">
			<tr>
				<td>Nim</td>
				<td>:</td>
				<td><?php echo $d['nim'] ?></td>
				<td>Nama</td>
				<td>:</td>
				<td><?php echo $d['nama'] ?></td>
			</tr>
			<tr>
				<td>Tempat/Tgl Lahir</td>
				<td>:</td>
				<td><?php echo $d['tempat_lahir'].', '.$d['tanggal_lahir'] ?></td>
				<td>Jurusan/Jenjang</td>
				<td>:</td>
				<td><?php echo $d['nama_konsentrasi'].'/'.$d['jenjang'] ?></td>
			</tr>
		</table>

		<table class='table table-bordered' id='daftarkrs'>
		    <tr>
		        <th>No</th>
		        <th>KODE MP</th>
		        <th>NAMA MATAKULIAH</th>
		        <th>SKS</th>
		        <th>NILAI</>
		        <th>Mutu</th>
		   	</tr>
		<?php 
		
		$jsks=0;
		for($smt=1;$smt<=$d['semester_aktif'];$smt++)
            {
                echo "<tr class='success'><th colspan='6'>SEMESTER $smt</th></tr>";
                $krs            =   "select kh.grade,mm.kode_makul,mm.nama_makul,mm.sks,ad.nama_lengkap,kh.mutu,kh.confirm,kh.khs_id,kh.kehadiran,kh.tugas
                            FROM makul_matakuliah as mm,akademik_jadwal_kuliah as jk,akademik_krs as ak,
                            app_dosen as ad,akademik_khs as kh
                            WHERE mm.makul_id=jk.makul_id and ad.dosen_id=jk.dosen_id and jk.jadwal_id=ak.jadwal_id 
                            and ak.nim='$nim' and kh.krs_id=ak.krs_id and ak.semester='$smt'";
                $data           =  $this->db->query($krs);
                if($data->num_rows()<1)
                {
                    echo "<tr><td colspan='6'>Data Tidak Ditemukan</td></tr>";
                }
                else
                {
                    $no=1;
                    $sks=0;
                    $mutu=0;
                    $ipk = 0;
                    foreach ($data->result() as $r)
                    {
                        
                        echo "<tr id='krshide$r->khs_id'>
                            <td>$no</td>
                            <td>".  strtoupper($r->kode_makul)."</td>
                            <td>".  strtoupper($r->nama_makul)."</td>
                            <td align='center'>".  $r->sks."</td>
                            <td>".$r->grade."</td>
                            <td>$r->mutu</td>
                            </tr>";
                        $no++;
                        $sks=$sks+$r->sks;
                        $mutu=$mutu+$r->mutu;
                    }
                    $ip = $mutu/$sks;
                    

                    //echo"<tr class='success'><td colspan='3' align='right'>Total SKS</td><td>$sks</td><td colspan='1' align='right'>IP</td><td colspan='4'>".number_format($ip,2)."</td></tr><tr>";
                }
                $jsks = $jsks + $sks;
            }
            // end foreach

		 ?>
		<tr class='success'>
			<td colspan='3' align='right'>Total SKS</td><th colspan='3'><?php echo $jsks ?></th>
		</tr>
		<tr>
			<td colspan='3' align='right'>IP Komulatif</td><th colspan='3'><?php echo number_format($d['ipk'], 2) ?></th>
		</tr>
		</table>
</body>
</html>