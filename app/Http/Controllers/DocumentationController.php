<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;

class DocumentationController extends Controller
{
    /**
     * Available documentation files with role-based access.
     */
    protected array $docs = [
        'admin' => [
            'title' => 'Admin Guide',
            'description' => 'Complete administration guide for Odo Studio',
            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'roles' => ['admin'],
            'visual' => 'admin',
            'readTime' => '12 min',
        ],
        'manager' => [
            'title' => 'Manager Guide',
            'description' => 'Business operations and booking management',
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            'roles' => ['admin', 'manager'],
            'visual' => 'manager',
            'readTime' => '10 min',
        ],
        'photographer' => [
            'title' => 'Photographer Guide',
            'description' => 'Calendar, assignments, and media management',
            'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z',
            'roles' => ['admin', 'manager', 'crew'],
            'visual' => 'photographer',
            'readTime' => '8 min',
        ],
        'public' => [
            'title' => 'Public Features',
            'description' => 'Website functionality available to visitors',
            'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'roles' => ['admin', 'manager', 'crew', 'guest'],
            'visual' => 'public',
            'readTime' => '5 min',
        ],
        'architecture' => [
            'title' => 'System Architecture',
            'description' => 'Technical architecture and database schema',
            'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
            'roles' => ['admin'],
            'visual' => 'architecture',
            'readTime' => '15 min',
        ],
    ];

    /**
     * Display the documentation index or a specific doc.
     */
    public function show(Request $request, ?string $doc = null)
    {
        $user = $request->user();
        $filteredDocs = $this->getDocsForUser($user);

        // If no doc specified, show the documentation viewer
        if (! $doc) {
            return view('admin.documentation', ['docs' => $filteredDocs]);
        }

        // Check if doc exists
        $docKey = strtolower($doc);
        if (! isset($this->docs[$docKey])) {
            abort(404);
        }

        // Check if user can view this doc
        if (! $this->canView($docKey, $user)) {
            abort(404);
        }

        // Load the markdown file with caching (invalidated when file changes)
        $docPath = base_path('docs/'.strtoupper($docKey).'.md');

        if (! file_exists($docPath)) {
            Log::warning('Documentation file not found', [
                'path' => $docPath,
                'requested' => $docKey,
            ]);
            $html = null;
        } else {
            $fileModifiedTime = filemtime($docPath);
            $cacheKey = "docs.{$docKey}.{$fileModifiedTime}";
            $html = Cache::remember($cacheKey, 3600, function () use ($docPath) {
                $content = file_get_contents($docPath);

                return $this->parseMarkdown($content);
            });
        }

        if ($html === null) {
            abort(404);
        }

        // Get doc metadata
        $metadata = $this->docs[$docKey];

        return view('docs.show', [
            'html' => $html,
            'doc' => $docKey,
            'title' => $metadata['title'],
            'description' => $metadata['description'],
            'icon' => $metadata['icon'],
            'docs' => $filteredDocs,
        ]);
    }

    /**
     * Determine if the current user can view a specific documentation file.
     */
    public function canView(string $docKey, ?User $user = null): bool
    {
        if (! isset($this->docs[$docKey])) {
            return false;
        }

        $allowedRoles = $this->docs[$docKey]['roles'];

        // Guest access
        if ($user === null) {
            return in_array('guest', $allowedRoles);
        }

        // Check if user has any of the allowed roles
        foreach ($allowedRoles as $role) {
            if ($role === 'guest') {
                continue; // guest already handled above
            }
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get documentation array filtered for the given user.
     */
    public function getDocsForUser(?User $user = null): array
    {
        $filtered = [];
        foreach ($this->docs as $key => $doc) {
            if ($this->canView($key, $user)) {
                $filtered[$key] = $doc;
            }
        }

        return $filtered;
    }

    /**
     * Parse markdown to HTML using CommonMark with extensions.
     */
    protected function parseMarkdown(string $markdown): string
    {
        // Configure environment with GFM (includes strikethrough, tables, etc.)
        // Security: Disable raw HTML parsing to prevent XSS
        $environment = Environment::createGFMEnvironment([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);

        // Add table extension (GFM doesn't include it by default in this version)
        $environment->addExtension(new TableExtension);

        // Add task list extension
        $environment->addExtension(new TaskListExtension);

        $converter = new MarkdownConverter($environment);

        $html = $converter->convertToHtml($markdown);

        return $html;
    }
}
