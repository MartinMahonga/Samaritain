<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visit_passes', function (Blueprint $table) {
            $table->unsignedInteger('allowed_visits')->default(3)->after('amount');
            $table->unsignedInteger('remaining_visits')->default(3)->after('allowed_visits');
        });

        $visitPasses = DB::table('visit_passes')->select('id', 'status')->get();

        foreach ($visitPasses as $visitPass) {
            $scanCount = DB::table('pass_scans')->where('visit_pass_id', $visitPass->id)->count();
            $remainingVisits = max(0, 3 - $scanCount);

            DB::table('visit_passes')->where('id', $visitPass->id)->update([
                'allowed_visits' => 3,
                'remaining_visits' => $remainingVisits,
                'status' => $remainingVisits <= 0 && $visitPass->status === 'active' ? 'used' : $visitPass->status,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('visit_passes', function (Blueprint $table) {
            $table->dropColumn(['allowed_visits', 'remaining_visits']);
        });
    }
};
