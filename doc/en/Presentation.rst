Presentation
=============

Centreon-Poller-Display is a module designed to provide a light version of
Centreon locally on your pollers. This interface allows users to view the
monitored resources of the poller from the poller itself. 

This is interesting for users who are near the Poller server and away from the
Central or in case of a Central/network outage. It can be used as a backup 
solution. It also allows not to use WAN interconnections for example.

.. note::
    This interface is not compatible with poller which receives data from
    many pollers.

.. warning::
    Warning version 1.6 uses a new ACLs management system.
    All ACLs initially created on the Centreon Poller Display interface will be removed.
    Only the contacts ACLs related to objects supervised by the collector will be synchronized.


Centreon Poller Display allows to build the following architecture:

.. image :: /images/eschema.png
   :align: center 
