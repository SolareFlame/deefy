# 🎵 DEEFY

Cette application permet aux utilisateurs de créer, gérer et personnaliser leurs playlists musicales. Elle offre un ensemble de fonctionnalités intuitives accessibles depuis un menu d'accueil convivial.

## 📋 Fonctionnalités

### Mes Playlists
- **Mes playlists** : affiche la liste des playlists de l’utilisateur authentifié ; chaque élément de la liste est cliquable et permet d’afficher une playlist qui devient la playlist courante, stockée en session.

### Créer une Playlist Vide
- **Créer une playlist vide** : un formulaire permettant de saisir le nom d’une nouvelle playlist est affiché. À la validation, la playlist est créée et stockée en base de données ; elle devient la playlist courante.

### Afficher la Playlist Courante
- **Afficher la playlist courante** : affiche la playlist stockée en session.

### Inscription et Authentification
- **S’inscrire** : permet la création d’un compte utilisateur avec le rôle STANDARD.
- **S’authentifier** : l’utilisateur fournit ses identifiants pour s’authentifier en tant qu’utilisateur enregistré.

### Ajout de Piste
- **Affichage de la playlist** : permet toujours d’ajouter une nouvelle piste à la playlist. Un formulaire de saisie des données de description d’une piste est affiché. À la validation, la piste est créée, enregistrée dans la base, puis ajoutée à la playlist affichée.

## 🔐 Compléments et Sécurité
- L’affichage d’une playlist et l’ajout d’une piste sont réservés au **propriétaire de la playlist** ou à un utilisateur ayant le rôle **ADMIN**.
- **Sécurité** : 
  - Les mots de passe sont stockés de manière sécurisée.
  - Des protections contre l’injection XSS et SQL sont mises en place.
- **HTML** : le code HTML généré est valide.
- **Mise en page** : l’utilisation d’un framework CSS pour la mise en page est autorisée.

HEUERTZ Zacharie
KNORST Valentin
