<?php

namespace iutnc\deefy\audio\lists;

class Album extends AudioList {

    protected string $artist;
    protected string $release_date;

    public function __construct(string $fn, string $fp, int $d, string $a, string $rd) {
        parent::__construct($fn, $fp, $d);
        $this->artist = $a;
        $this->release_date = $rd;
    }

    public function setArtist($a) {
        $this->artist = $a;
    }

    public function setReleaseDate($rd) {
        $this->release_date = $rd;
    }


}