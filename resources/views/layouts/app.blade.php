<!doctype html>
<html lang="en" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mini Twitter') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* small tweaks for nicer default appearance */
        body { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
        .card-shadow { box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
        .muted { color: #64748b; }
        .btn-primary { background: linear-gradient(90deg,#2563eb,#7c3aed); }
        .btn-primary:hover { opacity: .95; }
        
        /* Dark mode styles */
        html.dark body { @apply bg-black text-slate-100 !important; }
        html.dark main { @apply bg-black !important; }
        html.dark .card-shadow { box-shadow: 0 6px 18px rgba(0,0,0,0.3); }
        html.dark .muted { @apply text-slate-400; }
        html.dark header { @apply bg-black border-slate-800 !important; }
        html.dark .bg-white { @apply bg-slate-900 !important; }
        html.dark .bg-slate-50 { @apply bg-black !important; }
        html.dark .bg-slate-100 { @apply bg-slate-800 !important; }
        html.dark .border-slate-200 { @apply border-slate-700; }
        html.dark input, html.dark textarea { @apply bg-slate-900 text-slate-100 border-slate-700 !important; }
        html.dark .text-slate-600 { @apply text-slate-400 !important; }
        html.dark .text-slate-700 { @apply text-slate-300 !important; }
        html.dark .text-slate-800 { @apply text-slate-100 !important; }
        html.dark .text-slate-900 { @apply text-slate-50 !important; }
        html.dark .bg-slate-200 { @apply bg-slate-700 !important; }
        html.dark .border-b { @apply border-slate-800 !important; }
        html.dark .hover\:shadow-lg:hover { @apply shadow-lg !important; }
        html.dark article { @apply bg-slate-900 !important; }
        html.dark aside { @apply bg-black !important; }
        html.dark .card-shadow { @apply bg-slate-900 !important; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">
    <header class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tweets.index') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-violet-600 text-white rounded-full flex items-center justify-center font-bold">M</div>
                        <span class="text-xl font-semibold">Mini Twitter</span>
                    </a>

                    <div class="hidden sm:block">
                        <form action="{{ route('tweets.index') }}" method="GET">
                            <div class="relative">
                                <input name="q" type="search" placeholder="Search tweets, users..." class="w-64 pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300" />
                                <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"></path></svg>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center space-x-2 text-sm text-slate-700">
                            <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-slate-700 overflow-hidden">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="profile" class="w-full h-full object-cover" />
                                @else
                                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                                @endif
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="text-sm text-slate-700">🔍 Discover</a>
                        <a href="{{ route('messages.index') }}" class="text-sm text-slate-700">💬 Messages</a>
                        <a href="{{ route('stories.index') }}" class="text-sm text-slate-700">📸 My Day</a>
                        <button id="theme-toggle" class="text-sm text-slate-700 hover:text-slate-900">🌙</button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-slate-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-slate-700">Login</a>
                        <a href="{{ route('register') }}" class="text-sm text-slate-700">Register</a>
                        <button id="theme-toggle" class="text-sm text-slate-700 hover:text-slate-900">🌙</button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <aside class="hidden md:block">
                <div class="sticky top-6">
                    <div class="bg-white p-4 rounded card-shadow">
                        <h3 class="font-semibold">Welcome</h3>
                        <p class="text-sm muted mt-2">See latest tweets from people you follow.</p>
                    </div>

                    <div class="mt-4 bg-white p-4 rounded card-shadow">
                        <h4 class="font-semibold">Trends for you</h4>
                        <ul class="mt-2 text-sm muted space-y-2">
                            <li>#WebDev</li>
                            <li>#Laravel</li>
                            <li>#TailwindCSS</li>
                        </ul>
                    </div>
                </div>
            </aside>

            <section class="md:col-span-2">
                @yield('content')
            </section>

            <aside class="hidden md:block">
                <div class="sticky top-6">
                    <div class="bg-white p-4 rounded card-shadow">
                        <h4 class="font-semibold">Profile</h4>
                        @auth
                            <a href="{{ route('profile.show', auth()->user()) }}" class="text-sm text-slate-700">View your profile</a>
                        @else
                            <p class="text-sm muted">Login to interact</p>
                        @endauth
                    </div>

                    <div class="mt-4 bg-white p-4 rounded card-shadow">
                        <h4 class="font-semibold">Who to follow</h4>
                        <div class="mt-2 space-y-3">
                            <a class="flex items-center justify-between" href="#"><div class="flex items-center space-x-3"><div class="w-8 h-8 bg-slate-200 rounded-full"></div><div class="text-sm">Jane Doe</div></div><button class="text-sm text-sky-600">Follow</button></a>
                            <a class="flex items-center justify-between" href="#"><div class="flex items-center space-x-3"><div class="w-8 h-8 bg-slate-200 rounded-full"></div><div class="text-sm">Dev Team</div></div><button class="text-sm text-sky-600">Follow</button></a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script>
        // Setup global helpers: CSRF token for fetch
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Intercept like forms to call AJAX and update UI
        document.addEventListener('click', async (e) => {
            const likeBtn = e.target.closest('[data-like-button]');
            if (!likeBtn) return;
            e.preventDefault();
            e.stopPropagation();
            
            const form = likeBtn.closest('form');
            if (!form) return;
            
            const url = form.getAttribute('action');
            const csrfToken = form.querySelector('input[name="_token"]').value;
            
            try {
                likeBtn.disabled = true;
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({}),
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                // Update like count
                const countSpan = form.querySelector('[data-like-count]');
                if (countSpan) countSpan.textContent = data.count;
                
                // Update heart emoji and color
                const heartIcon = form.querySelector('[data-heart-icon]');
                if (heartIcon) {
                    if (data.liked) {
                        heartIcon.textContent = '❤️';
                        heartIcon.style.color = 'red';
                    } else {
                        heartIcon.textContent = '🤍';
                        heartIcon.style.color = 'inherit';
                    }
                }
            } catch (err) {
                console.error('Like error', err);
            } finally {
                likeBtn.disabled = false;
            }
        });

        // Character counter for tweet forms
        document.addEventListener('input', (e) => {
            const ta = e.target.closest('textarea[data-char-count]');
            if (!ta) return;
            const max = parseInt(ta.getAttribute('maxlength') || '280', 10);
            const counter = ta.closest('form').querySelector('[data-char-remaining]');
            if (counter) counter.textContent = max - ta.value.length;
        });

        // Image preview for file inputs
        document.addEventListener('change', (e) => {
            const fileInput = e.target.closest('input[type="file"]');
            if (!fileInput) return;
            const previewId = fileInput.id.replace('-', '') + 'Preview';
            const preview = document.getElementById(previewId) || fileInput.closest('form')?.querySelector('[id$="Preview"]');
            if (!preview) return;
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    preview.innerHTML = `<img src="${event.target.result}" alt="preview" class="rounded-lg max-h-48" />`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Toggle comments section
        document.addEventListener('click', (e) => {
            const toggleBtn = e.target.closest('.toggle-comments');
            if (!toggleBtn) return;
            e.preventDefault();
            const commentsSection = toggleBtn.closest('article').querySelector('.comments-section');
            if (commentsSection) {
                commentsSection.classList.toggle('hidden');
            }
        });

        // Theme toggle (dark/light mode)
        const themeToggle = document.getElementById('theme-toggle');
        const htmlRoot = document.getElementById('html-root');
        
        // Load theme from localStorage
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            htmlRoot.classList.add('dark');
            themeToggle.textContent = '☀️';
        } else {
            htmlRoot.classList.remove('dark');
            themeToggle.textContent = '🌙';
        }

        // Theme toggle click handler
        themeToggle.addEventListener('click', () => {
            const isDark = htmlRoot.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeToggle.textContent = isDark ? '☀️' : '🌙';
        });
    </script>
</body>
</html>
