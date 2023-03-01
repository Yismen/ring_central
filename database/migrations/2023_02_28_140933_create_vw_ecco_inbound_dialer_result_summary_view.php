<?php

use Illuminate\Database\Migrations\Migration;
use Dainsys\RingCentral\Traits\HasRCConnection;

class CreateVwEccoInboundDialerResultSummaryView extends Migration
{
    use HasRCConnection;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vw_ecco_inbound_dialer_result_summary')) {
            DB::statement("
                CREATE OR REPLACE VIEW vw_ecco_inbound_dialer_result_summary AS
                SELECT
                    a.agent_id,
                    TRIM(a.agent_fname + ' ' + a.agent_lname) AS agent_name,
                    CONVERT(DATE, a.enqueue_time) AS date,
                    a.gate_name AS dial_group_name,
                    LEFT(TRIM(a.gate_name), 3) AS dial_group_prefix,
                    'inbound' AS queue,
                    a.agent_disposition,
                    a.agent_duration AS duration,
                    a.sec_on_hold,
                    a.agent_wait_time,
                    a.agent_wrap_time,
                    1 AS calls,
                    b.contacts,
                    b.sales,
                    a.ani AS lead_phone,
                    a.recording_url
                FROM
                    dbo.Inbound_Call_Detail_Download AS a
                    LEFT OUTER JOIN dbo.ecco_dispositions AS b ON b.name = a.agent_disposition
                WHERE
                    (
                        NOT (
                            a.agent_disposition LIKE '%No Agent Disposition%'
                        )
                    )
            ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
        DROP VIEW IF EXIST vw_ecco_inbound_dialer_result_summary
        ');
    }
}
