Présentation
=============

Centreon-Poller-Display est un module qui a pour but d'offrir l'interface Centreon sur vos différents pollers de supervision. Cette interface permet aux utilisateurs d'avoir un vue sur les ressources d'un poller depuis le poller lui-même. Ce besoin est intéressant quand les utilisateurs se trouvent à proximité du serveur de collecte et à distance du central. Cela permet alors d'avoir une vue direct sur les ressources de ce collecteur uniquement. 

En cas de coupure de la laison réseau entre le central et le poller, cette interface peut être également utilisée comme solution de secours. Cela permet également de ne pas utiliser des interconnexion WAN par exemple. 

Centreon Poller Display permet alors de mettre en place l'architecture suivante : 

.. image :: /images/eschema.png
   :align: center 

Pour plus d'information sur cette architecture, merci de vous reporter à la `documentation de Centreon`_.

.. `documentation de Centreon`::http://documentation-fr.centreon.com/docs/centreon/fr/2.5.x/architecture/03e.html
