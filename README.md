# Créez votre premier blog en PHP

[![SymfonyInsight](https://insight.symfony.com/projects/9f256418-df09-4e7a-a303-707c4c641616/big.svg)](https://insight.symfony.com/projects/9f256418-df09-4e7a-a303-707c4c641616)

## Contexte
Ça y est, vous avez sauté le pas ! Le monde du développement web avec PHP est à portée de main et vous avez besoin de visibilité pour pouvoir convaincre vos futurs employeurs/clients en un seul regard. Vous êtes développeur PHP, il est donc temps de montrer vos talents au travers d’un blog à vos couleurs.

## Description du besoin

Le projet est donc de développer votre blog professionnel. Ce site web se décompose en deux grands groupes de pages :

- les pages utiles à tous les visiteurs ;
- les pages permettant d’administrer votre blog.

Voici la liste des pages qui devront être accessibles depuis votre site web :

- la page d'accueil ;
- la page listant l’ensemble des blog posts ;
- la page affichant un blog post ;
- la page permettant d’ajouter un blog post ;
- la page permettant de modifier un blog post ;
- les pages permettant de modifier/supprimer un blog post ;
- les pages de connexion/enregistrement des utilisateurs.

Vous développerez une partie administration qui devra être accessible uniquement aux utilisateurs inscrits et validés.

Les pages d’administration seront donc accessibles sur conditions et vous veillerez à la sécurité de la partie administration.

## Installation du projet

**Etape 1 :** Cloner le projet sur un serveur web ou un serveur local.

**Etape 2 :** Faite pointer le serveur vers le dossier **/public**

**Etape 3 :** Importez le fichier **/datas/oc_blog_mvc.sql** dans votre SGBD (MySQL). Vous n'avez pas besoin de créer la base de données.

**Etape 4 :** Renommez le fichier **config/dev-sample.ini** en **config/dev.ini** ou **config/prod.ini** selon votre environnement (le fichier récupéré en priorité est le prod.ini)

**Etape 5 :** Complétez le fichier avec vos informations
- **rootWeb :** URL d'acès à votre site (exemple : http://localhost:8888)
- **rootPath :** Chemin d'accès à la racine du projet (exemple si votre serveur pointe vers le dossier /public : ../)


- **dsn :** 'mysql:host=AdresseBaseDeDonnées;dbname=NomBaseDeDonnées;charset=utf8'
- **username :** Nom d'utilisateur de la base de données
- **password :** Mot de passe de la base de données


- **timezone :** Modifiez ce paramètre si vous souhaiter utiliser un autre fuseau horaire que celui de la France


- **contact_email :** Adresse mail de réception du formulaire de contact
- **noreply_email :** Adresse mail d'envoi du formulaire de contact


- **error_404 :** Message affiché lors d'une erreur 404

Pour l'envoi des emails de contacte (valeurs d'exemple dans dev-sample.ini) :
- **smtp_host :** Hôte SMTP
- **smtp_smtpAuth :** Authentification SMTP
- **smtp_port :** Port SMTP
- **smtp_username :** Utilisateur SMTP
- **smtp_password :** Mot de passe SMTP


**Etape 6 :** Le site est installé. Vous pouvez vous connecter en tant qu'admin avec les identifiants ci-dessous

*Email : admin@admin.fr*<br>
*Mot de passe : 1adminadmin* 
