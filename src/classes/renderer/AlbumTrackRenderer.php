<?php

namespace iutnc\deefy\renderer;

class AlbumTrackRenderer extends AudioTrackRenderer
{
    function render_short(): string
    {
        $title = $this->audio_track->title;
        $file_path = $this->audio_track->file_name;

        $result = '<div class="compact-view">';
        $result .= '<p>Title: ' . $title . '</p>';
        $result .= '<audio controls src="' . $file_path . '"></audio>';
        $result .= '</div>';

        return $result;
    }

    function render_long(): string
    {
        $title = $this->audio_track->title;
        $gender = $this->audio_track->gender;
        $duration = $this->audio_track->duration;
        $artists = $this->audio_track->artists;
        $year = $this->audio_track->date;

        $artists_list = implode(', ', $artists);

        $file_path = $this->audio_track->file_name;

        $result = <<<HTML
    <div class="large-view">
        <p>Titre Musique: $title</p>
        <p>Genre: $gender</p>
        <p>Durée: $duration seconds</p>
        <p>Artistes: $artists_list</p>
        <p>Année: $year</p>
        <audio controls>
            <source src="$file_path" type="audio/mpeg">
            Your browser does not support the audio tag.
        </audio>
    </div>
HTML;

        return $result;
    }

}
    
    


