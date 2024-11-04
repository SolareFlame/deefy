<?php

namespace iutnc\deefy\audio\lists;

class Album extends AudioList {

    protected array $artist;
    protected string $release_date;

    public function __construct(string $name, array $tracks, array $artist, string $release_date) {
        parent::__construct($name, $tracks);
        $this->artist = $artist;
        $this->release_date = $release_date;
    }
}