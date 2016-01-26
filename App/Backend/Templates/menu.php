<nav>
    <ul>
        <li><a href="/">Accueil</a></li>
        <?php if (!$user->isAuthenticated() ) {?>
            <li><a href="/admin/">Connexion</a></li> <?php } ?>
        <?php if ($user->isAuthenticated()) { ?>
        <?php if($user->getAttribute('admin')->level()==1){ ?>
            <li><a href="/admin/members.html">Gestion des utilisateurs</a></li><?php } ?>
        <li><a href="/admin/news.html">Gestion des news</a></li>
        <li><a href="/admin/deconnexion.html">Déconnexion de <?php echo htmlspecialchars($user->getAttribute('admin')->login()); } ?></a></li>

    </ul>
</nav>