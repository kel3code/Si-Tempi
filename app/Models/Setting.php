<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model {
    protected $table = 'pengaturan'; protected $primaryKey = 'kunci'; public $incrementing = false; protected $keyType = 'string'; public $timestamps = false; protected $guarded = [];
    static function get($k, $d = null) { return static::find($k)?->nilai ?? $d; }
    static function put($k, $v) { static::updateOrCreate(['kunci' => $k], ['nilai' => (string) $v]); }
}
