<div class="page addevent">
    
    <h1>Modifier mon évènement</h1>

    <form method="post" action="index.php?page=modifyevent&id=<?=$_GET['id']?>">
        <fieldset>
            <div class="thinInput">
                <label for="name"> Nom  :</label>
                <input type="text" id="name" name="name" placeholder="mon évènement" required value="<?=$infoEvent['name']?>">
            </div>

            <div class="thinInput">
                <label for="time"> Heure :</label>
                <input type="time" id="time" name="time" required value="<?=$date[1]?>">
            </div>

            <div class="thinInput">
                <label for="date"> Date :</label>
                <input type="date" id="date" name="date" required value="<?=$date[0]?>">
            </div>

            <div class="thinInput">
                <label for="duration"> Durée :</br> (min 15 minutes et max 5 heures)</label>
                <input type="time" max="15:00" min="00:00" id="duration" name="duration" required value="<?=$infoEvent['duration']?>">
            </div>

            <div class="largeInput">
                <label for="description"> Description de l'évènement :</label>
                <textarea id="description" name="description" row="2" col="5" placeholder="ma description" required ><?=$infoEvent['description']?></textarea>
            </div>

            <input class="submit button-style" type="submit" value="modifier mon évènement">
        </fieldset>
    </form>

</div>