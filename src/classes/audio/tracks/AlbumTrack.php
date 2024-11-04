<?php

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack
{
    public function __construct(string $id, string $t, string $g, string $d, string $fn, array $a, string $da) {
        parent::__construct($id, $t, $g, $d, $fn, $a, $da);
    }
}



