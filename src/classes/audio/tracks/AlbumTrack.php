<?php

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack
{
    public function __construct(string $fn, string $fp, int $d, string $c, string $da)
    {
        parent::__construct($fn, $fp, $d, $c, $da);
    }
}



