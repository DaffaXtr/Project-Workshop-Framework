<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'timestamp',
        'total',
        'metode_bayar',
        'status_bayar',
        'transaction_id',
        'snap_token',
        'status_message'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }

    // Generate guest name otomatis
    public static function generateGuestName()
    {
        $last = self::count() + 1;
        return 'Guest_' . str_pad($last, 7, '0', STR_PAD_LEFT);
    }
}
