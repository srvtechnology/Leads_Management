<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $fillable=['tc', 'tl', 'bm', 'from_date', 'to_date','subject', 'reason', 'status', 'remark'];

}
