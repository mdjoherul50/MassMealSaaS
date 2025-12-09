<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Conversations table
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['group', 'private'])->default('private');
            $table->string('name')->nullable(); // For group chats
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'type']);
            $table->index('last_message_at');
        });

        // Conversation participants
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_admin')->default(false); // For group chat admins
            $table->boolean('muted')->default(false);
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id']);
            $table->index('user_id');
        });

        // Messages table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_type')->nullable(); // image, file, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id', 'created_at']);
            $table->index('user_id');
        });

        // Message reads (for group chats to track who read what)
        Schema::create('message_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->useCurrent();

            $table->unique(['message_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reads');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};
