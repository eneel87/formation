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
    echo '<tr><td>', $news->Membre()->login(), '</td><td>', $news->titre(), '</td><td>le ', $news->dateAjout()->format('d/m/Y à H\hi'), '</td><td>', ($news->dateAjout() == $news->dateModif() ? '-' : 'le '.$news->dateModif()->format('d/m/Y à H\hi')), '</td><td><a href="news-update-', $news['id'], '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="news-delete-', $news->id(), '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
  }
  ?>
  </table>
<?php }
else{?>
  <p style="text-align: center">Il n'y a actuellement aucune news.</p>
<?php } ?>