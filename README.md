### GeoZones

Ce projet a pour objectif de s'insérer dans une architecture orientée services. Le nano-service GeoZones
récupère la liste des pays du monde classés par continents et sous-continents ainsi que les langues utilisées
dans les pays.

Au niveau fonctionnel, il faut :

- se reposer sur une source indiscutable (j'ai choisi la table des Nations-Unies) pour simplifier le côté idéologique
inhérent à la classification
- fournir un format de sortie qui soit indépendant de la source utilisée
- le service doit fournir des traductions et renvoyer du Json (le xml est facultatif)
