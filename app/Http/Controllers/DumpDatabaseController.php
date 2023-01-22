<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Spatie\DbDumper\Databases\Sqlite;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DumpDatabaseController extends Controller
{
    public function index(): BinaryFileResponse
    {
        $pathToDatabaseFile = storage_path('app/database.sqlite');
        $pathToFile = storage_path('app/dump.sql');

        Sqlite::create()
            ->setDbName($pathToDatabaseFile)
            ->dumpToFile($pathToFile);

        return response()->download($pathToFile);
    }
}
