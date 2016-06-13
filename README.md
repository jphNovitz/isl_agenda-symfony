isl_agenda-symfony
==================

A Symfony project created on June 13, 2016, 1:00 pm.


Objectif
========

Developper un agenda d'évènements en ligne.
Il sera possible de créer, modifier, supprimer et afficher :
- Des catégories ;
- Des Evenements ;
- Des Participants.

1. Creation des entités
-----------------------

Quatre entités

* Event pour les evenements.
- nom (string 255)
- description (text)
- debut (datetime not null)
- fin (datetime null)

* Categorie pour classer les evenements:
- nom (string 255)

* Participant
- nom (string 255 not null)
- prenom (string 255 null)

* Image: contiendra les url des image pour les participants et pour les evenements

2. Relations
------------

Event est en relation avec :
- Categorie (Many To One)
- Participant (MAny To Many)
- Image (One To One)

Les relations entre Participant/event et Image contient les options persist et remove 

