<h2><?=htmlspecialchars(ucfirst($Member->login()))?></h2>

<div>
    <p>Niveau de l'utilisateur : <?=htmlspecialchars(ucfirst($Member->levelNom()))?></p>
    <p>Date d'inscription : <?=$Member->dateAjout()->format('d/m/Y à H\hi')?></p>
    <P>Nombre de messages postés : <?=$Member->commentscount?></P>
</div>

<h2>News rédigées par <?=htmlspecialchars(ucfirst($Member->login()))?></h2>

<?php

if(!empty($News_a))
{
    foreach($News_a as $News)
    {
        $html = '<div>';
            $html.= '<h3>Titre de la News : "'.htmlspecialchars(ucfirst($News->titre())).'"</h3>';
            $html.= '<p style="padding:inherit; padding-left:10px"><em>Ajouté le '.$News->dateAjout()->format('d/m/Y à H\hi').'</em></p>';
            $html.= '<p>'.$News->contenu().'</p>';
            $html.= '<p><em>La News a été commentée '.$News->commentscount.' fois</em></p>';
            $html.= '<hr>';
        $html.= '</div>';

        echo $html;
    }
}
else
{
    echo "<p>Le membre n'a posté actuellement aucune news.</p>";
}

?>

<h2>Commentaires rédigés par <?=htmlspecialchars(ucfirst($Member->login()))?></h2>

<?php

if(!empty($Comments_a))
{
    foreach($Comments_a as $Comment)
    {
        $html = '<div>';
            $html.= '<h3>Dans la News : "'.htmlspecialchars(ucfirst($Comment->News->titre())).'"</h3>';
            $html.= '<p style="padding:inherit; padding-left:10px"><em>Ajouté le '.$Comment->dateAjout()->format('d/m/Y à H\hi').'</em></p>';
            $html.= '<p>'.$Comment->contenu().'</p>';
            $html.= '<hr>';
        $html.= '</div>';

        echo $html;
    }
}
else
{
    echo "<p>Le membre n'a posté actuellement aucun commentaire.</p>";
}

?>

