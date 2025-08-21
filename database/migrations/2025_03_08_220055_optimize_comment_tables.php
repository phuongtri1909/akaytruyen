<?php

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
        // Tối ưu bảng livechat
        if (Schema::hasTable('livechat')) {
            Schema::table('livechat', function (Blueprint $table) {
                // Thêm index cho các trường thường query
                if (!Schema::hasIndex('livechat', 'livechat_parent_created_index')) {
                    $table->index(['parent_id', 'created_at'], 'livechat_parent_created_index');
                }
                if (!Schema::hasIndex('livechat', 'livechat_pinned_created_index')) {
                    $table->index(['pinned', 'created_at'], 'livechat_pinned_created_index');
                }
                if (!Schema::hasIndex('livechat', 'livechat_user_id_index')) {
                    $table->index('user_id', 'livechat_user_id_index');
                }
            });
        }

        // Tối ưu bảng live_reactions
        if (Schema::hasTable('live_reactions')) {
            Schema::table('live_reactions', function (Blueprint $table) {
                // Thêm index cho các trường thường query
                if (!Schema::hasIndex('live_reactions', 'live_reactions_comment_type_index')) {
                    $table->index(['comment_id', 'type'], 'live_reactions_comment_type_index');
                }
                if (!Schema::hasIndex('live_reactions', 'live_reactions_user_comment_index')) {
                    $table->index(['user_id', 'comment_id'], 'live_reactions_user_comment_index');
                }
                if (!Schema::hasIndex('live_reactions', 'live_reactions_type_index')) {
                    $table->index('type', 'live_reactions_type_index');
                }
            });
        }

        // Tối ưu bảng users (nếu chưa có)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasIndex('users', 'users_ban_comment_index')) {
                    $table->index('ban_comment', 'users_ban_comment_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes cho bảng livechat
        if (Schema::hasTable('livechat')) {
            Schema::table('livechat', function (Blueprint $table) {
                $table->dropIndex('livechat_parent_created_index');
                $table->dropIndex('livechat_pinned_created_index');
                $table->dropIndex('livechat_user_id_index');
            });
        }

        // Drop indexes cho bảng live_reactions
        if (Schema::hasTable('live_reactions')) {
            Schema::table('live_reactions', function (Blueprint $table) {
                $table->dropIndex('live_reactions_comment_type_index');
                $table->dropIndex('live_reactions_user_comment_index');
                $table->dropIndex('live_reactions_type_index');
            });
        }

        // Drop index cho bảng users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_ban_comment_index');
            });
        }
    }
};
