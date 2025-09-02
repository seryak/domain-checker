<?php

use App\Models\Domain;
use App\Service\DomainNameConverter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('name');
        });

        $domains = Domain::all();
        foreach ($domains as $domain) {
            if (empty($domain->original_name)) {
                $domain->original_name = app(DomainNameConverter::class)->toUnicode($domain->name);
                $domain->save();
            }
        }

        Schema::table('domains', function (Blueprint $table) {
            $table->string('original_name')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('original_name');
        });
    }
};
