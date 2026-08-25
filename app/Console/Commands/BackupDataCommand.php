<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Folder\Folder;
use App\Models\Note\Note;
use App\Models\Task\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDataCommand extends Command
{
    protected $signature = 'data:backup';

    protected $description = 'Export folders, notes and tasks to a JSON file so a snapshot can be committed to git';

    private const BACKUP_PATH = 'database/backups/backup.json';

    public function handle(): int
    {
        $backup = [
            'folders' => Folder::query()->orderBy('id')->get()->toArray(),
            'notes' => Note::query()->orderBy('id')->get()->toArray(),
            'tasks' => Task::query()->orderBy('id')->get()->toArray(),
        ];

        $path = base_path(self::BACKUP_PATH);
        File::ensureDirectoryExists(\dirname($path));
        File::put($path, \json_encode($backup, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE).\PHP_EOL);

        $this->info(\sprintf(
            'Backed up %d folder(s), %d note(s), %d task(s) to %s',
            \count($backup['folders']),
            \count($backup['notes']),
            \count($backup['tasks']),
            self::BACKUP_PATH
        ));

        return self::SUCCESS;
    }
}
