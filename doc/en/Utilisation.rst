Utilisation
===========

Now you must apply new configuration to the Poller from the Central. For this please proceed with this steps in the following order :

* Generate new configuration for the Poller
* Test new configuration
* Export new configuration
* Connect on the Poller and start the Broker (/etc/init.d/cbd start)
* Start centreon-engine on the Poller.

Monitoring states, performance graphs and Dashboard are now available on the Poller in addition to the Central.

.. note::
  If you don't use at least Centreon Engine 1.4, consider putting at least one host or Centreon Engine won't start.

You can make use of ACL's on the poller. Consider configuring auto import of users with LDAP authentification.
