<ul>
    <li><a href="member-insert.html">Ajouter un utilisateur</a></li>
</ul>

<p style="text-align: center">Il y a actuellement <?= $nombreMembers ?> utilisateurs. En voici la liste :</p>

<table>
    <tr><th>Login</th><th>Password</th><th>Level</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
    <?php

    foreach ($listeMembers as $member)
    {
        echo '<tr>';
            echo '<td>'.htmlspecialchars($member->login()).'</td>';
            echo '<td>'.htmlspecialchars($member->password()).'</td>';
            echo '<td>'.htmlspecialchars($member->levelNom()).'</td>';
            echo '<td>le '.htmlspecialchars($member->dateAjout()->format('d/m/Y à H\hi')).'</td>';
            echo '<td>'.($member->dateAjout() == $member->dateModif() ? '-' : 'le '.htmlspecialchars($member->dateModif()->format('d/m/Y à H\hi'))).'</td>';
            echo '<td><a href="member-update-'.htmlspecialchars($member->id()).'.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="member-delete-', htmlspecialchars($member->id()), '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td>';
        echo '</tr>';
    }
    ?>
</table>