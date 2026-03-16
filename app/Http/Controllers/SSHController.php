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

        if ($testA['status'] === 'OK' && $testB['status'] === 'OK') {
            $ssh = $testA['ssh']; // sekarang key 'ssh' ada
            $sshB = $testB['ssh'];

            // cek file wp-config.php
            // note* update public_html/ipcek.sndyaccess.my.id/wp-config.php >> ke dinamis by $request
            $output = $ssh->exec('cat '.$request->source_path. '/wp-config.php');

            //parsing wp config
            preg_match("/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*['\"](.+?)['\"]\s*\)/", $output, $dbName);
            preg_match("/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*['\"](.+?)['\"]\s*\)/", $output, $dbUser);
            preg_match("/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"](.+?)['\"]\s*\)/", $output, $dbPassword);
            preg_match("/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*['\"](.+?)['\"]\s*\)/", $output, $dbHost);
            preg_match("/\\\$table_prefix\s*=\s*['\"](.+?)['\"]\s*;/", $output, $tablePrefix);

            // hasil parsing
            $dbInfo = [
                'DB_NAME' => $dbName[1] ?? null,
                'DB_USER' => $dbUser[1] ?? null,
                'DB_PASSWORD' => $dbPassword[1] ?? null,
                'DB_HOST' => $dbHost[1] ?? 'localhost',
                'table_prefix' => $tablePrefix[1] ?? 'wp_',
            ];

            // Backup folder & file
            $backupFolder = '/home/'.$request->source_username.'/backups';
            $ssh->exec("mkdir -p $backupFolder"); // pastikan folder ada
            $backupFile = $backupFolder . '/backup_' . date('Ymd_His') . '.sql';

            // Jalankan mysqldump
            $command = "mysqldump -h {$dbInfo['DB_HOST']} -u {$dbInfo['DB_USER']} -p'{$dbInfo['DB_PASSWORD']}' {$dbInfo['DB_NAME']} > {$backupFile}";
            $ssh->exec($command);

            // Backup file WordPress
            $backupFiles = $backupFolder . '/wp_files_'. date('Ymd_His') . '.zip';
            $sourcePath = dirname($request->source_path); // folder wordpress

            $zipCommand = "cd {$sourcePath} && zip -r {$backupFiles} .";
            $ssh->exec($zipCommand);


            // buat symlink ke public-html sementara
            $publicLink = "/home/".$request->source_username."/public_html/tmp-backups";
            $backupFolder = "/home/".$request->source_username."/backups";

            // hapus jika sudah ada
            $ssh->exec("rm -rf {$publicLink}");

            // buat symlink
            $ssh->exec("ln -s {$backupFolder} {$publicLink}");

            // get basename hasil backup db dan wp
            $backupDBName = basename($backupFile);
            $backupWPName = basename($backupFiles);

            // create url wget
            $backupDBurl = $request->source_host."/tmp-backups/".$backupDBName;
            $backupWPurl = $request->source_host."/tmp-backups/".$backupWPName;

            // wget dari hosting baru
            $targetBackupFolder = "/home/".$request->target_username."/".$request->target_path;

            $sshB->exec("mkdir -p {$targetBackupFolder}");

            $sshB->exec("wget -c -O {$targetBackupFolder}/{$backupDBName} {$backupDBurl}");
            $sshB->exec("wget -c -O {$targetBackupFolder}/{$backupWPName} {$backupWPurl}");

            //proses hapus menghapus
            //hapus file db dan wp di folder backups
            $ssh->exec("rm -f {$backupFile}");
            $ssh->exec("rm -f {$backupFiles}");

            // hapus symlink
            $ssh->exec("rm -f {$publicLink}");

            return response()->json([
                'status' => 'OK',
            ]);
        } else {
            return response()->json([
                'status' => 'BAD',
                'message' => $testA['message']
            ]);
        }

       

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
            'hosting_a' => [
                'status' => $testA['status'],
                'message' => $testA['message']
            ],
            'hosting_b' => [
                'status' => $testB['status'],
                'message' => $testB['message']
            ]
        ]);

    }
}
