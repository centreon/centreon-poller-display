UPDATE topology SET topology_show = '0' WHERE topology_id = '5';
UPDATE topology SET topology_show = '0' WHERE topology_parent = '5';
UPDATE topology SET topology_show = '0' WHERE topology_parent => '500' AND topology_parent < '600';
UPDATE topology SET topology_show = '0' WHERE topology_id = '6';
UPDATE topology SET topology_show = '0' WHERE topology_parent = '6';
UPDATE topology SET topology_show = '0' WHERE topology_parent => '600' AND topology_parent < '700';
