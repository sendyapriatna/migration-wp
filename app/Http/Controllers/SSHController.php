<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SSH_Access;
use App\Services\SSHServices;

class SSHController extends Controller
{
    public function index(){
        return view('landing-page');
    }
    // public function checkSSH(Request $request){
    //     SSH_Access::create($request->all());
    //     return redirect()->back()->with('success', 'Data berhasil disimpan');
    // }

    public function store(Request $request,SSHServices $sshService)
    {

        $request->validate([

            'source_host' => 'required',
            'source_username' => 'required',
            'source_password' => 'required',
            'source_path' => 'required',
            'source_port' => 'required',
            'target_host' => 'required',
            'target_username' => 'required',
            'target_password' => 'required',
            'target_path' => 'required',
            'target_port' => 'required'

        ]);

        // test SSH Hosting A
        $testA = $sshService->testConnection(
            $request->source_host,
            $request->source_port,
            $request->source_username,
            $request->source_password
        );

        // test SSH Hosting B
        $testB = $sshService->testConnection(
            $request->target_host,
            $request->target_port,
            $request->target_username,
            $request->target_password
        );

        // dd($testB);

        // simpan data migrasi
        $migration = SSH_Access::create([
            'source_host' => $request->source_host,
            'source_username' => $request->source_username,
            'source_password' => $request->source_password,
            'source_path' => $request->source_path,
            'source_port' => $request->source_port,
            'target_host' => $request->target_host,
            'target_username' => $request->target_username,
            'target_password' => $request->target_password,
            'target_path' => $request->target_path,
            'target_port' => $request->target_port,
            'status' => 'OK'
        ]);

        return response()->json([

            'migration_id' => $migration->id,

            'hosting_a' => $testA,

            'hosting_b' => $testB

        ]);

    }
}
