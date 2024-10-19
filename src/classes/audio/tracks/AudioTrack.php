<?php

namespace iutnc\deefy\audio\tracks;

use \iutnc\deefy\exception as exception;

abstract class AudioTrack
{
    private string $creator;
    private string $date;
    private string $duration;
    private string $file_name;
    private string $file_path;

    function __construct(string $fn, string $fp, int $d, string $c, string $da)
    {
        if (empty($fn) || empty($fp)) {
            throw new exception\InvalidPropertyNameException("File name and path cannot be empty.");
        }

        if ($d <= 0) {
            throw new exception\InvalidPropertyValueException("Duration must be greater than 0.");
        }

        $this->file_name = $fn;
        $this->file_path = $fp;
        $this->creator = $c;
        $this->duration = $d;
        $this->date = $da;
    }

    function __toString()
    {
        return json_encode($this);
    }

    function __get($name)
    {
        return $this->$name;
    }
}
