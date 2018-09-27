<?php
class khs extends CI_Controller{
        
    var $folder =   "khs";
    var $tables =   "akademik_khs";
    var $pk     =   "khs_id";
    var $title  =   "Kartu Hasil Studi";
    
    function __construct() {
        parent::__construct();
        error_reporting(0);
    }
    
    function index()
    {
        $data['title']=  $this->title;
        $data['tahun_angkatan']=  $this->db->get('student_angkatan')->result();
        $this->template->load('template', $this->folder.'/view',$data);
    }

    function rekap_khs($id)
    {
        $mhs            =   "SELECT sm.nim,sm.nama,sm.semester,sm.tempat_lahir,sm.tanggal_lahir,sm.ipk,ap.nama_prodi,ak.nama_konsentrasi,ak.jenjang
                            FROM student_mahasiswa as sm,akademik_konsentrasi as ak,akademik_prodi as ap
                            WHERE ap.prodi_id=ak.prodi_id and sm.konsentrasi_id=ak.konsentrasi_id and sm.mahasiswa_id=$id";
        $nim            =  getField('student_mahasiswa', 'nim', 'mahasiswa_id', $id);
        $data['data'] = $this->db->query($mhs);
        $data['nim'] = $nim;
        $this->load->view('khs/rekap_khs', $data);
    }
    
    function loaddata()
    {
        $id             =  $_GET['id_mahasiswa'];
        $semester       =  $_GET['semester'];
        $mhs            =   "SELECT sm.nim,sm.nama,sm.semester,ap.nama_prodi,ak.nama_konsentrasi
                            FROM student_mahasiswa as sm,akademik_konsentrasi as ak,akademik_prodi as ap
                            WHERE ap.prodi_id=ak.prodi_id and sm.konsentrasi_id=ak.konsentrasi_id and sm.mahasiswa_id=$id";
        $semester =  getField('student_mahasiswa', 'semester', 'mahasiswa_id', $id);
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
            <td>Semester</td><td>".$d['semester']."</td>
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
        <th>Grade</th>
        <th width='10'>Konfirm</th>";

        if($this->session->userdata('level')=='1')
        {
            echo"<th width='10' colspan=2>Operasi</th></tr>";
        }

        if($semester==0)
        {
            // foreach semester dari semester aktif
            for($smt=1;$smt<=$d['semester'];$smt++)
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
                        $btn=$r->confirm==1?'fa fa-pencil-square-o':'fa fa-suitcase';
                        echo "<tr id='krshide$r->khs_id'>
                            <td>$no</td>
                            <td>".  strtoupper($r->kode_makul)."</td>
                            <td>".  strtoupper($r->nama_makul)."</td>
                            <td>".  strtoupper($r->nama_lengkap)."</td>
                            <td align='center'>".  $r->sks."</td>
                            <td>$r->kehadiran</td>
                            <td>$r->tugas</td>
                            <td>$r->mutu</td>
                            <td>".$r->grade."</td>";

                            if($this->session->userdata('level')==1)
                            {
                                echo "<td>$confirm</td>
                                    <td width=5><i title='konfirm' class='$btn' onclick='konfirm($r->khs_id)'></i></td>
                                    
                                    <td width=5><i class='fa fa-trash-o' onclick='hapus($r->khs_id)'></i></td>
                                    ";
                            }
                            echo "</tr>";
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
                    $btn=$r->confirm==1?'fa fa-pencil-square-o':'fa fa-suitcase';
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
                        <td>$confirm</td>";
                        if($this->session->userdata('level')=='1')
                        {
                        "<td width=5><i title='konfirm' class='$btn' onclick='konfirm($r->khs_id)'></i></td>
                
                        <td width=5><i class='fa fa-trash-o' onclick='hapus($r->khs_id)'></i></td>
                        </tr>";
                    }
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
    
    
    function semester_mhs()
    {
        $id=$_GET['id_mahasiswa'];
        $sms=  getField('student_mahasiswa', 'semester', 'mahasiswa_id', $id);
        for($i=1;$i<=$sms;$i++)
        {
            echo "<option value='$i'> Semester $i</option>";
        }
        echo "<option value='0'>Semua Semester</option>";
    }
    
    function berinilai()
    {

        $dosen  =  $this->session->userdata('keterangan');
        $thn    = get_tahun_ajaran_aktif('tahun_akademik_id');
        $query="SELECT mm.nama_makul,jk.jadwal_id
                FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm
                WHERE mm.makul_id=jk.makul_id and jk.dosen_id=$dosen and jk.tahun_akademik_id=$thn";
        $data['title']="Beri Nilai";
        $data['kelas']=  $this->db->query($query)->result();
        $this->template->load('template', $this->folder.'/berinilai',$data);
    }
    
    function form_berinilai()
    {
        $jadwal_id=  $_GET['jadwal_id'];
        $thn      =  get_tahun_ajaran_aktif('tahun_akademik_id');
        $d        =  $this->db->query("SELECT ad.nama_lengkap,mm.nama_makul 
                    FROM app_dosen as ad,makul_matakuliah as mm,akademik_jadwal_kuliah as jk 
                    WHERE jk.makul_id=mm.makul_id and jk.dosen_id=ad.dosen_id and jk.jadwal_id=$jadwal_id")->row_array();
        $sql="  SELECT sm.nim,sm.nama,kh.mutu,kh.khs_id,kh.tugas,kh.kehadiran,kh.grade
                FROM akademik_krs as ak,student_mahasiswa as sm,akademik_khs as kh,akademik_jadwal_kuliah as jk
                WHERE kh.krs_id=ak.krs_id and sm.nim=ak.nim and ak.jadwal_id='$jadwal_id' and jk.jadwal_id=ak.jadwal_id and jk.tahun_akademik_id='$thn'";
        echo " <div class='table table-responsive'><table class='table table-bordered'>
              <tr class='success'><th colspan=2>MATAKULIAH</th></tr>
               <tr><td width=120>Matakuliah</td><td>".  strtoupper($d['nama_makul'])."</td></tr>
               <tr><td>Dosen Pengapu</td><td>".  strtoupper($d['nama_lengkap'])."</td></tr>
               </table>
               <table class='table table-bordered'>
               <tr class='success'><th colspan=7>FORM NILAI MAHASISWA</th></tr>
               <tr><th>No</th><th>NIM</th><th>NAMA MAHASISWA</th><th width=90>Kehadiran</th><th width=90>Tugas</th><th width=90>Mutu</th><th>Grade</th></tr>";
        $data=  $this->db->query($sql)->result();
        $no=1;
        foreach ($data as $r)
        {
            echo "<tr>
                <td width='7'>$no</td>
                <td width='70'>".  strtoupper($r->nim)."</td>
                <td>".  strtoupper($r->nama)."</td>
                <td align='center' width='120'>";
                echo inputan('text', '','col-sm-12','Kehadiran', 0, $r->kehadiran,array('onkeyup'=>'simpankehadiran('.$r->khs_id.')','id'=>'ambilkehadiran'.$r->khs_id)).'</td><td align="center" width="120">';
                echo inputan('text', '','col-sm-12','Tugas ..', 0, $r->tugas,array('onkeyup'=>'simpantugas('.$r->khs_id.')','id'=>'ambiltugas'.$r->khs_id)).'</td><td align="center" width="120">';
                echo inputan('text', 'link','col-sm-12','Link ...', 1, $r->mutu,array('onkeyup'=>'simpan('.$r->khs_id.')','id'=>'ambil'.$r->khs_id));
                echo"</td>
                    <td width='150'>";
                echo editcombo('grade','app_nilai_grade','col-sm-14','grade','grade','',array('onChange'=>'simpangrade('.$r->khs_id.')','id'=>'ambilgrade'.$r->khs_id),  $r->grade);
                echo"</td>
                </tr>";
            $no++;
        }
        echo"  </table></div>";
    }
    
    
    function grade($nilai)
    {
        $set_nilai=  $this->db->get('app_nilai_grade')->result();
        foreach ($set_nilai as $s)
        {
            if($nilai >=$s->dari and $nilai <= $s->sampai)
            {
                return $s->grade;
            }
        }

    }
    
    function simpan_nilai()
    {
        akses_dosen();
        $id     =   $_GET['id'];
        $nilai  =   $_GET['nilai'];
        $this->mcrud->update($this->tables,array('mutu'=>$nilai), $this->pk,$id);
    }
    
    function simpan_kehadiran()
    {
        akses_dosen();
        $id     =   $_GET['id'];
        $nilai  =   $_GET['nilai'];
        $this->mcrud->update($this->tables,array('kehadiran'=>$nilai), $this->pk,$id);
    }
    
    function simpan_tugas()
    {
        akses_dosen();
        $id     =   $_GET['id'];
        $nilai  =   $_GET['nilai'];
        $this->mcrud->update($this->tables,array('tugas'=>$nilai), $this->pk,$id);
    }
    
    function simpan_grade()
    {
        akses_dosen();
        $id     =   $_GET['id'];
        $nilai  =   $_GET['nilai'];
        $this->mcrud->update($this->tables,array('grade'=>$nilai), $this->pk,$id);
    }
    
    function tampilkanmahasiswa()
    {
        $konsentrasi    =   $_GET['konsentrasi'];
        $tahun_angkatan =   $_GET['tahun_angkatan']; // tahun_akademik_id
        $query="select mahasiswa_id,nama from student_mahasiswa where angkatan_id='$tahun_angkatan' and konsentrasi_id='$konsentrasi'";
        $data=  $this->db->query($query)->result();
        foreach ($data as $r)
        {
                   echo "<option onclick='tampilkan_semester($r->mahasiswa_id)' value='$r->mahasiswa_id'>".  strtoupper($r->nama)."</option>"; 
        }
    }
    
    function konfirm()
    {
        akses_admin();
        $id=$_GET['khs_id'];
        $this->mcrud->update($this->tables,array('confirm'=>1), $this->pk,$id);
    }
 function delete()
    {
        $id     =  $_GET['khs_id'];
        $this->mcrud->delete($this->tables,  $this->pk,  $id);
        $this->mcrud->delete('akademik_khs',  $this->pk,  $id);
 
    }


}