<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 12:40 pm.
 * @license Apache-2.0
 */

namespace App\Console\Commands;

use App\Rating;
use Illuminate\Console\Command;

/**
 * Class IMDbRatingsCommand.
 */
class IMDbRatingsCommand extends Command
{
    protected $name = 'imdb:ratings';
    protected $signature = 'imdb:ratings';

    private $ratingFile;
    private $tsvFile;

    public function __construct()
    {
        parent::__construct();

        $this->ratingFile = storage_path('ratings.gz');
        $this->tsvFile = storage_path('ratings.tsv');
    }

    public function handle(): void
    {
        ini_set('memory_limit', '-1');

        $start = microtime(true);

        $this->deleteFiles();
        $this->downloadFile();
        $this->unzipFile();
        $this->readAndParseTSV();

        $this->comment('Done. Time Taken: ' . microtime(true) - $start);
    }

    private function readAndParseTSV(): void
    {
        $fileObject = new \SplFileObject($this->tsvFile);

        $this->info('Lines to process: ' . count(file($this->tsvFile)));
        $i = 0;

        while (!$fileObject->eof()) {
            $line = $fileObject->fgetcsv("\t");
            $this->info('Processing entry number: ' . $i++);

            if ($line[0] !== 'tconst') {
                Rating::query()->updateOrCreate(['imdbID' => $line[0]], [
                    'imdbID' => $line[0],
                    'rating' => $line[1] ?? 0,
                    'votes' => $line[2] ?? 0,
                ]);
            }
        }
    }

    private function downloadFile(): void
    {
        $this->comment('Downloading archive...');
        $ratingsFileHandle = fopen($this->ratingFile, 'wb');
        $ratingsResponse = file_get_contents('https://datasets.imdbws.com/title.ratings.tsv.gz');

        fwrite($ratingsFileHandle, $ratingsResponse);
        fclose($ratingsFileHandle);

        $this->info('Download complete: ' . $this->ratingFile);
    }

    private function deleteFiles(): void
    {
        $this->comment('Deleting existing files...');
        if (file_exists($this->ratingFile)) {
            unlink($this->ratingFile);
            $this->comment('Deleted gzip file.');
        }
        if (file_exists($this->tsvFile)) {
            unlink($this->tsvFile);
            $this->comment('Deleted TSV file.');
        }
    }

    private function unzipFile(): void
    {
        $this->comment('Unzipping archive...');
        // Raising this value may increase performance
        $buffer_size = 4096; // read 4kb at a time

        // Open our files (in binary mode)
        $file = gzopen($this->ratingFile, 'rb');
        $out_file = fopen($this->tsvFile, 'wb');

        // Keep repeating until the end of the input file
        while (!gzeof($file)) {
            fwrite($out_file, gzread($file, $buffer_size));
        }

        // Files are done, close files
        fclose($out_file);
        gzclose($file);

        $this->info('Unzipped file...' . $this->tsvFile);
    }
}
