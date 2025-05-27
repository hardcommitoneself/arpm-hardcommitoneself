<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\Request as GoogleSheetsRequest;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = $this->getClient();
        $this->service = new Sheets($this->client);
    }

    protected function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Laravel Google Sheets API');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('credentials.json'));
        $client->setAccessType('offline');

        return $client;
    }

    public function updateSheet($spreadsheetId, $range, $values)
    {
        $body = new ValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        return $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }

    public function createChart($spreadsheetId)
    {
        $series = [];
        $sheetId = 0;

        for($i = 0; $i < 10; $i++) {
            $series[] = [
                'series' => [
                    'sourceRange' => [
                        'sources' => [
                            [
                                'sheetId' => 0,
                                'startRowIndex' => $i,
                                'endRowIndex' => $i + 1,
                                'startColumnIndex' => 0,
                                'endColumnIndex' => 52
                            ]
                        ]
                    ]
                ]
            ];
        }

        $chartRequest = new GoogleSheetsRequest([
            'addChart' => [
                'chart' => [
                    'spec' => [
                        'title' => 'Cumulative Sum per Individual',
                        'basicChart' => [
                            'chartType' => 'LINE',
                            'legendPosition' => 'BOTTOM_LEGEND',
                            'axis' => [
                                [
                                    'position' => 'BOTTOM_AXIS',
                                    'title' => 'Week'
                                ],
                                [
                                    'position' => 'LEFT_AXIS',
                                    'title' => 'Cumulative Sum'
                                ]
                            ],
                            'series' => $series
                        ]
                    ],
                    'position' => [
                        'newSheet' => true // The chart will be added in a new sheet
                    ]
                ]
            ]
        ]);
    
        // Prepare batch update request
        $batchUpdateRequest = new BatchUpdateSpreadsheetRequest([
            'requests' => [$chartRequest]
        ]);
    
        // Execute the batch update request
        return $this->service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
    }
}
