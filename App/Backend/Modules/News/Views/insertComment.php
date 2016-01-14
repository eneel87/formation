<h2>Ajouter un commentaire</h2>
<form action="<?=$form_action?>" data-ajax-validation="<?=$form_action_ajax_validation?>" method="post" id="insertCommentForm">
  <p>
    <?= $form ?>

    <input type="submit" value="Commenter" id="submit"/>
  </p>
</form>

<?php

if (isset($jsFiles_a) && !empty($jsFiles_a))
{
  foreach($jsFiles_a as $jsFiles)
  {
    echo $jsFiles;
  }
}

?>