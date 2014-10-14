Configuration
=============

Il est maintenant nécessaire de configurer différents nouveaux flux Centreon-Broker au niveau du serveur de type "poller" qui hébergera l'interface web. Ces flux sont à configurer au niveau du serveur central. Il ne sera jamais nécessaire de configurer quoi que ce soit au niveau du poller au sujet de la configuration des ressources supervisées. Seule les ACL, les contacts et les options générales peuvent être configurées au niveau des pollers.

Pour gérer les différents flux de Centreon-Broker, il est nécessaire de créer trois configurations différentes : 

* Une configuration "classique" entre le module Centreon-Broker du collecteur et le démon Centreon-Broker sur le serveur central
* Une configuration pour le flux entre le module Centreon-Broker sur le collecteur et le démon Centreon-Broker sur le poller
* Une configuration pour le flux vers le démon Centreon-Broker pour la génération des fichiers RRDs.

 
Configuration "Poller"
----------------------

La première étape revient à configurer votre poller avec une configuration classique. Il est donc possible de la générer avec le wizzard. Dans le menu :

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add with wizard

* Sélectionnez l'option *Simple Poller*.
* Cliquez sur Next.
* Donnez un nom à votre fichier de configuration (nous utiliserons "poller" dans notre exemple).
* Sélectionnez le poller voulu.
* Selectionnez le protocole  de communication (NDO ou BBDO). Cela doit être le même que pour votre serveur central.
* Indiquez l'addresse du serveur Central.

Il est possible que vous n'ayez pas besoin de passer par cette étape si votre poller est déjà en fonctionnement et que les données remontent déjà sur le serveur central. Dans ce cas, passez à la deuxième étape.


Configuration "Poller-Display-Broker"
-------------------------------------

La deuxième étape consiste à configurer le flux entre le module Centreon-Broker du poller et le démon Centreon-Broker sur le poller. Un démon Centreon-Broker sera nécessaire afin de constituer un cache local dans la base de données "centreon_storage" pour que l'interface Web de Centreon puisse l'afficher. Pour cela aller dans : 

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add

et suivez les différentes étapes.

Onglet General :

.. image:: images/General-1.png
   :align: center
   :width: 800 px


**Onglet Input :** Ajoutez un Input de type *IPv4*.

.. image:: images/Input-1.png
   :align: center
   :width: 800 px

**Onglet Logger :** Ajoutez un Logger de type *File*.

.. image:: images/Logger-1.png
   :align: center
   :width: 800 px

**Onglet Output :** Ajoutez un Output  de type *Broker SQL database*.

.. image:: images/Output-1-1.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *Perfdata Generator (Centreon Storage)*.

.. image:: images/Output-1-2.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *IPV4*.

.. image:: images/Output-1-3.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

.. image:: images/Output-1-4.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

.. image:: images/Output-1-5.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

.. image:: images/Output-1-6.png
   :align: center
   :width: 800 px


Configuration "Poller-Display-RRD"
----------------------------------

Cette étape consiste maintenant à configurer le flux pour la création des fichiers RRDs sur le poller. Un démon "RRD" sera également ajouté pour la création des bases de données RRDTool sur le poller comme nous avons l'habitude de le faire sur le serveur Central. Pour cela aller dans : 

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add

et suivez les différentes étapes.

Onglet General :

.. image:: images/General-1.png
   :align: center
   :width: 800 px


**Onglet Input :** Ajoutez un Input de type *IPv4*.

.. image:: images/Input-2.png
   :align: center
   :width: 800 px

**Onglet Logger :** Ajoutez un Logger de type *File*.

.. image:: images/Logger-2.png
   :align: center
   :width: 800 px

**Onglet Output :** Ajoutez un Output de type *IPv4*.

.. image:: images/Output-2-1.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

.. image:: images/Output-2-2.png
   :align: center
   :width: 800 px

Vous pouvez maintenant passer à l'étape suivante qui consistera à appliquer les modifications.
