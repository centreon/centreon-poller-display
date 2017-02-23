<?php
/*
 * Centreon
 *
 * Source Copyright 2005-2016 Centreon
 *
 * Unauthorized reproduction, copy and distribution
 * are not allowed.
 *
 * For more informations : contact@centreon.com
 *
 */

require_once dirname(__FILE__) . '/subclass/export_central.class.php';

class CentralExport extends AbstractObject
{


    public function generateFromPollerId($poller_id, $localhost) {

        $stmt = $this->backend_instance->db->prepare("SELECT id 
                                                    FROM mod_poller_display_server_relations 
                                                    WHERE nagios_server_id = :pollerId
        ");
        $stmt->bindParam(':pollerId', $poller_id, PDO::PARAM_INT);
        $stmt->execute();
        $polerDisplays = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);

        if($polerDisplays){

            ExportCentral::getInstance()->generateObjects($poller_id);

            if($bam){
                ExportBam::getInstance()->generateObjects($poller_id);
            }
        }
    }


/*
    public function centralExport($polerDisplays)
    {
        foreach ($polerDisplays AS $polerID) {

            if (!is_dir($exportDir)) {
                mkdir($exportDir);
            }

            $fileName = 'Export_central_cfg.sql.gz';
            $file = $exportDir . $fileName;

            $mysql_base = 'centreon';

            $mysql_data_table = 'nagios_server cfg_nagios host extended_host_information ns_host_relation '
                .'service extended_service_information host_service_relation hostgroup hostgroup_relation '
                .'acl_resources';

            $dump = $mysql_base . ' ' . $mysql_data_table;

            $cmd = '`mysqldump -u ' . user
                . ' -h ' . db
                . ' -p ' . password . $dump
                . ' > ' . $file . '`;';

        }
        exec($cmd);
    }

    public function bamExport($polerDisplays)
    {
        foreach ($polerDisplays AS $polerID) {
            $exportDir = "/usr/share/centreon/filesGeneration/broker/$polerID/";
            if (!is_dir($exportDir)) {
                mkdir($exportDir);
            }

            $fileName = 'Export_bam_cfg.sql.gz';
            $file = $exportDir . $fileName;

            $mysql_base = 'centreon';
            $mysql_data_table = 'mod_bam mod_bam_poller_relations mod_bam_boolean mod_bam_kpi mod_bam_impacts';
            $dump = $mysql_base . ' ' . $mysql_data_table;

            $cmd = '`mysqldump -u ' . user
                . ' -h ' . db
                . ' -p ' . password . $dump
                . ' > ' . $file . '`;';

        }
        exec($cmd);
    }
*/


}
