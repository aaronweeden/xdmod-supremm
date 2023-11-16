<?php
/**
 * Test SUPREMM database interface.
 */

namespace ComponentTests\DataWarehouse\Query\SUPREMM;

class SupremmDbInterfaceTest extends \PHPUnit_Framework_TestCase
{
    public function testTimeseriesFilter()
    {
        $dbif = new \DataWarehouse\Query\SUPREMM\SupremmDbInterface();

        $filter = array('cpuuser' => 1);
        $query = "^6117153-.*";
        $data = $dbif->getDocument(5, 'timeseries', $query, $filter);

        $present = array(
                '_id',
                'cpuuser'
        );

        foreach ($present as $key) {
            $this->assertArrayHasKey($key, $data);
        }

        $absent = array(
                'lnet',
                'ib_lnet',
                'version',
                'nfs',
                'simdins',
                'membw',
                'process_mem_usage',
                'hosts',
                'memused',
                'memused_minus_diskcache',
                'block'
        );

        foreach ($absent as $key) {
            $this->assertArrayNotHasKey($key, $data);
        }
    }

    public function testSchema()
    {
        $dbif = new \DataWarehouse\Query\SUPREMM\SupremmDbInterface();

        $data = $dbif->getDocument(5, 'schema', 'timeseries-4');

        $keys = array(
            '_id',
            'type',
            'applies_to_version',
            'metrics'
        );

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }
}
