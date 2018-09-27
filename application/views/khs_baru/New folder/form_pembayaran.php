<form action="krs_baru/simpan_bukti_pembayaran" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label>Nim</label>
		<input type="text" name="nim" value="" placeholder="Nim" class="form-control">
	</div>
	<div class="form-group">
		<label>Tanggal Bayar</label>
		<input type="date" name="tgl_bayar" value=""  class="form-control">
	</div>
	<div class="form-group">
		<label>Semester</label>
		<input type="text" name="semester" value=""  class="form-control">
	</div>
	<div class="form-group">
		<label>Bukti Pembayaran</label>
		<input type="file" name="bukti_pembayaran" value=""  class="form-control">
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary">Kirim</button>
	</div>


</form>