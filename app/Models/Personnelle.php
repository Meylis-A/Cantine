<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnelle extends Model
{
    use HasFactory;
    protected $table = 'personnelle';
    
    protected $primaryKey = 'matricule';

    protected $keyType = 'string';

    public $timestamps = false;

}
