<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sequence extends Model
{
    protected $table = 'sequences';
    protected $fillable = ['key', 'value'];
    public $timestamps = true;

    /**
     * Incrementa de forma atómica y devuelve el nuevo valor.
     * $key: e.g. 'actas', 'boleta_A001', 'expedientes'
     */
    public static function next(string $key, int $step = 1): int
    {
        $key = strtolower($key);

        return DB::transaction(function () use ($key, $step) {
            $row = DB::table('sequences')
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                DB::table('sequences')->insert([
                    'key' => $key,
                    'value' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $row = (object)['value' => 0];
            }

            $new = (int)$row->value + $step;

            DB::table('sequences')
                ->where('key', $key)
                ->update(['value' => $new, 'updated_at' => now()]);

            return $new;
        });
    }

    /** Devuelve el valor actual (sin incrementar). */
    public static function current(string $key): int
    {
        $key = strtolower($key);
        $row = DB::table('sequences')->where('key', $key)->first();
        return (int)($row->value ?? 0);
    }
}
