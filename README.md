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
-- nom (string 255)
-- description (text)
-- debut (datetime not null)
-- fin (datetime null)

* Categorie pour classer les evenements:
-- nom (string 255)

* Participant
-- nom (string 255 not null)
-- prenom (string 255 null)

* Image: contiendra les url des image pour les participants et pour les evenements

2. Relations.
-------------

Event est en relation avec :
- Categorie (Many To One)
- Participant (MAny To Many)
- Image (One To One)

Les relations entre Participant/event et Image contient les options persist et remove 

3. Data Fixtures.
----------------

Installation de deux bundles.

* composer.phar require --dev doctrine/doctrine-fixtures-bundle
Installe le doctrine-fixtures-bundle qui permet de 'remplir' la base de données afin de pouvoir commencer à travailler.

* composer.phar require fzaninotto/faker
Faker nous fournit des 'fausses' donnée pour éviter de mettre chaque fois les mêmes données ou de perdre du temps à en créer.

* Les fixtures se place dans le répertoire src/AppBundle/DataFixtures/ORM/
Un fichier par entité à remplir, avec un ordre (getOrder) pour que les fixtures se fassent dans le bon ordre.
Par exemple avant de créer un évènement je met des données dans l'entité categorie.

J'ai des données dans ma base, de quoi commencere à travailler sur les controllers et les vues.



