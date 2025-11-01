@extends('layouts.admin')

@section('title', 'Project Context - BixCash Admin')
@section('page-title', 'Project Context')

@push('styles')
<style>
    /* Animated Gradient Background */
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .animated-gradient {
        background: linear-gradient(-45deg, #021c47, #1e3a8a, #7c3aed, #4f46e5);
        background-size: 400% 400%;
        animation: gradient-shift 15s ease infinite;
    }

    /* Glassmorphism */
    .glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .glass-dark {
        background: rgba(2, 28, 71, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(118, 211, 122, 0.3);
    }

    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .float-animation {
        animation: float 6s ease-in-out infinite;
    }

    /* Shimmer Effect */
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    .shimmer {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        background-size: 1000px 100%;
        animation: shimmer 3s infinite;
    }

    /* Progress Bar */
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

    /* Code Block Enhancements */
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

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* TOC Active Link */
    .toc-link.active {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white !important;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    /* Particle Background */
    .particle-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: particle-float 20s infinite;
    }

    @keyframes particle-float {
        0%, 100% { transform: translate(0, 0); opacity: 0; }
        10%, 90% { opacity: 1; }
        50% { transform: translate(100px, -100px); }
    }

    /* Heading Anchor Links */
    .heading-anchor {
        opacity: 0;
        margin-left: 8px;
        color: #76d37a;
        transition: opacity 0.3s;
    }

    h2:hover .heading-anchor,
    h3:hover .heading-anchor {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<!-- Reading Progress Bar -->
<div id="reading-progress" class="reading-progress"></div>

<div x-data="contextPage()" class="relative">
    <!-- Hero Header with Animated Gradient -->
    <div class="animated-gradient rounded-2xl shadow-2xl mb-8 overflow-hidden relative" style="min-height: 280px;">
        <!-- Particle Background -->
        <div class="particle-bg">
            <div class="particle" style="width: 60px; height: 60px; left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="width: 40px; height: 40px; left: 30%; animation-delay: 2s;"></div>
            <div class="particle" style="width: 80px; height: 80px; left: 50%; animation-delay: 4s;"></div>
            <div class="particle" style="width: 50px; height: 50px; left: 70%; animation-delay: 6s;"></div>
            <div class="particle" style="width: 70px; height: 70px; left: 90%; animation-delay: 8s;"></div>
        </div>

        <!-- Shimmer Overlay -->
        <div class="shimmer absolute inset-0 pointer-events-none"></div>

        <div class="relative z-10 p-8 md:p-12">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">BixCash</h1>
                            <p class="text-xl text-white/90 font-semibold">Project Documentation</p>
                        </div>
                    </div>

                    <p class="text-white/80 text-lg mb-6 max-w-2xl">
                        Complete technical documentation, architecture details, and development guide for the BixCash "Shop to Earn" platform.
                    </p>

                    @if(!$error)
                    <!-- Stats Badges -->
                    <div class="flex flex-wrap gap-3">
                        <div class="glass px-4 py-2 rounded-full flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-white font-semibold">{{ count($tableOfContents) }} Sections</span>
                        </div>
                        <div class="glass px-4 py-2 rounded-full flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-white font-semibold">{{ $lastUpdated ?? 'Unknown' }}</span>
                        </div>
                        <div class="glass px-4 py-2 rounded-full flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            <span class="text-white font-semibold">Production Ready</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Floating Book Illustration -->
                <div class="hidden lg:block">
                    <div class="float-animation">
                        <svg class="w-48 h-48 text-white/20" viewBox="0 0 200 200" fill="currentColor">
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

    <div class="flex gap-6">
        <!-- Glassmorphic Sidebar TOC -->
        @if(!$error && count($tableOfContents) > 0)
        <aside class="hidden xl:block w-80 flex-shrink-0">
            <div class="sticky top-6 space-y-4">
                <!-- TOC Card -->
                <div class="glass-dark rounded-2xl overflow-hidden shadow-2xl border border-green-500/20">
                    <div class="bg-gradient-to-r from-blue-600/30 to-purple-600/30 px-6 py-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Table of Contents</h3>
                        </div>
                    </div>

                    <div class="p-4 max-h-[calc(100vh-280px)] overflow-y-auto scrollbar-thin scrollbar-thumb-green-500 scrollbar-track-transparent">
                        <nav class="space-y-1">
                            @foreach($tableOfContents as $index => $item)
                                <a href="#{{ $item['slug'] }}"
                                   class="toc-link block py-3 px-4 text-sm rounded-xl transition-all duration-300 hover:bg-white/10 {{ $item['level'] == 3 ? 'ml-4 text-gray-300' : 'font-semibold text-white' }}"
                                   data-toc-link="{{ $item['slug'] }}"
                                   @click="scrollToSection('{{ $item['slug'] }}')">
                                    <span class="flex items-center gap-2">
                                        @if($item['level'] == 2)
                                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                        @endif
                                        {{ $item['title'] }}
                                    </span>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Reading Progress Indicator -->
                    <div class="px-6 py-4 border-t border-white/10 bg-white/5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-300 font-semibold">Reading Progress</span>
                            <span class="text-xs text-green-400 font-bold" x-text="Math.round(scrollProgress) + '%'"></span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-300 shadow-lg shadow-green-500/50"
                                 :style="`width: ${scrollProgress}%`"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="glass-dark rounded-2xl p-4 shadow-2xl border border-green-500/20">
                    <h4 class="text-white font-semibold mb-3 text-sm">Quick Actions</h4>
                    <div class="space-y-2">
                        <button @click="window.print()"
                                class="w-full flex items-center gap-3 px-4 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 text-white text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                        <button @click="adjustFontSize('increase')"
                                class="w-full flex items-center gap-3 px-4 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 text-white text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                            </svg>
                            Larger Text
                        </button>
                        <button @click="adjustFontSize('decrease')"
                                class="w-full flex items-center gap-3 px-4 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 text-white text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                            </svg>
                            Smaller Text
                        </button>
                    </div>
                </div>
            </div>
        </aside>
        @endif

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 space-y-6">
            <!-- Glassmorphic Search Bar -->
            @if(!$error)
            <div class="glass-dark rounded-2xl p-6 shadow-2xl border border-green-500/20">
                <div class="relative">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           id="doc-search"
                           x-model="searchQuery"
                           @input="performSearch"
                           placeholder="Search documentation..."
                           class="w-full px-14 py-4 bg-white/10 border-2 border-white/20 rounded-xl text-white placeholder-gray-300 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-400/20 transition-all duration-300 backdrop-blur-sm text-lg">
                    <div class="absolute inset-y-0 right-4 flex items-center">
                        <kbd class="px-3 py-1 bg-white/10 rounded-lg text-xs text-gray-300 font-mono">Ctrl+K</kbd>
                    </div>
                </div>

                <!-- Search Results Counter -->
                <div x-show="searchQuery.length >= 3" x-transition class="mt-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-white font-medium" x-text="`Found ${searchMatches} match${searchMatches !== 1 ? 'es' : ''}`"></p>
                </div>
            </div>
            @endif

            <!-- Documentation Content with Enhanced Styling -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl overflow-hidden border-2 border-gray-200">
                <div id="markdown-content"
                     :style="`font-size: ${fontSize}px`"
                     class="prose prose-slate max-w-none p-8 md:p-12
                            prose-headings:scroll-mt-24
                            prose-headings:font-black
                            prose-h1:text-5xl prose-h1:bg-gradient-to-r prose-h1:from-blue-600 prose-h1:to-purple-600 prose-h1:bg-clip-text prose-h1:text-transparent prose-h1:pb-4 prose-h1:border-b-4 prose-h1:border-gradient-to-r prose-h1:mb-8
                            prose-h2:text-3xl prose-h2:bg-gradient-to-r prose-h2:from-blue-700 prose-h2:to-blue-900 prose-h2:bg-clip-text prose-h2:text-transparent prose-h2:mt-12 prose-h2:mb-6 prose-h2:pb-3 prose-h2:border-b-2 prose-h2:border-blue-200
                            prose-h3:text-2xl prose-h3:text-gray-800 prose-h3:mt-8 prose-h3:mb-4
                            prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-lg
                            prose-strong:text-gray-900 prose-strong:font-bold prose-strong:bg-yellow-100 prose-strong:px-1
                            prose-code:text-blue-600 prose-code:bg-gradient-to-r prose-code:from-blue-50 prose-code:to-purple-50 prose-code:px-2 prose-code:py-1 prose-code:rounded-lg prose-code:font-mono prose-code:text-sm prose-code:before:content-[''] prose-code:after:content-[''] prose-code:border prose-code:border-blue-200
                            prose-pre:bg-gradient-to-br prose-pre:from-gray-900 prose-pre:to-gray-800 prose-pre:shadow-2xl prose-pre:border-2 prose-pre:border-transparent
                            prose-a:text-blue-600 prose-a:font-semibold prose-a:no-underline hover:prose-a:underline hover:prose-a:text-blue-700
                            prose-ul:list-none prose-ul:pl-0
                            prose-li:text-gray-700 prose-li:relative prose-li:pl-7 prose-li:mb-2 prose-li:before:content-['→'] prose-li:before:absolute prose-li:before:left-0 prose-li:before:text-green-500 prose-li:before:font-bold
                            prose-ol:pl-6
                            prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:bg-gradient-to-r prose-blockquote:from-blue-50 prose-blockquote:to-purple-50 prose-blockquote:italic prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:rounded-r-xl prose-blockquote:shadow-md
                            prose-table:border-collapse prose-table:w-full prose-table:shadow-lg prose-table:rounded-xl prose-table:overflow-hidden
                            prose-th:bg-gradient-to-r prose-th:from-blue-600 prose-th:to-purple-600 prose-th:text-white prose-th:font-bold prose-th:p-4 prose-th:text-left
                            prose-td:border prose-td:border-gray-200 prose-td:p-4 prose-td:hover:bg-blue-50 prose-td:transition-colors
                            prose-img:rounded-xl prose-img:shadow-2xl prose-img:border-4 prose-img:border-white">
                    {!! $content !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    @if(!$error)
    <div class="fixed bottom-8 right-8 flex flex-col gap-3 z-50">
        <!-- Back to Top -->
        <button @click="scrollToTop"
                x-show="scrollProgress > 10"
                x-transition
                class="w-14 h-14 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full shadow-2xl hover:shadow-blue-500/50 flex items-center justify-center transition-all duration-300 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>
    </div>
    @endif
</div>

<script>
function contextPage() {
    return {
        scrollProgress: 0,
        searchQuery: '',
        searchMatches: 0,
        fontSize: 16,

        init() {
            this.updateScrollProgress();
            this.addHeadingAnchors();
            this.addCodeCopyButtons();
            this.setupScrollTracking();
            this.setupKeyboardShortcuts();
        },

        updateScrollProgress() {
            window.addEventListener('scroll', () => {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                this.scrollProgress = (winScroll / height) * 100;

                // Update reading progress bar
                const progressBar = document.getElementById('reading-progress');
                if (progressBar) {
                    progressBar.style.width = this.scrollProgress + '%';
                }

                // Update active TOC link
                this.updateActiveTOC();
            });
        },

        performSearch() {
            const content = document.getElementById('markdown-content');
            if (!content) return;

            // Remove previous highlights
            content.querySelectorAll('.highlight').forEach(el => {
                el.outerHTML = el.textContent;
            });

            if (this.searchQuery.length < 3) {
                this.searchMatches = 0;
                return;
            }

            // Count and highlight matches
            const text = content.innerText;
            const regex = new RegExp(this.searchQuery, 'gi');
            const matches = text.match(regex);
            this.searchMatches = matches ? matches.length : 0;

            // Highlight text
            this.highlightText(content, this.searchQuery);
        },

        highlightText(element, query) {
            const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
            const nodesToReplace = [];

            while (walker.nextNode()) {
                const node = walker.currentNode;
                if (node.nodeValue.toLowerCase().includes(query.toLowerCase())) {
                    nodesToReplace.push(node);
                }
            }

            nodesToReplace.forEach(node => {
                const regex = new RegExp(`(${query})`, 'gi');
                const span = document.createElement('span');
                span.innerHTML = node.nodeValue.replace(regex, '<mark class="highlight bg-yellow-300 px-1 rounded font-semibold animate-pulse">$1</mark>');
                node.parentNode.replaceChild(span, node);
            });
        },

        addHeadingAnchors() {
            const content = document.getElementById('markdown-content');
            if (!content) return;

            content.querySelectorAll('h2, h3').forEach(heading => {
                const text = heading.textContent.trim();
                const slug = text.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .trim();
                heading.id = slug;

                // Add anchor link
                const anchor = document.createElement('a');
                anchor.href = '#' + slug;
                anchor.className = 'heading-anchor';
                anchor.innerHTML = '🔗';
                heading.appendChild(anchor);
            });
        },

        addCodeCopyButtons() {
            document.querySelectorAll('pre code').forEach((block) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'code-block-wrapper relative';

                const copyBtn = document.createElement('button');
                copyBtn.className = 'copy-button absolute top-2 right-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-all duration-200 flex items-center gap-2';
                copyBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Copy';

                copyBtn.addEventListener('click', () => {
                    navigator.clipboard.writeText(block.textContent);
                    copyBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!';
                    setTimeout(() => {
                        copyBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Copy';
                    }, 2000);
                });

                block.parentNode.parentNode.insertBefore(wrapper, block.parentNode);
                wrapper.appendChild(block.parentNode);
                wrapper.appendChild(copyBtn);
            });
        },

        updateActiveTOC() {
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
        },

        setupScrollTracking() {
            // Smooth scroll to sections
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = document.querySelector(anchor.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        },

        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Ctrl+K for search focus
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    document.getElementById('doc-search')?.focus();
                }
            });
        },

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        scrollToSection(slug) {
            const element = document.getElementById(slug);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },

        adjustFontSize(action) {
            if (action === 'increase' && this.fontSize < 24) {
                this.fontSize += 2;
            } else if (action === 'decrease' && this.fontSize > 12) {
                this.fontSize -= 2;
            }
        }
    };
}
</script>

{{-- Enhanced Syntax Highlighting --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>

@endsection
