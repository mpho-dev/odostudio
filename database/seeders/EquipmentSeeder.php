<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use App\Models\EquipmentItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cameras', 'slug' => 'cameras', 'icon' => 'camera', 'description' => 'Digital and cinema cameras'],
            ['name' => 'Lenses', 'slug' => 'lenses', 'icon' => 'aperture', 'description' => 'Prime and zoom lenses'],
            ['name' => 'Lighting', 'slug' => 'lighting', 'icon' => 'lightbulb', 'description' => 'LED panels, strobes, and modifiers'],
            ['name' => 'Audio', 'slug' => 'audio', 'icon' => 'microphone', 'description' => 'Microphones and audio recorders'],
            ['name' => 'Support', 'slug' => 'support', 'icon' => 'tripod', 'description' => 'Tripods, gimbals, and sliders'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => 'box', 'description' => 'Batteries, memory cards, and cables'],
        ];

        foreach ($categories as $category) {
            EquipmentCategory::create($category);
        }

        $cameraCategory = EquipmentCategory::where('slug', 'cameras')->first();
        $lensCategory = EquipmentCategory::where('slug', 'lenses')->first();
        $lightingCategory = EquipmentCategory::where('slug', 'lighting')->first();

        $items = [
            [
                'category_id' => $cameraCategory->id,
                'name' => 'Sony A7 IV',
                'model' => 'ILCE-7M4',
                'serial_number' => 'SN12345678',
                'sku' => 'CAM-SON-A74-001',
                'purchase_price' => 2498.00,
                'specifications' => ['sensor' => '33MP Full Frame', 'mount' => 'E-mount', 'weight' => '658g'],
                'storage_location' => 'Cabinet A, Shelf 1',
            ],
            [
                'category_id' => $cameraCategory->id,
                'name' => 'Sony A7S III',
                'model' => 'ILCE-7SM3',
                'serial_number' => 'SN87654321',
                'sku' => 'CAM-SON-A7S3-001',
                'purchase_price' => 3498.00,
                'specifications' => ['sensor' => '12MP Full Frame', 'mount' => 'E-mount', 'video' => '4K 120p'],
                'storage_location' => 'Cabinet A, Shelf 1',
            ],
            [
                'category_id' => $lensCategory->id,
                'name' => 'Sony 24-70mm f/2.8 GM II',
                'model' => 'SEL2470GM2',
                'serial_number' => 'SN24680135',
                'sku' => 'LENS-SON-2470-001',
                'purchase_price' => 2298.00,
                'specifications' => ['focal' => '24-70mm', 'aperture' => 'f/2.8', 'mount' => 'E-mount'],
                'storage_location' => 'Cabinet B, Shelf 1',
            ],
            [
                'category_id' => $lensCategory->id,
                'name' => 'Sony 85mm f/1.4 GM',
                'model' => 'SEL85F14GM',
                'serial_number' => 'SN13579246',
                'sku' => 'LENS-SON-85-001',
                'purchase_price' => 1798.00,
                'specifications' => ['focal' => '85mm', 'aperture' => 'f/1.4', 'mount' => 'E-mount'],
                'storage_location' => 'Cabinet B, Shelf 2',
            ],
            [
                'category_id' => $lightingCategory->id,
                'name' => 'Aputure 120D II',
                'model' => 'AL-120DII',
                'serial_number' => 'SN98765432',
                'sku' => 'LIGHT-APU-120D-001',
                'purchase_price' => 745.00,
                'specifications' => ['power' => '120W', 'color_temp' => '5500K', 'output' => '135000 lux'],
                'storage_location' => 'Lighting Rack, Position 1',
            ],
        ];

        foreach ($items as $item) {
            EquipmentItem::create(array_merge($item, [
                'condition' => 'excellent',
                'status' => 'available',
                'purchase_date' => now()->subMonths(rand(6, 24)),
            ]));
        }
    }
}
