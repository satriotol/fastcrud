<?php

namespace Satriotol\Fastcrud\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKeyLog extends Model
{
    protected $table = 'api_key_logs';
    protected $fillable = ['api_key_id', 'method', 'endpoint', 'ip_address', 'user_agent', 'client_type', 'status_code'];
}
