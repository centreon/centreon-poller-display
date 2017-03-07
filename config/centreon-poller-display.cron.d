# Cron Config File for centreon-poller-display
*/5 * * * * centreon php /usr/share/centreon/cron/centreon-poller-display-sync.sh >> /var/log/centreon/centreon-poller-display.log 2>&1
