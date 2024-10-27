<?php

require_once 'vendor/autoload.php';

\iutnc\deefy\repository\DeefyRepository::setConfig('db.config.ini');

$repo = \iutnc\deefy\repository\DeefyRepository::getInstance();

$playlists = $repo->findUserPlaylists("67212bbd-6414-4f9b-a5bd-31733d6ebeee");
foreach ($playlists as $pl) {
    print "<p>$pl</p>";
}

$user = $repo->getUserInfoByEmail("zeldac332@gmail.com");
print_r($user);

$user = $repo->getUserInfoByUUID("67212bbd-6414-4f9b-a5bd-31733d6ebeee");
print_r($user);




/*
\iutnc\deefy\auth\AuthnProvider::signin(
    "zeldac332@gmail.com",
    "password"
);
*/

//$pl = new \iutnc\deefy\audio\lists\PlayList('test');
//$pl = $repo->saveEmptyPlaylist($pl);
//print "playlist  : " . $pl->nom . ":". $pl->id . "\n";

//$track = new \iutnc\deefy\audio\tracks\PodcastTrack('test', 'test.mp3', 'auteur', '2021-01-01', 10, 'genre');
//$track = $repo->savePodcastTrack($track);
//print "track 2 : " . $track->titre . ":". get_class($track). "\n";
//$repo->addTrackToPlaylist($pl->id, $track->id);

