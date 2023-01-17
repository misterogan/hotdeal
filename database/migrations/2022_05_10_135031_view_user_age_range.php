<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewUserAgeRange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::statement("
                        select t.age_group,count(*) AS age_count
                            from
                            (
                                SELECT
                                    CASE WHEN EXTRACT(YEAR from AGE(NOW(), dob)) BETWEEN 20 AND 25
                                         THEN '20-25'
                                         WHEN  EXTRACT(YEAR from AGE(NOW(), dob)) BETWEEN 26 AND 35
                                         THEN '26-35'
                                         WHEN  EXTRACT(YEAR from AGE(NOW(), dob)) BETWEEN 36 AND 45
                                         THEN '36-45'
                                         WHEN  EXTRACT(YEAR from AGE(NOW(), dob)) BETWEEN 46 AND 55
                                         THEN '46-55'
                                         WHEN   EXTRACT(YEAR from AGE(NOW(), dob)) > 55
                                         THEN '46-55'
                                         ELSE 'Other'
                                    END AS age_group
                                FROM users
                            )t
                            GROUP BY t.age_group
                            ORDER BY  t.age_group ASC
         ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
