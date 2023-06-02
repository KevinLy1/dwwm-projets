# Evaluation passée en Cours de Formation 2 (ECF2 - Back-end)

## Consignes

1. Créer une base de données todolist avec 4 champs (**id_task** _INT PK Auto increment_, **date_task** _DATETIME_, **description** _TEXT_ et **status** _INT_) et l'exporter (todolist.sql)\
   Les statuts sont les suivants : 1 - À faire, 2 - En cours, 3 - Terminée, 4 - Archivée
2. Créer un formulaire d'ajout de tâche dans la base de données
3. Affichage, mise à jour et suppression des tâches
4. Consultation des archives

### Note personnelle : Axes d'amélioration à l'issue de l'ECF2

| Criticité      | Description                                                                                                                                                               | Commentaire                                                                                                   |
| -------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| **Importante** | En mettant un status > 4 dans l'URL, il est possible de modifier le statut en dehors de 1, 2, 3 et 4. Exemple : **?action=update_status&id_task=1&status=5** fonctionnera | Dérangeant car la tâche n'est plus visible dans l'application mais seulement en base de données               |
| **Moindre**    | Un message de succès de mise à jour/suppression/archivage s'affichera même si l'id_task n'existe pas. Exemple : **?action=update_status&id_task=111&status=2**            | En admettant que l'id_task 111 n'existe pas, hormis un message de succès, aucun impact sur la base de données |