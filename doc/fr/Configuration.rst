Configuration
=============

Il est maintenant nécessaire de configurer les différents flux Centreon-Broker.

Sur le serveur central :

Pour gérer les différents flux de Centreon-Broker, il est nécessaire de créer trois configurations différentes.

* Une configuration "classique" entre le module Centreon-Broker du collecteur et le démon Centreon-Broker sur le serveur central
* Une configuration pour le flux entre le module Centreon-Broker sur le collecteur et le démon Centreon-Broker sur le poller
* Une configuration pour le flux vers le démon Centreon-Broker pour la génération des fichiers RRDs.

 
Configuration "Poller"
----------------------

C'est la configuration classique pour un focntionnement normal. Il est donc possible de la générer avec le wizzard. Dans le menu :

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add with wizard

* Sélectionnez l'option *Simple Poller*.
* Cliquez sur Next.
* Donnez un nom à votre fichier de configuration (nous utiliserons "poller" dans notre exemple).
* Sélectionnez le poller voulu.
* Selectionnez le protocole  de communication (NDO ou BBDO). Cela doit être le même que pour votre serveur central.
* Indiquez l'addresse du serveur Central.

Configuration "Poller-Display-Broker"
-------------------------------------

Cette étape consiste maintenant à configurer le flux entre le module Centreon-Broker du poller et le démon Centreon-Broker sur le poller.

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add

Onglet General :

.. image:: images/General-1.png
   :align: center
   :width: 800 px

Onglet Input :

Ajoutez un Input de type *IPv4*.

.. image:: images/Input-1.png
   :align: center
   :width: 800 px

Onglet Logger :

Ajoutez un Logger de type *File*.

.. image:: images/Logger-1.png
   :align: center
   :width: 800 px

Onglet Output :

Ajoutez un Output  de type *Broker SQL database*.

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

Cette étape consiste maintenant à configurer le flux pour la création des fichiers RRDs sur le poller.

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add

Onglet General :

.. image:: images/General-1.png
   :align: center
   :width: 800 px

Onglet Input :

Ajoutez un Input de type *IPv4*.

.. image:: images/Input-2.png
   :align: center
   :width: 800 px

Onglet Logger :

Ajoutez un Logger de type *File*.

.. image:: images/Logger-2.png
   :align: center
   :width: 800 px

Onglet Output :

Ajoutez un Output de type *IPv4*.

.. image:: images/Output-2-1.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

.. image:: images/Output-2-2.png
   :align: center
   :width: 800 px

