# MiniTwit (PHP) — AUCUN LOG (Starter pour exercice de LOG)

Ce mini-twitter *ne log rien* volontairement, afin que vos étudiant·e·s ajoutent ensuite une stratégie de logging (fichiers, BDD, syslog, Monolog, etc.).

## Installation rapide

1. Créez une base `mini_twitter` sur MySQL/MariaDB.
2. Importez `init.sql`.
3. Copiez tout le dossier sur un serveur PHP 8+ (Apache recommandé).  
   - Configurez un virtual host ou utilisez `http://localhost/mini-twitter/`.
4. Ajustez les variables DB dans `config.php` si besoin (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` via env ou directement).
5. Ouvrez le site et créez un compte.

## Fonctionnalités
- Inscription, connexion, déconnexion
- Édition du profil (bio, avatar URL)
- Publier un post (280 caractères)
- Suivre / ne plus suivre des utilisateurs
- Fil d'actualités (soi + abonnements)
- Pages utilisateurs et recherche

## Pistes d'exercice LOG (à ajouter par les étudiant·e·s)
- Journaliser les actions sensibles (auth, follow/unfollow, post) avec niveaux (INFO, WARNING, ERROR).
- Tracer l’IP, l’agent utilisateur, l’ID utilisateur, l’horodatage.
- Ajouter un corrélation ID par requête.
- Capturer les erreurs/Exceptions et les consigner.
- Exposer une page d’administration des logs ou exporter vers un SIEM.

> Le code actuel n’écrit **aucun** log.
"# MiniProjet_php_MiniTwitter_ajout_de_logs" 
"# MiniProjet_php_MiniTwitter_ajout_de_logs" 
