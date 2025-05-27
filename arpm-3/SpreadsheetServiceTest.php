<?php

namespace Tests\Unit\Services;

use App\Jobs\ProcessProductImage;
use App\Models\Product;
use App\Services\SpreadsheetService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SpreadsheetServiceTest extends TestCase
{
    private SpreadsheetService $service;
    private $importer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->importer = $this->mock('importer');
        $this->service = new SpreadsheetService();
    }

    public function test_process_spreadsheet_creates_valid_products_and_dispatches_jobs()
    {
        // Arrange
        $validData = [
            [
                'product_code' => 'TEST001',
                'quantity' => 5
            ],
            [
                'product_code' => 'TEST002',
                'quantity' => 10
            ]
        ];

        $this->importer->shouldReceive('import')
            ->once()
            ->with('test.xlsx')
            ->andReturn($validData);

        Bus::fake();

        // Act
        $this->service->processSpreadsheet('test.xlsx');

        // Assert
        $this->assertDatabaseHas('products', [
            'code' => 'TEST001',
            'quantity' => 5
        ]);
        $this->assertDatabaseHas('products', [
            'code' => 'TEST002',
            'quantity' => 10
        ]);

        Bus::assertDispatched(ProcessProductImage::class, 2);
    }

    public function test_process_spreadsheet_skips_invalid_products()
    {
        // Arrange
        $mixedData = [
            [
                'product_code' => 'TEST001',
                'quantity' => 5
            ],
            [
                'product_code' => 'TEST001', // Duplicate code
                'quantity' => 10
            ],
            [
                'product_code' => 'TEST002',
                'quantity' => -1 // Invalid quantity
            ]
        ];

        $this->importer->shouldReceive('import')
            ->once()
            ->with('test.xlsx')
            ->andReturn($mixedData);

        Bus::fake();

        // Act
        $this->service->processSpreadsheet('test.xlsx');

        // Assert
        $this->assertDatabaseHas('products', [
            'code' => 'TEST001',
            'quantity' => 5
        ]);
        $this->assertDatabaseCount('products', 1);
        Bus::assertDispatched(ProcessProductImage::class, 1);
    }
}