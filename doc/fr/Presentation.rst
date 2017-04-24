Présentation
=============

Centreon-Poller-Display est un module qui a pour but d’offrir l’interface
Centreon sur vos différents pollers de supervision. Cette interface permet aux
utilisateurs de visualiser les ressources d’un collecteur depuis ce dernier. Ce
besoin est intéressant quand les utilisateurs se trouvent à proximité du serveur 
de collecte et à distance du central. Cela permet alors d’avoir une vue directe
sur les ressources de ce collecteur uniquement.

En cas de coupure de la liaison réseau entre le central et le poller, cette 
interface peut être également utilisée comme solution de secours. Cela permet
également de ne pas utiliser des interconnexion WAN par exemple.

.. note::
    Cette interface n'est pas compatible avec un poller recevant les données
    collectés de plusieurs pollers.

Centreon Poller Display permet alors de mettre en place l’architecture suivante :

.. image :: /images/eschema.png
   :align: center 

