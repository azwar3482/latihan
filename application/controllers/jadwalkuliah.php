<?php
class jadwalkuliah extends CI_Controller
{
    var $folder =   "jadwalkuliah";
    var $tables =   "akademik_jadwal_kuliah";
    var $pk     =   "jadwal_id";
    var $title  =   "Jadwal Kuliah";
    
    function __construct() {
        parent::__construct();
    }
    
    function index()
    {
        //akses_admin();
        $data['title']=  $this->title;
        $data['desc']="";
        $this->template->load('template', $this->folder.'/view',$data);
    }
    
    function tampiljadwal()
    {
        $konsentrasi    =   $_GET['konsentrasi'];
        $tahun_akademik =   $_GET['tahun_akademik'];
        $semester       =   $_GET['semester'];
        
        
        echo "<table class='table table-bordered' id='jadwal'>
        <tr><th width=3>No</th>
        <th width=145>Hari</th>
      
        <th width=50>Matakuliah</th>
        <th width=3>SKS</th>
        <th width=125>Ruang</th>
        <th width=165>Jam</th>
        <th width=180>Dosen</th>
        <th>Operasi</th></tr>";
        $i=1;
       
        if($semester==0)
        {
            // looping semester
            $smt=  getField('akademik_konsentrasi', 'jml_semester', 'konsentrasi_id', $konsentrasi);
            for($j=1;$j<=$smt;$j++)
            {
                echo"<tr class='success'><th colspan=9>SEMESTER $j</th></tr>";
                $sql="  SELECT jk.*,mm.jam,mm.nama_makul,mm.kode_makul,mm.sks,mm.semester,jk.jam_mulai,jk.jam_selesai
                FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm
                WHERE mm.makul_id=jk.makul_id and jk.tahun_akademik_id=$tahun_akademik and jk.konsentrasi_id=$konsentrasi and jk.semester=$j";
                $data=  $this->db->query($sql)->result();
                $class="class='form-control'";
                foreach ($data as $r)
                {

                    echo "<tr><td>$i</td>
                        <td>";
                        echo editcombo('hari','app_hari','col-sm-24','hari','hari_id','',array('onchange'=>'simpanhari('.$r->jadwal_id.')','id'=>'hariid'.$r->jadwal_id),$r->hari_id);
                        echo"</td>
                      
                        <td>";
                        echo editcombo('nama_makul','makul_matakuliah','col-sm-17','nama_makul','makul_id','',array('onchange'=>'simpannamamakul('.$r->jadwal_id.')','id'=>'makulid'.$r->jadwal_id),$r->makul_id);
                        echo"</td>
                        <td align='center'>$r->sks</td>
                        <td>";
                        echo editcombo('ruang','app_ruangan','col-sm-14','nama_ruangan','ruangan_id','',array('onchange'=>'simpanruang('.$r->jadwal_id.')','id'=>'ruangid'.$r->jadwal_id),$r->ruangan_id);
                        echo"</td>
                        <td>";
                        echo inputan('text', '', 'col-sm-11', '', 1, $r->jam_mulai, array('onKeyup'=>'simpanjam('.$r->jadwal_id.')','id'=>'jamid'.$r->jadwal_id));
                        echo inputan('text','', 'col-sm-11', '', 1, $r->jam_selesai, array('disabled'=>'disabled'));
                        
                        //echo editcombo('waktu_kuliah','akademik_waktu_kuliah','col-sm-13','keterangan','waktu_id','',array('onchange'=>'simpanjam('.$r->jadwal_id.')','id'=>'jamid'.$r->jadwal_id),$r->waktu_id);
                        echo"</td>
                        <td>";
                        echo editcombo('dosen','app_dosen','col-sm-17','nama_lengkap','dosen_id','',array('onchange'=>'simpandosen('.$r->jadwal_id.')','id'=>'dosenid'.$r->jadwal_id),$r->dosen_id);
                        // echo"</td>
                        //     <td><i class='gi gi-print' title='cetak absen'></i></td></tr>"; disini
                        
                        echo "<td width=5>".anchor('jadwalkuliah/edit/'.$r->mahasiswa_id,'<i class="fa fa-pencil-square-o"></i>',array('title'=>'edit data'))."</td>
                <td width=5><i class='fa fa-trash-o' onclick='hapus($r->jadwal_id)'></i></td>";
                //<td width=5>".anchor('mahasiswa/cetak/'.$r->mahasiswa_id,'<i class="fa fa-print"></i>',array('title'=>'Cetak data'))."</td>
                echo"</tr>";
                                        
                                       
                                                                            


                    $i++;




                    
                }  
            }
        }
        else
        {
            $sql="  SELECT jk.*,mm.jam,mm.nama_makul,mm.kode_makul,mm.sks,mm.semester,jk.jam_mulai,jk.jam_selesai
                FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm
                WHERE mm.makul_id=jk.makul_id and jk.tahun_akademik_id=$tahun_akademik and jk.konsentrasi_id=$konsentrasi and jk.semester=$semester";
                $data=  $this->db->query($sql)->result();
                $class="class='form-control'";
                foreach ($data as $r)
                {

                    echo "<tr><td>$i</td>
                        <td>";
                        echo editcombo('hari','app_hari','col-sm-24','hari','hari_id','',array('onchange'=>'simpanhari('.$r->jadwal_id.')','id'=>'hariid'.$r->jadwal_id),$r->hari_id);
                        echo"</td>
                      
                        <td>";

                        echo editcombo('nama_makul','makul_matakuliah','col-sm-17','nama_makul','makul_id','',array('onchange'=>'simpannamamakul('.$r->jadwal_id.')','id'=>'makulid'.$r->jadwal_id),$r->makul_id);
                         echo"</td>


                        
                        <td align='center'>$r->sks</td>
                        <td>";
                        echo editcombo('ruang','app_ruangan','col-sm-14','nama_ruangan','ruangan_id','',array('onchange'=>'simpanruang('.$r->jadwal_id.')','id'=>'ruangid'.$r->jadwal_id),$r->ruangan_id);
                        echo"</td>
                        <td>";
                        echo inputan('text', '', 'col-sm-14', '', 1, $r->jam_mulai, array('onKeyup'=>'simpanjam('.$r->jadwal_id.')','id'=>'jamid'.$r->jadwal_id));
                        echo inputan('text','', 'col-sm-14', '', 1, $r->jam_selesai, array('disabled'=>'disabled'));
                        //echo editcombo('waktu_kuliah','akademik_waktu_kuliah','col-sm-13','keterangan','waktu_id','',array('onchange'=>'simpanjam('.$r->jadwal_id.')','id'=>'jamid'.$r->jadwal_id),$r->waktu_id);
                        echo"</td>
                        <td>";
                        echo editcombo('dosen','app_dosen','col-sm-33','nama_lengkap','dosen_id','',array('onchange'=>'simpandosen('.$r->jadwal_id.')','id'=>'dosenid'.$r->jadwal_id),$r->dosen_id);
                        // echo"</td>
                        //     <td><i class='fa fa-file-text' title='cetak absen'></i></td></tr>";
                        //     
                        echo "<td width=5>".anchor('jadwalkuliah/edit/'.$r->jadwal_id,'<i class="fa fa-pencil-square-o"></i>',array('title'=>'edit data'))."</td>
                <td width=5><i class='fa fa-trash-o' onclick='hapus($r->jadwal_id)'></i></td>";
                //<td width=5>".anchor('mahasiswa/cetak/'.$r->mahasiswa_id,'<i class="fa fa-print"></i>',array('title'=>'Cetak data'))."</td>
                echo"</tr>";


                    $i++;
                }
        }
        echo"</table>";
    }
    
    function simpannamamakul()
    {
        $id              =   $_GET['id'];
        $nilainamamakul  =   $_GET['nilainamamakul'];
        $nilaihari       =   $_GET['nilaihari'];
        $nilai_jam       =   $_GET['nilai_jam'];
        $nilairuang      =   $_GET['nilai_ruang'];
        $get_jam         =   $this->db->query("SELECT mm.jam,jk.ruangan_id,jk.hari_id,jk.jadwal_id FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm WHERE mm.makul_id=jk.makul_id and jk.jadwal_id=$id")->row_array();
        $chek= $this->chek_ruangan($nilairuang, $nilaihari, $nilaijam, $nilainamamakul);
        if($chek==1){
            $this->mcrud->update($this->tables,array('hari_id'=>$nilaihari), $this->pk,$id);
            echo "<div class='alert alert-success'>nama makul berhasil Diperbaharui <i class=gi gi-ok'></i></div>";
        }
            else{
                echo "<div class='alert-danger'>nama makul Gagal Diperbaharui <i class='gi gi-remove'></i></div>";
            }
    }
    function post(){
        if(isset($_POST['submit'])){
            $hari       =   $this->input->post('hari');
            $matakuliah =   $this->input->post('matakuliah');
           // $sks        =   $this->input->post('sks');
            $ruang      =   $this->input->post('ruang');
          //  $jam        =   $this->input->post('jam');
            $dosen      =   $this->input->post('dosen');
            $semester   =   $this->input->post('semester');

            $tahun_akademik =   $this->input->post('tahun_akademik');
            $konsentrasi    =   $this->input->post('konsentrasi');
            
            $data     =   array('hari_id'=>$hari, 'makul_id'=>$matakuliah, 'ruangan_id'=>$ruang, 'dosen_id'=>$dosen, 'semester'=>$semester, 'tahun_akademik_id'=>$tahun_akademik, 'konsentrasi_id'=>$konsentrasi);

            $this->db->insert($this->tables,$data);
            // $id     =getField('akademik_jadwal_kuliah', 'jadwal_id', 'semester', $semester);
            // $this->session->set_flashdata('pesan', "<div class='alert-success'> Data $dose sudah tersimpan </div>");
          //  redirect('jadwalkuliah/post');
            redirect($this->uri->segment(1));

        }
    else{
        $data['title']= $this->title;
        $data['desc']="";
        $this->template->load('template',$this->folder.'/post',$data);
        }
    }

    function edit()
    {
        if(isset($_POST['submit']))
        {
            $id     = $this->input->post('id');
                        // pribadi
            $nama               =   $this->input->post('nama');
            $nim                =   $this->input->post('nim');
            $semester           =   $this->input->post('semester');
            $alamat             =   $this->input->post('alamat');
            $konsentrasi        =   $this->input->post('konsentrasi');
            $tahun              =   $this->input->post('tahun_angkatan');
            $tempat_lahir       =   $this->input->post('tempat_lahir');
            $tgl_lahir          =   $this->input->post('tanggal_lahir');
            $agama              =   $this->input->post('agama');
            $gender             =   $this->input->post('gender');
            $angkatan           =   $this->input->post('tahun_angkatan');
            // orang tua
            $hari       =   $this->input->post('hari');
            $matakuliah =   $this->input->post('matakuliah');
            $sks        =   $this->input->post('sks');
            $ruang      =   $this->input->post('ruang');
            $jam        =   $this->input->post('jam');
            $dosen      =   $this->input->post('dosen');
            $semester   =   $this->input->post('semester');

            $tahun_akademik =   $this->input->post('tahun_akademik');
            $konsentrasi    =   $this->input->post('konsentrasi');
            $nama_ayah          =   $this->input->post('nama_ayah');
            $nama_ibu           =   $this->input->post('nama_ibu');
            $pekerjaan_ayah     =   $this->input->post('pekerjan_ayah');
            $pekerjaan_ibu      =   $this->input->post('pekerjaan_ibu');
            $alamat_ayah        =   $this->input->post('alamat_ayah');
            $alamat_ibu         =   $this->input->post('alamat_ibu');
            $penghsln_ayah      =   $this->input->post('penghasilan_ayah');
            $penghsln_ibu       =   $this->input->post('penghasilan_ibu');
            $no_hp_ortu         =   $this->input->post('no_hp_ortu');
            
            // sekolah
            $sekolah_nama       =   $this->input->post('sekolah_nama');
            $sekolah_telpon     =   $this->input->post('sekolah_telpon');
            $sekolah_alamat     =   $this->input->post('sekolah_alamat');
            $sekolah_jurus      =   $this->input->post('sekolah_jurusan');
            $sekolah_tahun      =   $this->input->post('sekolah_tahun');
            // kampus
            $kampus_nama        =   $this->input->post('kampus_nama');
            $kampus_telpon      =   $this->input->post('kampus_telpon');
            $kampus_alamat      =   $this->input->post('kampus_alamat');
            $kampus_jurus       =   $this->input->post('kampus_jurusan');
            $kampus_tahun       =   $this->input->post('kampus_tahun');
            // instansi
            $instansi_nama      =   $this->input->post('instansi_nama');
            $instansi_telpon    =   $this->input->post('instansi_telpon');
            $instansi_alamat    =   $this->input->post('instansi_alamat');
            $instansi_mulai     =   $this->input->post('instansi_mulai');
            $instansi_sampai    =   $this->input->post('instansi_sampai');
            // institusi
            $institusi_nama     =   $this->input->post('institusi_nama');
            $institusi_telpon   =   $this->input->post('institusi_telpon');
            $institusi_alamat   =   $this->input->post('institusi_alamat');
            
            $instansi           =   array(  'instansi_nama'=>$instansi_nama,
                                            'instansi_telpon'=>$instansi_telpon,
                                            'instansi_alamat'=>$instansi_alamat,
                                            'instansi_mulai'=>$instansi_mulai,
                                            'instansi_sampai'=>$instansi_sampai,
                                            'semester'=>$semester,
                                            'angkatan_id'=> $angkatan);
            $institusi          =   array(  'institusi_nama'=>$institusi_nama,
                                            'institusi_telpon'=>$institusi_telpon,
                                            'institusi_alamat'=>$institusi_alamat);
            $jad                =   array(  'hari'       =>$hari,
                                            'matakuliah' =>$matakuliah,
                                            'sks'        =>$sks,
                                            'ruang'      =>$ruang,
                                            'jam'        =>$jam,
                                            'dosen'      =>$dosen,
                                            'semester'   =>$semester);

            $tahun_akademik =   $this->input->post('tahun_akademik');
            $konsentrasi    =   $this->input->post('konsentrasi');





            $pribadi            =   array(  'nama'=>$nama,
                                            'agama_id'=>$agama,
                                            'gender'=>$gender,
                                            'tempat_lahir'=>$tempat_lahir,
                                            'tanggal_lahir'=>$tgl_lahir,
                                            'nim'=>$nim,
                                            'konsentrasi_id'=>$konsentrasi,
                                            'alamat'=>$alamat
                                            );
            
            $sekolah            =   array(  'sekolah_nama'=>$sekolah_nama,
                                            'sekolah_telpon'=>$sekolah_telpon,
                                            'sekolah_alamat'=>$sekolah_alamat,
                                            'sekolah_tahun_lulus'=>$sekolah_tahun,
                                            'sekolah_jurusan'=>$sekolah_jurus);
            
            $kampus             =   array(  'kampus_nama'=>$sekolah_nama,
                                            'kampus_telpon'=>$sekolah_telpon,
                                            'kampus_alamat'=>$sekolah_alamat,
                                            'kampus_tahun_lulus'=>$sekolah_tahun,
                                            'kampus_jurusan'=>$sekolah_jurus);
            
            $orangtua           =   array(  'nama_ayah'=>$nama_ayah,
                                            'nama_ibu'=>$nama_ibu,
                                            'pekerjaan_id_ayah'=>$pekerjaan_ayah,
                                            'pekerjaan_id_ibu'=>$pekerjaan_ibu,
                                            'alamat_ayah'=>$alamat_ayah,
                                            'alamat_ibu'=>$alamat_ibu,
                                            'no_hp_ortu'=>$no_hp_ortu,
                                            'penghasilan_ayah'=>$penghsln_ayah,
                'penghasilan_ibu'=>$penghsln_ibu);
            $data               =array_merge($orangtua,$kampus,$jad,$sekolah,$pribadi,$instansi,$institusi);
            $this->mcrud->update($this->tables,$data, $this->pk,$id);
            $id             = getField('student_mahasiswa', 'mahasiswa_id', 'nim', $nim);
            $account        = array('username'=>$nim,'password'=>  md5($nim),'keterangan'=>$id,'level'=>4);
            $this->db->insert('app_users',$account);
            redirect($this->uri->segment(1));
        }
        else
        {
            $data['title']=  $this->title;
            $data['desc']="";
            $id          =  $this->uri->segment(3);
            $data['r']   =  $this->mcrud->getByID($this->tables,  $this->pk,$id)->row_array();
            $this->template->load('template', $this->folder.'/edit',$data);
        }
    }
    function delete()
    {
        $id     =  $_GET['id'];
        $this->mcrud->delete($this->tables,  $this->pk,  $id);
 
    }


    function simpanhari()
    {
        $id         =   $_GET['id'];
        $nilaihari  =   $_GET['nilaihari'];
        $nilaijam   =   $_GET['nilai_jam'];
        $nilairuang =   $_GET['nilai_ruang'];
        $get_jam    =   $this->db->query("SELECT mm.jam,jk.ruangan_id,jk.hari_id,jk.jadwal_id
                        FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm 
                        WHERE mm.makul_id=jk.makul_id and jk.jadwal_id=$id")->row_array();
        $chek=  $this->chek_ruangan($nilairuang, $nilaihari, $nilaijam);
        if($chek==1)
        {
             $this->mcrud->update($this->tables,array('hari_id'=>$nilaihari), $this->pk,$id);
             echo "<div class='alert alert-success'>Jadwal Berhasil Diperbaharui <i class='gi gi-ok'></i> </div>"; 
        }
        else
        {
            echo "<div class='alert alert-danger'>Jadwal Gagal Diperbaharui <i class='gi gi-remove'></i> </div>";
        }
        
           
           
    }
    
    function simpanruang()
    {
        $id         =   $_GET['id'];
        $nilaijam   =   $_GET['nilai_jam'];
        $nilaihari  =   $_GET['nilaihari'];
        $nilairuang =   $_GET['nilai_ruang'];
        $get_jam    =   $this->db->query("SELECT mm.jam,jk.ruangan_id,jk.hari_id,jk.jadwal_id
                        FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm 
                        WHERE mm.makul_id=jk.makul_id and jk.jadwal_id=$id")->row_array();
        $chek=  $this->chek_ruangan($nilairuang, $nilaihari, $nilaijam);
        if($chek==1)
        {
            
            $this->mcrud->update($this->tables,array('ruangan_id'=>$nilairuang), $this->pk,$id);
             echo "<div class='alert alert-success'>Jadwal Berhasil Diperbaharui <i class='gi gi-ok'></i> </div>"; 
        }
        else
        {
            echo "<div class='alert alert-danger'>Jadwal Gagal Diperbaharui <i class='gi gi-remove'></i> </div>";
        }
 
    }
    
    function simpandosen()
    {
        $id         =   $_GET['id'];
        $nilaidosen =   $_GET['nilai_dosen'];
        $this->mcrud->update($this->tables,array('dosen_id'=>$nilaidosen), $this->pk,$id);
        echo "<div class='alert alert-success'>Jadwal Berhasil Diperbaharui <i class='gi gi-ok'></i> </div>"; 
        
    }
    
    
    function chek_ruangan($ruangan_id,$hari_id,$jam)
    {
        $query="SELECT jadwal_id,timediff(jam_selesai,'$jam') as selisih 
                FROM akademik_jadwal_kuliah
                WHERE hari_id='$hari_id' and ruangan_id='$ruangan_id'";
        
        $chek=$this->db->query($query)->num_rows();
        if($chek==0)
        {
            return 1;
        }
        else 
        {
            $r      =   $this->db->query($query)->row_array();
            $jam    =   substr($r['selisih'],0,2);
            $menit  =   substr($r['selisih'],3,2);
            if($menit>0 or $jam>0)
            {
                // tidak
                return 0;
            }
            else
            {
                return 1;
            }
        }
    }
    function simpanjam()
    {
        $id         =   $_GET['id'];
        $nilaijam   =   $_GET['nilai_jam'];
        $nilaihari  =   $_GET['nilaihari'];
        $nilairuang =   $_GET['nilai_ruang'];
        $get_jam    =   $this->db->query("SELECT mm.jam,jk.ruangan_id,jk.hari_id,jk.jadwal_id
                        FROM akademik_jadwal_kuliah as jk,makul_matakuliah as mm 
                        WHERE mm.makul_id=jk.makul_id and jk.jadwal_id=$id")->row_array();
        $chek=  $this->chek_ruangan($nilairuang, $nilaihari, $nilaijam);

        if($chek==1)
        {
            // save
            $jam_selesai=  $this->get_jam_selesai_kuliah($nilaijam.':00', ($get_jam['jam']*50));
            $this->mcrud->update($this->tables,array('jam_mulai'=>$nilaijam,'jam_selesai'=>$jam_selesai), $this->pk,$id);
            echo "<div class='alert alert-success'>Jadwal Berhasil Diperbaharui <i class='gi gi-ok'></i> </div>";
        }
        else
        {
             echo "<div class='alert alert-danger'>Jadwal Gagal Diperbaharui <i class='gi gi-remove'></i> </div>";
        } 
    }
    
    function autosetup()
    {
        $tahun_akademik_id  =   $this->input->post('tahun_akademik');
        $tahun_akd          =   getField('akademik_tahun_akademik', 'keterangan', 'tahun_akademik_id', $tahun_akademik_id);
        $tahun_akd=  substr($tahun_akd, 4,1);
        $prodi              =   $this->input->post('prodi');
        $konsentrasi        =   $this->input->post('konsentrasi');
        // get semester
        $semester           =   getField('akademik_konsentrasi', 'jml_semester', 'konsentrasi_id', $konsentrasi);
        // looping semester
        
        if($tahun_akd==1)
        {
            $sms=array(1,3,5,7);
        }
        else
        {
            $sms=array(2,4,6,8);
        }
        //for($i=1;$i<=$semester;$i++)
        for($i=0;($i<=count($sms)-1);$i++)
        {
            $smstr=$sms[$i];
            // ambil makul_id dari makul_matakuliah
            $makul      =   $this->db->get_where('makul_matakuliah',array('semester'=>$smstr,'konsentrasi_id'=>$konsentrasi,'aktif'=>'y'))->result();
            foreach ($makul as $makul)
            {
                $makul_id   =   $makul->makul_id;
                // chek udah ada belum
                $param      =   array('tahun_akademik_id'=>  $tahun_akademik_id,
                                       'konsentrasi_id'=>$konsentrasi,
                                       'makul_id'=>$makul_id);
                $chek       =  $this->db->get_where('akademik_jadwal_kuliah',$param)->num_rows();
                if($chek<1)
                {
                    $data       =   array(  'tahun_akademik_id'=>  get_tahun_akademik(),
                                            'konsentrasi_id'=>$konsentrasi,
                                            'makul_id'=>$makul_id,
                                            'hari_id'=>0,
                                            'semester'=>$i,
                                            'waktu_id'=>0,
                                            'ruangan_id'=>0,
                                            'semester'=>$smstr,
                                            'dosen_id'=>0);
                    $this->db->insert('akademik_jadwal_kuliah',$data);
                }
            }  
        }
        redirect('jadwalkuliah');
    }
    
    
    
    function jadwalngajar()
    {
        $dosen  =  $this->session->userdata('keterangan');
        $thn    = get_tahun_ajaran_aktif('tahun_akademik_id');
        
        $query="SELECT ak.jenjang,ak.nama_konsentrasi,ar.nama_ruangan,mm.sks,mm.nama_makul,mm.kode_makul,ah.hari,aj.jam_mulai,aj.jam_selesai
                FROM akademik_jadwal_kuliah as aj,app_ruangan as ar,akademik_konsentrasi as ak,makul_matakuliah as mm,app_hari as ah
                WHERE ar.ruangan_id=aj.ruangan_id and ak.konsentrasi_id=aj.konsentrasi_id and mm.makul_id=aj.makul_id and ah.hari_id=aj.hari_id and aj.dosen_id=1 and aj.tahun_akademik_id";
        $data['jadwal']=  $this->db->query($query)->result();
        $data['title']="Jadwal Mengajar";
        $data['dosen']=$dosen;
        $this->template->load('template', $this->folder.'/jadwalngajar',$data);
    }
    
    
            function get_jam($menit)
        {
            for($i=0;$i<=7;$i++)
            {
                if(($i*60)>$menit)
                {
                    return $i-1;
                    exit();
                }
            }
        }
        
        
        function get_menit($menit)
        {
            $jam=  $this->get_jam($menit);
            return $menit-$jam*60;
        }
        
        function get_nol($nilai)
        {
            if($nilai>9)
            {
                return $nilai;
            }
            else
            {
                return "0$nilai";
            }
        }
        
        function get_jam_selesai_kuliah($jam_mulai,$waktu_kuliah)
        {
            $jam=  $this->get_jam($waktu_kuliah);
            $menit=  $this->get_menit($waktu_kuliah);
            $dateString = "Tue, 13 Mar 2012 $jam_mulai";
            $date = new DateTime( $dateString );
            $nextHour   = (intval($date->format('H'))+$jam) % 24;
            $nextMinute = (intval($date->format('i'))+$menit) % 60;
            return $this->get_nol($nextHour).':'.$this->get_nol($nextMinute); 
        }
        

        
        function cetak()
        {
            //$konsen             =  $this->uri->segment(3);
            //$semester           =  $this->uri->segment(4);
            //$tahun              =  $this->uri->segment(5);
            //$konsen             =  $this->uri->segment(3);
            $konsen             =  $this->input->post('konsentrasi');
            $semester           =  $this->input->post('semester');
            $tahun              =  $this->input->post('tahun_akademik');
            $data['konsen']     =  $konsen;
            $data['semester']   =  $semester;
            $data['tahun']      =  $tahun;
            $data['hari']       =  array('','senin','selasa','rabu','kamis','jumat','sabtu','minggu');
            $data['prodi']      =  strtoupper(getField('akademik_prodi', 'nama_prodi', 'prodi_id', getField('akademik_konsentrasi', 'prodi_id', 'konsentrasi_id', $konsen)));
            $data['konsentrasi']=  strtoupper(getField('akademik_konsentrasi', 'nama_konsentrasi', 'konsentrasi_id', $konsen));
            $this->load->view($this->folder.'/cetak',$data);
            
        }
}