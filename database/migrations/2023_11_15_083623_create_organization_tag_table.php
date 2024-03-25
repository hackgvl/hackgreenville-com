<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('org_tag', function (Blueprint $table) {
            $table->foreignIdFor(App\Models\Org::class);
            $table->foreignIdFor(App\Models\Tag::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_tag');
    }
};
