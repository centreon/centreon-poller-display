Utilisation
===========

Vous allez maintenant devoir appliquer la nouvelle configuration sur le poller depuis le serveur Central. Pour faire cela veuillez procéder dans l'ordre aux étapes suivantes : 

* Générez la nouvelle configuration pour le poller en question
* Testez cette configuration
* Si l'étape précédente est validée, exportez la configuration vers le poller 
* Connectez vous sur le poller et démarrez le broker sur le poller (/etc/init.d/cbd start)
* Redémarrez alors le centreon-engine sur le poller.

Les statuts remontent alors sur le serveur central et sur l'interface "light" du poller. Les graphiques de performance sont maintenant disponibles sur ce poller et les informations d'accessibilité seront également calculé de manière quotidienne sur le poller. 

.. note::
  Si vous n'utilisez pas Centreon Engine 1.4 minimum, pensez à mettre à minima un host pour voir des informations dans l'interface sur le poller.

Il vous est également possible de configurer des contacts et des ACL pour avoir des vues restreintes pour certains utilisateurs. Pensez à configurer l'auto import des utilisateurs via l'authentification LDAP.
