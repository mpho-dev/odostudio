<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use App\Models\EquipmentImage;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class EquipmentItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = EquipmentItem::with(['category', 'primaryImage'])
            ->withCount('checkouts');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = EquipmentCategory::all();

        return view('admin.equipment.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = EquipmentCategory::orderBy('sort_order')->get();

        return view('admin.equipment.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|unique:equipment_items',
            'sku' => 'nullable|string|unique:equipment_items',
            'description' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|in:excellent,good,fair,poor',
            'storage_location' => 'nullable|string',
            'specifications' => 'nullable|array',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $item = EquipmentItem::create($validated);

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver);

            foreach ($request->file('images') as $index => $image) {
                $img = $manager->read($image);
                $encoded = $img->encode(new WebpEncoder(quality: 85));
                $path = 'equipment/'.$item->id.'_'.time().'_'.$index.'.webp';
                Storage::disk('public')->put($path, (string) $encoded);

                $item->images()->create([
                    'image_path' => '/storage/'.$path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.equipment.items.index')
            ->with('success', 'Equipment item added.');
    }

    public function show(EquipmentItem $item)
    {
        $item->load(['category', 'images', 'checkouts' => function ($q) {
            $q->latest()->limit(10);
        }, 'checkouts.booking', 'checkouts.user']);

        return view('admin.equipment.items.show', compact('item'));
    }

    public function edit(EquipmentItem $item)
    {
        $categories = EquipmentCategory::orderBy('sort_order')->get();

        return view('admin.equipment.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, EquipmentItem $item)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|unique:equipment_items,serial_number,'.$item->id,
            'sku' => 'nullable|string|unique:equipment_items,sku,'.$item->id,
            'description' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|in:excellent,good,fair,poor',
            'status' => 'required|in:available,maintenance,retired',
            'storage_location' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date',
            'notes' => 'nullable|string',
            'specifications' => 'nullable|array',
            'images.*' => 'nullable|image|max:5120',
            'remove_images' => 'nullable|array',
        ]);

        $item->update($validated);

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = EquipmentImage::find($imageId);
                if ($image && $image->equipment_item_id === $item->id) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $image->image_path));
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver);
            $currentCount = $item->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $img = $manager->read($image);
                $encoded = $img->encode(new WebpEncoder(quality: 85));
                $path = 'equipment/'.$item->id.'_'.time().'_'.$index.'.webp';
                Storage::disk('public')->put($path, (string) $encoded);

                $item->images()->create([
                    'image_path' => '/storage/'.$path,
                    'is_primary' => $currentCount === 0 && $index === 0,
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.equipment.items.show', $item)
            ->with('success', 'Equipment updated.');
    }

    public function destroy(EquipmentItem $item)
    {
        $item->delete();

        return redirect()->route('admin.equipment.items.index')
            ->with('success', 'Equipment archived.');
    }
}
