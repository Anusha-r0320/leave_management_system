<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLogs extends Model
{
    protected $table = 'audit_logs';
    protected $guarded = [];
}
