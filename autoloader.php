<?php

class autoloader {

    /**
     * @var string
     */
    private $prefix; // ex: "iutnc\\deefy\\"

    /**
     * @var string
     */
    private $baseDir; // ex: "src/classes/'

    function __construct(string $prefix, string $baseDir) {
        $this->prefix = $prefix;
        $this->baseDir = $baseDir;
    }

    /**
     * Load class file
     *
     * @param string $class Fully qualified class name
     * @throws Exception if file not found
     */
    function loadClass(string $class) {
        //print $class . "<br>";

        // iutnc\deefy\audio\tracks\AlbumTrack -> src/classes/audio/tracks/AlbumTrack.php
        $file = $this->baseDir . str_replace($this->prefix, '', $class) . '.php';
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

        /*
        print $file . "<br>";
        print !is_file($file) . "<br>";

        print realpath($file) . "<br>";
        */

        if (!is_file($file)) {
            throw new Exception("File not found: $file");
        } else {
            require_once $file;
        }
    }

    /**
     * Register autoloader
     */
    function register() {
        spl_autoload_register([$this, 'loadClass']);
    }
}
