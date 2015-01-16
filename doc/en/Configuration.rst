Configuration
=============

Specific Centreon Broker streams configurations are required for the "Poller" server that will host the Centreon light web GUI. These streams are configured from the main Centreon Web GUI on the Central server. Supervised ressources configuration is not made on the Poller. Only ACLs, contacts and general options can be configured at the Poller's end.

It is necessary to setup tree Centreon-Broker streams:

* A "classic" configuration for the stream between Centreon-Broker module on the Poller server and Centreon-Broker daemon (cbd) on the Central server
* A configuration for the stream between Centreon-Broker module on the Poller server and Centreon-Broker daemon (cbd) on the Poller server
* A configuration for the stream between Centreon-Broker module on the Poller server and Centreon-Broker daemon (cbd) on the Central server for RRDs files generation

 
"Poller" Configuration
----------------------

In the first step you need a classic configuration for your Poller server which can be handled by the wizard. In the menu :

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add with wizard

* Select *Simple Poller* option.
* Click Next.
* Give a name to your configuration file (We will use "Poller" in our example).
* Select the desired Requester.
* Select communication protocol (NDO or BBDO). It must be the same protocol that is used on the Central.
* Specify the Central server IP address.

You may not have to proceed with this step if your Poller server is already linked to the Central server.

However you need to configure the stream between Centreon-Broker module on the Poller server and the Centreon-Broker daemon (cbd) on the Poller server.

For that, in the Poller configuration, you need to add an **Output** of type **IPv4** :

.. image:: images/Poller-output.png
   :align: center
   :width: 800 px

"Poller-Display-Broker" Configuration
-------------------------------------

The second step is to configure the stream between Centreon-Broker module on the Poller server and Centreon-Broker daemon (cbd) on the Poller server. A Centreon-Broker daemon (cbd) is required on the poller to generate datas in centreon_storage database for the Centreon light GUI to work.
For that, go to:

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add

And follow the following steps.

**Etape 1 : Onglet General**

.. image:: images/General-1.png
   :align: center
   :width: 800 px

Configure your broker file.

.. note::
  Ouch....
  Pensez à nommer le fichier de configuration du démon sql avec le même nom que sur le serveur Central afin que le script d'init puisse le prendre en compte sans changement majeur. Le nom doit être Central-broker.xml même si le serveur est un Poller.


**Etape 2 : Onglet Input**

.. image:: images/Input-1.png
   :align: center
   :width: 800 px

Add an *IPv4* output.

**Etape 3 : Onglet Logger**

.. image:: images/Logger-1.png
   :align: center
   :width: 800 px

Add a *Logger* of type *FIle*.

**Etape 4 : Onglet Output**

Now we add several *output*.

**Etape 4a : Connexion à la base de données 'temps réel'**

.. image:: images/Output-1-1.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *Broker SQL database*.

.. note::
  Attention, les accès à la base de données sont ceux de la base de données du Poller. Connectez vous au Poller pour connaître le mot de passe de la base de données pour l'utilisateur "centreon".

**Etape 4b : stockage des données dans data_bin**

Ajoutez un Output de type *Perfdata Generator (Centreon Storage)*.

.. image:: images/Output-1-2.png
   :align: center
   :width: 800 px

.. note::
   Les options **Store in performance data in data_bin** et **Insert in index data** doivent être à **Yes** sinon les graphiques ne pourront pas se créer.


Ajoutez un Output de type *IPV4*.

.. note::
  Attention, les accès à la base de données sont ceux de la base de données du Poller. Connectez vous au Poller pour connaître le mot de passe de la base de données pour l'utilisateur "centreon".


**Etape 4c : envoi de flux vers le broker rrd local**

.. image:: images/Output-1-3.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *IPV4*.


**Etape 4d : envoi de flux vers le broker sql local**

.. image:: images/Output-1-4.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *IPV4*.

**Etape 4e : mise en place du failover rrd**

.. image:: images/Output-1-5.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

**Etape 4e : mise en place du failover sql**

.. image:: images/Output-1-6.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

Vous pouvez maintenant valider le formulaire. Votre configuration est maintenant opérationnel pour cet objet.

|

Configuration "Poller-Display-RRD"
----------------------------------

Cette étape consiste maintenant à configurer le flux pour la création des fichiers RRDs sur le Poller. Un démon "RRD" sera également ajouté pour la création des bases de données RRDTool sur le Poller comme nous avons l'habitude de le faire sur le serveur Central. Pour cela aller dans : 

::

 Configuration > Centreon > Centreon-Broker > Configuration > Add

et suivez les différentes étapes.

**Etape 1 : Onglet General**

.. image:: images/General-2.png
   :align: center
   :width: 800 px

Configurer votre fichier broker

.. note::
  Pensez à nommer le fichier de configuration du démon rrd avec le même nom que sur le serveur Central afin que le script d'init puisse le prendre en compte sans changement majeur. Le nom doit être Central-rrd.xml même si le serveur est un Poller.

**Etape 2 : Onglet Input**

.. image:: images/Input-2.png
   :align: center
   :width: 800 px

Ajoutez un Input de type *IPv4*.

**Etape 3 : Onglet Logger**

.. image:: images/Logger-2.png
   :align: center
   :width: 800 px

Ajoutez un Logger de type *File*.

**Etape 4 : Onglet Output**

.. image:: images/Output-2-1.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *IPv4*.

**Etape 5 : Ajouter un Failover**

.. image:: images/Output-2-2.png
   :align: center
   :width: 800 px

Ajoutez un Output de type *File*.

Vous pouvez maintenant valider le formulaire. Votre configuration est maintenant opérationnel pour cet objet.

|

.. warning::
   Sur l'interface du Poller dans le menu : **Administration** > **Options** > **Centstorage** > **Options**, l'option **Enable resources's insertion in index_data by Centreon** ne doit pas être cochée.

Vous pouvez maintenant passer à l'étape suivante qui consistera à appliquer les modifications.
