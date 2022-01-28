<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=460, initial-scale=1.0">
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
        <title><?= $title ?></title>
        <meta name="description" content="Achetez vos instruments de Musiques, ou venez participez à nos nombreuses activités"/>
        <meta property="og:url" content="https://musical-monk.alwaysdata.net/index.php"/>
        <meta property="og:title" content="<?= $title ?>"/>
        <meta property="og:description" content="Achetez vos instruments de Musiques, et venez participez à nos nombreuses activités"/>
        <meta property="og:image" content="https://musical-monk.alwaysdata.net/public/images/MM-L.png" /> 
    </head>

    <body>
        <?php include_once("view/component/header.php"); ?>
        
        <?= $content ?>
        
        <?php include_once("view/component/footer.php"); ?>
    </body>
</html>