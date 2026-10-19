<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // kolom yang boleh diisi
    protected $fillable = array(
        'nim', 'name', 'email', 'phone',
        'address', 'gender', 'birth_date', 'major'
    );

    // update created_at dan updated_at secara otomatis
    public $timestamps = true;
}