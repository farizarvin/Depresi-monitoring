<?php
$gurus = App\Models\Guru::with('user')->get();
foreach($gurus as $g) {
    if(!$g->user) {
        echo "NIP: {$g->nip} | NO USER\n";
        continue;
    }
    $u = $g->user;
    $status = 'Unknown';
    $dob = \Carbon\Carbon::parse($g->tgl_lahir)->format('dmY');
    
    if(Hash::check('12345678', $u->password)) $status = '12345678';
    elseif(Hash::check('password', $u->password)) $status = 'password';
    elseif(Hash::check('Nubi-'.$dob, $u->password)) $status = 'Nubi-'.$dob;
    elseif(Hash::check('Guru_'.$dob, $u->password)) $status = 'Guru_'.$dob;
    
    echo "User: {$u->username} | NIP: {$g->nip} | Name: {$g->nama_lengkap} | Pass: {$status}\n";
}
