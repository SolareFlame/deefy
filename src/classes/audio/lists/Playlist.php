<?php

namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks as tracks;

class Playlist extends AudioList
{
    public function addTrack(tracks\AudioTrack $track): void
    {
        if(!in_array($track, $this->tracks)) {
            $this->tracks[] = $track;
            $this->nb_tracks++;
            $this->total_duration += $track->__get('duration');
        }
    }

    public function removeTrack(int $i) : void
    {
        if (isset($this->tracks[$i])) {
            $this->total_duration -= $this->tracks[$i]->__get('duration');
            unset($this->tracks[$i]);

            $this->tracks = array_values($this->tracks);
            $this->nb_tracks--;
        }
    }

    public function addTracks(array $tracks): void
    {
        foreach ($tracks as $track) {
            if (!in_array($track, $this->tracks)) {
                $this->addTrack($track);
            }
        }
    }

    public function getTrack(int $i): ?tracks\AudioTrack
    {
        return $this->tracks[$i] ?? null;
    }
}