<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Offer;
use Artisan;
use Validator;
use Storage;
use File;
use App\Stats;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importuje dane z pliku csv do bazy danych. W momencie importu dane są filtrowane i tylko wybrane rekordy są zapisywane w bazie - reszta, która nie spełnia kryteriów trafia do osobnego pliku w oryginalnej formie';

    public $linesInSourceFile;
    public $importedLines = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }



    public function checkFile($file)
    {
        if(!isset($file)) {

            $this->error("W poleceniu nie podano nazwy pliku.");
            die();
        }

        if(!file_exists(public_path()."/".$file))
        {
            $this->error("Plik nie istnieje.");
            die();            
        }
    }

    public function createTablesInDB()
    {
        Artisan::call('migrate');
    }

    // public function openFile($name)
    // {
    //     $filePath = public_path()."/".$name;

    //     if(file_exists($filePath)) $file = fopen($filePath, "r");
    //     else return false;

    //     return $file;
    // }

    public function reject($csvLine) 
    {
        $csvLine = str_replace("\n", "", $csvLine); 
        Storage::disk('local')->append('rejected.csv', $csvLine);
    }

    public function createStats()
    {
        $statsDate = new Stats;
        $statsDate->type = "lastImportDate";
        $statsDate->value = date("Y-m-d");
        $statsDate->save();

        $statsImported = new Stats;
        $statsImported->type = "imported";
        $statsImported->value = $this->importedLines;
        $statsImported->save();        

        $statsTotal = new Stats;
        $statsTotal->type = "total";
        $statsTotal->value = $this->linesInSourceFile;
        $statsTotal->save();

        $statsPercent = new Stats;
        $statsPercent->type = "percent";
        $statsPercent->value = round(($statsImported->value/$statsTotal->value)*100);
        $statsPercent->save();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileName = $this->argument('file');

        $this->checkFile($fileName);
        $this->createTablesInDB();

        $pathToSource = public_path()."/".$fileName;
        $source = fopen($pathToSource, "r");
        $this->linesInSourceFile = count(file($pathToSource))-1;
        
        fgetcsv($source);

        $bar = $this->output->createProgressBar($this->linesInSourceFile);

        while($csvLine = fgets($source)) {

            $csvLineAsArray = str_getcsv($csvLine, ';', '"');

            $record = new Offer;

            $model = [];

            $model['kod_produktu']   = strval($csvLineAsArray[1]);
            $model['ilosc']          = $csvLineAsArray[6];
            $model['rok_produkcji']  = substr(trim($csvLineAsArray[7], '"'), -4, 4);
            $model['cena']           = $csvLineAsArray[4];

            $record->fill($model);

            $validator = Validator::make($model, $record->rules);

            if ($validator->fails()) {$this->reject($csvLine); }
            else {
                $this->importedLines++;
                $record->save();
            }
            $bar->advance();    
        }

        $bar->finish();
        fclose($source);

        $this->createStats();
          
    }
}
