# EbooX
php javascript epub collection manager multi-user

<h3>Pr&eacute;sentation</h3>
<p>Eboox est un <strong>gestionnaire priv&eacute; de collection de livres &eacute;lectroniques au format epub</strong> bas&eacute; sur bootstrap.<br />Il est destin&eacute; &agrave; &ecirc;tre utilis&eacute; par les membres de la famille ou &agrave; un cercle ferm&eacute; d'amis.</p>
<p>Il a les fonctions suivantes :</p>
<ul>
<li>authentification par login et mot de passe</li>
<li>upload en masse de livres</li>
<li>r&eacute;cup&eacute;ration des informations automatique des livres upload&eacute;s</li>
<li>gestion des utilisateurs
<ul>
<li>lecteur : peut seulement naviguer dans la biblioth&egrave;que</li>
<li>contributeur : peut en plus uploader des livres</li>
<li>admin : peut ajouter des utilisateurs et utiliser les outils de maintenance</li>
</ul>
</li>
<li>recherche avanc&eacute;e de livres pr&eacute;sents</li>
<li>divers outils de maintenance</li>
<li>installation simple, m&ecirc;me sur un h&eacute;bergement mutualis&eacute;</li>
</ul>
<p>Il est n&eacute;c&eacute;ssaire d'avoir un h&eacute;bergement PHP 5+, php_gd, une base mysql.</p>
<h3>Installation</h3>
<ol>
<li>T&eacute;l&eacute;chargez les fichiers</li>
<li>Copiez les sur votre h&eacute;bergement</li>
<li>installez la base de donn&eacute;e Mysql avec le fichier bdd.sql</li>
<li>Cr&eacute;ez un utilisateur dans ebooX_users avec un pass encod&eacute; en md5 et valid=1</li>
<li>Donnez les droits en &eacute;criture aux dossiers Books et upload/files</li>
<li>Have fun !</li>
</ol>
<h3>Contribution</h3>
<p>EbooX est fonctionnel et aussi s&eacute;curis&eacute; que possible. N&eacute;anmoins le code n'est pas ce qu'il y a de plus propre et je suis certain que vous trouverez des fonctionnalit&eacute;s manquantes ou am&eacute;liorables.<br />N'h&eacute;sitez pas &agrave; m'en faire part et m'aider &agrave; am&eacute;liorer ce projet.</p>
