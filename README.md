# AP1 - MediatekFormation

Projet réalisé dans le cadre de l’atelier **AP1 - MediatekFormation**.

> Le dépôt d’origine, qui contient la présentation de l’application initiale dans son propre README, est disponible ici :  
> **[CNED-SLAM/mediatekformation](https://github.com/CNED-SLAM/mediatekformation)**

---

## Présentation

**MediatekFormation** est une application web développée avec **Symfony 6.4** permettant d’accéder à des vidéos d’auto-formation organisées par playlists.

L’application comprend :

- un **front office** permettant la consultation des formations et playlists ;
- un **back office** permettant l’administration du contenu.

Ce dépôt présente uniquement les **fonctionnalités ajoutées** dans le cadre de l’atelier ainsi que le **mode opératoire** pour installer, utiliser et tester l’application.

---

## Fonctionnalités ajoutées

### Front office

Les évolutions suivantes ont été ajoutées dans la partie front office :

- affichage du **nombre de formations par playlist** dans la page **Playlists** ;
- ajout d’un **tri croissant et décroissant** sur cette nouvelle colonne ;
- affichage du **nombre de formations** dans la page de détail d’une playlist.

### Back office

Une partie **back office** a été développée pour permettre l’administration du site.

#### Gestion des formations

- affichage de la liste des formations ;
- ajout ;
- modification ;
- suppression ;
- tris et filtres identiques à ceux du front office ;
- contrôle des saisies via formulaire Symfony.

#### Gestion des playlists

- affichage de la liste des playlists ;
- ajout ;
- modification ;
- suppression sécurisée ;
- interdiction de suppression si des formations sont encore rattachées ;
- affichage des formations liées dans le formulaire de modification.

#### Gestion des catégories

- affichage de la liste des catégories ;
- ajout ;
- suppression ;
- interdiction de suppression si la catégorie est encore utilisée par une formation ;
- sécurisation des actions via requêtes POST et protection CSRF.

#### Authentification

- sécurisation de l’accès au **back office** ;
- accès réservé aux utilisateurs disposant du rôle **ROLE_ADMIN** ;
- protection de toutes les routes commençant par `/admin` ;
- connexion et déconnexion opérationnelles.

### Tests et documentation

Le projet a également été complété par :

- des **tests unitaires** ;
- des **tests d’intégration** ;
- des **tests fonctionnels** ;
- une **documentation technique** ;
- une **documentation utilisateur** sous forme de vidéo.

### Déploiement et exploitation

L’application a été mise en ligne sur **Railway** avec :

- une base de données **MySQL** ;
- une sauvegarde automatisée de la base ;
- un déploiement continu via **GitHub** et **Railway**.

---

## Aperçu de l’application

### Front office – Playlists

La page playlists affiche désormais le nombre de formations par playlist avec possibilité de tri.

![Capture front office playlists](mettre-ici-une-image)

### Back office – Formations

La partie administration permet de gérer les formations.

![Capture back office formations](mettre-ici-une-image)

### Back office – Playlists

La partie administration permet de gérer les playlists.

![Capture back office playlists](mettre-ici-une-image)

### Back office – Catégories

La partie administration permet de gérer les catégories.

![Capture back office catégories](mettre-ici-une-image)

### Authentification

L’accès au back office est sécurisé par un formulaire de connexion.

![Capture connexion admin](mettre-ici-une-image)

---

## Technologies utilisées

- **PHP**
- **Symfony 6.4**
- **Twig**
- **MySQL**
- **Doctrine**
- **HTML / CSS**
- **Bootstrap**
- **Git / GitHub**
- **Railway**
- **MySQL Workbench**
- **Shell script**

---

## Base de données

L’application utilise une base de données **MySQL**.

Le script SQL de la base est présent dans le dépôt et permet de recréer les données nécessaires au fonctionnement de l’application en local.

---

## Installation en local

### Prérequis

Vérifier que les outils suivants sont installés :

- **PHP**
- **Composer**
- **Git**
- **MySQL** ou **Wamp/Xampp** (ou équivalent)

### 1. Cloner le projet

```bash
git clone https://github.com/webgodess/mediatekformation.git
cd mediatekformation

```
