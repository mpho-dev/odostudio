@extends('layouts.app')

@section('title', $title . ' — Documentation')

@section('header', 'Documentation')

@push('styles')
<style>
/* Search Modal Styles */
.search-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 70;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
}
.search-modal.active {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 10vh;
}
.search-modal .glass {
    background: rgba(26, 26, 26, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}
.search-modal input {
    font-family: 'Outfit', system-ui, sans-serif;
}
.search-results {
    max-height: 60vh;
    overflow-y: auto;
}
.search-result-item {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    cursor: pointer;
    transition: background 0.2s;
}
.search-result-item:hover {
    background: rgba(201, 168, 76, 0.1);
}
.search-result-item:last-child {
    border-bottom: none;
}
.search-highlight {
    background: rgba(201, 168, 76, 0.3);
    padding: 0 2px;
    border-radius: 2px;
}
.search-empty {
    text-align: center;
    padding: 40px;
    color: #6b6b6b;
    font-size: 0.875rem;
}

/* Copy Link Button */
.copy-link-btn {
    opacity: 0;
    transition: opacity 0.2s;
    margin-left: 8px;
    padding: 4px 8px;
    background: rgba(201, 168, 76, 0.1);
    border: 1px solid rgba(201, 168, 76, 0.2);
    border-radius: 4px;
    color: #c9a84c;
    font-size: 0.7rem;
    cursor: pointer;
}
.copy-link-btn.copied {
    background: rgba(16, 185, 129, 0.2);
    border-color: rgba(16, 185, 129, 0.3);
    color: #10b981;
}
h2:hover .copy-link-btn,
h3:hover .copy-link-btn {
    opacity: 1;
}

/* Feedback Widget */
.feedback-widget {
    margin-top: 40px;
    padding: 24px;
    background: rgba(26, 26, 26, 0.5);
    border: 1px solid rgba(201, 168, 76, 0.15);
    border-radius: 16px;
    text-align: center;
}
.feedback-question {
    font-size: 0.9rem;
    color: #d4d4d4;
    margin-bottom: 12px;
}
.feedback-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
}
.feedback-btn {
    padding: 8px 24px;
    border-radius: 8px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
}
.feedback-btn-yes {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}
.feedback-btn-yes:hover {
    background: rgba(16, 185, 129, 0.2);
}
.feedback-btn-no {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}
.feedback-btn-no:hover {
    background: rgba(239, 68, 68, 0.2);
}
.feedback-thanks {
    display: none;
    color: #10b981;
    font-size: 0.9rem;
}

/* Mobile TOC Panel - now handled via Tailwind classes */
.mobile-toc-close {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    background: rgba(201, 168, 76, 0.1);
    border: 1px solid rgba(201, 168, 76, 0.2);
    border-radius: 8px;
    color: #c9a84c;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: all 0.2s;
}
.mobile-toc-close:hover {
    background: rgba(201, 168, 76, 0.2);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.min.js"></script>
<script>
    console.log('[MERMAID] Script loaded, checking...');
    
    // Debug: Check if mermaid is loaded
    if (typeof mermaid === 'undefined') {
        console.error('[MERMAID] Mermaid not loaded!');
    } else {
        console.log('[MERMAID] Mermaid version:', mermaid.version || 'unknown');
    }
    
    mermaid.initialize({
        startOnLoad: false,
        theme: 'dark',
        themeVariables: {
            primaryColor: '#c9a84c',
            primaryTextColor: '#ffffff',
            primaryBorderColor: '#c9a84c',
            lineColor: '#6b6b6b',
            secondaryColor: '#1a1a1a',
            tertiaryColor: '#111111',
            background: '#0a0a0a',
            mainBkg: '#1a1a1a',
            nodeBorder: '#c9a84c',
            clusterBkg: '#111111',
            clusterBorder: '#2a2a2a',
            titleColor: '#ffffff',
            edgeLabelBackground: '#1a1a1a',
            fontFamily: 'Outfit, system-ui, sans-serif',
        },
        flowchart: {
            curve: 'basis',
            padding: 20,
            useMaxWidth: false,
        },
        er: {
            useMaxWidth: false,
            layoutDirection: 'TB',
            minEntityWidth: 100,
        },
        securityLevel: 'loose',
    });
    
    console.log('[MERMAID] Initialization complete');
    
    // Search functionality
    const searchDocs = @json($docs);
    const currentDoc = '{{ $doc }}';
    const docTitles = Object.keys(searchDocs);
    
    // Store all searchable content
    let searchableContent = [];
    
    // Collect all headings and content from the page
    function collectSearchContent() {
        const content = document.getElementById('doc-content');
        if (!content) return;
        
        // Get all headings
        const headings = content.querySelectorAll('h2, h3');
        headings.forEach((heading, index) => {
            const id = 'section-' + index;
            heading.id = id;
            searchableContent.push({
                type: 'heading',
                level: heading.tagName,
                text: heading.textContent,
                id: id
            });
        });
        
        // Get paragraphs and list items
        const paragraphs = content.querySelectorAll('p, li');
        paragraphs.forEach((para, index) => {
            const text = para.textContent.trim();
            if (text.length > 20) {
                const id = 'content-' + index;
                para.id = id;
                searchableContent.push({
                    type: 'content',
                    text: text,
                    preview: text.substring(0, 150) + (text.length > 150 ? '...' : ''),
                    id: id
                });
            }
        });
    }
    
    // Search function
    function performSearch(query) {
        const resultsContainer = document.getElementById('search-results');
        if (!resultsContainer) return;
        
        if (!query || query.length < 2) {
            resultsContainer.innerHTML = '<div class="search-empty">Type at least 2 characters to search</div>';
            return;
        }
        
        const lowerQuery = query.toLowerCase();
        const results = [];
        
        // Search in current doc headings and content
        searchableContent.forEach(item => {
            if (item.text.toLowerCase().includes(lowerQuery)) {
                if (item.type === 'heading') {
                    results.push({
                        type: 'heading',
                        text: item.text,
                        id: item.id,
                        level: item.level
                    });
                } else if (item.type === 'content') {
                    results.push({
                        type: 'content',
                        text: item.text,
                        preview: item.preview,
                        id: item.id
                    });
                }
            }
        });
        
        // Search in other docs
        docTitles.forEach(docKey => {
            const docData = searchDocs[docKey];
            if (docKey !== currentDoc) {
                if (docData.title.toLowerCase().includes(lowerQuery) || 
                    docData.description.toLowerCase().includes(lowerQuery)) {
                    results.push({
                        type: 'doc',
                        text: docData.title,
                        description: docData.description,
                        key: docKey
                    });
                }
            }
        });
        
        // Render results
        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="search-empty">No results found for "' + query + '"</div>';
            return;
        }
        
        let html = '';
        results.forEach(result => {
            if (result.type === 'heading') {
                const isH2 = result.level === 'H2';
                html += `
                    <div class="search-result-item" onclick="scrollToSection('${result.id}')">
                        <div class="${isH2 ? 'text-white font-medium' : 'text-ash/70 pl-3'}">${result.text}</div>
                    </div>
                `;
            } else if (result.type === 'doc') {
                html += `
                    <div class="search-result-item" onclick="window.location.href='/docs/${result.key}'">
                        <div class="text-gold font-medium">${result.text}</div>
                        <div class="text-ash/50 text-xs mt-1">${result.description}</div>
                    </div>
                `;
            } else if (result.type === 'content') {
                html += `
                    <div class="search-result-item" onclick="scrollToSection('${result.id}')">
                        <div class="text-ash/80 text-xs">${result.preview}</div>
                    </div>
                `;
            }
        });
        
        resultsContainer.innerHTML = html;
    }
    
    function scrollToSection(id) {
        closeSearch();
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    function openSearch() {
        searchableContent = []; // Clear previous content
        const modal = document.getElementById('search-modal');
        modal.classList.add('active');
        trapFocus(modal);
        collectSearchContent();
    }
    
    function closeSearch() {
        releaseFocus();
        document.getElementById('search-modal').classList.remove('active');
        document.getElementById('search-input').value = '';
        document.getElementById('search-results').innerHTML = '<div class="search-empty">Start typing to search documentation</div>';
    }
    
    // Close search on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSearch();
        }
        // Open search with Cmd/Ctrl + K
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            openSearch();
        }
    });
    
    // Copy link to section
    function copyLink(sectionId) {
        const url = window.location.origin + window.location.pathname + '#' + sectionId;
        navigator.clipboard.writeText(url).then(() => {
            const btn = document.querySelector(`[data-section="${sectionId}"]`);
            if (btn) {
                btn.textContent = 'Copied!';
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.textContent = 'Copy Link';
                    btn.classList.remove('copied');
                }, 2000);
            }
        });
    }
    
    // Add copy link buttons to headings
    function addCopyLinkButtons() {
        const headings = document.querySelectorAll('#doc-content h2, #doc-content h3');
        headings.forEach((heading, index) => {
            const id = heading.id || 'section-' + index;
            heading.id = id;
            
            const btn = document.createElement('button');
            btn.className = 'copy-link-btn';
            btn.setAttribute('data-section', id);
            btn.setAttribute('aria-label', 'Copy link to section');
            btn.textContent = 'Copy Link';
            btn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                copyLink(id);
            };
            
            heading.style.display = 'inline-flex';
            heading.style.alignItems = 'center';
            heading.appendChild(btn);
        });
    }
    
    // Feedback handling
    function submitFeedback(isHelpful) {
        const widget = document.querySelector('.feedback-widget');
        const buttons = document.querySelector('.feedback-buttons');
        const thanks = document.querySelector('.feedback-thanks');
        
        buttons.style.display = 'none';
        thanks.style.display = 'block';
        thanks.textContent = isHelpful 
            ? 'Thanks for your feedback! Glad we could help.'
            : 'Thanks for the feedback. We\'ll work on improving this documentation.';
    }
    
    // Mobile TOC toggle
    function toggleMobileTOC() {
        const panel = document.getElementById('mobile-toc-panel');
        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            trapFocus(panel);
        } else {
            releaseFocus();
            panel.classList.add('hidden');
        }
    }
    
    function closeMobileTOC() {
        releaseFocus();
        document.getElementById('mobile-toc-panel').classList.add('hidden');
    }
    
    // Focus trap for modals
    let previousActiveElement = null;
    
    function trapFocus(modalElement) {
        previousActiveElement = document.activeElement;
        const focusableElements = modalElement.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        modalElement.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
        
        firstElement.focus();
    }
    
    function releaseFocus() {
        if (previousActiveElement) {
            previousActiveElement.focus();
            previousActiveElement = null;
        }
    }
    
    // Find all mermaid code blocks and render them
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[MERMAID] DOM loaded, scanning for diagrams...');
        
        // Find ALL pre blocks that contain code
        const allPreBlocks = document.querySelectorAll('#doc-content pre');
        console.log('[MERMAID] Found', allPreBlocks.length, 'pre blocks');
        
        allPreBlocks.forEach(function(pre, index) {
            console.log('[MERMAID] Checking pre block', index, ':', pre.innerText.substring(0, 50));
            
            const code = pre.querySelector('code');
            if (!code) {
                console.log('[MERMAID] No code element in pre', index);
                return;
            }
            
            const text = code.textContent.trim();
            console.log('[MERMAID] Code text:', text.substring(0, 80));
            
            // Check if it's a mermaid diagram
            const isMermaid = text.startsWith('graph ') || 
                text.startsWith('flowchart ') ||
                text.startsWith('erDiagram') ||
                text.startsWith('sequenceDiagram') ||
                text.startsWith('classDiagram') ||
                text.startsWith('stateDiagram') ||
                text.startsWith('pie ') ||
                text.startsWith('journey');
            
            console.log('[MERMAID] Is mermaid?', isMermaid);
            
            if (isMermaid) {
                console.log('[MERMAID] Rendering diagram', index);
                
                const graphDefinition = text;
                
                // Create a div for the rendered diagram
                const div = document.createElement('div');
                div.className = 'mermaid-diagram';
                div.id = 'mermaid-' + index;
                div.style.cssText = 'background: #111111; border-radius: 8px; padding: 20px; margin: 16px 0; overflow-x: auto; text-align: center;';
                
                // Insert before the pre block
                pre.parentNode.insertBefore(div, pre);
                
                // Hide the original code block
                pre.style.display = 'none';
                
                // Render the diagram
                mermaid.render('mermaid-graph-' + index, graphDefinition).then(function(result) {
                    console.log('[MERMAID] Successfully rendered diagram', index);
                    div.innerHTML = result.svg;
                }).catch(function(error) {
                    console.error('[MERMAID] Error rendering diagram', index, ':', error);
                    div.innerHTML = '<div class="text-red-400 p-4 bg-red-500/10 rounded">' +
                        '<strong>Diagram Error:</strong> ' + error.message + 
                        '<pre style="margin-top:8px;overflow:auto;">' + graphDefinition + '</pre>' +
                        '</div>';
                });
            }
        });
        
        console.log('[MERMAID] Scan complete');
        
        // Initialize TOC and progress bar after content is loaded
        generateTOC();
        initProgressBar();
        initScrollSpy();
        addCopyLinkButtons();
    });
    
    // Add search input listener (outside DOMContentLoaded)
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => performSearch(e.target.value));
    }
    
    // Generate Table of Contents from headings
    function generateTOC() {
        const headings = document.querySelectorAll('#doc-content h2, #doc-content h3');
        const tocNav = document.getElementById('toc-nav');
        
        if (!tocNav || headings.length === 0) return;
        
        let tocHTML = '';
        headings.forEach((heading, index) => {
            const id = heading.id || 'section-' + index;
            heading.id = id;
            
            const isH2 = heading.tagName === 'H2';
            const indentClass = isH2 ? '' : 'pl-4';
            const textClass = isH2 ? 'text-white font-medium' : 'text-ash/70';
            
            tocHTML += `
                <a href="#${id}" class="block py-1.5 px-2 rounded hover:bg-white/5 transition-colors ${indentClass} ${textClass} text-xs" data-section="${id}">
                    ${heading.textContent}
                </a>
            `;
        });
        
        tocNav.innerHTML = tocHTML || '<span class="text-ash/40 text-xs italic">No sections found</span>';
        
        // Also populate mobile TOC
        const mobileTocNav = document.getElementById('mobile-toc-nav');
        if (mobileTocNav) {
            mobileTocNav.innerHTML = tocHTML;
        }
        
        // Add smooth scroll to TOC links
        tocNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }
    
    // Initialize reading progress bar
    function initProgressBar() {
        const contentArea = document.querySelector('#doc-content').closest('.overflow-y-auto');
        const progressBar = document.getElementById('reading-progress');
        
        if (!contentArea || !progressBar) return;
        
        contentArea.addEventListener('scroll', () => {
            const scrollTop = contentArea.scrollTop;
            const scrollHeight = contentArea.scrollHeight - contentArea.clientHeight;
            const progress = (scrollTop / scrollHeight) * 100;
            progressBar.style.width = progress + '%';
        });
    }
    
    // Initialize scroll spy for TOC highlighting
    function initScrollSpy() {
        const contentArea = document.querySelector('#doc-content').closest('.overflow-y-auto');
        const tocLinks = document.querySelectorAll('#toc-nav a[data-section]');
        const headings = document.querySelectorAll('#doc-content h2, #doc-content h3');
        
        if (!contentArea || tocLinks.length === 0) return;
        
        contentArea.addEventListener('scroll', () => {
            const scrollPos = contentArea.scrollTop + 100;
            
            headings.forEach((heading, index) => {
                const headingTop = heading.offsetTop;
                const headingBottom = headingTop + heading.offsetHeight;
                
                if (scrollPos >= headingTop && scrollPos < headingBottom) {
                    tocLinks.forEach(link => {
                        link.classList.remove('text-gold', 'bg-gold/10');
                        if (link.dataset.section === heading.id) {
                            link.classList.add('text-gold', 'bg-gold/10');
                        }
                    });
                }
            });
        });
    }
</script>
@endpush

@section('content')
<!-- Skip navigation link -->
<a href="#doc-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-gold focus:text-black focus:rounded">Skip to content</a>
<!-- Reading Progress Bar -->
<div class="reading-progress" id="reading-progress" role="progressbar" aria-label="Reading progress"></div>

<!-- Search Modal -->
<div id="search-modal" class="search-modal" onclick="if(event.target === this) closeSearch()" role="dialog" aria-modal="true" aria-label="Search documentation">
    <div class="glass w-full max-w-2xl mx-4 rounded-2xl border border-charcoal/20 dark:border-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/5">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-ash/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <label for="search-input" class="sr-only">Search documentation</label>
                <input type="text" id="search-input" placeholder="Search documentation..." 
                    class="flex-1 bg-transparent border-none outline-none text-white placeholder-ash/40 text-sm">
                <button onclick="closeSearch()" class="p-1 hover:bg-white/5 rounded">
                    <svg class="w-5 h-5 text-ash/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div id="search-results" class="search-results max-h-96">
            <div class="search-empty">Start typing to search documentation</div>
        </div>
        <div class="p-3 border-t border-white/5 text-xs text-ash/40 text-center">
            Press <kbd class="px-1.5 py-0.5 bg-white/10 rounded">ESC</kbd> to close
        </div>
    </div>
</div>

<!-- Mobile TOC Panel - hidden on desktop, overlay on mobile -->
<div id="mobile-toc-panel" class="hidden fixed inset-0 z-60 bg-black/95 backdrop-blur-xl p-4 overflow-y-auto lg:hidden" role="dialog" aria-modal="true" aria-label="Table of contents">
    <button class="mobile-toc-close" onclick="closeMobileTOC()" aria-label="Close table of contents">✕ Close</button>
    <div class="mt-16 max-w-sm mx-auto">
        <div class="glass">
            <h3 class="text-xs tracking-[0.3em] uppercase text-gold font-bold mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                Contents
            </h3>
            <nav id="mobile-toc-nav" class="space-y-1 text-sm" onclick="closeMobileTOC()">
            </nav>
        </div>
    </div>
</div>
    </div>
</div>

<div class="flex gap-6">
    <!-- Left Sidebar - Table of Contents -->
    <aside class="hidden lg:block w-72 shrink-0">
        <div class="sticky top-24 glass rounded-xl border border-white/5 p-5 max-h-[calc(100vh-8rem)] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs tracking-[0.3em] uppercase text-gold font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    Contents
                </h3>
            </div>
            <nav id="toc-nav" class="space-y-1 text-sm">
                <!-- TOC items will be populated by JavaScript -->
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 min-w-0 space-y-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm flex-wrap">
            <a href="{{ route('docs.index') }}" class="text-ash/60 hover:text-gold transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="hidden sm:inline">Docs</span>
            </a>
            <span class="text-ash/40">/</span>
            <span class="text-gold" aria-current="page">{{ $title }}</span>
            <span class="text-ash/40">/</span>
            <button onclick="openSearch()" class="text-ash/60 hover:text-gold transition-colors flex items-center gap-1 ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-xs">Search</span>
                <kbd class="hidden md:inline-block px-1.5 py-0.5 bg-white/10 rounded text-[0.6rem]">⌘K</kbd>
            </button>
        </div>

        <!-- Header -->
        <div class="reveal">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gold/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="font-serif text-3xl text-white leading-tight">{{ $title }}</h1>
                    <p class="text-xs tracking-[0.3em] uppercase text-ash/60 mt-1">{{ $description }}</p>
                </div>
                <button onclick="openSearch()" class="lg:hidden p-3 bg-gold/10 rounded-lg hover:bg-gold/20 transition-colors">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Doc Navigation -->
        <div class="glass rounded-xl p-4 border border-white/5 reveal" style="animation-delay: 100ms">
            <div class="flex flex-wrap gap-2">
                @foreach($docs as $key => $docData)
                    <a href="{{ route('docs.show', $key) }}" 
                        class="px-4 py-2 rounded-lg text-xs tracking-wide font-medium transition-all duration-300 {{ $key === $doc ? 'bg-gold text-black' : 'text-silver hover:text-white hover:bg-white/5' }}"
                       style="{{ $key === $doc ? 'background-color: var(--color-gold); color: black;' : '' }}">
                        {{ $docData['title'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Content with Sidebar Layout -->
        <div class="glass rounded-xl border border-white/5 overflow-hidden relative">
            <div class="flex">
                <!-- Mobile TOC Toggle -->
                <button id="mobile-toc-toggle" class="lg:hidden absolute top-4 right-4 z-10 p-2 bg-charcoal/80 rounded-lg border border-white/10 hover:bg-charcoal hover:border-gold/30 transition-colors" onclick="toggleMobileTOC()">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </button>

                <!-- Main Content -->
                <div class="flex-1 p-8 md:p-12 overflow-y-auto" style="max-height: 70vh; min-height: 500px; background-color: #0a0a0a;">
                    <article id="doc-content">
                        {!! $html !!}
                    </article>
                    
                    <!-- Feedback Widget -->
                    <div class="feedback-widget">
                        <p class="feedback-question">Was this documentation helpful?</p>
                        <div class="feedback-buttons">
                            <button class="feedback-btn feedback-btn-yes" onclick="submitFeedback(true)">
                                ✓ Yes, helpful
                            </button>
                            <button class="feedback-btn feedback-btn-no" onclick="submitFeedback(false)">
                                ✗ Not helpful
                            </button>
                        </div>
                        <p class="feedback-thanks"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Viewer -->
        <div class="flex justify-center">
            <a href="{{ route('docs.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-charcoal/50 hover:bg-charcoal border border-white/5 hover:border-gold/30 rounded-xl text-sm text-ash hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Back to Documentation Viewer
            </a>
        </div>
    </div>
</div>

<style>
/* DOCUMENT STYLES */
#doc-content {
    font-family: 'Outfit', system-ui, sans-serif;
    line-height: 1.75;
    font-size: 0.95rem;
    color: #d4d4d4;
}

#doc-content h1 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 2.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 2.5rem 0 1.25rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #c9a84c;
    line-height: 1.2;
}

#doc-content h1:first-child {
    margin-top: 0;
}

#doc-content h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.625rem;
    font-weight: 600;
    color: #ffffff;
    margin: 2rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

#doc-content h3 {
    font-family: 'Outfit', system-ui, sans-serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: #c9a84c;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin: 1.75rem 0 0.75rem 0;
}

#doc-content p {
    margin: 0 0 1.25rem 0;
    color: #c4c4c4;
    line-height: 1.8;
}

#doc-content ul, #doc-content ol {
    margin: 1rem 0 1.25rem 0;
    padding-left: 1.5rem;
}

#doc-content ul {
    list-style-type: none;
}

#doc-content ul li {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 0.625rem;
    color: #c4c4c4;
}

#doc-content ul li::before {
    content: '▸';
    position: absolute;
    left: 0;
    color: #c9a84c;
    font-size: 0.75rem;
}

#doc-content ol {
    list-style-type: decimal;
}

#doc-content a {
    color: #c9a84c;
    text-decoration: none;
    border-bottom: 1px solid transparent;
}

#doc-content a:hover {
    color: #e4c97f;
    border-bottom-color: #c9a84c;
}

#doc-content strong {
    font-weight: 600;
    color: #ffffff;
}

#doc-content code {
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 0.85rem;
    background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
    border: 1px solid #2a2a2a;
    border-radius: 4px;
    padding: 0.2rem 0.4rem;
    color: #c9a84c;
}

#doc-content pre {
    background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    padding: 1.25rem;
    margin: 1.5rem 0;
    overflow-x: auto;
}

#doc-content pre code {
    background: transparent;
    border: none;
    padding: 0;
    color: #d4d4d4;
    font-size: 0.8rem;
    line-height: 1.6;
}

#doc-content table {
    display: table;
    width: 100% !important;
    margin: 1.5rem 0;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    overflow: hidden;
}

#doc-content thead {
    background: linear-gradient(135deg, #1a1a1a 0%, #111111 100%) !important;
}

#doc-content th {
    text-align: left;
    padding: 0.875rem 1rem;
    font-weight: 600;
    color: #ffffff !important;
    font-size: 0.8rem;
    text-transform: uppercase;
    border-bottom: 2px solid #c9a84c;
}

#doc-content tbody tr:nth-child(odd) {
    background: #0f0f0f !important;
}

#doc-content tbody tr:nth-child(even) {
    background: #141414 !important;
}

#doc-content tbody tr:hover {
    background: #1a1a1a !important;
}

#doc-content td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #1f1f1f;
    color: #c4c4c4 !important;
}

#doc-content blockquote {
    margin: 1.5rem 0;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, rgba(201,168,76,0.08) 0%, rgba(201,168,76,0.03) 100%);
    border-left: 4px solid #c9a84c;
    border-radius: 0 8px 8px 0;
}

#doc-content hr {
    border: none;
    height: 1px;
    background: linear-gradient(90deg, transparent, #2a2a2a, transparent);
    margin: 2.5rem 0;
}

/* Reading Progress Bar */
.reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3px;
    background: linear-gradient(90deg, #c9a84c, #e4c97f);
    z-index: 50;
    transition: width 0.1s ease-out;
}

/* Callout Boxes */
#doc-content blockquote.doc-note {
    border-left-color: #c9a84c;
    background: linear-gradient(135deg, rgba(201,168,76,0.08) 0%, rgba(201,168,76,0.03) 100%);
}

#doc-content blockquote.doc-warning {
    border-left-color: #ef4444;
    background: linear-gradient(135deg, rgba(239,68,68,0.08) 0%, rgba(239,68,68,0.03) 100%);
}

#doc-content blockquote.doc-tip {
    border-left-color: #10b981;
    background: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(16,185,129,0.03) 100%);
}

/* Code Block Language Label & Copy Button */
#doc-content pre {
    position: relative;
}

#doc-content pre[data-language]::before {
    content: attr(data-language);
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.25rem 0.75rem;
    background: #2a2a2a;
    color: #c9a84c;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-radius: 0 8px 0 4px;
}

.copy-code-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    opacity: 0;
    transition: opacity 0.2s;
    background: #1a1a1a;
    border: 1px solid #3a3a3a;
    color: #c9a84c;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
}

#doc-content pre:hover .copy-code-btn {
    opacity: 1;
}

.copy-code-btn:hover {
    background: #2a2a2a;
}

/* Smooth scroll for TOC */
html {
    scroll-behavior: smooth;
}

/* Mermaid Diagram Container */
.mermaid-diagram {
    background: #111111;
    border-radius: 8px;
    padding: 20px;
    margin: 16px 0;
    overflow-x: auto;
    text-align: center;
    border: 1px solid #2a2a2a;
}

.mermaid-diagram svg {
    max-width: 100%;
    height: auto;
}

/* Focus styles for accessibility */
.copy-link-btn:focus,
.search-result-item:focus,
button:focus,
a:focus {
    outline: 2px solid #c9a84c;
    outline-offset: 2px;
}
</style>
@endsection
