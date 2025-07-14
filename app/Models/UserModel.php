<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    public $timestamps = true; // timestamps enabled

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        // Cegah delete untuk user dengan id 1
        static::deleting(function ($user) {
            if ($user->id == 1 || $user->email === 'ikn@gmail.com') {
                throw new \Exception("User ini tidak dapat dihapus.");
            }
        });

        // Cegah update manual untuk user tertentu
        static::updating(function ($user) {
            // Jika ada perubahan data yang bukan internal Laravel
            if ($user->isDirty(['email', 'name', 'password'])) {
                if ($user->id == 1 || $user->email === 'ikn@gmail.com') {
                    throw new \Exception("User ini tidak dapat diubah.");
                }
            }
        });
    }
}
