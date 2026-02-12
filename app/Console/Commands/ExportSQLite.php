<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportSQLite extends Command
{
    protected $signature = 'sqlite:export {file=export.txt} {table?}';
    protected $description = 'Exporta os dados do banco SQLite como INSERTs SQL em um arquivo .txt';

    public function handle()
    {
        DB::statement("PRAGMA encoding = 'UTF-8'");
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $file = $this->argument('file');

        $output = "";

        foreach ($tables as $table) {
            if ($this->argument('table') && $this->argument('table') !== $table->name) {
                continue;
            }
            $this->info("Exportando tabela: {$table->name}");
            $check = $this->argument('table') && auth()->check() ? true : false;
            $rows = $check ? DB::table($table->name)->where('user_id', auth()->id())->get() : DB::table($table->name)->get();
            foreach ($rows as $row) {
                $columns = implode(', ', array_map(fn($col) => "`$col`", array_keys((array) $row)));
                $values = implode(', ', array_map(fn($val) => "'" . addslashes($val) . "'", (array) $row));
                $output .= "INSERT INTO `{$table->name}` ({$columns}) VALUES ({$values});\n";
            }
        }

        File::put(storage_path("app/public/{$file}"), "\xEF\xBB\xBF" . $output);
        $this->info("Banco de dados exportado com sucesso para: storage/$file");
    }
}
