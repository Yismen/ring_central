<?php

use Illuminate\Database\Migrations\Migration;
use Dainsys\RingCentral\Traits\HasRCConnection;

class CreateVwEccoCombinedDialerResultSummaryView extends Migration
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
            DB::statement('
                CREATE OR REPLACE VIEW vw_ecco_combined_dialer_result_summary AS
                SELECT
                    agent_id,
                    agent_name,
                    date,
                    dial_group_name AS dial_group,
                    dial_group_prefix,
                    queue,
                    agent_disposition,
                    duration,
                    sec_on_hold,
                    agent_wait_time,
                    agent_wrap_time,
                    calls,
                    contacts,
                    sales,
                    lead_phone,
                    recording_url
                FROM
                    (
                        SELECT
                            agent_id,
                            agent_name,
                            date,
                            dial_group_name,
                            dial_group_prefix,
                            queue,
                            agent_disposition,
                            duration,
                            sec_on_hold,
                            agent_wait_time,
                            agent_wrap_time,
                            calls,
                            contacts,
                            sales,
                            lead_phone,
                            recording_url
                        FROM
                            dbo.vw_ecco_inbound_dialer_result_summary
                        UNION ALL
                        SELECT
                            agent_id,
                            agent_name,
                            date,
                            dial_group,
                            dial_group_prefix,
                            queue,
                            agent_disposition,
                            duration,
                            sec_on_hold,
                            agent_wait_time,
                            agent_wrap_time,
                            calls,
                            contacts,
                            sales,
                            lead_phone,
                            recording_url
                        FROM
                            dbo.vw_ecco_outbound_dialer_result_summary
                    ) AS unified_views
            ');
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
        DROP VIEW IF EXIST vw_ecco_combined_dialer_result_summary
        ');
    }
}
