# 🎵 DEEFY

Cette application permet aux utilisateurs de créer, gérer et personnaliser leurs playlists musicales. Elle offre un ensemble de fonctionnalités intuitives accessibles depuis un menu d'accueil convivial.

## 📋 Fonctionnalités

[^1] ### Bibliothèque
- Affiche la liste des playlists de l’utilisateur authentifié ; chaque élément de la liste est cliquable et permet d’afficher une playlist qui devient la playlist courante, stockée en session.

[^2] ### Créer une Playlist
- Un formulaire permettant de saisir le nom d’une nouvelle playlist est affiché. À la validation, la playlist est créée et stockée en base de données ; elle devient la playlist courante.

### Playlist en session
- Affiche la playlist stockée en session.

### Playlist via uuid
- Affiche une playlist via l'uuid dans le lien. Celle-ci est affichée uniquement si la playlist vous appartient ou si votre compte à la permission suffisante (role >= 100).

### Inscription et Authentification
- **S’inscrire** : permet la création d’un compte utilisateur avec le rôle STANDARD (role : 1).
- **S’authentifier** : l’utilisateur fournit ses identifiants pour s’authentifier en tant qu’utilisateur enregistré.

### Barre de recherche
- Permet de rechercher une musique parmis les musiques de la base. Un bouton permet de les enregistrer à une des playlists de la bibliothèque.

### Ajouter un track (Fonction administrateur)
- Permet de rajouter une musique à la base de donnée. Lui donnant un uuid et prenant les flags contenu dans le fichier pour les informations nécessaires (titre, genre, duree, artistes, date).

## 🔐 Compléments et Sécurité
- L’affichage d’une playlist et l’ajout d’une piste sont réservés au **propriétaire de la playlist** ou à un utilisateur ayant le rôle **ADMIN**.
- **Sécurité** : 
  - Les mots de passe sont stockés de manière sécurisée (hash + salt)
  - Des protections contre l’injection XSS (filter_var) et SQL (requetes préparées) sont mises en place.


