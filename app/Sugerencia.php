<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sugerencia extends Model{
    protected $casts = ['updated_at' => 'timestamp'];
    protected $fillable = ['nombre','asunto','email','mensaje'];
}
