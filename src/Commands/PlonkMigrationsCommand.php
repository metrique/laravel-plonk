<?php

namespace Metrique\Plonk\Commands;

use Illuminate\Console\Command;

class PlonkMigrationsCommand extends Command
{
    use \Metrique\Plonk\Commands\Traits\PlonkCommandOutputTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metrique:migrate-plonk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create metrique/plonk migration files.';

    /**
     * Path to database migrations in your laravel app.
     *
     * @var string
     */
    protected $migrationPath = 'database/migrations';

    /**
     * List of migrations to be processed in order.
     *
     * @var array
     */
    protected $migrations = [
        'create_plonk_assets',
        'create_plonk_variations',
        'fk_plonk_variations'
    ];

    /**
     * Track any migration files that are created.
     *
     * @var array
     */
    protected $migrationsOutput = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        foreach ($this->migrations as $key => $value) {
            $migration = [
                'view' => 'metrique-plonk::migrations.' . $value,
                'file' => base_path($this->migrationPath) . '/' . date('Y_m_d_His') . '_' . $value . '.php',
            ];

            if($this->createMigration($migration) === false)
            {
                $this->output(self::$CONSOLE_ERROR, 'Could not create migration. (' . $migration['file'] . ')');
            }
        }

        // To do, roll back migrations if any failed..
    }

    public function createMigration($migration)
    {
        array_push($this->migrationsOutput, $migration['file']);

        if(file_exists($migration['file']))
        {
            return false;
        }

        $fh = fopen($migration['file'], 'x');

        if($fh === false)
        {
            return false;
        }

        if(fwrite($fh, view()->make($migration['view'])->render()) === false)
        {
            return false;
        }

        fclose($fh);

        $this->output(self::$CONSOLE_INFO, 'Created migration. (' . $migration['file'] . ')');
        
        array_pop($this->migrationsOutput);
        
        return true;
    }
}
