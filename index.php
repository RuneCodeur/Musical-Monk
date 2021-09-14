<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./favicon.ico">
    <link rel="stylesheet" href="style.css">
    <title>Musical-Monk</title>
    <meta name="description" content="Achetez vos instruments de Musiques, ou venez participez à nos nombreuses activités"/>
    <meta property="og:title" content="Musical-Monk"/>
    <meta property="og:description" content="Achetez vos instruments de Musiques, ou venez participez à nos nombreuses activités"/>
    <meta property="og:image" content="./ressource/miniature.png."/>
    <meta property="og:url" content=""/>
</head>
<body>

    <?php include("PHP/component/header.php"); ?>

    <?php
    if(isset($_GET['page'])){
        $page= $_GET['page'];
            
        switch ($page) 						
        {
                
            case 'planning':
                include("view/planning.php");
            break;
                
            case 'contact':
                include("view/contact.php");
            break;
                    
            case 'addproduct':
                include("view/addproduct.php");
            break;
                    
            case 'login':
                include("view/login.php");;
            break;
                    
            case 'research':
                include("view/research.php");
            break;
                
            case 'product':
                include("view/product.php");
            break;
                
            case 'addevent':
                include("view/addevent.php");
            break;
                
            case 'registration':
                include("view/registration.php");
            break;
                
            case 'account':
                include("view/account.php");
            break;
                
            default:
                include("view/home.php");
        }
    }
    else
    {
        include("view/home.php");
    }
    ?>

    <?php include("PHP/component/footer.php"); ?>
        
</body>
</html>