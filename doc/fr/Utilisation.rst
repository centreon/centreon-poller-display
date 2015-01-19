Utilisation
===========

Vous allez maintenant devoir appliquer la nouvelle configuration sur le Poller depuis le serveur Central. Pour faire cela veuillez procéder dans l'ordre aux étapes suivantes : 

* Générez la nouvelle configuration pour le Poller en question
* Testez cette configuration
* Si l'étape précédente est validée, exportez la configuration vers le Poller 
* Connectez vous sur le Poller et démarrez le broker (/etc/init.d/cbd start)
* Redémarrez alors centreon-engine sur le Poller.

Les statuts, les graphiques de performance et le Dashboard sont désormais disponibles sur le serveur Central et sur l'interface "light" du Poller.

.. note::
  Si vous n'utilisez pas Centreon Engine 1.4 minimum, pensez à peupler votre configuration d'au moins un host ou Centreon Engine ne pourra pas démarrer sur le Poller.

Il vous est également possible de configurer des contacts et des ACL pour avoir des vues restreintes pour certains utilisateurs. Pensez à configurer l'auto import des utilisateurs via l'authentification LDAP.
