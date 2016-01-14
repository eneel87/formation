<p>Par <em><?php
    if($news->Membre->login()!=null)
    {
      echo htmlspecialchars($news->Membre->login());
    }
    else
    {
      echo "Inconnu";
    }
    ?>
  </em>, le <?= $news->dateAjout()->format('d/m/Y à H\hi') ?></p>
<h2><?= htmlspecialchars($news->titre()) ?></h2>
<?php

  if(($user->isAuthenticated() && $news->auteurId() == $user->getAttribute('admin')->id()) || ($user->isAuthenticated() && $user->getAttribute('admin')->level() == \Model\MemberManager::ADMINISTRATOR))
  {
    ?>
    <p><a href="<?=$router->getUrl('Backend', 'News', 'update', array('id' => $news->id())) ?>"><em>Modifier la News</em></a></p>
  <?php
  }
?>
<p><?= nl2br(htmlspecialchars($news->contenu())) ?></p>

<?php if ($news->dateAjout()!= $news->dateModif()) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news->dateModif()->format('d/m/Y à H\hi') ?></em></small></p>
<?php }
if($user->isAuthenticated()) {
 ?>
  <p><a href="<?=$router->getUrl('Backend', 'News', 'insertComment', array('news_id' => $news->id()))?>">Ajouter un commentaire</a></p>
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
    if (($user->isAuthenticated() && $comment->Membre()->id() == $user->getAttribute('admin')->id()) || ($user->isAuthenticated() && $user->getAttribute('admin')->level() == \Model\MemberManager::ADMINISTRATOR)) { ?> -
    <?php  if ($comment->dateAjout() == $comment->dateModif())
      {
        echo $dateAjoutFormated;
      }
      else
      {
        echo $dateAjoutFormated.'<em> (Modifié le '.$dateModifFormated.')</em>';
      }  ?>
      <a href="<?=$router->getUrl('Backend', 'News', 'updateComment', array('comment_id' => $comment->id())) ?>">Modifier</a> |
      <a href="<?=$router->getUrl('Backend', 'News', 'deleteComment', array('comment_id' => $comment->id())) ?>">Supprimer</a>
    <?php } ?>
  </legend>
  <p><?= nl2br(htmlspecialchars($comment->contenu())) ?></p>
</fieldset>
<?php
}
