<?php

require_once APPPATH . 'controllers/Auth.php';

class Notifikasi extends Auth
{
    function __construct()
    {
        parent::__construct();
        $this->checkToken();
        $this->load->model('m_notifikasi', 'notifikasi');
    }

    public function index_get()
    {
        $id = $this->get('id_notifikasi');

        $data_notifikasi = $this->notifikasi->getData($id);
        if ($data_notifikasi) {
            $this->response([
                'status' => true,
                'massage' => 'Notifikasi ditemukan',
                'data ' => $data_notifikasi
            ], self::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], self::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        if ($this->_validationCheck() == FALSE) {
            $this->response([
                'status' => false,
                'message' => strip_tags(validation_errors())
            ], self::HTTP_BAD_REQUEST);
        } else {
            $data = [
                'id_notifikasi' => $this->post('id_notifikasi'),
                'pesan' => $this->post('pesan'),
                'sistem_id' => $this->post('sistem_id'),
                'no_pegawai' => $this->post('no_pegawai'),
                'is_terkirim' => $this->post('is_terkirim'),
                'created_at' => $this->post('created_at'),
                'kode' => $this->post('kode')
            ];

            $saved = $this->notifikasi->insert_data($data);
            if ($saved > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Notifikasi baru telah ditambahkan'
                ], self::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan notifikasi baru'
                ], self::HTTP_BAD_REQUEST);
            }
        }
    }
    public function index_put()
    {
        $this->form_validation->set_data($this->put());
        if ($this->_validationCheck() == FALSE) {
            $this->response([
                'status' => false,
                'message' => strip_tags(validation_errors())
            ], self::HTTP_BAD_REQUEST);
        } else {
            $id = $this->put('id_notifikasi');
            $data = [
                'pesan' => $this->put('pesan'),
                'sistem_id' => $this->put('sistem_id'),
                'no_pegawai' => $this->put('no_pegawai'),
                'is_terkirim' => $this->put('is_terkirim'),
                'created_at' => $this->put('created_at'),
                'kode' => $this->put('kode')
            ];

            $updated = $this->notifikasi->update_data($data, $id);
            if ($updated > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data notifikasi berhasil diperbarui'
                ], self::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data notifikasi gagal diperbarui'
                ], self::HTTP_BAD_REQUEST);
            }
        }
    }

    private function _validationCheck()
    {
        $this->form_validation->set_rules(
            'pesan',
            'Pesan',
            'required',
            array('required' => '{field} harus diisi')
        );
        $this->form_validation->set_rules(
            'no_pegawai',
            'No Pegawai',
            'required|numeric',
            array(
                'required' => '{field} harus diisi',
                'numeric' => '{field} harus berupa angka'
            )
        );
        $this->form_validation->set_rules(
            'kode',
            'Kode BPS',
            'required|numeric',
            array(
                'required' => 'Kode harus diisi',
                'numeric' => 'Kode harus berupa angka'
            )
        );

        return $this->form_validation->run();
    }

    public function index_delete()
    {
        $id = $this->delete('id_notifikasi');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], self::HTTP_NOT_FOUND);
        } else {
            $deleted = $this->notifikasi->delete_data($id);
            if ($deleted > 0) {
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'Notifikasi berhasil dihapus'
                ], self::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Notifikasi gagal dihapus'
                ], self::HTTP_BAD_REQUEST);
            }
        }
    }
}