<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use App\Services\HtmlPurifier as PurifierService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class SiteSettingController extends Controller
{
    /**
     * Only authenticated administrators should be able to tweak the narrative
     * and swap out the landing page imagery.  Lock the controller down with
     * middleware so that the route itself can't be hit by unauthorized users.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function edit()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.site', compact('settings'));
    }

    public function update(UpdateSiteSettingRequest $request)
    {
        $validated = $request->validated();

        $data = collect($validated)->except(['hero_bg_image', 'about_portrait_image', 'remove_hero_bg_image', 'remove_about_portrait_image'])->toArray();

        foreach ($data as $key => $value) {
            // Sanitize all text fields that might contain HTML or script tags
            if (in_array($key, ['hero_name', 'hero_title', 'about_title', 'about_body_1', 'about_body_2', 'cta_title', 'cta_subtext'])) {
                $value = PurifierService::clean($value);
            }
            SiteSetting::set($key, $value);
        }

        // Handle hero background image removal
        if ($request->boolean('remove_hero_bg_image')) {
            $heroImagePath = SiteSetting::get('hero_bg_image');
            if ($heroImagePath) {
                $storagePath = str_replace('/storage/', '', $heroImagePath);
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                    Log::info("Hero background image removed: {$storagePath}");
                }
            }
            SiteSetting::set('hero_bg_image', null);
        }

        // Handle about portrait image removal
        if ($request->boolean('remove_about_portrait_image')) {
            $portraitImagePath = SiteSetting::get('about_portrait_image');
            if ($portraitImagePath) {
                $storagePath = str_replace('/storage/', '', $portraitImagePath);
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                    Log::info("About portrait image removed: {$storagePath}");
                }
            }
            SiteSetting::set('about_portrait_image', null);
        }

        $manager = new ImageManager(new Driver);

        if ($request->hasFile('hero_bg_image')) {
            $file = $request->file('hero_bg_image');
            $image = $manager->read($file);
            $encoded = $image->encode(new WebpEncoder(quality: 85));
            $path = 'site/hero_'.time().'.webp';
            Storage::disk('public')->put($path, (string) $encoded);

            Log::warning("Hero background image optimized to WebP: {$path}");
            SiteSetting::set('hero_bg_image', '/storage/'.$path);
        }

        if ($request->hasFile('about_portrait_image')) {
            $file = $request->file('about_portrait_image');
            $image = $manager->read($file);
            $encoded = $image->encode(new WebpEncoder(quality: 85));
            $path = 'site/portrait_'.time().'.webp';
            Storage::disk('public')->put($path, (string) $encoded);

            Log::warning("About portrait image optimized to WebP: {$path}");
            SiteSetting::set('about_portrait_image', '/storage/'.$path);
        }

        return back()->with('success', 'Site configuration has been sealed and optimized.');
    }
}
