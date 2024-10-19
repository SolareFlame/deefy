<?php

namespace iutnc\deefy\renderer;

class AlbumTrackRenderer extends AudioTrackRenderer
{
    function render_short(): string
    {
        $title = $this->audio_track->__get("file_name");
        $file_path = $this->audio_track->__get("file_path");

        $result = '<div class="compact-view">';
        $result .= '<p>Title: ' . $title . '</p>';
        $result .= '<audio controls src="' . $file_path . '"></audio>';
        $result .= '</div>';

        return $result;
    }

    function render_long(): string
    {
        $title = $this->audio_track->__get("file_name");
        $file_path =  $this->audio_track->__get("file_path");
        $duration = $this->audio_track->__get("duration");

        $artist = "test";
        $year = "test";
        $track_id = "test";
        $gender = "test";

        $result = '<div class="compact-view">';
        $result .= '<p>Title: ' . $title . '</p>';
        $result .= '<p>Artist: ' . $artist . '</p>';
        $result .= '<p>Year: ' . $year . '</p>';
        $result .= '<p>Track ID: ' . $track_id . '</p>';
        $result .= '<p>Gender: ' . $gender . '</p>';
        $result .= '<p>Duration: ' . $duration . '</p>';
        $result .= '<audio controls src="' . $file_path . '"></audio>';
        $result .= '</div>';

        return $result;
    }
}
    
    


