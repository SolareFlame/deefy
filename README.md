# <img src="images/logo2.png" alt="Logo de Deefy" width="50"/> DEEFY

Cette application permet aux utilisateurs de créer, gérer et personnaliser leurs playlists musicales. Elle offre un ensemble de fonctionnalités intuitives accessibles depuis un menu d'accueil convivial.

> [!WARNING]
> Il faudra le répertoire `audio/`, dans lequel les musiques sont stockées pour que le projet soit fonctionnel.
> Il sera aussi nécessaire d'avoir le fichier `db.config.ini` afin de se connecter à la DB.

## 📋 Fonctionnalités

**1. Bibliothèque**
- Affiche la liste des playlists de l’utilisateur authentifié ; chaque élément de la liste est cliquable et permet d’afficher une playlist qui devient la playlist courante, stockée en session.

**2. Créer une Playlist**
- Un formulaire permettant de saisir le nom d’une nouvelle playlist est affiché. À la validation, la playlist est créée et stockée en base de données ; elle devient la playlist courante.

**3. Playlist en session**
- Affiche la playlist stockée en session. 

**4. Playlist via uuid**
- Affiche une playlist via l'uuid dans le lien. Celle-ci est affichée uniquement si la playlist vous appartient ou si votre compte à la permission suffisante _(role >= 100)_.

> [!TIP]
> Exemple `&id=000a4f14-851f-401b-8fe8-28d441661351`.

**5. Inscription et Authentification**
- **S’inscrire** : permet la création d’un compte utilisateur avec le rôle STANDARD _(role = 1)_.
- **S’authentifier** : l’utilisateur fournit ses identifiants pour s’authentifier en tant qu’utilisateur enregistré.

**6. Barre de recherche**
- Permet de rechercher une musique parmis les musiques de la base. Un bouton permet de les enregistrer à une des playlists de la bibliothèque.

**7. Ajouter un track** (Fonction administrateur)
- Permet de rajouter une musique à la base de donnée. Lui donnant un uuid et prenant les flags contenu dans le fichier pour les informations nécessaires (titre, genre, duree, artistes, date).

> [!IMPORTANT]
> Il n'est donc pas possible d'ajouter ses propres musiques à la base sans les permissions nécessaires.

## 🔐 Compléments et Sécurité
- L’affichage d’une playlist et l’ajout d’une piste sont réservés au **propriétaire de la playlist** ou à un utilisateur ayant le rôle **ADMIN**. _(role >= 100)_.
- **Sécurité** : 
  - Les mots de passe sont stockés de manière sécurisée (hash + salt)
  - Des protections contre l’injection **XSS** (filter_var) et **SQL** (requetes préparées) sont mises en place.

## 📝 Notes supplémentaires
Contrairement aux indications du sujet, qui indique "_L’affichage d’une playlist propose toujours d’ajouter une nouvelle piste à la playlist_"; J'ai décidé de retirer cette fonctionnalité aux utilisateurs STANDARD **au profit d'une barre de recherche** et d'une suposée base de musique déjà remplie. Ainsi les utilisateurs, à l'image d'un site de streaming de musique standard, peuvent chercher des musiques dans la base et les ajouter dans des playlists si ils sont connectés.

Le fait de ne pas pouvoir ajouter les podcasts aux playlists est **voulu**, cela permet de différencier les deux types de pistes (et cela parait logique, on écoute pas un podcast comme une musique)

De cette façon, les utilisateurs ADMIN peuvent donc ajouter des musiques à la base publique.

HEUERTZ Zacharie





