<?php

namespace iutnc\deefy\renderer;
use \iutnc\deefy\audio\lists as lists;
use \iutnc\deefy\audio\tracks as tracks;

class AudioListRenderer implements Renderer
{
    protected lists\AudioList $audio_list;

    public function __construct(lists\AudioList $al)
    {
        $this->audio_list = $al;
    }

    public function render(int $selector): string
    {
        $title = $this->audio_list->__get("name");


        $result = '<div class="compact-view">';
        $result .= '<p>Title: ' . $title . '</p>';

        foreach ($this->audio_list->__get("tracks") as $track) {

            if($track instanceof tracks\PodcastTrack) {
                $renderer = new PodcastRenderer($track);
                $result .= $renderer->render($selector);
            }

            if($track instanceof tracks\AlbumTrack) {
                $renderer = new AlbumTrackRenderer($track);
                $result .= $renderer->render($selector);
            }
        }

        return $result;
    }
}