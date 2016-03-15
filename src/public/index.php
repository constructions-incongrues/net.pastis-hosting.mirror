<?php
// Get list of backuped sites
$sites = array();
$directories = glob(dirname(__FILE__).'/blogs/*', GLOB_ONLYDIR);
foreach ($directories as $directory) {
    if (strstr(basename($directory), '.') === false) {
        continue;
    }
    $sites[] = array('domain' => basename($directory), 'url' => sprintf('http://%s', basename($directory)));
}

// Client language detection
// TODO : could be better !
$languages = getUserLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']);
if ($lang = filter_input(INPUT_GET, 'lang')) {
    if ($lang == 'en') {
            $locale = 'en';
            setlocale(LC_ALL, 'en_EN');
    } else {
        $locale = 'fr';
        setlocale(LC_ALL, 'fr_FR.utf8');
    }
} else {
    foreach ($languages as $language => $weight) {
        if (stristr($language, 'fr')) {
            $locale = 'fr';
            setlocale(LC_ALL, 'fr_FR.utf8');
            break;
        } else if (stristr($language, 'en')) {
            $locale = 'en';
            setlocale(LC_ALL, 'en_EN');
        } else {
            $locale = 'fr';
            setlocale(LC_ALL, 'fr_FR.utf8');
            break;
        }
    }
}
$localeInfo = localeconv();

function getUserLanguage($acceptLanguage) {
    $langs = array();

    // break up string into pieces (languages and q factors)
    preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

    if (count($lang_parse[1])) {
        // create a list like "en" => 0.8
        $langs = array_combine($lang_parse[1], $lang_parse[4]);

        // set default to 1 for any without q factor
        foreach ($langs as $lang => $val) {
            if ($val === '') {
                $langs[$lang] = 1;
            }
        }

        // sort list based on value
        arsort($langs, SORT_NUMERIC);
    }

    return $langs;
}

$strings = array(
    'fr' => array(
        'meta.title'        => 'Miroir, mon beau Miroir',
        'meta.description'  => "Miroir, mon beau Miroir est un système de sauvegarde visant à se prémunir contre les suppressions arbitraires de sites internet par les tenants des grandes plateformes d'hébergement comme Tumblr ou Blogpost.",
        'body.saved'        => sprintf('%d sites sont sauvegardés actuellement', count($sites)),
        'body.contact'      => 'Nous contacter',
    ),
    'en' => array(
        'meta.title'        => 'Mirror, Mirror on the wall',
        'meta.description'  => 'Mirror, Mirror on the Wall is a backup system which aims to protect against arbitrary deletions of websites by
the owners of the major hosting platforms like Tumblr or Blogpost',
        'body.saved'        => sprintf('We are actually backuping %d sites', count($sites)),
        'body.contact'      => 'Contact us',
    ),
);

$strings['fr']['body.intent'] = <<<EOF
            <h2>Note d'intention</h2>
            <p>Aussi pratiques soient les services de création et d'hébergement de site Internet fournis par Tumblr, Blogspot et consorts, il ne faut jamais oublier qu'ils appartiennent à des sociétés commerciales.</p>

            <p>Cela représente <strong>une réelle menace quant à la pérennité des contenus</strong> mis en ligne. À tout moment, sans avertissement préalable et sans recours possible, <strong>un site entrant en conflit avec les conditions d'utilisation</strong> de la plateforme (<strong>propriété intellectuelle, pornographie</strong>, etc) <strong>peut être supprimé</strong>.</p>

            <p>En outre, la pérennité des contenus hébergés sur ces plateformes est conditionnée par la survie des sociétés qui les gèrent.</p>

            <p style="text-align:center;">Pastis Hosting se propose donc d'effectuer <strong>une sauvegarde régulière de vos travaux</strong>, au cas où.</p>
EOF;

$strings['en']['body.intent'] = <<<EOF
<h2>Statement of intent</h2>
<p>As practical as the web creation and hosting services given by Tumblr, Blogspot and others might be, we shall not forget that they belong to corporations.</p>

<p>It means that <strong>sustainability of online content is threatened for real</strong>. At any time, without prior warning and without possible resort, a website which violates the plateform's terms of service (intellectual propriety pornography, etc.) can be deleted.</p>

<p>Furthermore, the sustainability of contents hosted on these plateforms is conditionned by the surviving of the corporations which manage them.</p>

<p style="text-align:center;">Pastis Hosting is offering a <strong>regular backup of your works</strong>, just in case.</p>
EOF;

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $locale ?>">

    <head>
        <title><?php echo $strings[$locale]['meta.title'] ?> | Pastis Hosting</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="original.css" />
        <link href='http://fonts.googleapis.com/css?family=Federant' rel='stylesheet' type='text/css'>
        <meta property="og:description" content="<?php echo $strings[$locale]['meta.description'] ?>" />
    </head>

    <body>
        <div id="container">
            <h1><img src="header.png" title="<?php echo $strings[$locale]['meta.title'] ?>" /></h1>
            <div id="contents">
            <div id="intent">
            <?php echo $strings[$locale]['body.intent'] ?>
            </div>

            <h2><?php echo $strings[$locale]['body.saved'] ?></h2>
            <ul>
<?php foreach ($sites as $site): ?>
                <li>
                    <strong><?php echo $site['domain'] ?> :</strong>
                    <a href="<?php echo $site['url'] ?>">website</a>
                    | <a href="blogs/<?php echo $site['domain'] ?>/">backups</a>
                </li>
<?php endforeach; ?>
            </ul>

            <h2><p style="text-align:center;"><a href="mailto:contact@pastis-hosting.net"><?php echo $strings[$locale]['body.contact'] ?></a></p></h2>
            <h3>Design by <a href="http://templealphacanismajoris.tumblr.com/">Satanik Mike</a></h3>
            </div>
        </div>
    </body>

</html>

