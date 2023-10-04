<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StressCheckTest extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'check_test_id'];
}
