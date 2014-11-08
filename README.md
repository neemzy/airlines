# Airlines

Airlines est un tableau LEAN collaboratif virtuel, où chaque utilisateur gère ses tâches planifiées sur chaque jour de la semaine. Une vue globale est disponible dans l'objectif d'être visualisée sur un écran commun.

## Solution technique

- Symfony (framework PHP MVC)
- PHPUnit (moteur de tests unitaires PHP)
- React.js (framework JS front-end)
- Gulp (builder front-end embarquant LESS, Browserify...)
- Jenkins (outil d'intégration continue)

## Ébauche conceptuelle

Table
Row
Day
Task (load, consumed, remaining)

## Use case

- L'utilisateur crée un tableau
- L'utilisateur ajoute des collaborateurs au tableau
- Les collaborateurs accèdent au tableau, puis à leur propre ligne
- Les collaborateurs renseignent les tâches qui leur sont affectées ainsi que leur charge, l'objectif étant d'obtenir un total de 5