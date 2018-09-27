<?php
class Krs_baru extends CI_Controller
{
    
    
    function __construct() {
        parent::__construct();

    }

    function beranda()
    {
        $data['title']=  'Dasboard';
        $this->template->load('template', 'dashboard',$data);
    }
    
    function index()
    {
        $data = array(
            'title' => 'KRS Mahasiswa',
            'loadpertamajs' => 'loaddata('.$this->session->userdata("keterangan").')', 
        );
        $this->template->load('template', 'krs_baru/krs',$data);
    }

    function bukti_pembayaran()
    {
        $data = array(
            'title' => 'Upload Pembayaran',
        );
        $this->template->load('template', 'krs_baru/form_pembayaran',$data);
    }

    function lihat_pembayaran()
    {
        $data = array(
            'title' => 'List Pembayaran',
        );
        $this->template->load('template', 'krs_baru/view_pembayaran',$data);
    }

    function simpan_bukti_pembayaran()
    {
        $nim = $this->input->post('nim');
        $tgl_bayar = $this->input->post('tgl_bayar');
        $semester = $this->input->post('semester');

        $nmfile = "buktipembayaran_".time();
        $config['upload_path'] = './image/bukti_pembayaran';
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = '20000';
        $config['file_name'] = $nmfile;
        // load library upload
        $this->load->library('upload', $config);
        // upload gambar 1
        $this->upload->do_upload('bukti_pembayaran');
        $result1 = $this->upload->data();
        $result = array('gambar'=>$result1);
        $dfile = $result['gambar']['file_name'];

        $data = array(
            'nim' => $nim,
            'tgl_bayar' => $tgl_bayar,
            'semester' => $semester,
            'foto_pembayaran' => $dfile,
        );

        $this->db->insert('cek_pembayaran', $data);
        ?>
        <script type="text/javascript">
            alert('Terima Kasih telah melakukan Pembayaran');
            window.location = '<?php echo base_url() ?>krs_baru';
        </script>
        <?php
    }

    function dosen_pa()
    {
        if ($_POST == NULL) {
            $data = array(
                'title' => 'Dosen Pembimbing Akademik',
            );
            $this->template->load('template', 'krs_baru/dosen_pa',$data);
        } else {
            $id_dosen = $this->input->post('id_dosen');
            $id_mahasiswa = $this->input->post('id_mahasiswa');

            $data = array(
                'id_dosen' => $id_dosen,
                'id_mahasiswa' => $id_mahasiswa,
            );
            $this->db->insert('dosen_pa', $data);
            ?>
            <script type="text/javascript">
                alert('Berhasil Simpan data');
                window.location = '<?php echo base_url() ?>krs_baru/dosen_pa';
            </script>
            <?php
        }
        
    }
    
    
}