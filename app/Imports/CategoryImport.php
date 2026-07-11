<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip if name is empty
        if (!isset($row['name']) || empty($row['name'])) {
            return null;
        }

        return new Category([
            'name'        => $row['name'],
            'description' => $row['description'] ?? null,
        ]);
    }
}
