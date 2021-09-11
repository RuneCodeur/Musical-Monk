<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./favicon.ico">
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
                
            case 'addproduit':
                include("view/addproduit.php");
            break;
                
            case 'login':
                include("view/login.php");;
            break;
                
            case 'recherche':
                include("view/recherche.php");
            break;
            
            case 'produit':
                include("view/produit.php");
            break;
            
            case 'addevenement':
                include("view/addevenement.php");
            break;
            
            case 'inscription':
                include("view/inscription.php");
            break;
            
            case 'compte':
                include("view/compte.php");
            break;
                
            default:
            echo $page;
            include("view/accueil.php");
        }
    }
    else
    {
        include("view/accueil.php");
    }
?>

    <?php include("PHP/component/footer.php"); ?>
        
</body>
</html>