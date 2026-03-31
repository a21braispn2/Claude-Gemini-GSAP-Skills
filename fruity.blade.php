<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API - Fruity Edge</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Organic & clean fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Nunito:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES FRUITY ══════════════ */
        :root {
            /* Modo Oscuro: Blackberry / Berries */
            --bg:         #231630;
            --bg-card:    #352245;
            --text-pri:   #fff5f7;
            --text-sec:   #d1b8e4;
            --border:     rgba(255, 255, 255, 0.08);

            --shadow-sm:  0 8px 24px rgba(0, 0, 0, 0.15);
            --shadow-lg:  0 20px 40px rgba(0, 0, 0, 0.25);

            --color-watermelon: #FF4D6D;
            --color-mango:      #FF8E3C;
            --color-lime:       #80ED99;
            --color-skyblue:    #00B4D8;
            --color-grape:      #9D4EDD;

            --blob-1: radial-gradient(circle at 50% 50%, rgba(255, 77, 109, 0.15), transparent 60%);
            --blob-2: radial-gradient(circle at 50% 50%, rgba(255, 142, 60, 0.15), transparent 60%);
            --blob-3: radial-gradient(circle at 50% 50%, rgba(128, 237, 153, 0.1), transparent 60%);

            --font-head:  'Fredoka', sans-serif;
            --font-body:  'Nunito', sans-serif;
            --font-mono:  'JetBrains Mono', monospace;
            
            --radius-btn:  16px;
            --radius-card: 32px;
        }

        :root.light {
            /* Modo Claro: Melocotón / Limón suave */
            --bg:         #FFF2F2;
            --bg-card:    #FFFFFF;
            --text-pri:   #2D132C;
            --text-sec:   #6C5B7B;
            --border:     rgba(0, 0, 0, 0.05);

            --shadow-sm:  0 8px 24px rgba(255, 77, 109, 0.06);
            --shadow-lg:  0 20px 40px rgba(255, 142, 60, 0.08);

            --blob-1: radial-gradient(circle at 50% 50%, rgba(255, 77, 109, 0.08), transparent 60%);
            --blob-2: radial-gradient(circle at 50% 50%, rgba(255, 142, 60, 0.08), transparent 60%);
            --blob-3: radial-gradient(circle at 50% 50%, rgba(128, 237, 153, 0.15), transparent 60%);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            background-color: var(--bg);
            color: var(--text-pri);
            font-family: var(--font-body);
            font-size: 15px;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.5s ease, color 0.5s ease;
            cursor: none; /* Cursor de goma */
        }

        /* ══════════════ BACKGROUND AMBIENT BLOBS ══════════════ */
        .blobs-wrapper {
            position: fixed; inset: 0;
            z-index: -2; pointer-events: none;
            overflow: hidden;
        }
        .blob { position: absolute; border-radius: 50%; width: 60vw; height: 60vw; filter: blur(40px); opacity: 0.8; }
        .blob-1 { background: var(--blob-1); top: -10vw; left: -10vw; }
        .blob-2 { background: var(--blob-2); bottom: -20vw; right: -10vw; width: 80vw; height: 80vw; }
        .blob-3 { background: var(--blob-3); top: 30vh; left: 30vw; width: 50vw; height: 50vw; }

        /* ══════════════ JELLY CURSOR ══════════════ */
        .cursor-jelly {
            position: fixed; top: 0; left: 0;
            width: 16px; height: 16px;
            background: var(--color-watermelon);
            border-radius: 50%; pointer-events: none; z-index: 9999;
            transform: translate(-50%, -50%);
            mix-blend-mode: screen; 
            transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                        height 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                        background-color 0.3s;
        }
        :root.light .cursor-jelly { mix-blend-mode: multiply; }

        .cursor-jelly.hovering {
            width: 50px; height: 50px;
            background: var(--color-mango);
            opacity: 0.5;
        }

        /* ══════════════ NAV JUICY ══════════════ */
        nav {
            position: sticky; top: 20px; z-index: 100;
            margin: 0 24px; display: flex; align-items: stretch;
            height: 64px; background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px); border: 2px solid var(--border);
            border-radius: var(--radius-card); box-shadow: var(--shadow-sm);
            visibility: hidden; /* GSAP reveal */
        }
        :root.light nav { background: rgba(255, 255, 255, 0.7); }

        .nav-brand {
            display: flex; align-items: center; gap: 12px; padding: 0 24px;
        }
        .nav-brand-icon {
            font-size: 24px; line-height: 1; filter: drop-shadow(0 4px 6px rgba(255, 77, 109, 0.3));
        }
        .nav-brand h1 {
            font-family: var(--font-head); font-size: 20px; font-weight: 700; color: var(--text-pri); letter-spacing: -0.02em;
        }

        .nav-status {
            display: flex; align-items: center; gap: 10px; padding: 0 24px;
            color: var(--color-lime); font-size: 13px; font-weight: 700;
            border-left: 2px dashed var(--border); margin-left: auto;
        }
        .status-dot {
            width: 10px; height: 10px; border-radius: 50%; background: var(--color-lime);
            box-shadow: 0 0 10px var(--color-lime); animation: bounce 2s infinite ease-in-out;
        }
        @keyframes bounce { 0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-3px) scale(1.1); } }

        .theme-btn {
            background: transparent; border: none; padding: 0 32px;
            border-left: 2px dashed var(--border); cursor: pointer; display: flex; align-items: center;
            color: var(--text-sec); font-size: 20px; transition: color 0.3s;
        }
        .theme-btn:hover { color: var(--color-mango); }
        .theme-btn .moon { display: none; }
        :root.light .theme-btn .sun { display: none; }
        :root.light .theme-btn .moon { display: block; }

        /* ══════════════ LAYOUT ══════════════ */
        .container {
            max-width: 900px; margin: 40px auto 0; padding: 0 24px 100px;
        }

        .section-label {
            display: inline-block; font-family: var(--font-head); font-size: 24px; font-weight: 600;
            color: var(--text-sec); margin-bottom: 24px; margin-left: 16px; visibility: hidden;
            position: relative;
        }
        .section-label::after {
            content: ''; position: absolute; bottom: -4px; left: 0; width: 40px; height: 4px;
            background: var(--color-mango); border-radius: 4px;
        }

        /* ══════════════ HERO (FRUITY CARD) ══════════════ */
        .hero-wrapper { margin-bottom: 60px; visibility: hidden; perspective: 1000px; }
        .hero {
            display: flex; flex-direction: column; gap: 30px;
            background: var(--bg-card); border: 2px solid var(--border);
            border-radius: 40px; padding: 48px; box-shadow: var(--shadow-lg);
            position: relative; overflow: hidden;
        }
        
        .hero-tag {
            align-self: flex-start; background: rgba(255, 77, 109, 0.1); color: var(--color-watermelon);
            padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; letter-spacing: 0.05em;
        }

        .hero h2 {
            font-family: var(--font-head); font-size: 56px; font-weight: 700; line-height: 1.1; letter-spacing: -0.02em;
        }
        .hero h2 em { font-style: normal; color: var(--color-watermelon); display: block; }

        .hero-sub { color: var(--text-sec); font-size: 16px; max-width: 500px; line-height: 1.5; font-weight: 600; }

        .hero-stats {
            display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap;
        }
        .stat-pill {
            background: rgba(255,255,255,0.03); border: 2px solid var(--border);
            padding: 16px 24px; border-radius: 24px; display: flex; flex-direction: column; gap: 4px;
            flex: 1; min-width: 140px;
        }
        :root.light .stat-pill { background: #fafafa; }
        .stat-label { font-size: 12px; font-weight: 700; color: var(--text-sec); text-transform: uppercase; letter-spacing: 0.1em; }
        .stat-value { font-family: var(--font-head); font-size: 24px; font-weight: 600; color: var(--text-pri); }
        .stat-value.accent { color: var(--color-skyblue); }

        /* ══════════════ DEBUG SECTION ══════════════ */
        .debug-warn {
            display: flex; align-items: center; gap: 16px; padding: 20px 24px;
            background: rgba(255, 142, 60, 0.1); border: 2px dashed rgba(255, 142, 60, 0.4);
            border-radius: var(--radius-card); color: var(--color-mango); font-weight: 700;
            font-size: 14px; margin-bottom: 30px; visibility: hidden;
        }

        .debug-card {
            background: var(--bg-card); border: 2px solid var(--border); border-radius: var(--radius-card);
            padding: 10px; margin-bottom: 60px; box-shadow: var(--shadow-sm); visibility: hidden;
        }

        .debug-row {
            display: grid; grid-template-columns: 180px 1fr; padding: 16px 20px;
            border-bottom: 2px dashed var(--border); visibility: hidden; border-radius: 16px;
        }
        .debug-row:last-child { border-bottom: none; }
        .debug-row:hover { background: rgba(0, 180, 216, 0.05); }

        .dk { font-size: 13px; font-weight: 700; color: var(--text-sec); display: flex; align-items: center; }
        .dv { font-family: var(--font-mono); font-size: 14px; color: var(--text-pri); display: flex; align-items: center; }

        .dv.hi { color: var(--color-skyblue); font-weight: 700; }
        .dv.wa { color: var(--color-mango); }
        .dv.pa { background: var(--border); color: transparent; border-radius: 8px; cursor: pointer; transition: all 0.3s; padding: 4px 12px;}
        .dv.pa:hover { color: var(--color-grape); background: transparent; }

        /* ══════════════ ENDPOINTS LIST (BUBBLING) ══════════════ */
        .endpoints-wrapper { perspective: 1000px; }
        
        .endpoints {
            display: flex; flex-direction: column; gap: 12px;
            margin-bottom: 60px; visibility: hidden;
        }

        .ep-row {
            display: flex; align-items: center; gap: 20px; padding: 16px 24px;
            background: var(--bg-card); border: 2px solid var(--border); border-radius: 24px;
            box-shadow: var(--shadow-sm); transform-origin: left background; transition: border-color 0.3s;
        }
        .ep-row:hover { border-color: rgba(255, 77, 109, 0.3); }

        .ep-num { font-family: var(--font-head); font-size: 18px; font-weight: 700; color: var(--border); }

        .badge {
            font-family: var(--font-head); font-size: 12px; font-weight: 600; letter-spacing: 0.1em;
            padding: 8px 16px; border-radius: 16px; min-width: 72px; text-align: center;
        }
        .badge.GET    { color: #fff; background: var(--color-skyblue); }
        .badge.POST   { color: #1a4f32; background: var(--color-lime); }
        .badge.PUT    { color: #fff; background: var(--color-mango); }
        .badge.DELETE { color: #fff; background: var(--color-watermelon); }

        .ep-path { font-family: var(--font-mono); font-size: 14px; font-weight: 600; color: var(--text-pri); }
        
        .copy-btn {
            margin-left: auto; padding: 10px 16px;
            font-family: var(--font-head); font-size: 13px; font-weight: 700; 
            color: var(--text-sec); background: rgba(255, 255, 255, 0.05); border: 2px solid var(--border);
            border-radius: var(--radius-btn); cursor: pointer; transition: all 0.2s;
        }
        :root.light .copy-btn { background: #f3f4f6; }
        .copy-btn:hover { background: var(--color-grape); color: #fff; border-color: var(--color-grape); }
        
        .copy-btn.copied { background: var(--color-lime); color: #1a4f32; border-color: var(--color-lime); }

        /* ══════════════ FOOTER ══════════════ */
        footer {
            margin: 0 24px 24px; border: 2px solid var(--border); padding: 24px 32px; border-radius: 32px;
            display: flex; align-items: center; justify-content: space-between; background: var(--bg-card);
            font-family: var(--font-head); font-size: 14px; font-weight: 600; color: var(--text-sec);
            visibility: hidden;
        }
        .footer-accent { display: flex; gap: 8px; }
        .footer-accent span { width: 12px; height: 12px; border-radius: 50%; background: var(--color-watermelon); }
        .footer-accent span:nth-child(2) { background: var(--color-mango); }
        .footer-accent span:nth-child(3) { background: var(--color-lime); }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 768px) {
            nav { margin: 10px; padding: 0; }
            .theme-btn { padding: 0 16px; }
            .hero { padding: 32px 24px; border-radius: 32px; }
            .hero h2 { font-size: 40px; }
            .stat-pill { min-width: 100%; }
            .ep-path { font-size: 12px; }
            .copy-btn, .ep-num { display: none; }
            .cursor-jelly { display: none; }
            body, .dv.pa { cursor: auto; }
        }
    </style>
    
    <!-- Evitar FOUC -->
    <script>
        if (localStorage.getItem('uo-theme-fruity') === 'light') {
            document.documentElement.classList.add('light');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- Background Smooth Blobs -->
    <div class="blobs-wrapper">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Elástico Cursor  -->
    <div class="cursor-jelly"></div>

    <nav>
        <div class="nav-brand">
            <span class="nav-brand-icon">🍉</span>
            <h1>UO API</h1>
        </div>
        <div class="nav-status">
            <span class="status-dot"></span> Fresh & Live
        </div>
        <button class="theme-btn" id="themeToggle" aria-label="Toggle Theme">
            <span class="sun">☀️</span>
            <span class="moon">🌙</span>
        </button>
    </nav>

    <div class="container">

        {{-- ── HERO (BOUNCY CARD) ── --}}
        <div class="hero-wrapper">
            <div class="hero">
                <div class="hero-tag">System Overview</div>
                <h2>Gateway<em>Online</em></h2>
                <p class="hero-sub">Welcome to the juicy side of the API. All backend services are happily running and dispatching your dynamic data.</p>
                <div class="hero-stats">
                    <div class="stat-pill">
                        <span class="stat-label">Uptime</span>
                        <span class="stat-value accent" id="uptime">00:00:00</span>
                    </div>
                    <div class="stat-pill">
                        <span class="stat-label">Requests/s</span>
                        <span class="stat-value">2,048</span>
                    </div>
                    <div class="stat-pill">
                        <span class="stat-label">Env</span>
                        <span class="stat-value" style="text-transform: capitalize;">{{ app()->environment() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── DEBUG JUICY WIDGET ── --}}
        @if(config('app.debug'))
        <div class="debug-warn">
            🦊 Heads up! Debug mode is active. Keep it private.
        </div>

        <h3 class="section-label">Server Details</h3>
        <div class="debug-card">
            <div class="debug-row"><span class="dk">Framework</span><span class="dv hi">Lrvl {{ app()->version() }}</span></div>
            <div class="debug-row"><span class="dk">App Version</span><span class="dv">v1.0{{ config('app.version') }}</span></div>
            <div class="debug-row"><span class="dk">PHP Engine</span><span class="dv">{{ phpversion() }}</span></div>
            <div class="debug-row"><span class="dk">Database</span><span class="dv wa">{{ config('database.default') }}</span></div>
            <div class="debug-row"><span class="dk">Host URL</span><span class="dv">{{ config('database.connections.mysql.host') }}</span></div>
            <div class="debug-row"><span class="dk">App Port</span><span class="dv">{{ config('database.connections.mysql.port') }}</span></div>
            <div class="debug-row"><span class="dk">DB Access</span><span class="dv">{{ config('database.connections.mysql.username') }}</span></div>
            <div class="debug-row"><span class="dk">DB Code</span><span class="dv pa">REVEAL SECRET INFO</span></div>
        </div>
        @endif

        {{-- ── ENDPOINTS LIST ── --}}
        <h3 class="section-label">Active Routes</h3>
        <div class="endpoints-wrapper">
            <div class="endpoints">
                <div class="ep-row hover-squish"><span class="ep-num">01</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/status</span><button class="copy-btn hover-target" data-path="/api/v1/status">Copy Path</button></div>
                <div class="ep-row hover-squish"><span class="ep-num">02</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/users</span><button class="copy-btn hover-target" data-path="/api/v1/users">Copy Path</button></div>
                <div class="ep-row hover-squish"><span class="ep-num">03</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/users/{id}</span><button class="copy-btn hover-target" data-path="/api/v1/users/{id}">Copy Path</button></div>
                <div class="ep-row hover-squish"><span class="ep-num">04</span><span class="badge POST">POST</span><span class="ep-path">/api/v1/users</span><button class="copy-btn hover-target" data-path="/api/v1/users">Copy Path</button></div>
                <div class="ep-row hover-squish"><span class="ep-num">05</span><span class="badge PUT">PUT</span><span class="ep-path">/api/v1/users/{id}</span><button class="copy-btn hover-target" data-path="/api/v1/users/{id}">Copy Path</button></div>
                <div class="ep-row hover-squish"><span class="ep-num">06</span><span class="badge DELETE">DEL</span><span class="ep-path">/api/v1/users/{id}</span><button class="copy-btn hover-target" data-path="/api/v1/users/{id}">Copy Path</button></div>
            </div>
        </div>

    </div>

    <footer>
        <span id="req-id"></span>
        <div class="footer-accent">
            <span></span><span></span><span></span>
        </div>
        <span>UO API &copy; {{ date('Y') }}</span>
    </footer>

    <!-- ══════════════ MOTOR GSAP FRUITY BUBBLY ══════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (rm) gsap.globalTimeline.timeScale(0);

        // -- ANIMACIÓN DE LOS BLOBS DE FONDO --
        gsap.to('.blob-1', { rotation: 360, x: 100, y: 50, duration: 40, repeat: -1, yoyo: true, ease: "sine.inOut" });
        gsap.to('.blob-2', { rotation: -360, x: -100, y: -80, duration: 45, repeat: -1, yoyo: true, ease: "sine.inOut" });
        gsap.to('.blob-3', { x: 50, y: 150, duration: 35, repeat: -1, yoyo: true, ease: "sine.inOut" });

        // -- JELLY CURSOR QUICKTO --
        const jelly = document.querySelector('.cursor-jelly');
        const xToJelly = gsap.quickTo(jelly, "x", {duration: 0.15, ease: "power3"});
        const yToJelly = gsap.quickTo(jelly, "y", {duration: 0.15, ease: "power3"});

        window.addEventListener("mousemove", (e) => {
            xToJelly(e.clientX); yToJelly(e.clientY);
        });

        document.querySelectorAll('.copy-btn, .theme-btn, .hover-target, a').forEach(el => {
            el.addEventListener('mouseenter', () => jelly.classList.add('hovering'));
            el.addEventListener('mouseleave', () => jelly.classList.remove('hovering'));
        });

        // -- EFECTO SQUISHY AL PASAR EL RATÓN (Gominolas) --
        document.querySelectorAll('.hover-squish').forEach(el => {
            el.addEventListener('mouseenter', () => {
                gsap.to(el, { scaleX: 1.02, scaleY: 0.96, duration: 0.3, ease: 'power2.out' });
            });
            el.addEventListener('mouseleave', () => {
                gsap.to(el, { scaleX: 1, scaleY: 1, duration: 0.6, ease: 'elastic.out(1, 0.4)' });
            });
        });

        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('mousedown', () => gsap.to(btn, { scale: 0.9, duration: 0.1 }));
            btn.addEventListener('click', (e) => {
                navigator.clipboard?.writeText(btn.dataset.path);
                btn.textContent = 'Copied! ✨';
                btn.classList.add('copied');
                gsap.fromTo(btn, { scale: 0.9 }, { scale: 1, duration: 0.8, ease: 'elastic.out(1, 0.3)' });
                setTimeout(() => { btn.textContent = 'Copy Path'; btn.classList.remove('copied'); }, 1500);
            });
        });

        // ══════════════ TIMELINES & BUBBLE REVEALS ══════════════
        const loadTl = gsap.timeline();
        
        // El NAV cae botando
        loadTl.from('nav', { 
            y: -100, autoAlpha: 0, duration: 1.2, ease: 'elastic.out(1, 0.5)', clearProps: 'transform' 
        });

        // El HeroCard escala suavemente hacia arriba como un globo
        loadTl.from('.hero-wrapper', { 
            scale: 0.8, autoAlpha: 0, y: 50, duration: 1, ease: 'elastic.out(1, 0.6)' 
        }, "-=0.8");

        // Elementos internos del Hero
        loadTl.from('.hero > *', {
            y: 20, autoAlpha: 0, stagger: 0.15, duration: 0.8, ease: 'power3.out'
        }, "-=0.6");

        // Labels y cards revelados en Scroll (Sin 3D, solo flotando hacia arriba)
        gsap.utils.toArray('.section-label').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
                autoAlpha: 0, y: 30, duration: 0.8, ease: 'power3.out'
            });
        });

        const dw = document.querySelector('.debug-warn');
        if(dw) {
            gsap.from(dw, {
                scrollTrigger: { trigger: dw, start: 'top 85%' },
                scale: 0.9, autoAlpha: 0, duration: 0.8, ease: 'elastic.out(1, 0.5)'
            });
        }

        const dg = document.querySelector('.debug-card');
        if(dg) {
            gsap.from(dg, {
                scrollTrigger: { trigger: dg, start: 'top 85%' },
                y: 40, autoAlpha: 0, duration: 0.7, ease: 'power3.out', clearProps: 'transform'
            });
            gsap.from('.debug-row', {
                scrollTrigger: { trigger: dg, start: 'top 80%' },
                x: -30, autoAlpha: 0, stagger: 0.08, duration: 0.6, ease: 'power2.out', clearProps: 'transform'
            });
        }

        const eps = document.querySelector('.endpoints');
        if(eps) {
            gsap.from(eps, {
                scrollTrigger: { trigger: ".endpoints-wrapper", start: 'top 85%' },
                y: 50, autoAlpha: 0, duration: 0.8, ease: 'power3.out'
            });
            
            // Las filas de endpoint se estiran al entrar (squishy reveal)
            gsap.from('.ep-row', {
                scrollTrigger: { trigger: ".endpoints-wrapper", start: 'top 80%' },
                scaleX: 0.9, scaleY: 1.1, autoAlpha: 0, y: 40,
                stagger: 0.1, duration: 0.9, ease: 'elastic.out(1, 0.4)', clearProps: 'transform'
            });
        }

        gsap.from('footer', {
            scrollTrigger: { trigger: 'footer', start: 'top 95%' },
            scale: 0.95, autoAlpha: 0, y: 20, duration: 0.8, ease: 'power3.out', clearProps: 'transform'
        });

        // ══════════════ CLOCK & MISC ══════════════
        const clockEl  = document.getElementById('clock');
        const uptimeEl = document.getElementById('uptime');
        const startTs  = Date.now();
        const pad      = n => String(n).padStart(2,'0');

        function updateTicks() {
            const now = new Date();
            if(clockEl) clockEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            const s = Math.floor((Date.now() - startTs) / 1000);
            if(uptimeEl) uptimeEl.textContent = `${pad(Math.floor(s/3600))}:${pad(Math.floor((s%3600)/60))}:${pad(s%60)}`;
        }
        updateTicks(); setInterval(updateTicks, 1000);

        const themeBtn = document.getElementById('themeToggle');
        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                const html = document.documentElement;
                html.classList.toggle('light');
                
                // Animated theme switch bubble style
                gsap.fromTo(themeBtn, { rotation: -30, scale: 0.8 }, { rotation: 0, scale: 1, duration: 0.8, ease: 'elastic.out(1, 0.4)', clearProps: 'all' });
                localStorage.setItem('uo-theme-fruity', html.classList.contains('light') ? 'light' : 'dark');
            });
        }

        const ri = document.getElementById('req-id');
        if (ri) ri.textContent = 'req_' + Math.random().toString(36).slice(2,12).toUpperCase();

    </script>
</body>
</html>