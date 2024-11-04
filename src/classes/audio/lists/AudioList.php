<?php

namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks as tracks;

abstract class AudioList
{
    //playlist: uuid, nom, genres (JSON: 3max)

    protected string $uuid;
    protected string $name;
    protected int $total_duration;
    protected array $tracks;
    protected int $nb_tracks;

    public function __construct(string $name, array $tracks)
    {
        if($tracks == null) {
            $this->name = $name;
            $this->tracks = [];
            $this->nb_tracks = 0;
            $this->total_duration = 0;
            return;
        }


        $this->name = $name;
        $this->tracks = $tracks;
        $this->nb_tracks = count($tracks);

        $temp_dur = 0;
        foreach ($tracks as $track) {
            if ($track instanceof tracks\AudioTrack) {
                $temp_dur += $track->__get('duration');
            }
        }

        $this->total_duration = $temp_dur;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function setID(string $id): void
    {
        $this->uuid = $id;
    }

}