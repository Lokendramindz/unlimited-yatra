<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('is_admin')->default(false)->after('password');
        });

        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->enum('type', ['domestic', 'international'])->default('domestic');
            $t->enum('region', ['east', 'west', 'north', 'south'])->nullable();
            $t->string('image')->nullable();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('yatras', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('destination');
            $t->unsignedSmallInteger('duration_days')->default(1);
            $t->unsignedSmallInteger('duration_nights')->default(0);
            $t->decimal('price', 10, 2)->nullable();
            $t->string('image')->nullable();
            $t->string('short_description', 500)->nullable();
            $t->longText('description')->nullable();
            $t->text('highlights')->nullable();
            $t->text('itinerary')->nullable();
            $t->text('inclusions')->nullable();
            $t->text('exclusions')->nullable();
            $t->string('best_time')->nullable();
            $t->json('gallery')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_special')->default(false);
            $t->string('special_type')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('banners', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('image');
            $t->string('button_text')->nullable();
            $t->string('button_link')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('guides', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('designation')->nullable();
            $t->string('image')->nullable();
            $t->text('bio')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('image')->nullable();
            $t->string('excerpt', 500)->nullable();
            $t->longText('body')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });

        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('question');
            $t->text('answer');
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('features', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('icon', 16)->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('fleets', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('image')->nullable();
            $t->string('capacity')->nullable();
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('affiliations', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('image')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('enquiries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('yatra_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('email')->nullable();
            $t->string('phone');
            $t->unsignedSmallInteger('travellers')->nullable();
            $t->date('travel_date')->nullable();
            $t->text('message')->nullable();
            $t->enum('status', ['new', 'contacted', 'converted', 'closed'])->default('new');
            $t->timestamps();
        });

        Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->timestamps();
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('subtitle')->nullable();
            $t->string('image')->nullable();
            $t->longText('content')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->text('value')->nullable();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'pages', 'subscribers', 'enquiries', 'affiliations', 'fleets', 'features', 'faqs', 'posts', 'guides', 'banners', 'yatras', 'categories'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_admin'));
    }
};
