<?php

namespace App\Services;

use App\Models\SSH_Access;

class WordpressMigrationService
{
    protected $ssh;

    public function store(Request $request, SSHService $sshService){

    $testA = $sshService->testConnection(
        $request->host_a,
        $request->port_a,
        $request->username_a,
        $request->password_a
    );

    $testB = $sshService->testConnection(
        $request->host_b,
        $request->port_b,
        $request->username_b,
        $request->password_b
    );

    $migration = WordpressMigration::create([
        'host_a' => $request->host_a,
        'port_a' => $request->port_a,
        'username_a' => $request->username_a,
        'password_a' => $request->password_a,
        'host_b' => $request->host_b,
        'port_b' => $request->port_b,
        'username_b' => $request->username_b,
        'password_b' => $request->password_b,
        'status' => 'OK'
    ]);

    return response()->json([
        'hosting_a' => $testA,
        'hosting_b' => $testB,
        'migration_id' => $migration->id
    ]);
}
}