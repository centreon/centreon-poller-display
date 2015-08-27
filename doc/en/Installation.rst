Installation
============

From the repository
--------------------

Centreon-poller-display is only compatible with pollers made with CES distribution. 

To install, proceed with the Central Server with Database installation option of the CES installation (including Database, Apache, Broker... components) and configure it like a Poller. Next you will be able to install the module :

::

 yum install centreon-base-config-centreon-engine centreon-poller-display

.. note::
   It is possible to install the package on an already existing Poller.

.. warning::
   In case of installation on an existing poller it is necessary to reconfigure centreontrapd (installation process overrides the configuration).

Web Installation
-----------------

Next Installation steps are made from **Centreon** WEB interface. 

Go to the modules management menu : Administration > Extensions

.. image:: images/centreon_administration_modules.png
   :align: center
   :width: 800 px
   
Click on the installation icon of the **centreon-poller-display** module.

On the next page, click on "Install Module".

Module is now installed.

The Centreon interface menus should not be visible anymore.
