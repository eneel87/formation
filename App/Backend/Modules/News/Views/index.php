<ul>
  <li><a href="news-insert.html">Ajouter une news</a></li>
</ul>

<?php if(isset($nombreNews))
{?>
  <p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

  <table>
    <tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
  <?php

  foreach ($listeNews as $news)
  {
    $loginToDisplay = $news->Membre->login() ? $news->Membre->login() : "Membre supprimé";

    echo '<tr><td>', htmlspecialchars($loginToDisplay), '</td><td>', htmlspecialchars($news->titre()), '</td><td>le ', htmlspecialchars($news->dateAjout()->format('d/m/Y à H\hi')), '</td><td>', ($news->dateAjout() == $news->dateModif() ? '-' : 'le '.htmlspecialchars($news->dateModif()->format('d/m/Y à H\hi'))), '</td><td><a href="news-update-', htmlspecialchars($news['id']), '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="news-delete-', htmlspecialchars($news->id()), '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
  }
  ?>
  </table>
<?php }
else{?>
  <p style="text-align: center">Il n'y a actuellement aucune news.</p>
<?php } ?>