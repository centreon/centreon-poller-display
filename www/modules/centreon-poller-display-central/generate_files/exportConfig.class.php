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


    public function generateFromPollerId($poller_id, $localhost)
    {

        $dir ='/usr/share/centreon/filesGeneration/broker/'.$poller_id;

        $stmt = $this->backend_instance->db->prepare("SELECT id 
                                                    FROM mod_poller_display_server_relations 
                                                    WHERE nagios_server_id = :pollerId
        ");
        $stmt->bindParam(':pollerId', $poller_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($data = $stmt->fetch()) {
            $polerDisplays = $data['id'];
        }


        if ($polerDisplays) {

            $pathCentral = $dir.'/Export_central_cfg.sql';
            if (!file_exists($pathCentral)) {
                touch($pathCentral);
            }
            $centralFile = ExportCentral::getInstance()->generateObjects($poller_id);

            $handle=fopen($pathCentral, "rw");
            fwrite($handle, $centralFile);
            fclose($handle, $centralFile);

            $stmt = $this->backend_instance->db->prepare("SELECT ba_id 
                                                    FROM mod_bam_poller_relations 
                                                    WHERE poller_id = :pollerId");

            $stmt->bindParam(':pollerId', $poller_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($data = $stmt->fetch()) {
                $bamDisplays = $data['ba_id'];
            }

            if ($bamDisplays) {
                ExportBam::getInstance()->generateObjects($poller_id);
            }
        }
    }

}
