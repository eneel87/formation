<h2>Formulaire de désinscription</h2>

<form action="/unsubscribe-form.html" method="POST">

    <label>Pourquoi voulez-vous nous quitter ?</label>
    <textarea name="unsubscribe_message"></textarea>
    <?php
        if(isset($erreur)) { echo '<p style="color:red">'.$erreur.'</p>';}
    ?>
    <p><input type="submit" value="Se désinscrire"/></p>

</form>