<p>Par <em><?php if($news->Membre()->login()!=null){echo $news->Membre()->login();}else{echo "Inconnu";}?></em>, le <?= $news->dateAjout()->format('d/m/Y à H\hi') ?></p>
<h2><?= $news->titre() ?></h2>
<p><?= nl2br($news->contenu()) ?></p>

<?php if ($news->dateAjout()!= $news->dateModif()) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news->dateModif()->format('d/m/Y à H\hi') ?></em></small></p>
<?php }


if($user->isAuthenticated()) {?>
  <p><a href="/admin/commenter-<?= $news->id() ?>.html">Ajouter un commentaire</a></p>
<?php } ?>
<?php
foreach ($comments as $comment)
{
?>
<fieldset>
  <legend>
    Posté par <strong><?= htmlspecialchars($comment->Membre()->login()) ?></strong> le <?php
    $dateAjoutFormated= $comment->dateAjout()->format('d/m/Y à H\hi');
    $dateModifFormated= $comment->dateModif()->format('d/m/Y à H\hi');

    if ($comment->dateAjout() == $comment->dateModif()){echo $dateAjoutFormated;}else{ echo $dateAjoutFormated.'<em>( Modifié le '.$dateModifFormated.')</em>';}  ?>
    <?php if (($user->isAuthenticated() && $comment->Membre()->id() == $user->getAttribute('admin')->id()) || ($user->isAuthenticated() && $user->getAttribute('admin')->level() == \Model\MemberManager::ADMINISTRATOR)) { ?> -
      <a href="admin/comment-update-<?= $comment->id() ?>.html">Modifier</a> |
      <a href="admin/comment-delete-<?= $comment->id() ?>.html">Supprimer</a>
    <?php } ?>
  </legend>
  <p><?= nl2br(htmlspecialchars($comment->contenu())) ?></p>
</fieldset>
<?php
}
