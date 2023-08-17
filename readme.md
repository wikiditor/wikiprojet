# Wikiditor

## Présentation de l’application

Ce projet est une refonte du site Wikipédia avec l'ajout de plusieurs fonctionnalités; la principale étant l’intégration aux articles Wikipédia d’un **outil de prise de notes, le wikiditor.**

![présentation du wikiditor](public\assets\img\wikiditor.jpg)

## Environnement

Les langages utilisés sont :

- PHP 8
- JavaScript
- HTML
- CSS
- Twig

Les outils et technologies utilisé(e)s sont : 

- Symfony 6
- Bootstrap 5.3.0
- TinyMCE
- Composer 2.5
- MongoDB

## Récupération du projet

Le code final de l’application se trouve dans la branche ***DEV.***

Dans un premier temps, assurez vous d’avoir installé :

- PHP. Vous pouvez suivre les instructions ici : [https://www.php.net/downloads](https://www.php.net/downloads)
- L’extension PHP pour MongoDB. Les instructions sont disponibles ici : [https://www.php.net/manual/en/mongodb.installation.php](https://www.php.net/manual/en/mongodb.installation.php)
- Le gestionnaire de dépendance Composer.  vous pouvez suivre les instructions ici: [https://getcomposer.org/](https://getcomposer.org/)
- Le framework Symfony. La documentation sur l’installation se trouve ici : [https://symfony.com/download](https://symfony.com/download).

Mettre à jour les dépendances du projet qui sont gérées par Composer :

```jsx
composer update
```

Compiler et construire les ressources JavaScript et CSS de l'application à partir des fichiers sources situés dans le dossier `assets` ou `resources` du projet :

```jsx
npm run dev
```

Démarrer le serveur de développement intégré de Symfony en mode détaché (en arrière-plan).

Une fois que le serveur est démarré, l'application Symfony sera accessible à l'adresse `http://localhost:8000` (par défaut) dans le navigateur.

```jsx
symfony server:start -d
```

## Structure

Le code de l’application est organisé selon l’architecture **MVC**.

### Les modèles

Les modèles encapsulent les données que l'application utilise. Il s’agit des documents de la base de données MongoDB : ***User.php*** et ***File.php***

![Modèle User.php](public\assets\img\Modele_User.png)

### Les Repository (intermédiaire entre le modèle et le controlleur)

Les Repository fournissent des méthodes pour effectuer des opérations sur les documents. Ils fournissent ainsi les méthodes pour ajouter, mettre à jour, consulter ou supprimer un document de la collection MongoDB. Il s’agit de ***UserRepositery.php*** et ***FileRepository.php***

![Repository UserRepository.php](public\assets\img\Repo_UserRepository.png)

### Les Contrôleurs

Le contrôleur fait le lien entre la vue et le modèle. Le projet contient 6 Contrôleurs qui utilisent les méthodes contenues dans les fichiers Repository : 

1. **HomeController.php** affiche la page d’accueil Wikipédia du site. Il n’est pas associé à un modèle car il n’y a pas d’interactions avec la base de données.
2. **AdminController.php** qui interagit avec le modèle *User.php* pour récupérer la liste des utilisateurs, modifier certaines informations de l’utilisateur, le bannir, et le supprimer de la base de données.
3. **SecurityController.php** qui interagit avec le modèle *User.php* pour connecter l’utilisateur et le déconnecter.
4. **RegistrationController.php** qui interagit avec le modèle *User.php* pour créer un utilisateur et le sauvegarder dans la base de données.
5. **ArticleController.php** qui interagit avec le modèle File.php. Il récupère les données fournies par l’API Wikipédia et crée le formulaire pour la rédaction du fichier par l’utilisateur.
6. **FileController.php** qui interagit avec le modèle File*.php.* Il récupère la liste des fichiers rédigés par l’utilisateur et permet également de supprimer des fichiers de la base de données.

![Controlleur articleController.php](public\assets\img\ArticleController.jpg)

### Les routes et les vues associées

L’application contient plusieurs routes et vues telles que :

- ‘**/** ’ : présente la page d’accueil de Wikipédia et est gérée par le contrôleur *HomeController.php.* La vue associée est */home/index.html.twig.*
- ‘**/article** ’ :  charge à gauche l’article de Stanley Kubrick généré par l’API Wikipédia, et l’éditeur de texte à droite.  La vue associée est */article/index.html.twig.*
- ‘**/article/{searchTerm}/{language}** ’ : charge à gauche l’article recherché et l’éditeur de texte à droite. Si l’article recherché n’est pas trouvé, une page d’erreur est renvoyée. La vue associée est */article/index.html.twig.*
- ‘ **/article/fake** ‘ : affiche l’article “fake” de Stanley Kubrick montrant ainsi le travail de refonte du site wikipédia. La vue associée est */article/fake.html.twig.*
- ‘ **/login** ‘ permet à l’utilisateur de se connecter. La vue associée est */security/login.html.twig.*
- ‘ **/logout** ‘ permet à l’utilisateur de se déconnecter.
- ‘**/file/list** ’ : permet à l’utilisateur de consulter la liste des fichiers créés. La vue associée est  */file/list.html.twig*.
- ‘***/admin** ’*  : permet à l’administrateur de consulter la liste des utilisateurs et d’avoir accès aux boutons pour modifier, bannir ou supprimer l’utilisateur. Elle est liée au contrôleur Admin*Controller.php.* La vue associée est: */admin/index.html.twig.*


### Identifiant de connexion pour tester le site

- Email: [test3@gmail.com](mailto:test3@gmail.com) (ayant un rôle administrateur)
- Mot de passe : 123456