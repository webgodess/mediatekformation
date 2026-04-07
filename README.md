#  Mediatekformation

## Présentation

Ce projet consiste en une application web développée avec Symfony permettant de consulter des contenus de formation proposés par le réseau de médiathèques Mediatek86.

L'application initiale est issue du dépôt suivant :   https://github.com/CNED-SLAM/mediatekformation  

Dans le cadre de cette réalisation, j’ai travaillé sur l’évolution de l’application existante, en développant notamment une interface d’administration et en ajoutant de nouvelles fonctionnalités.

---

## Interface utilisateur (Front-office)

### Page d’accueil

La page d’accueil permet de comprendre le fonctionnement du site et d’accéder rapidement aux contenus récents.

Elle contient :
- une bannière avec le nom du site et sa description
- un menu de navigation (Accueil, Formations, Playlists, Catégories)
- une présentation générale du service
- un affichage des dernières formations disponibles

![Accueil](images/accueil.png)

---

### Liste des playlists

Cette page présente l’ensemble des playlists disponibles et permet à l’utilisateur d’accéder facilement aux formations associées.

![Playlists](images/playlists.png)

---

###  Contenu d’une playlist

Affichage détaillé des formations appartenant à une playlist spécifique.

![Détail playlist](images/detail_playlist.png)

---

## Interface d’administration (Back-office)

### Connexion

Une interface de connexion permet d’accéder à l’espace d’administration.

![Login](images/login.png)

---

### Gestion des formations

L’administrateur peut :
- ajouter de nouvelles formations
- modifier les formations existantes
- supprimer des formations

![Formations](images/formations.png)

---

### Modification d’une formation

Interface dédiée à la modification des informations d’une formation.

![Modifier formation](images/modifier_formation.png)

---

###  Gestion des playlists

Permet de gérer les playlists disponibles sur le site.

![Playlists admin](images/admin_playlists.png)

---

### Création d’une playlist

Ajout de nouvelles playlists via l’interface d’administration.

![Ajouter playlist](images/ajouter_playlist.png)

---

### Gestion des catégories

Organisation des formations grâce à un système de catégories.

![Catégories](images/categories.png)

---


##  Installation en local

### Prérequis

- Composer
- Git
- WampServer / XAMPP ou équivalent

---

### Étapes d’installation

1. Cloner le projet :

```bash
git clone https://github.com/webgodess/mediatekformation.git
Installer les dépendances :
composer install
Créer une base de données mediatekformation dans phpMyAdmin
Importer le fichier mediatekformation.sql
Adapter le fichier .env si nécessaire
Lancer l’application :
http://localhost/mediatekformation/public/index.php
 Accès administrateur
URL : /login
Identifiant : ******
Mot de passe : *****


