<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Static pages
        $urls[] = [
            'loc' => route('home'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '1.0',
        ];

        $urls[] = [
            'loc' => route('portfolio'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ];

        $urls[] = [
            'loc' => route('contact.form'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];

        // Dynamic portfolio projects
        $projects = Project::published()->get();
        foreach ($projects as $project) {
            $urls[] = [
                'loc' => route('portfolio.project', $project->slug),
                'lastmod' => $project->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return Response::make($xml, 200)->header('Content-Type', 'application/xml');
    }
}
