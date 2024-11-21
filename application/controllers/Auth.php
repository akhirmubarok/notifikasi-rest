<?php

use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


class Auth extends RestController
{
    private $key;
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_user', 'user');
        $this->key = '1234567890';
    }

    public function index_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');
        $encrypt_pass = hash('sha512', $password . $this->key);

        $datauser = $this->user->doLogin($username, $encrypt_pass);
        if ($datauser) {
            // Inisialisasi DateTime
            $date = new DateTime();

            $payload = [
                'id' => $datauser[0]->id,
                'name' => $datauser[0]->name,
                'username' => $datauser[0]->username,
                'iat' => $date->getTimestamp(),
                'exp' => $date->getTimestamp() + (60 * 10) // Token akan kedaluwarsa dalam 10 menit
            ];

            $token = JWT::encode($payload, $this->key, 'HS256');
            $this->response([
                'status' => true,
                'message' => 'Login berhasil',
                'result' => [
                    'id' => $datauser[0]->id,
                    'name' => $datauser[0]->name,
                    'username' => $datauser[0]->username
                ],
                'token' => $token
            ], self::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Username atau password salah'
            ], self::HTTP_FORBIDDEN);
        }
    }

    protected function checkToken()
    {
        $jwt = $this->input->get_request_header('Authorization');
        try {
            jwt::decode($jwt, new Key($this->key, 'HS256'));
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Unauthorized'
            ], self::HTTP_UNAUTHORIZED);
        }
    }
}