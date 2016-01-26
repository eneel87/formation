<nav>
    <ul>
        <li><a href="<?=$router->getUrl('Frontend', 'News', 'index')?>">Accueil</a></li>
        <?php if ($user->isAuthenticated()== false ) {?>
            <li><a href="<?php echo $router->getUrl('Backend', 'Administration', 'index');?>">Connexion</a></li> <?php }
        else {?>
        <?php if($user->getAttribute('admin')->level()==1){ ?>
            <li><a href="<?=$router->getUrl('Backend', 'Member', 'index')?>">Gestion des utilisateurs</a></li> <?php } ?>
        <li><a href="<?=$router->getUrl('Backend', 'News', 'index')?>">Gestion des news</a></li>
        <li><a href="<?=$router->getUrl('Backend', 'Connexion', 'deconnexion')?>">Déconnexion de <?php echo htmlspecialchars($user->getAttribute('admin')->login()); } ?></a></li>
    </ul>
</nav>
