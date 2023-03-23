# Projet API de Blog R4.01

Ce projet est une API de blog qui permet de gérer les articles, les likes et plusieurs roles tels que publisher, moderator et des simples.
Réalisé par Alfred Yael et Combet FLorent

## Fonctionnalités

- Gestion des articles
- Gestion des likes
- Authentification et autorisation

## URL d'accès

Voici les différentes URL d'accès pour interagir avec l'API de blog:

url de base : http://34.147.14.131/apiBlog

### Authentification
- Se connecter et recevoir un token JWT : POST /login
    - Body : json {"username" : "...", "password" : "..."}

### Articles

Toute les méthodes necessite de fournir un token JWT avec les requetes

- Créer un article: POST /article
    - Body : json {"body" : "..."}
- Récupérer tous les articles: GET /article
- Récupérer tous nos articles: GET /article/own
- Modifier un article: PATCH /article/{id}
    - Body : json {"body" : "..."}
- Supprimer un article: DELETE /article/{id}
- Liker un article: PATCH /article/like/{id}
- Disliker un article: PATCH /article/dislike/{id}
