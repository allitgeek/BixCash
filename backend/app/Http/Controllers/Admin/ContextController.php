<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class ContextController extends Controller
{
    /**
     * Display the project context documentation (CLAUDE.md)
     */
    public function index()
    {
        try {
            // Path to CLAUDE.md file
            $claudeMdPath = base_path('../CLAUDE.md');

            // Check if file exists
            if (!file_exists($claudeMdPath)) {
                return view('admin.context.index', [
                    'content' => '<div class="text-center py-12"><p class="text-red-600 text-lg font-semibold">❌ CLAUDE.md file not found</p><p class="text-gray-500 mt-2">Expected location: ' . $claudeMdPath . '</p></div>',
                    'error' => true,
                    'tableOfContents' => []
                ]);
            }

            // Read the markdown content
            $markdownContent = file_get_contents($claudeMdPath);

            // Configure CommonMark with GitHub Flavored Markdown extension
            $environment = new Environment([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);

            $environment->addExtension(new CommonMarkCoreExtension());
            $environment->addExtension(new GithubFlavoredMarkdownExtension());

            $converter = new MarkdownConverter($environment);

            // Convert markdown to HTML
            $htmlContent = $converter->convert($markdownContent)->getContent();

            // Generate table of contents from headings
            $tableOfContents = $this->generateTableOfContents($markdownContent);

            return view('admin.context.index', [
                'content' => $htmlContent,
                'error' => false,
                'tableOfContents' => $tableOfContents,
                'lastUpdated' => date('F d, Y', filemtime($claudeMdPath))
            ]);

        } catch (\Exception $e) {
            return view('admin.context.index', [
                'content' => '<div class="text-center py-12"><p class="text-red-600 text-lg font-semibold">❌ Error loading documentation</p><p class="text-gray-500 mt-2">' . htmlspecialchars($e->getMessage()) . '</p></div>',
                'error' => true,
                'tableOfContents' => []
            ]);
        }
    }

    /**
     * Generate table of contents from markdown headings
     */
    private function generateTableOfContents($markdown)
    {
        $toc = [];
        $lines = explode("\n", $markdown);

        foreach ($lines as $line) {
            // Match markdown headings (## or ###)
            if (preg_match('/^(#{2,3})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]); // 2 for ##, 3 for ###
                $title = trim($matches[2]);

                // Create slug from title
                $slug = $this->createSlug($title);

                $toc[] = [
                    'level' => $level,
                    'title' => $title,
                    'slug' => $slug
                ];
            }
        }

        return $toc;
    }

    /**
     * Create URL-friendly slug from heading text
     */
    private function createSlug($text)
    {
        // Remove special characters and convert to lowercase
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
