<?php
namespace DataWarehouse\Query\SUPREMM;

use \DataWarehouse\Query\Model\Table;
use \DataWarehouse\Query\Model\TableField;
use \DataWarehouse\Query\Model\FormulaField;
use \DataWarehouse\Query\Model\WhereCondition;
use \DataWarehouse\Query\Model\Schema;
use \DataWarehouse\Query\Model\OrderBy;
use Psr\Log\LoggerInterface;

/*
* @author Amin Ghadersohi
* @date 2013-Feb-07
*
*/
class RawData extends \DataWarehouse\Query\Query implements \DataWarehouse\Query\iQuery
{

    public function __construct(
        $realmId,
        $aggregationUnitName,
        $start_date,
        $end_date,
        $groupById = null,
        $statisticId = null,
        array $parameters = array(),
        LoggerInterface $logger = null
    )
	{
        $realmId = 'SUPREMM';
        parent::__construct(
            $realmId,
            $aggregationUnitName,
            $start_date,
            $end_date,
            $groupById,
            null,
            $parameters
        );

        $this->setDistinct(true);

        $dataTable = $this->getDataTable();
        $joblistTable = new Table($dataTable->getSchema(), $dataTable->getName() . "_joblist", "jl");
        $factTable = new Table(new Schema('modw_supremm'), "job", "sj" );

		$resourcefactTable = new Table(new Schema('modw'),'resourcefact', 'rf');
		$this->addJoin(
            $resourcefactTable,
            new WhereCondition(
                new TableField($dataTable,"resource_id"),
                '=',
                new TableField($resourcefactTable,"id")
            )
        );

		$personTable = new Table(new Schema('modw'),'person', 'p');

		$this->addJoin(
            $personTable,
            new WhereCondition(
                new TableField($dataTable,"person_id"),
                '=',
                new TableField($personTable,"id")
            )
        );

		$this->addField(new TableField($resourcefactTable,"code", 'resource'));
        $this->addField(new TableField($resourcefactTable, "timezone"));
		$this->addField(new TableField($personTable, "long_name", "name"));

        $this->addField( new TableField($factTable, "_id", "jobid") );
        $this->addField( new TableField($factTable, "local_job_id" ) );
        $this->addField(new TableField($factTable, "start_time_ts"));
        $this->addField(new TableField($factTable, "end_time_ts"));
        $this->addField(new TableField($factTable, "cpu_user"));
        $this->addField(new TableField($factTable, "gpu_usage"));
        $this->addField(new TableField($factTable, "max_memory"));
        $this->addField(new TableField($factTable, "catastrophe"));
        $this->addField(new FormulaField('COALESCE(LEAST(sj.wall_time / sj.requested_wall_time, 1), -1)', 'walltime_accuracy'));

        $this->addJoin(
            $joblistTable,
            new WhereCondition(
                new TableField($joblistTable, "agg_id"),
                "=",
                new TableField($dataTable, "id")
            )
        );
        $this->addJoin(
            $factTable,
            new WhereCondition(
                new TableField($joblistTable, "jobid"),
                "=",
                new TableField($factTable, "_id")
            )
        );

        // This is used by Integrations and not currently shown on the XDMoD interface
        $jobnameTable = new Table(new Schema('modw_supremm'), "job_name", "jn" );
        $this->addField(new TableField($jobnameTable, 'name', 'job_name'));
        $this->addJoin(
            $jobnameTable,
            new WhereCondition(
                new TableField($factTable, "jobname_id"),
                '=',
                new TableField($jobnameTable, "id")
            )
        );

        switch($statisticId) {
            case "job_count":
                $this->addWhereCondition(new WhereCondition( "sj.end_time_ts", "BETWEEN", "duration.day_start_ts and duration.day_end_ts") );
                break;
            case "started_job_count":
                $this->addWhereCondition(new WhereCondition( "sj.start_time_ts", "BETWEEN", "duration.day_start_ts and duration.day_end_ts") );
                break;
            default:
                // All other metrics show running job count
                break;
        }

        $this->addOrder(new OrderBy(new TableField($factTable, 'end_time_ts'), 'DESC', 'end_time_ts'));
    }

    public function getQueryType(){
        return 'timeseries';
    }
}
?>
