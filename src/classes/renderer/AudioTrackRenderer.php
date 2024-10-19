<?php

namespace iutnc\deefy\renderer;
use iutnc\deefy\audio\tracks as tracks;

abstract class AudioTrackRenderer implements Renderer
{
    protected tracks\AudioTrack $audio_track;

    function __construct(tracks\AudioTrack $tr)
    {
        $this->audio_track = $tr;
    }

    function render(int $selector) : string
    {
        switch ($selector) {
            case 1:
                return $this->render_short();
            case 2:
                return $this->render_long();
            default :
                return "Invalid selector";
        }
    }

    abstract function render_short(): string;

    abstract function render_long(): string;
}




