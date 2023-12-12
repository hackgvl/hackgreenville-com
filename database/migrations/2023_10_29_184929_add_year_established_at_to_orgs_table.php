<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orgs', function (Blueprint $table) {
            $table->timestamp('established_at')->nullable()->after('cache');
        });
    }

    public function down(): void
    {
        Schema::table('orgs', function (Blueprint $table) {
            $table->dropColumn('established_at');
        });
    }
};
