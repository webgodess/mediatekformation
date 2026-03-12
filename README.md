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

<img width="749" height="413" alt="Screenshot 2026-03-12 055327" src="https://github.com/user-attachments/assets/9f7895af-be86-4e6c-aaf2-eedf37925e82" />



### Back office – Formations

La partie administration permet de gérer les formations.

<img width="754" height="413" alt="Screenshot 2026-03-12 054424" src="https://github.com/user-attachments/assets/c912af5c-a931-4939-a4f1-2b02ba6eba1a" />



### Back office – Playlists

La partie administration permet d' ajouter des playlists.

<img width="760" height="410" alt="Screenshot 2026-03-12 054241" src="https://github.com/user-attachments/assets/f9ddce07-71e1-4175-875b-ef846215a520" />


### Back office – Catégories

La partie administration permet de gérer les catégories.

<img width="764" height="410" alt="Screenshot 2026-03-12 054110" src="https://github.com/user-attachments/assets/2f48614a-5315-4fa8-973b-a705892a21d1" />


### Authentification

L’accès au back office est sécurisé par un formulaire de connexion.

<img width="758" height="412" alt="Screenshot 2026-03-12 053931" src="https://github.com/user-attachments/assets/5e2e68ab-d91f-4452-b801-4026a174945b" />


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
