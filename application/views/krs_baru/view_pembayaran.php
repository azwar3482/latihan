<table class="table table-boredered">
	<tr>
		<th>Tgl Pembayaran</th>
		<th>Nim</th>
		<th>Nama</th>
		<th>Bukti Pembayaran</th>
		<th>Semester</th>
	</tr>

	<?php 
	$idprodi = $this->session->userdata('keterangan');
	$sql = $this->db->query("SELECT a.tgl_bayar,a.nim, c.nama, a.foto_pembayaran, a.semester FROM cek_pembayaran as a, akademik_konsentrasi as b, student_mahasiswa as c 
		where a.nim=c.nim and c.konsentrasi_id=b.konsentrasi_id and b.prodi_id='$idprodi' order by a.tgl_bayar desc");
	foreach ($sql->result() as $rw) {
	 ?>
	<tr>
		<td><?php echo $rw->tgl_bayar ?></td>
		<td><?php echo $rw->nim ?></td>
		<td><?php echo $rw->nama ?></td>
		<td><a href="image/bukti_pembayaran/<?php echo $rw->foto_pembayaran ?>" target="_blank">
			<img src="image/bukti_pembayaran/<?php echo $rw->foto_pembayaran ?>" style="width: 100px; height: 100px;">
		</a></td>
		<td><?php echo $rw->semester ?></td>
	</tr>
	<?php } ?>
</table>