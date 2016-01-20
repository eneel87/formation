<fieldset>
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
        if (($user->isAuthenticated() && $comment->Membre()->id() == $user->getAttribute('admin')->id()) || ($user->isAuthenticated() && $user->getAttribute('admin')->level() == \Model\MemberManager::ADMINISTRATOR)) { ?>
            <a href="<?=$router->getUrl('Backend', 'News', 'updateComment', array('comment_id' => $comment->id())) ?>" data-ajax-update="<?=$router->getUrl('Backend', 'News', 'updateCommentUsingAjax', array('comment_id' => $comment->id())) ?>">Modifier</a> |
            <a href="<?=$router->getUrl('Backend', 'News', 'deleteComment', array('comment_id' => $comment->id())) ?>" data-ajax-delete="<?=$router->getUrl('Backend', 'News', 'deleteCommentUsingAjax', array('comment_id' => $comment->id())) ?>">Supprimer</a>
        <?php } ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($comment->contenu())) ?></p>
</fieldset>