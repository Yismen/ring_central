<?php

use Illuminate\Database\Migrations\Migration;
use Dainsys\RingCentral\Traits\HasRCConnection;

class CreateVwEccoAgentSessionRawSummaryView extends Migration
{
    use HasRCConnection;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vw_ecco_agent_session_raw_summary')) {
            DB::statement("
                CREATE OR REPLACE VIEW vw_ecco_agent_session_raw_summary
                AS
                SELECT
                    [Agent ID] AS agent_id,
                    TRIM([First Name] + ' ' + [Last Name]) AS agent_name,
                    Team,
                    CONVERT(date, [Login DTS]) AS date,
                    [Dial Group] AS dial_group,
                    LEFT(TRIM([Dial Group]), 3) AS dial_group_prefix,
                    CONVERT(FLOAT, [Login Time (min)]) AS login_time_mins,
                    CONVERT(FLOAT, [Work Time (min)]) AS work_time_mins,
                    CONVERT(FLOAT, [Talk Time (min)]) AS talk_time_mins,
                    CONVERT(FLOAT, [Off Hook Time (min)]) AS off_hook_time_mins,
                    CONVERT(FLOAT, [Break Time (min)]) AS break_time_mins,
                    CONVERT(FLOAT, [Away Time (min)]) AS away_time_mins,
                    CONVERT(FLOAT, [Lunch Time (min)]) AS lunch_time_mins,
                    CONVERT(FLOAT, [Training Time (min)]) AS training_time_mins,
                    CONVERT(
                        FLOAT,
                        [Pending Disp Time (min)]
                    ) AS pending_disp_time_mins,
                    CONVERT(FLOAT, [Time On Hold (min)]) AS time_on_hold_mins,
                    CONVERT(FLOAT, [Ring Time (min)]) AS ring_time_mins,
                    CONVERT(FLOAT, [EngagedTime (min)]) AS engaged_time_mins,
                    CONVERT(FLOAT, [RNA Time (min)]) AS rna_time_mins,
                    CONVERT(FLOAT, [Available Time (min)]) AS available_time_mins,
                    CONVERT(FLOAT, Presented) AS presented,
                    CONVERT(FLOAT, Accepted) AS accepted,
                    CONVERT(FLOAT, [Calls Placed On Hold]) AS calls_placed_on_hold,
                    CONVERT(FLOAT, [Monitoring Sessions]) AS monitoring_sessions
                FROM
                    dbo.Agent_Session_Report_Raw
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
            DROP VIEW IF EXIST vw_ecco_agent_session_raw_summary
        ');
    }
}
