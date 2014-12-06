# Airlines

Airlines est un outil de gestion de tableau LEAN virtuel collaboratif, permettant d'assigner des membres à un tableau, où chacun gère ses tâches planifiées sur une semaine donnée.

## Use cases

- L'utilisateur crée un tableau
- L'utilisateur ajoute des collaborateurs au tableau
- Les collaborateurs accèdent au tableau, puis à leur propre ligne
- Les collaborateurs renseignent les tâches qui leur sont affectées ainsi que leur charge, l'objectif étant d'obtenir un total de 5

## Schéma

### Board

- `name`

### Member

- `name`
- `board`
- `avatar` TODO
- `smiley` TODO

### Task

- `name`
- `date`
- `estimate`
- `consumed`
- `remaining`
- `color` TODO
- `member`

## API

TODO

## Logs

- Génération des entités Doctrine avec Symfony