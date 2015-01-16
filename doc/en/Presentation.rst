Presentation
=============
Centreon-Poller-Display is a module that is designed to provide a light version of Centreon web GUI locally on your pollers. This interface allows users to view the monitored resources of the poller from the poller itself. This is interesting for users who are near the Poller server and away from the Central or in case of a Central/network outage. 

In case of a network outage between the Central and the Poller, this interface can be used as a backup solution. It also allows not to use WAN interconnections for example.

Centreon Poller Display then allows to build the following architecture :

.. image :: /images/eschema.png
   :align: center 

For more informations on this architecture, please refer to the `Centreon documentation`_.

.. `Centreon documentation`::http://documentation.centreon.com/docs/centreon/en/2.5.x/architecture/03e.html
