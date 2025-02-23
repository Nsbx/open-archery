# Open-Archery
Open-Archery est un projet open source visant à aider les clubs de tir à l'arc. (Dans un premier temps mon propre club)

## Objectifs du projet
- Accessibilité : Fournir un outil simple et efficace pour les clubs.
- Respect de la vie privée : Open-Archery met un point d'honneur à protéger les données personnelles des utilisateurs et à garantir une utilisation sécurisée du service.

## Fonctionnalités actuelles
- Affichage du planning semaine par semaine.
- Gestion des créneaux hebdomadaires (création et modification des créneaux récurrents).
- Inscription et désinscription aux sessions en un clic.
- Inscription des utilisateurs via un lien unique (respect du RGPD).
- Gestion des utilisateurs et des créneaux via un panel d'administration.
- Génération d'une URL ICS permettant l'abonnement au planning via un calendrier externe (Google Agenda, Outlook, etc.).

## Fonctionnalités à venir
- Historique des sessions et statistiques.

## Installer le projet
### Pré-requis
- Git
- PHP 8.2
- Composer 2.8
- Symfony CLI 5.10

### Commandes
> composer install
> symfony server:start
> symfony console tailwind:build
> symfony console asset-map:compile

Ne pas oublier de modifier les variables d'environnements
