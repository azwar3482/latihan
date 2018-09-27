<form action="" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label>Dosen PA</label>
		<select class="form-control" name="id_dosen">
			<option value="">--Dosen PA--</option>
			<?php 
			$sql = $this->db->get('app_dosen');
			foreach ($sql->result() as $row) {
			 ?>
			<option value="<?php echo $row->dosen_id ?>"><?php echo $row->dosen_id.'-'.$row->nama_lengkap ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-group">
		<label>Mahasiswa</label>
		<select class="form-control" name="id_mahasiswa">
			<option value="">--Mahasiswa--</option>
			<?php 
			$sql = 0;
			$cek=$this->db->get('dosen_pa');
			if ($cek->num_rows() == 0) {
				$sql = $this->db->get('student_mahasiswa');
			} else {
				$sql = $this->db->query("SELECT a.mahasiswa_id, a.nama, a.nim FROM student_mahasiswa AS a, dosen_pa AS b WHERE a.mahasiswa_id!=b.id_mahasiswa");
			}
			foreach ($sql->result() as $row) {
			 ?>
			<option value="<?php echo $row->mahasiswa_id ?>"><?php echo $row->nim.'-'.$row->nama ?></option>
			<?php } ?>
		</select>
	</div>	
	<div class="form-group">
		<button type="submit" class="btn btn-primary">Kirim</button>
	</div>


</form>