<?php
require_once(__DIR__.'/../vendor/autoload.php');

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

// Full backup hour
if (!isset($_SERVER['argv'][1])) {
    $hourFullBackup = 0;
} else {
    $hourFullBackup = $_SERVER['argv'][1];
}

// List of blogs to backup
$blogs = [
    'allegeances.tumblr.com',
    'camilocollao.tumblr.com',
    'chateau-merdique.tumblr.com',
    'chatentacule.tumblr.com',
    'cobrafoutre.tumblr.com',
    'cobrafoutrediscotheque.tumblr.com',
    'cobrafoutremusic.tumblr.com',
    'cobrafoutrevideo.tumblr.com',
    'codexarcadia.tumblr.com',
    'cryptevengeance.tumblr.com',
    'damnationsauvage2.tumblr.com',
    'doppelgangertv.tumblr.com',
    'elleestbonnelhandicapee.partouze-cagoule.fr',
    'fondation2040.tumblr.com',
    'frozentemple.tumblr.com',
    'girlsjustwanttohaveananas.tumblr.com',
    'hyperwarlazerquest.tumblr.com',
    'lazerquestgalerie.tumblr.com',
    'natewab.tumblr.com',
    'nnjzz.tumblr.com',
    'nuclearsword.tumblr.com',
    'paranimalmusic.tumblr.com',
    'pinkposeidon.tumblr.com',
    'quartzquarantedeux.tumblr.com',
    'satanikmike.tumblr.com',
    'st3r3otyp3.tumblr.com',
    'templealphacanismajoris.tumblr.com',
    'templevengeance.incongru.org',
    'templevengeance.tumblr.com',
    'theflowerthiefmeetstheatomman.tumblr.com',
    'tintinaucongoapoil.tumblr.com',
    'www.blueforbirds.net',
];

$tplCommand = 'python %s/../vendor/git/tumblr-utils/tumblr_backup.py -a %d --outdir=%s/public/blogs/%s --json --save-video --save-audio %s';
foreach ($blogs as $blog) {
    try {
        $command = sprintf($tplCommand, __DIR__, $hourFullBackup, __DIR__, $blog, $blog);
        var_dump($command);
        $process = new Process($command);
        $process->setTimeout(43200);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
}
