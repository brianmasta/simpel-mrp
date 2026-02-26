<?php

namespace App\Policies;

use App\Models\PengajuanSurat;
use App\Models\User;

class PengajuanSuratPolicy
{
    public function view(User $user, PengajuanSurat $pengajuan): bool
    {
        return in_array($user->role, ['admin', 'petugas'])
            || $user->id === $pengajuan->user_id;
    }
}
