<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SSH_Access extends Model
{
    protected $table = "sshaccess";
    protected $fillable = [
        'id',
        'source_host',
        'source_username',
        'source_password',
        'source_path',
        'source_port',
        'target_host',
        'target_username',
        'target_password',
        'target_path',
        'target_port',
        'status',
    ];
}
