<?php

namespace RegressionTests\Controllers;

use RegressionTests\TestHarness\RegressionTestHelper;

/**
 * Test the usage explorer for jobs realm regressions.
 */
class UsageExplorerJobsTest extends aUsageExplorerTest
{
    public function csvExportProvider()
    {
        $statistics = [
            'avg_percent_cpu_idle',
            'avg_percent_cpu_system',
            'avg_percent_cpu_user',
            'avg_percent_gpu0_nv_utilization',
            'avg_netdir_home_write',
            'avg_netdir_projects_write',
            'avg_netdir_util_write',
            'avg_cpiref_per_core',
            'avg_cpldref_per_core',
            'avg_cpuusercv_per_core',
            'avg_cpuuserimb_per_core',
            'avg_flops_per_core',
            'avg_ib_rx_bytes',
            'avg_mem_bw_per_core',
            'avg_memory_per_core',
            'avg_total_memory_per_core',
            'avg_block_sda_rd_ios',
            'avg_block_sda_rd_bytes',
            'avg_block_sda_wr_ios',
            'avg_block_sda_wr_bytes',
            'avg_net_eth0_rx',
            'avg_net_eth0_tx',
            'avg_netdrv_gpfs_rx',
            'avg_netdrv_gpfs_tx',
            'avg_net_ib0_rx',
            'avg_net_ib0_tx',
            'avg_netdrv_isilon_rx',
            'avg_netdrv_isilon_tx',
            'avg_netdrv_panasas_rx',
            'avg_netdrv_panasas_tx',
            'cpu_time_idle',
            'cpu_time_system',
            'wall_time',
            'cpu_time_user',
            'gpu0_nv_utilization',
            'job_count',
            'running_job_count',
            'started_job_count',
            'submitted_job_count',
            'wait_time_per_job',
            'wait_time',
            'wall_time_per_job',
            'requested_wall_time_per_job',
            'requested_wall_time'
        ];

        $groupBys = [
            'none',
            'application',
            'cpi',
            'cpucv',
            'cpuuser',
            'catastrophe_bucket_id',
            'datasource',
            'nsfdirectorate',
            'parentscience',
            'exit_status',
            'gpu0_nv_utilization_bucketid',
            'granted_pe',
            'ibrxbyterate_bucket_id',
            'jobsize',
            'jobwalltime',
            'nodecount',
            'pi_institution',
            'max_mem',
            'queue',
            'resource',
            'provider',
            'shared',
            'institution',
            'netdrv_gpfs_rx_bucket_id',
            'netdrv_isilon_rx_bucket_id',
            'netdrv_panasas_rx_bucket_id',
            'fieldofscience',
            'pi',
            'username',
            'person'
        ];

        $settings = [
            'realm' => ['SUPREMM'],
            'dataset_type' => ['aggregate', 'timeseries'],
            'aggregation_unit' => ['Day', 'Month', 'Quarter', 'Year'],
            'statistic' => $statistics,
            'group_by' => $groupBys
        ];

        return RegressionTestHelper::generateTests($settings);
    }
}
