<?php

namespace iutnc\deefy\renderer;

class PodcastRenderer extends AudioTrackRenderer
{
    public function render_short(): string
    {
        $title = $this->audio_track->file_name;
        $file_path = $this->audio_track->file_path;

        $result = '<div class="compact-view">';
        $result .= '<p>Title: ' . $title . '</p>';
        $result .= '<audio controls src="' . $file_path . '"></audio>';
        $result .= '</div>';

        return $result;
    }

    public function render_long(): string
    {
        $title = $this->audio_track->file_name;
        $file_path = $this->audio_track->file_path;
        $duration = $this->audio_track->duration;
        $artist = $this->audio_track->creator;
        $date = $this->audio_track->date;

        $result = '<div class="large-view">';
        $result .= '<p>Title: ' . $title . '</p>';
        $result .= '<p>Artist: ' . $artist . '</p>';
        $result .= '<p>Year: ' . $date . '</p>';
        $result .= '<p>Duration: ' . $duration . '</p>';
        $result .= '<audio controls src="' . $file_path . '"></audio>';
        $result .= '</div>';

        return $result;
    }
}
