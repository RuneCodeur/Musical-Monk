<div class="page account">
    <h1>Mon compte personnel</h1>

    <div class="all-info">
        <div class="my-account">
            <div>Mon pseudo : <?=$_SESSION['auth']['pseudo']?></div>
            <div>Mon mail : <?=$_SESSION['auth']['mail']?></div>
            <input type="button" value="modifier mes informations" id="button-modify" class="input-style">

            <div id="modify">

                <form method="post" action="index.php?page=account&modify=pseudo">
                    <fieldset>
                        <div>
                            <label for="modify-pseudo">Nouveau pseudo :</label>
                            <input type="text" id="modify-pseudo" name="modify-pseudo" >
                        </div>

                        <input class="submit input-style" type="submit" value="Modifier mon pseudo" >
                    </fieldset>
                </form>

                <form method="post" action="index.php?page=account&modify=mail">
                    <fieldset>
                        <div>
                            <label for="modify-mail">Nouveau mail :</label>
                            <input type="mail" id="modify-mail" name="modify-mail" >
                        </div>

                        <input class="submit input-style" type="submit" value="Modifier mon adresse mail" >

                    </fieldset>
                </form>

                <form method="post" action="index.php?page=account&modify=mdp">
                    <fieldset>
                        <div>
                            <label for="modify-mdp">Nouveau mot de passe :</label>
                            <input type="password" id="modify-mdp" name="modify-mdp" onkeyup="check()">
                        </div>

                        <div>
                            <label for="confirm-modify-mdp">Confirmation du nouveau mot de passe :</label>
                            <input type="password" id="confirm-modify-mdp" name="confirm-modify-mdp" onkeyup="check()" >
                        </div>

                        <input class="submit input-style" type="submit" value="Modifier mon mot de passe" >

                    </fieldset>
                </form>

            </div>
        </div>

        <div class="reserved">
            <h2>Mes reservations</h2>

            <?php
                if($productReserved != null){
                    ?>

                        <div class='product'>
                            <h3>Produit</h3>
                            <div class="product-title">
                                <div class="name">Nom du produit</div>
                                <div class="quantity">Quantité</div>
                                <div class="price">Prix</div>
                                <div class="delete"></div>
                            </div>

                    <?php
                    foreach($productReserved as $elem){
                        ?>

                        <div class="item-product">
                            <a href="index.php?page=product&id=<?=$elem['product']?>" class="my-item">
                                <div class="name"><?=$elem['name']?></div>
                                <div class="quantity"> <?=$elem['quantity']?></div>
                                <div class="price"> <?=number_format($elem['price'] * $elem['quantity'], 2, '.', ' ')?> €</div>
                            </a>
                            <a href="index.php?page=account&deleteproduct=<?=$elem['id']?>" class="delete">X</a>
                        </div>

                        <?php
                    }
                    echo '</div>';
                }
                
                if ($eventReserved != null){
                    ?>
                        <div class='events'>
                        <h3>évènements</h3>
            
                    <?php

                    foreach($eventReserved as $elem){
                        $date = explode(' ', $elem['date']);
                        $day = explode('-', $date[0]);
                        $hour = explode(':', $date[1]);

                        ?>
                            
                        <div class="item-event">
                            <a href="index.php?page=event&id=<?=$elem['event']?>" class="my-event">
                                <div class="name-event"><?=$elem['name']?></div>
                                <div class="date">le <?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> à <?= $hour[0].':'.$hour[1]?></div>
                                <div class="friend"> <?php if($elem['friend'] == 1){echo 'je viens avec un ami';}else{echo 'je viens tout seul';}?></div>
                            </a>
                            <a href="index.php?page=account&deleteevent=<?=$elem['id']?>" class="delete">X</a>
                        </div>
                        
                    <?php
                    }
                    echo '</div>' ;
                }

                if(empty($productReserved) & empty($eventReserved)){
                    echo '<p>Tu n\'a aucune reservation</p>';
                }
            ?>
        </div>
    </div>

    <div class="leader-event">
        <h3>Les évènements que j'organise</h3>

        <?php
        foreach($myEvents as $elem){
            $date = explode(' ', $elem['date']);
            $day = explode('-', $date[0]);
            $hour = explode(':', $date[1]);
            ?>
        
            <a href="index.php?page=event&id=<?=$elem['id']?>">
                <div class="name"><?=$elem['name']?></div>
                <div class="date"><?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> <br> à <?= $hour[0].':'.$hour[1]?> </div>
                <div class="users">Participants : <?=$elem['registration']?> / <?=$elem['max_registration']?> </div>
            </a>
                
        <?php
        }
        ?>
    </div>
</div>

<script src="public/js/modify-account.js"></script>