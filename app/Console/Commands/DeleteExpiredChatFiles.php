<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredChatFiles extends Command
{
    protected $signature = 'chat:delete-expired-files';
    protected $description = 'Delete chat files that have expired (older than 15 days)';

    public function handle()
    {
        $this->info('Starting to delete expired chat files...');

        $expiredMessages = Message::whereNotNull('attachment_path')
            ->whereNotNull('file_expires_at')
            ->where('file_expires_at', '<', now())
            ->get();

        $deletedCount = 0;

        foreach ($expiredMessages as $message) {
            if ($message->attachment_path && Storage::disk('public')->exists($message->attachment_path)) {
                Storage::disk('public')->delete($message->attachment_path);
                $deletedCount++;
                $this->info("Deleted: {$message->attachment_path}");
            }
        }

        $this->info("Total files deleted: {$deletedCount}");

        return Command::SUCCESS;
    }
}
