<?php
/*
 * CENTREON
 *
 * Source Copyright 2005-2016 CENTREON
 *
 * Unauthorized reproduction, copy and distribution
 * are not allowed.
 *
 * For more informations : contact@centreon.com
 *
 */

function updateOptionsInDB($pearDB, $form) {
    foreach ($form as $key => $value) {
        if ($key == 'server_address' || $key == 'mapbox_token' || $key == 'refresh_status_interval' || $key == 'refresh_graph_interval') {
            $DBRESULT = $pearDB->query("DELETE FROM `options` WHERE `key` = 'map_light_" . $key . "'");
            if (PEAR::isError($DBRESULT)) {
                print "DB Error : ".$DBRESULT->getDebugInfo()."<br>";
            }
            $DBRESULT = $pearDB->query("INSERT INTO `options` (`key`, `value`) VALUES ('map_light_" . $key . "', '" . $value . "')");
            if (PEAR::isError($DBRESULT)) {
                print "DB Error : ".$DBRESULT->getDebugInfo()."<br>";
            }
        }
    }
}

function getMapOptions($pearDB) {
    $options = array();

    $DBRESULT = $pearDB->query("SELECT options.key,options.value FROM options WHERE options.key like 'map_light%'");

    while ($data = $DBRESULT->fetchRow()) {
        $options[$data['key']] = $data['value'];
    }

    return $options;
}

function validateAddressFormat($address = '') {
    if (preg_match('/^https*:\/\/.+:\d+$/', $address)) {
        return true;
    } else {
        return false;
    }
}
