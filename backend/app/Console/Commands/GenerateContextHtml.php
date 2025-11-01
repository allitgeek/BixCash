<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class GenerateContextHtml extends Command
{
    protected $signature = 'context:generate';
    protected $description = 'Generate standalone HTML documentation from CLAUDE.md';

    public function handle()
    {
        $this->info('🚀 Generating context.html from CLAUDE.md...');

        try {
            // Path to CLAUDE.md
            $claudeMdPath = base_path('../CLAUDE.md');

            if (!file_exists($claudeMdPath)) {
                $this->error('❌ CLAUDE.md not found at: ' . $claudeMdPath);
                return 1;
            }

            // Read markdown content
            $markdownContent = file_get_contents($claudeMdPath);
            $this->info('📖 Read CLAUDE.md (' . number_format(strlen($markdownContent)) . ' bytes)');

            // Convert markdown to HTML
            $environment = new Environment([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
            $environment->addExtension(new CommonMarkCoreExtension());
            $environment->addExtension(new GithubFlavoredMarkdownExtension());
            $converter = new MarkdownConverter($environment);
            $htmlContent = $converter->convert($markdownContent)->getContent();

            // Generate table of contents
            $toc = $this->generateTableOfContents($markdownContent);
            $this->info('📑 Generated TOC with ' . count($toc) . ' sections');

            // Generate complete HTML
            $completeHtml = $this->generateHtmlTemplate($htmlContent, $toc, filemtime($claudeMdPath));

            // Save to public directory
            $outputPath = public_path('context.html');
            file_put_contents($outputPath, $completeHtml);

            $this->info('✅ Successfully generated: ' . $outputPath);
            $this->info('🌐 Access at: https://bixcash.com/context.html');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }

    private function generateTableOfContents($markdown)
    {
        $toc = [];
        $lines = explode("\n", $markdown);

        foreach ($lines as $line) {
            if (preg_match('/^(#{2,3})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $title = trim($matches[2]);
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

    private function createSlug($text)
    {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        return trim($slug, '-');
    }

    private function generateHtmlTemplate($content, $toc, $lastModified)
    {
        $lastUpdated = date('F d, Y', $lastModified);
        $sectionCount = count($toc);

        $tocHtml = '';
        foreach ($toc as $item) {
            $indent = $item['level'] == 3 ? 'ml-4' : '';
            $fontWeight = $item['level'] == 2 ? 'font-semibold' : '';
            $dot = $item['level'] == 2 ? '<span class="w-2 h-2 bg-green-400 rounded-full inline-block mr-2"></span>' : '';

            $tocHtml .= '<a href="#' . $item['slug'] . '" class="toc-link block py-3 px-4 text-sm rounded-xl transition-all duration-300 hover:bg-white/10 text-white ' . $indent . ' ' . $fontWeight . '" data-toc-link="' . $item['slug'] . '">' . $dot . htmlspecialchars($item['title']) . '</a>';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BixCash Project Documentation</title>
    <meta name="description" content="Complete technical documentation for the BixCash Shop to Earn platform">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />

    <style>
        /* Custom Animations */
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes particle-float {
            0%, 100% { transform: translate(0, 0); opacity: 0; }
            10%, 90% { opacity: 1; }
            50% { transform: translate(100px, -100px); }
        }

        .animated-gradient {
            background: linear-gradient(-45deg, #021c47, #1e3a8a, #7c3aed, #4f46e5, #06b6d4);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: particle-float 20s infinite;
        }

        .glass-dark {
            background: rgba(2, 28, 71, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(118, 211, 122, 0.3);
        }

        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, #76d37a, #4ade80, #22c55e);
            z-index: 9999;
            transition: width 0.3s ease;
            box-shadow: 0 0 20px rgba(118, 211, 122, 0.8);
        }

        .toc-link.active {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
            color: white !important;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .code-block-wrapper {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid transparent;
            background: linear-gradient(#1e293b, #1e293b) padding-box,
                        linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899) border-box;
        }

        .copy-button {
            opacity: 0;
            transition: opacity 0.3s;
        }

        .code-block-wrapper:hover .copy-button {
            opacity: 1;
        }

        /* Prose styling for markdown content */
        .prose { max-width: none; }
        .prose h1 { font-size: 3rem; font-weight: 900; background: linear-gradient(135deg, #2563eb, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; border-bottom: 4px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 2rem; }
        .prose h2 { font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, #1e40af, #1e3a8a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-top: 3rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #dbeafe; scroll-margin-top: 6rem; }
        .prose h3 { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-top: 2rem; margin-bottom: 1rem; scroll-margin-top: 6rem; }
        .prose p { color: #374151; line-height: 1.75; font-size: 1.125rem; margin-bottom: 1rem; }
        .prose strong { color: #111827; font-weight: 700; background: #fef3c7; padding: 0 0.25rem; border-radius: 0.25rem; }
        .prose code { color: #2563eb; background: linear-gradient(135deg, #eff6ff, #f5f3ff); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.875rem; border: 1px solid #bfdbfe; }
        .prose pre { background: linear-gradient(135deg, #1e293b, #0f172a) !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3) !important; border-radius: 0.75rem; }
        .prose a { color: #2563eb; font-weight: 600; text-decoration: none; }
        .prose a:hover { text-decoration: underline; color: #1e40af; }
        .prose ul { list-style: none; padding-left: 0; }
        .prose li { color: #374151; position: relative; padding-left: 1.75rem; margin-bottom: 0.5rem; }
        .prose li:before { content: '→'; position: absolute; left: 0; color: #22c55e; font-weight: 700; }
        .prose ol { padding-left: 1.5rem; }
        .prose blockquote { border-left: 4px solid #3b82f6; background: linear-gradient(135deg, #eff6ff, #f5f3ff); font-style: italic; padding: 1rem 1.5rem; border-radius: 0 0.75rem 0.75rem 0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .prose table { border-collapse: collapse; width: 100%; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-radius: 0.75rem; overflow: hidden; }
        .prose th { background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; font-weight: 700; padding: 1rem; text-align: left; }
        .prose td { border: 1px solid #e5e7eb; padding: 1rem; transition: background 0.2s; }
        .prose td:hover { background: #eff6ff; }
        .prose img { border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); border: 4px solid white; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #76d37a, #22c55e); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #4ade80, #16a34a); }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20 min-h-screen">

    <!-- Reading Progress Bar -->
    <div id="reading-progress" class="reading-progress"></div>

    <div class="container mx-auto px-4 py-8 max-w-[1600px]">
        <!-- Animated Hero Header -->
        <div class="animated-gradient rounded-3xl shadow-2xl mb-8 overflow-hidden relative" style="min-height: 320px;">
            <!-- Particles -->
            <div class="absolute w-full h-full overflow-hidden">
                <div class="particle" style="width: 60px; height: 60px; left: 10%; animation-delay: 0s;"></div>
                <div class="particle" style="width: 40px; height: 40px; left: 30%; animation-delay: 2s;"></div>
                <div class="particle" style="width: 80px; height: 80px; left: 50%; animation-delay: 4s;"></div>
                <div class="particle" style="width: 50px; height: 50px; left: 70%; animation-delay: 6s;"></div>
                <div class="particle" style="width: 70px; height: 70px; left: 90%; animation-delay: 8s;"></div>
            </div>

            <!-- Shimmer -->
            <div class="shimmer absolute inset-0 pointer-events-none"></div>

            <div class="relative z-10 p-12">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm shadow-2xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-6xl font-black text-white tracking-tight drop-shadow-lg">BixCash</h1>
                                <p class="text-2xl text-white/95 font-bold mt-1">Project Documentation</p>
                            </div>
                        </div>

                        <p class="text-white/90 text-xl mb-8 max-w-3xl leading-relaxed">
                            Complete technical documentation, architecture details, and development guide for the BixCash "Shop to Earn" platform.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full flex items-center gap-3 shadow-lg">
                                <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-white font-bold text-lg">{$sectionCount} Sections</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full flex items-center gap-3 shadow-lg">
                                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-white font-bold text-lg">{$lastUpdated}</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full flex items-center gap-3 shadow-lg">
                                <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                <span class="text-white font-bold text-lg">Production Ready</span>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:block">
                        <div class="float-animation">
                            <svg class="w-56 h-56 text-white/20 drop-shadow-2xl" viewBox="0 0 200 200" fill="currentColor">
                                <path d="M40,60 L40,160 L160,160 L160,60 Q150,50 140,60 L60,60 Q50,50 40,60 Z"/>
                                <path d="M100,60 L100,160" stroke="currentColor" stroke-width="2" fill="none"/>
                                <circle cx="70" cy="90" r="5"/>
                                <circle cx="130" cy="90" r="5"/>
                                <rect x="60" y="110" width="80" height="3" rx="1.5"/>
                                <rect x="60" y="120" width="60" height="3" rx="1.5"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-8">
            <!-- Sidebar TOC -->
            <aside class="hidden xl:block w-96 flex-shrink-0">
                <div class="sticky top-8 space-y-6">
                    <!-- TOC Card -->
                    <div class="glass-dark rounded-3xl overflow-hidden shadow-2xl">
                        <div class="bg-gradient-to-r from-blue-600/40 to-purple-600/40 px-8 py-6 border-b border-white/10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-white">Table of Contents</h3>
                            </div>
                        </div>

                        <div class="p-6 max-h-[calc(100vh-400px)] overflow-y-auto">
                            <nav class="space-y-2">{$tocHtml}</nav>
                        </div>

                        <!-- Reading Progress -->
                        <div class="px-8 py-6 border-t border-white/10 bg-white/5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-200 font-bold">Reading Progress</span>
                                <span id="progress-percentage" class="text-sm text-green-400 font-black">0%</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-3">
                                <div id="progress-bar" class="bg-gradient-to-r from-green-400 to-blue-500 h-3 rounded-full transition-all duration-300 shadow-lg" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="glass-dark rounded-3xl p-6 shadow-2xl">
                        <h4 class="text-white font-bold mb-4 text-lg">Quick Actions</h4>
                        <div class="space-y-3">
                            <button onclick="window.print()" class="w-full flex items-center gap-3 px-5 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all text-white font-semibold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Document
                            </button>
                            <button onclick="increaseFontSize()" class="w-full flex items-center gap-3 px-5 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all text-white font-semibold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                </svg>
                                Increase Text
                            </button>
                            <button onclick="decreaseFontSize()" class="w-full flex items-center gap-3 px-5 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all text-white font-semibold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                                </svg>
                                Decrease Text
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 min-w-0 space-y-8">
                <!-- Search Bar -->
                <div class="glass-dark rounded-3xl p-8 shadow-2xl">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                            <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="doc-search" placeholder="Search documentation..."
                               class="w-full px-16 py-5 bg-white/10 border-2 border-white/20 rounded-2xl text-white text-xl placeholder-gray-300 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-400/30 transition-all">
                        <div class="absolute inset-y-0 right-5 flex items-center">
                            <kbd class="px-4 py-2 bg-white/10 rounded-lg text-sm text-gray-200 font-mono font-bold">Ctrl+K</kbd>
                        </div>
                    </div>
                    <div id="search-results" class="mt-5 flex items-center gap-3 hidden">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-white font-bold text-lg" id="search-count"></p>
                    </div>
                </div>

                <!-- Documentation Content -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-200">
                    <div id="markdown-content" class="prose p-12 lg:p-16">{$content}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" onclick="scrollToTop()"
            class="fixed bottom-10 right-10 w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full shadow-2xl hover:shadow-blue-500/50 flex items-center justify-center transition-all hover:scale-110 hidden">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>

    <script>
        let fontSize = 18;

        // Update reading progress
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;

            document.getElementById('reading-progress').style.width = scrolled + '%';
            document.getElementById('progress-bar').style.width = scrolled + '%';
            document.getElementById('progress-percentage').textContent = Math.round(scrolled) + '%';

            // Show back to top button
            document.getElementById('back-to-top').classList.toggle('hidden', scrolled < 10);

            // Update active TOC
            updateActiveTOC();
        });

        // Add IDs to headings
        document.querySelectorAll('#markdown-content h2, #markdown-content h3').forEach(heading => {
            const text = heading.textContent.trim();
            const slug = text.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').trim();
            heading.id = slug;

            // Add anchor link
            const anchor = document.createElement('a');
            anchor.href = '#' + slug;
            anchor.className = 'ml-2 text-green-500 opacity-0 hover:opacity-100 transition-opacity';
            anchor.innerHTML = '🔗';
            heading.appendChild(anchor);
        });

        // Add copy buttons to code blocks
        document.querySelectorAll('pre code').forEach((block) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'code-block-wrapper relative';

            const copyBtn = document.createElement('button');
            copyBtn.className = 'copy-button absolute top-3 right-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all flex items-center gap-2 shadow-lg';
            copyBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Copy';

            copyBtn.addEventListener('click', () => {
                navigator.clipboard.writeText(block.textContent);
                copyBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!';
                setTimeout(() => {
                    copyBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Copy';
                }, 2000);
            });

            block.parentNode.parentNode.insertBefore(wrapper, block.parentNode);
            wrapper.appendChild(block.parentNode);
            wrapper.appendChild(copyBtn);
        });

        // Search functionality
        document.getElementById('doc-search').addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const content = document.getElementById('markdown-content');
            const resultsDiv = document.getElementById('search-results');
            const countSpan = document.getElementById('search-count');

            // Remove previous highlights
            content.querySelectorAll('mark').forEach(el => {
                el.outerHTML = el.textContent;
            });

            if (query.length < 3) {
                resultsDiv.classList.add('hidden');
                return;
            }

            // Count matches
            const text = content.innerText;
            const regex = new RegExp(query, 'gi');
            const matchCount = (text.match(regex) || []).length;

            resultsDiv.classList.remove('hidden');
            countSpan.textContent = `Found \${matchCount} match\${matchCount !== 1 ? 'es' : ''}`;

            // Highlight matches
            const walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT);
            const nodesToReplace = [];

            while (walker.nextNode()) {
                const node = walker.currentNode;
                if (node.nodeValue.toLowerCase().includes(query)) {
                    nodesToReplace.push(node);
                }
            }

            nodesToReplace.forEach(node => {
                const span = document.createElement('span');
                span.innerHTML = node.nodeValue.replace(regex, '<mark class="bg-yellow-300 px-1 rounded font-bold">\$&</mark>');
                node.parentNode.replaceChild(span, node);
            });
        });

        // Update active TOC
        function updateActiveTOC() {
            const headings = document.querySelectorAll('#markdown-content h2, #markdown-content h3');
            const tocLinks = document.querySelectorAll('.toc-link');

            let current = '';
            headings.forEach(heading => {
                const rect = heading.getBoundingClientRect();
                if (rect.top <= 100) {
                    current = heading.id;
                }
            });

            tocLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-toc-link') === current) {
                    link.classList.add('active');
                }
            });
        }

        // Scroll to top
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Font size controls
        function increaseFontSize() {
            if (fontSize < 24) {
                fontSize += 2;
                document.getElementById('markdown-content').style.fontSize = fontSize + 'px';
            }
        }

        function decreaseFontSize() {
            if (fontSize > 12) {
                fontSize -= 2;
                document.getElementById('markdown-content').style.fontSize = fontSize + 'px';
            }
        }

        // Keyboard shortcut
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('doc-search').focus();
            }
        });

        // Smooth scroll for TOC links
        document.querySelectorAll('.toc-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
HTML;
    }
}
