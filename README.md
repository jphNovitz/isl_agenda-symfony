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

4. Routes/controllers vides
---------------------------

je crée mes routes/controllers principaux, tels des case vides je n'aurais plus qu'à les remplir pour implémenter des 
fonctionnalités.

5. Controllers / Vues : affichage de la liste
---------------------------------------------

* Je crée pour chaque controller une methode qui récupère via un findAll() toutes les données et le renvoie vers une page 
(simple) de vue. Affichage des datas de l'entité et des entités liées.
 - simple mais permet de verifier que tout fonctionne avant d'aller plus loin -

* je place mes vues dans app/ressources/public

* dans mes entités j'ajoute une methode magique __toString() qui me permet de chosir ce qui sera afficher au cas ou on 
appelerait l'affichage de l'entité sans préciser la propriété.
ex categorie affichera la même chose que categorie.nom

* vues de base pour les liste d'Event, de Participants et de Categorie.

Vues ultra simples et sans design mais permet d'avancer.

6. listes vers details
----------------------

* Je modifie les vues listes pour qu'elles affichent une sipmle liste nom (categorie)  
* Je crée trois controleur pour les vues détail et les vues correspondantes. 
* Les liens des listes renvoient aux vues details.  
    event_list -> event_detail  
    categorie_list -> categorie_detail  
    participant_list -> participant_detail  


7. Formulaires
--------------

* Creation des fichier CategorieType.php, EventType.php,ParticipantType via les commandes:   
** $ php bin/console doctrine:generate:form AppBundle:Event  
** $ php bin/console doctrine:generate:form AppBundle:Categorie  
** $ php bin/console doctrine:generate:form AppBundle:Participant  

* Ajout d'une vue views/admin/categorie_add   
** Modification de CategorieController par implémentation de la méthode addaµction  
*** Création du formulaire via modèle CategorieType  
*** Verification si formulaire est soumit et valide  
*** Si oui -> ajout (persist et flush) l'objet dans la BD et revnoie vers une vue  
*** Si non -> affiche le formulaire accompagné d'un message flash (variable de session)  
*** Besoin d'ajouter une containtre pour evitere les erreurs en cas de doublons  
**** @UniqueEntity(fields="nom", message="Cette Categorie existe dejà !")  dans l'annotation de l'entité  
**** unique=true dans l'annotation qui correspond au champs  
**** methode updateAction a deux routes avec ou sans id, la route sans id met un id par defaut de 0 il est testé plus bas.  
**** au départ je teste si mon id vaut 0=> si oui je redirrige car je sais que la categorie est inexistante  
     plus bas je teste si $categorie est nul => je redirrige car j'ai un id mais il ne permet pas de trouver une categorie.  
**** Suppression : ajout d'un bouton supprimer dans le formulaire update, j'utilise la méthose isClicked() pour tester
si l'utilisateur souhaite supprimer la categorie. Si oui je redirige vers un formulaire delete qui ne contient qu'un seul bouton:
'Oui Supprimer !' -> ce bouton enclenche la deleteAction qui supprime la categorie.  

reste a faire les mêmes démarches pour les participants et les events.

**A ce stade je sais que je reviendrais pour essayer de refactorer mes controllers pour les rendres plus courts, pour
utiliser des méthodes personnalisées dans le repository car pour afficher une liste de categories je n'ai pas besoin de tout récupérer 
 pas de select * si j'ai juste besoin du nom et de l'id.  Pas de grande différence pour categorie mais pour Event ou Participant cela
sera plus lourd.  J'utilise des test et des flashbags pour tester certaines chose je devrais peut-être modifier certaines choses pour 
utiliser la gestion d'exceptions**

8. Un peu de mise en forme
--------------------------

J'ai appliqué le design simple [materialDesign.homePage] (https://github.com/Jeanphinov/materialDesign.homePage) pour mettre un
peu de formes et de couleur au travail déjà accompli. La réfléxion est venu des formulaires.  
En fait twig utilise un des themes fournis pour mettre en forme le formulaire : 
  
-- form_div_layout.html.twig  
-- form_table_layout.html.twig  
-- bootstrap_3_layout.html.twig  
-- bootstrap_3_horizontal_layout.html.twig  
-- foundation_5_layout.html.twig, wrap  

Il suffit de configurer le form_theme de twig dans le config.yml et le formulaire est presque comme on le souhaite.  
J'ai utilisé 'bootstrap_3_horizontal_layout.html.twig' qui me convient.   
Si on veut modifier le formulaire plus en profondeur on peux soit ajouter une classe à tel ou tel élément du formulaire dans la vue, 
soit modifier le theme de base en ajoutant un fichier, l'utilisant avec la commande {% form_theme form 'form/nom_fichier.html.twig' %}

Les listes de categories (public et admin) se font rapidement grâce à Bootstrap.  

9.  DQL -> myFindAll  
--------------------  

Je modifie un peu la methode findAll par muFindAll en utilisant le DQL dans le repository.
Cela me permet de mieux controller ce que je récupère.

10. Personnalisation des pages 404.  
----------------------------------  

creétion des fichier error404.html.twig (prod) et exception.html.twig (dev) dans le nouveau dossier
app/Ressources/TwigBundle/views/Exception/......



