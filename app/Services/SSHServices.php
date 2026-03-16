<?php

namespace App\Services;

use phpseclib3\Net\SSH2;

class SSHServices
{

    public function testConnection($host, $port, $username, $password)
    {
        try {
            // $ssh = new \phpseclib3\Net\SSH2($host, $port);
            $ssh = new SSH2($host,$port);

            if (!$ssh->login($username, $password)) {
                return [
                    'status' => 'BAD',
                    'message' => 'Login SSH gagal'
                ];
            }

            // kembalikan objek SSH
            return [
                'status' => 'OK',
                'message' => 'Koneksi SSH berhasil',
                'ssh' => $ssh
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'BAD',
                'message' => $e->getMessage()
            ];
        }
    }

}