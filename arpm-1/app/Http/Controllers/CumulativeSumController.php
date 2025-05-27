<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;

class CumulativeSumController extends Controller
{
    protected $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    // Generate random uniform table
    protected function generateUniformTable($rows, $cols)
    {
        $table = [];
        for ($i = 0; $i < $rows; $i++) {
            $table[$i] = [];
            for ($j = 0; $j < $cols; $j++) {
                $table[$i][$j] = mt_rand() / mt_getrandmax(); // Generate random values [0, 1]
            }
        }
        return $table;
    }

    // Calculate cumulative sums
    protected function calculateCumulativeSums($table)
    {
        $cumulativeSums = [];
        foreach ($table as $row) {
            $cumulative = 0;
            $cumulativeRow = [];
            foreach ($row as $value) {
                $cumulative += $value;
                $cumulativeRow[] = $cumulative;
            }
            $cumulativeSums[] = $cumulativeRow;
        }
        return $cumulativeSums;
    }

    // Generate 10 x 52 random values table and calculate cumulative sums
    public function generateAndUploadData()
    {
        $rows = 10;
        $cols = 52;
        $table = $this->generateUniformTable($rows, $cols);
        $cumulativeSums = $this->calculateCumulativeSums($table);

        // Google Sheet ID and range
        $spreadsheetId = config('google.spreadsheet_id');
        $range = 'Sheet1!A1:AZ10';

        // Upload data to Google Sheets
        $this->googleSheetsService->updateSheet($spreadsheetId, $range, $cumulativeSums);

        // Create chart in Google Sheets
        $this->googleSheetsService->createChart($spreadsheetId);

        return response()->json(['message' => 'Data and chart created successfully.']);
    }
}
