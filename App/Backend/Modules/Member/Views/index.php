<ul>
    <li><a href="member-insert.html">Ajouter un utilisateur</a></li>
</ul>

<p style="text-align: center">Il y a actuellement <?= $nombreMembers ?> utilisateurs. En voici la liste :</p>

<table>
    <tr><th>Login</th><th>Password</th><th>Level</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
    <?php
    foreach ($listeMembers as $member)
    {
        echo '<tr><td>', $member['login'], '</td><td>', $member['password'], '</td><td>',$member['level'],'</td><td> le', $member['dateAjout']->format('d/m/Y à H\hi'), '</td><td>', ($member['dateAjout'] == $member['dateModif'] ? '-' : 'le '.$member['dateModif']->format('d/m/Y à H\hi')), '</td><td><a href="member-update-', $member['id'], '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="member-delete-', $member['id'], '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
    }
    ?>
</table>