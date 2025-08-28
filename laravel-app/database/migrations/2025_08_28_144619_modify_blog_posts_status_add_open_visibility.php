<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyBlogPostsStatusAddOpenVisibility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'open'])->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published'])->default('draft');
        });
    }
}
