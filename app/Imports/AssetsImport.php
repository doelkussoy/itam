<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class AssetsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip if asset_code or name is empty
        if (empty($row['asset_code']) || empty($row['name'])) {
            return null;
        }

        // Find or create category
        $categoryId = null;
        if (!empty($row['category'])) {
            $category = Category::firstOrCreate(['name' => $row['category']]);
            $categoryId = $category->id;
        }

        // Find or create brand
        $brandId = null;
        if (!empty($row['brand'])) {
            $brand = Brand::firstOrCreate(['name' => $row['brand']]);
            $brandId = $brand->id;
        }

        // Find or create location
        $locationId = null;
        if (!empty($row['location'])) {
            $location = Location::firstOrCreate(['name' => $row['location']]);
            $locationId = $location->id;
        }

        // Standardize status
        $status = 'Available';
        $validStatuses = ['Available', 'Assigned', 'Maintenance', 'Retired', 'Missing'];
        if (!empty($row['status'])) {
            $rowStatus = ucfirst(strtolower(trim($row['status'])));
            if (in_array($rowStatus, $validStatuses)) {
                $status = $rowStatus;
            }
        }

        // Ensure date_received is a valid format or null
        $dateReceived = !empty($row['date_received']) ? date('Y-m-d', strtotime($row['date_received'])) : null;

        // Check if asset already exists by asset_tag to update instead of duplicate
        $asset = Asset::where('asset_tag', $row['asset_code'])->first();

        if ($asset) {
            $asset->update([
                'name' => $row['name'],
                'serial_number' => $row['serial_number'] ?? null,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'location_id' => $locationId,
                'date_received' => $dateReceived,
                'delivery_order_number' => $row['delivery_order_number'] ?? null,
                'warranty_months' => $row['warranty_months'] ?? 0,
                'status' => $status,
                'notes' => $row['notes'] ?? null,
            ]);
            return null; // Return null because we manually updated
        }

        return new Asset([
            'asset_tag' => $row['asset_code'],
            'name' => $row['name'],
            'serial_number' => $row['serial_number'] ?? null,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'location_id' => $locationId,
            'date_received' => $dateReceived,
            'delivery_order_number' => $row['delivery_order_number'] ?? null,
            'warranty_months' => $row['warranty_months'] ?? 0,
            'status' => $status,
            'notes' => $row['notes'] ?? null,
        ]);
    }
}
