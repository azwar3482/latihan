<?php

class Khs_baru extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function beranda()
	{
		$data['title']= 'Dasboard';
		$this->template->load('template', 'dashboard', $data);
	}

	 // function index()
	 // {
	 // 	$data = array(
	 // 		'title' => 'KHS Mahasiswa',
	 // 		'loadpertamajs' => 'loaddata('.$this->session->userdata("keterangan").')',);
	// 	$this->template->load('template', 'khs_baru/khs',$data);
	// }
	function index()
    {
        $data = array(
            'title' => 'KHS Mahasiswa',
            'loadpertamajs' => 'loaddata('.$this->session->userdata("keterangan").')', 
        );
        $this->template->load('template', 'khs_baru/khs',$data);
    }


     function loaddata()
    {
        $id             =  $_GET['id_mahasiswa'];
        $semester       =  $_GET['semester'];
        $mhs            =   "SELECT sm.nim,sm.nama,sm.semester_aktif,ap.nama_prodi,ak.nama_konsentrasi
                            FROM student_mahasiswa as sm,akademik_konsentrasi as ak,akademik_prodi as ap
                            WHERE ap.prodi_id=ak.prodi_id and sm.konsentrasi_id=ak.konsentrasi_id and sm.mahasiswa_id=$id";
        $semester_aktif =  getField('student_mahasiswa', 'semester_aktif', 'mahasiswa_id', $id);
        $d              = $this->db->query($mhs)->row_array();
        $nim            =  getField('student_mahasiswa', 'nim', 'mahasiswa_id', $id);
        echo "
        <table class='table table-bordered'>
        <tr>
            <td width='150'>NAMA</td><td>".  strtoupper($d['nama'])."</td>
            <td width=100>NIM</td><td>".  strtoupper($d['nim'])."</td><td rowspan='2' width='70'><img src='".  base_url()."assets/images/noprofile.gif' width='50'></td>
        </tr>
        <tr>
            <td>Prodi, Konsentrasi</td><td>".  strtoupper($d['nama_prodi'].' / '.$d['nama_konsentrasi'])."</td>
            <td>Semester</td><td>".$d['semester_aktif']."</td>
        </tr>
        </table>
        
        <table class='table table-bordered' id='daftarkrs'>
        <tr><th width='5'>No</th>
        <th width='90'>KODE MP</th>
        <th>NAMA MATAKULIAH</th>
        <th>DOSEN PENGAPU</th>
        <th width=10>SKS</th>
         <th width=20>Kehadiran</th>
         <th width=10>Tugas</th>
        <th width=10>Mutu</th>
        <th>Grade</>
        <th width='10'>Konfirm</th>
        <th width='10'>Aksi</th></tr>";
        if($semester==0)
        {
            // foreach semester dari semester aktif
            for($smt=1;$smt<=$d['semester_aktif'];$smt++)
            {
                echo "<tr class='success'><th colspan='11'>SEMESTER $smt</th></tr>";
                $krs            =   "select kh.grade,mm.kode_makul,mm.nama_makul,mm.sks,ad.nama_lengkap,kh.mutu,kh.confirm,kh.khs_id,kh.kehadiran,kh.tugas
                            FROM makul_matakuliah as mm,akademik_jadwal_kuliah as jk,akademik_krs as ak,
                            app_dosen as ad,akademik_khs as kh
                            WHERE mm.makul_id=jk.makul_id and ad.dosen_id=jk.dosen_id and jk.jadwal_id=ak.jadwal_id 
                            and ak.nim='$nim' and kh.krs_id=ak.krs_id and ak.semester='$smt'";
                $data           =  $this->db->query($krs);
                if($data->num_rows()<1)
                {
                    echo "<tr><td colspan='9'>Data Tidak Ditemukan</td></tr>";
                }
                else
                {
                    $no=1;
                    $sks=0;
                    $mutu=0;
                    $ipk = 0;
                    foreach ($data->result() as $r)
                    {
                        
                        $confirm=$r->confirm==1?'Ya':'Tidak';
                        $btn=$r->confirm==1?'fa fa-trash-o':'fa fa-suitcase';
                        echo "<tr id='krshide$r->khs_id'>
                            <td>$no</td>
                            <td>".  strtoupper($r->kode_makul)."</td>
                            <td>".  strtoupper($r->nama_makul)."</td>
                            <td>".  strtoupper($r->nama_lengkap)."</td>
                            <td align='center'>".  $r->sks."</td>
                            <td>$r->kehadiran</td>
                            <td>$r->tugas</td>
                            <td>$r->mutu</td>
                            <td>".$r->grade."</td>
                            <td>$confirm</td>
                            <td align='center'><i title='konfirm' class='$btn' onclick='konfirm($r->khs_id)'></i></td>
                            </tr>";
                        $no++;
                        $sks=$sks+$r->sks;
                        $mutu=$mutu+$r->mutu;
                    }
                    $ip = $mutu/$sks;
                    //update ip di krs
                    $this->db->where('nim',$d['nim']);
                    $this->db->where('semester',$semester);
                    $this->db->update('akademik_krs',array('ip'=>$ip));
                    //update ipk di mahasiswa
                    $xnim = $d['nim'];
                    $cek_ipk = $this->db->query("SELECT DISTINCT ip FROM akademik_krs WHERE nim='$xnim'");
                    foreach ($cek_ipk->result() as $ripk) {
                        $ipk = $ipk + $ripk->ip;
                    }
                    $xipk = $ipk/$cek_ipk->num_rows();
                    $this->db->where('nim',$d['nim']);
                    $this->db->update('student_mahasiswa',array('ipk'=>$xipk));

                    echo"<tr class='success'><td colspan='4' align='right'>Total SKS</td><td>$sks</td><td colspan='2' align='right'>IP</td><td colspan='4'>".number_format($ip,2)."</td></tr><tr>
            <td colspan=11>".anchor('cetak/cetakkhs/'.$smt.'/'.$id,'<i class="gi gi-print"></i> Cetak KHS',array('title'=>'Cetak KHS','class'=>'btn btn-primary btn-sm'))."
            
            ".anchor('','<i class="gi gi-charts"></i> Grafik',array('Title'=>'Lihat Grafik','class'=>'btn btn-primary btn-sm'))."
            </td></tr>";
                }
            }
            // end foreach
            echo "<td colspan=11>".anchor('khs/rekap_khs/'.$id,'<i class="gi gi-print"></i> Cetak REKAP KHS',array('title'=>'Cetak REKAP KHS','class'=>'btn btn-primary btn-sm','target'=>'blank'))."</td></tr>";
        }
        else
        {
            $krs       =   "select kh.grade,mm.kode_makul,mm.nama_makul,mm.sks,ad.nama_lengkap,kh.mutu,kh.confirm,kh.khs_id,kh.tugas,kh.kehadiran
                            FROM makul_matakuliah as mm,akademik_jadwal_kuliah as jk,akademik_krs as ak,
                            app_dosen as ad,akademik_khs as kh
                            WHERE mm.makul_id=jk.makul_id and ad.dosen_id=jk.dosen_id and jk.jadwal_id=ak.jadwal_id 
                            and ak.nim='$nim' and kh.krs_id=ak.krs_id and ak.semester='$semester' ";
 
            $data      =  $this->db->query($krs);
            $sks=0;
            $mutu=0;
            $ipk = 0;
            if($data->num_rows()<1)
            {
                echo "<tr class='danger'><td colspan=11>DATA KHS TIDAK DITEMUKAN</td></tr>";
            }
            else
            {
                $no=1;
                foreach ($data->result() as $r)
                {
                    $confirm=$r->confirm==1?'Ya':'Tidak';
                    $btn=$r->confirm==1?'fa fa-trash-o':'fa fa-suitcase';
                    echo "<tr id='krshide$r->khs_id'>
                        <td>$no</td>
                        <td>".  strtoupper($r->kode_makul)."</td>
                        <td>".  strtoupper($r->nama_makul)."</td>
                        <td>".  strtoupper($r->nama_lengkap)."</td>
                        <td align='center'>".  $r->sks."</td>
                        <td>$r->kehadiran</td>
                        <td>$r->tugas</td>
                        <td>$r->mutu</td>
                        <td>".$r->grade."</td>
                        <td>$confirm</td>
                        <td align='center'><i title='konfirm' class='$btn' onclick='konfirm($r->khs_id)'></i></td>
                        </tr>";
                    $no++;
                    $sks=$sks+$r->sks;
                    $mutu=$mutu+$r->mutu;
                }
            }
            $ip = $mutu/$sks;
            $this->db->where('nim',$d['nim']);
            $this->db->where('semester',$semester);
            $this->db->update('akademik_krs',array('ip'=>$ip));
            //update ipk di mahasiswa
            $xnim = $d['nim'];
            $cek_ipk = $this->db->query("SELECT DISTINCT ip FROM akademik_krs WHERE nim='$xnim'");
            foreach ($cek_ipk->result() as $ripk) {
                $ipk = $ipk + $ripk->ip;
            }
            $xipk = $ipk/$cek_ipk->num_rows();
            $this->db->where('nim',$d['nim']);
            $this->db->update('student_mahasiswa',array('ipk'=>$xipk));

        echo"<tr class='success'><td colspan='4' align='right'>Total SKS</td><td>$sks</td><td colspan='2' align='right'>IP</td><td colspan='4'>".number_format($ip,2)."</td></tr><tr>
            <td colspan=11>".anchor('cetak/cetakkhs/'.$semester.'/'.$id,'<i class="gi gi-print"></i> Cetak KHS',array('title'=>'Cetak KHS','class'=>'btn btn-primary btn-sm'))."</td></tr><tr>
            <td colspan=11>".anchor('khs/rekap_khs/'.$id,'<i class="gi gi-print"></i> Cetak REKAP KHS',array('title'=>'Cetak REKAP KHS','class'=>'btn btn-primary btn-sm','target'=>'blank'))."</td></tr></table>";
    }
    }
    



    
}
?>