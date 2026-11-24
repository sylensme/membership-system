<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('membership_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('fee', 10, 2)->default(0);
            $table->unsignedSmallInteger('validity_months')->default(12);
            $table->text('benefits')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $now = now();
        foreach (['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $name) {
            DB::table('membership_categories')->insert(['name' => $name, 'created_at' => $now, 'updated_at' => $now]);
        }

        foreach ([
            ['name' => 'Basic', 'fee' => 500, 'benefits' => 'Member directory access'],
            ['name' => 'Silver', 'fee' => 750, 'benefits' => 'Directory and event access'],
            ['name' => 'Gold', 'fee' => 1200, 'benefits' => 'Priority events and partner offers'],
            ['name' => 'Platinum', 'fee' => 2500, 'benefits' => 'All benefits and priority support'],
        ] as $level) {
            DB::table('membership_levels')->insert($level + ['validity_months' => 12, 'created_at' => $now, 'updated_at' => $now]);
        }

        Schema::table('members', function (Blueprint $table): void {
            $table->string('membership_number')->nullable()->unique();
            $table->foreignId('membership_category_id')->nullable()->constrained('membership_categories')->restrictOnDelete();
            $table->foreignId('membership_level_id')->nullable()->constrained('membership_levels')->restrictOnDelete();
        });

        $categories = DB::table('membership_categories')->pluck('id', 'name');
        $levels = DB::table('membership_levels')->pluck('id', 'name');
        foreach (DB::table('members')->orderBy('id')->get() as $member) {
            DB::table('members')->where('id', $member->id)->update([
                'membership_number' => 'MEM-'.str_pad((string) $member->id, 6, '0', STR_PAD_LEFT),
                'membership_category_id' => $categories[$member->category] ?? $categories['Regular'],
                'membership_level_id' => $levels[$member->level] ?? $levels['Basic'],
            ]);
        }

        Schema::table('members', function (Blueprint $table): void {
            $table->dropIndex(['category']);
            $table->dropIndex(['level']);
            $table->dropColumn(['category', 'level']);
            $table->unsignedBigInteger('membership_category_id')->nullable(false)->change();
            $table->unsignedBigInteger('membership_level_id')->nullable(false)->change();
        });

        Schema::create('membership_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_category_id')->constrained('membership_categories')->restrictOnDelete();
            $table->foreignId('membership_level_id')->constrained('membership_levels')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reference_number')->unique();
            $table->string('type', 20)->index();
            $table->string('status', 20)->default('Pending')->index();
            $table->string('category_name');
            $table->string('level_name');
            $table->decimal('amount', 10, 2);
            $table->unsignedSmallInteger('validity_months');
            $table->text('benefits')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('membership_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reference_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('method', 30);
            $table->timestamp('paid_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('membership_transactions');
        Schema::table('members', function (Blueprint $table): void {
            $table->string('category', 100)->nullable();
            $table->string('level', 100)->nullable();
        });
        foreach (DB::table('members')->join('membership_categories', 'members.membership_category_id', '=', 'membership_categories.id')->join('membership_levels', 'members.membership_level_id', '=', 'membership_levels.id')->select('members.id', 'membership_categories.name as category_name', 'membership_levels.name as level_name')->get() as $member) {
            DB::table('members')->where('id', $member->id)->update(['category' => $member->category_name, 'level' => $member->level_name]);
        }
        Schema::table('members', function (Blueprint $table): void {
            $table->string('category', 100)->nullable(false)->change();
            $table->string('level', 100)->nullable(false)->change();
            $table->dropForeign(['membership_category_id']);
            $table->dropForeign(['membership_level_id']);
            $table->dropUnique(['membership_number']);
            $table->dropColumn(['membership_number', 'membership_category_id', 'membership_level_id']);
            $table->index('category');
            $table->index('level');
        });
        Schema::dropIfExists('membership_levels');
        Schema::dropIfExists('membership_categories');
    }
};
