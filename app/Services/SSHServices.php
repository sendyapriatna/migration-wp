<?php

namespace App\Services;

use phpseclib3\Net\SSH2;

class SSHServices
{

    public function testConnection($host,$port,$username,$password)
    {
        try {

            $ssh = new SSH2($host,$port);

            if (!$ssh->login($username,$password)) {
                return [
                    'status' => 'BAD',
                    'message' => 'Login SSH gagal'
                ];
            }

            return [
                'status' => 'OK',
                'message' => 'Koneksi SSH berhasil'
            ];

        } catch (\Exception $e) {

            return [
                'status' => 'BAD',
                'message' => $e->getMessage()
            ];
        }
    }

}