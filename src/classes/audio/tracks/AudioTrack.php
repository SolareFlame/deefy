<?php

namespace iutnc\deefy\audio\tracks;

use \iutnc\deefy\exception as exception;

abstract class AudioTrack
{

    //track: uuid, titre, genre, duree, file_name (AUTO uuid.mp3), artistes (JSON), annee_sortie.
    private string $uuid;
    private string $title;
    private string $gender;
    private string $duration; //seconds
    private string $file_name;
    private array $artists;
    private string $date; //iso

    function __construct(string $id, string $t, string $g, string $d, string $fn, array $a, string $da) {
        if (empty($id)) {
            throw new exception\InvalidPropertyNameException("File name and path cannot be empty.");
        }

        if ($d <= 0) {
            throw new exception\InvalidPropertyValueException("Duration must be greater than 0.");
        }

        $this->uuid = $id;              //ex : a78d79fa-03a6-4cac-b191-880f15f44dd8
        $this->title = $t;              //ex : Tachiiri Kinshi
        $this->gender = $g;             //ex : J-Pop
        $this->duration = $d;           //ex : 180
        $this->file_name = $fn;         //ex : a78d79fa-03a6-4cac-b191-880f15f44dd8.mp3
        $this->artists = $a;            //ex : ["Ado", "Ayase"]
        $this->date = $da;              //ex : 2024-01-01
    }

    function __toString()
    {
        return json_encode($this);
    }

    function __get($name)
    {
        return $this->$name;
    }

    function setID(string $id) {
        $this->uuid = $id;
    }
}
