<?php

use Illuminate\Database\Migrations\Migration;
use Dainsys\RingCentral\Traits\HasRCConnection;

class CreateVwEccoOutboundDialerResultSummaryView extends Migration
{
    use HasRCConnection;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vw_ecco_outbound_dialer_result_summary')) {
            DB::statement("
                CREATE OR REPLACE VIEW vw_ecco_outbound_dialer_result_summary AS
                SELECT
                    lef.agent_id,
                    TRIM(lef.agent_first_name + ' ' + lef.agent_last_name) AS agent_name,
                    CONVERT(DATE, lef.call_start) AS date,
                    lef.dial_group_name AS dial_group,
                    LEFT(TRIM(lef.dial_group_name), 3) AS dial_group_prefix,
                    'outbound' AS queue,
                    lef.agent_disposition,
                    lef.duration,
                    lef.on_hold AS sec_on_hold,
                    lef.agent_wait_time,
                    lef.agent_wrap_time,
                    1 AS calls,
                    b.contacts,
                    b.sales,
                    lef.lead_phone,
                    lef.recording_url
                FROM
                    dbo.DIALER_RESULT_DOWNLOAD AS lef
                    INNER JOIN dbo.ecco_dispositions AS b ON b.name = lef.agent_disposition
                WHERE
                    (lef.agent_disposition NOT LIKE '')
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
        DROP VIEW IF EXIST vw_ecco_outbound_dialer_result_summary
        ');
    }
}
