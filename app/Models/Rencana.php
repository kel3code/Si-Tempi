<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Rencana extends Model { protected $table = 'rencana_produksi_harian'; protected $guarded = []; public $timestamps = false; protected $primaryKey = 'tanggal'; public $incrementing = false; protected $keyType = 'string'; }
