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
<h2 data-news-id="<?=$news->id()?>"><?= htmlspecialchars($news->titre()) ?></h2>
<?php

  if(($user->isAuthenticated() && $news->auteurId() == $user->getAttribute('user')->id()) || ($user->isAuthenticated() && $user->getAttribute('user')->level() == \Model\MemberManager::ADMINISTRATOR))
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
    <h2>Ajouter un commentaire</h2>
    <form action="<?=$form_action?>" data-ajax-validation="<?=$form_action_ajax_validation?>" method="post" id="insertCommentForm">
        <p>
            <?= $form ?>

            <input type="submit" value="Commenter" id="submit"/>
        </p>
    </form>

<?php } ?>
<?php
foreach ($comments as $comment)
{
?>
<fieldset data-comment-id="<?=$comment->id()?>">
  <legend>
    Posté par <strong><?= htmlspecialchars($comment->Membre()->login()) ?></strong> le <?php
    $dateAjoutFormated= $comment->dateAjout()->format('d/m/Y à H\hi');
    $dateModifFormated= $comment->dateModif()->format('d/m/Y à H\hi');

    if ($comment->dateAjout() == $comment->dateModif())
      {
        echo $dateAjoutFormated;
      }
      else
      {
        echo $dateAjoutFormated.'<em> (Modifié le '.$dateModifFormated.')</em>';
      }
      if (($user->isAuthenticated() && $comment->Membre()->id() == $user->getAttribute('user')->id()) || ($user->isAuthenticated() && $user->getAttribute('user')->level() == \Model\MemberManager::ADMINISTRATOR)) { ?>
      <a href="<?=$router->getUrl('Backend', 'News', 'updateComment', array('comment_id' => $comment->id())) ?>" data-ajax-update="<?=$router->getUrl('Backend', 'News', 'updateCommentUsingAjax', array('comment_id' => $comment->id())) ?>">Modifier</a> |
      <a href="<?=$router->getUrl('Backend', 'News', 'deleteComment', array('comment_id' => $comment->id())) ?>" data-ajax-delete="<?=$router->getUrl('Backend', 'News', 'deleteCommentUsingAjax', array('comment_id' => $comment->id())) ?>">Supprimer</a>
    <?php } ?>
  </legend>
  <p><?= nl2br(htmlspecialchars($comment->contenu())) ?></p>
</fieldset>
<?php
}

if (isset($jsFiles_a) && !empty($jsFiles_a))
{
    foreach($jsFiles_a as $jsFiles)
    {
        echo $jsFiles;
    }
}

?>
