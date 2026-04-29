<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tiket';
    protected $primaryKey = 'id_tiket';

    protected $fillable = [
        'nama_pelapor',
        'no_whatsapp',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kategori',
        'deskripsi',
        'status',
        'tgl_lapor',
        'id_petugas',
        'is_read',
        'catatan_teknisi',
        'foto_keluhan',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'tgl_lapor' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function getFormattedWhatsappAttribute()
    {
        $number = preg_replace('/[^0-9]/', '', $this->no_whatsapp);
        
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }

    public function getTicketNoAttribute()
    {
        return '#TKT-' . str_pad($this->id_tiket, 5, '0', STR_PAD_LEFT);
    }
}
