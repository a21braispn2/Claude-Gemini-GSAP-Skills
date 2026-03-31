<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;700;800&family=Fragment+Mono:ital@0;1&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES CYBERPUNK ══════════════ */
        :root {
            --bg:         #02040a;
            --bg-grid:    #0a1122;
            --text-pri:   #e2e8f0;
            --text-sec:   #64748b;
            --border:     rgba(0, 240, 255, 0.2);
            --border-hov: rgba(0, 240, 255, 0.6);
            
            --neon-blue:  #00f0ff;
            --neon-pink:  #ff003c;
            --neon-green: #00ff66;
            --neon-warn:  #fcd34d;
            --neon-err:   #ef4444;

            --glass:      rgba(2, 4, 10, 0.6);
            --glass-blur: blur(12px);

            --font-head:  'Space Grotesk', sans-serif;
            --font-mono:  'Fragment Mono', monospace;
        }

        :root.light {
            --bg:         #f1f5f9;
            --bg-grid:    #cbd5e1;
            --text-pri:   #0f172a;
            --text-sec:   #475569;
            --border:     rgba(15, 23, 42, 0.15);
            --border-hov: rgba(15, 23, 42, 0.4);
            
            --neon-blue:  #2563eb;
            --neon-pink:  #e11d48;
            --neon-green: #16a34a;
            --neon-warn:  #d97706;
            --neon-err:   #dc2626;

            --glass:      rgba(241, 245, 249, 0.7);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            background-color: var(--bg);
            color: var(--text-pri);
            font-family: var(--font-mono);
            font-size: 13px;
            min-height: 100vh;
            overflow-x: hidden;
            cursor: none; 
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* ══════════════ BACKGROUND PARALLAX GRID ══════════════ */
        #grid-bg {
            position: fixed;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background-image: 
                linear-gradient(to right, var(--bg-grid) 1px, transparent 1px),
                linear-gradient(to bottom, var(--bg-grid) 1px, transparent 1px);
            background-size: 40px 40px;
            transform: perspective(600px) rotateX(60deg) translateY(100px) translateZ(-200px);
            opacity: 0.3;
            z-index: -2;
            pointer-events: none;
        }
        
        .ambient-light {
            position: fixed;
            top: 20%; left: 30%;
            width: 60vw; height: 60vh;
            background: radial-gradient(circle closest-side, rgba(0,240,255,0.05), transparent);
            filter: blur(80px);
            z-index: -1;
            pointer-events: none;
        }

        /* ══════════════ CUSTOM CURSOR ══════════════ */
        .cursor-dot, .cursor-aura {
            position: fixed;
            top: 0; left: 0;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
        }
        
        .cursor-dot {
            width: 6px; height: 6px;
            background: var(--neon-blue);
        }
        
        .cursor-aura {
            width: 40px; height: 40px;
            border: 1px solid rgba(0, 240, 255, 0.4);
            box-shadow: 0 0 10px rgba(0,240,255,0.2);
            transition: width 0.3s, height 0.3s, background 0.3s, border-color 0.3s;
        }

        .cursor-aura.hovering {
            width: 60px; height: 60px;
            background: rgba(255, 0, 60, 0.1);
            border-color: var(--neon-pink);
            box-shadow: 0 0 15px rgba(255,0,60,0.4);
            backdrop-filter: blur(2px);
        }

        /* ══════════════ NAV ══════════════ */
        nav {
            position: sticky;
            top: 0; z-index: 100;
            display: flex;
            align-items: stretch;
            height: 60px;
            background: var(--glass);
            backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--border);
            visibility: hidden; /* GSAP reveal */
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 30px;
            border-right: 1px solid var(--border);
        }

        .nav-brand-icon {
            color: var(--neon-pink);
            font-size: 20px;
            filter: drop-shadow(0 0 5px var(--neon-pink));
        }

        .nav-brand h1 {
            font-family: var(--font-head);
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.1em;
            color: var(--text-pri);
        }

        .nav-status {
            display: flex; align-items: center; gap: 8px;
            padding: 0 24px;
            border-right: 1px solid var(--border);
            color: var(--neon-green);
            font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;
        }

        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--neon-green);
            box-shadow: 0 0 8px var(--neon-green);
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        nav .clock-wrapper {
            margin-left: auto;
            display: flex; align-items: center;
            padding: 0 24px;
            border-left: 1px solid var(--border);
            color: var(--neon-blue);
            font-size: 11px; letter-spacing: 0.1em;
        }
        
        /* ── Theme Button ── */
        .theme-btn {
            background: transparent; border: none; padding: 0 24px;
            border-left: 1px solid var(--border); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-sec); transition: color 0.3s;
            font-family: var(--font-mono); font-size: 14px;
        }
        .theme-btn:hover { color: var(--neon-blue); }
        .theme-btn .moon { display: none; }
        :root.light .theme-btn .sun { display: none; }
        :root.light .theme-btn .moon { display: block; }

        /* ══════════════ LAYOUT & TYPOGRAPHY ══════════════ */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 24px 100px;
            perspective: 1200px;
        }

        .section-label {
            display: inline-block;
            font-family: var(--font-head);
            font-size: 12px; font-weight: 700; letter-spacing: 0.25em; text-transform: uppercase;
            color: var(--neon-blue);
            margin-bottom: 24px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
            visibility: hidden;
            text-shadow: 0 0 10px rgba(0,240,255,0.3);
        }

        /* ══════════════ HERO (3D TILT) ══════════════ */
        .hero-wrapper {
            transform-style: preserve-3d;
            margin-bottom: 60px;
            visibility: hidden;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            background: var(--glass);
            border: 1px solid var(--border);
            backdrop-filter: var(--glass-blur);
            box-shadow: inset 0 0 20px rgba(0,240,255,0.1), 0 10px 30px rgba(0,0,0,0.5);
            position: relative;
            transform-style: preserve-3d;
            border-radius: 8px; /* Redondear un poco para un look m\xE1s org\xE1nico */
        }

        .hero::before { /* Glitch Edge */
            content: ''; position: absolute; top: -1px; left: -1px; width: 20px; height: 20px;
            border-top: 2px solid var(--neon-pink); border-left: 2px solid var(--neon-pink);
            box-shadow: -2px -2px 10px rgba(255,0,60,0.5);
        }

        .hero-main {
            padding: 48px; border-right: 1px solid var(--border);
            transform: translateZ(30px); /* 3D pop out */
        }

        .hero-tag {
            display: inline-block; font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--bg); background: var(--neon-blue); padding: 4px 12px; margin-bottom: 24px;
            box-shadow: 0 0 10px var(--neon-blue);
        }

        .hero h2 {
            font-family: var(--font-head); font-size: 48px; font-weight: 800; line-height: 1;
            text-transform: uppercase; letter-spacing: -0.02em; margin-bottom: 16px;
        }
        
        .char { display: inline-block; /* For SplitText effect */ }

        .hero h2 em { font-style: normal; color: transparent; -webkit-text-stroke: 1px var(--neon-pink); display: block; }

        .hero-sub { color: var(--text-sec); font-size: 12px; line-height: 1.6; max-width: 400px; }

        .hero-stats {
            display: grid; grid-template-rows: repeat(3, 1fr);
            transform: translateZ(20px);
        }

        .stat-cell {
            padding: 0 32px; display: flex; flex-direction: column; justify-content: center; gap: 8px;
            border-bottom: 1px solid var(--border);
        }
        .stat-cell:last-child { border-bottom: none; }

        .stat-label { font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase; color: var(--text-sec); }
        .stat-value { font-family: var(--font-head); font-size: 18px; font-weight: 700; color: var(--text-pri); }
        .stat-value.accent { color: var(--neon-blue); text-shadow: 0 0 8px rgba(0,240,255,0.4); }

        /* ══════════════ DEBUG SECTION ══════════════ */
        .debug-warn {
            display: flex; align-items: center; gap: 16px; padding: 16px 24px;
            background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.3);
            border-left: 4px solid var(--neon-err); color: var(--neon-err);
            font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.1); visibility: hidden;
        }

        .debug-grid {
            border: 1px solid var(--border); background: var(--glass); backdrop-filter: var(--glass-blur);
            margin-bottom: 60px; visibility: hidden;
        }

        .debug-row {
            display: grid; grid-template-columns: 220px 1fr; border-bottom: 1px solid var(--border);
            visibility: hidden; transition: background 0.3s;
        }
        .debug-row:hover { background: rgba(0, 240, 255, 0.03); }
        .debug-row:last-child { border-bottom: none; }

        .dk {
            padding: 16px 24px; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--text-sec); border-right: 1px solid var(--border); background: rgba(0,0,0,0.2);
            display: flex; align-items: center;
        }
        .dv { padding: 16px 24px; font-size: 12px; color: var(--text-pri); display: flex; align-items: center; }

        .dv.hi { color: var(--neon-blue); }
        .dv.wa { color: var(--neon-warn); }
        .dv.pa { color: var(--text-sec); filter: blur(2px); transition: filter 0.3s; }
        .dv.pa:hover { filter: blur(0); color: var(--neon-pink); } /* Easter Egg */

        /* ══════════════ ENDPOINTS CON 3D PERSPECTIVE ══════════════ */
        .endpoints-wrapper { perspective: 1000px; transform-style: preserve-3d; }
        
        .endpoints {
            border: 1px solid var(--border); background: var(--glass); backdrop-filter: var(--glass-blur);
            margin-bottom: 40px; visibility: hidden; border-radius: 4px; overflow: hidden;
        }

        .ep-row {
            display: flex; align-items: center; gap: 20px; padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s, transform 0.2s;
            transform-origin: center right;
        }
        .ep-row:last-child { border-bottom: none; }
        .ep-row:hover { background: rgba(0, 240, 255, 0.05); }

        .ep-num { font-family: var(--font-head); font-size: 10px; font-weight: 700; color: var(--text-sec); opacity: 0.5; }

        .badge {
            font-family: var(--font-head); font-size: 9px; font-weight: 800; letter-spacing: 0.15em;
            padding: 6px 14px; border-radius: 2px; min-width: 64px; text-align: center; border: 1px solid;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
        }
        .badge.GET    { color: var(--neon-blue);  border-color: rgba(0,240,255,0.4);  background: rgba(0,240,255,0.05); }
        .badge.POST   { color: var(--neon-green); border-color: rgba(0,255,102,0.4); background: rgba(0,255,102,0.05); }
        .badge.PUT    { color: var(--neon-warn);  border-color: rgba(252,211,77,0.4); background: rgba(252,211,77,0.05); }
        .badge.DELETE { color: var(--neon-err);   border-color: rgba(239,68,68,0.4);  background: rgba(239,68,68,0.05); }

        :root.light .badge.GET    { border-color: rgba(37,99,235,0.4);  background: rgba(37,99,235,0.05); }
        :root.light .badge.POST   { border-color: rgba(22,163,74,0.4);  background: rgba(22,163,74,0.05); }
        :root.light .badge.PUT    { border-color: rgba(217,119,6,0.4);  background: rgba(217,119,6,0.05); }
        :root.light .badge.DELETE { border-color: rgba(220,38,38,0.4);  background: rgba(220,38,38,0.05); }
        :root.light .ambient-light { display: none; } /* En modo claro bajamos el resplandor */
        :root.light .hero { box-shadow: inset 0 0 20px rgba(0,0,0,0.02), 0 10px 30px rgba(0,0,0,0.05); }
        :root.light .hero::before { box-shadow: none; }
        :root.light .cursor-aura { border-color: rgba(15,23,42,0.2); box-shadow: none; }
        :root.light .cursor-aura.hovering { border-color: var(--neon-blue); background: rgba(37,99,235,0.1); }
        :root.light .section-label { text-shadow: none; }

        .ep-row:hover .badge { filter: brightness(1.3); box-shadow: 0 0 15px currentColor; }

        .ep-path { font-size: 13px; color: var(--text-pri); }
        .ep-desc { margin-left: auto; font-size: 10px; color: var(--text-sec); letter-spacing: 0.1em; text-transform: uppercase; }

        /* Copy Btn Estilo Cyber */
        .copy-btn {
            margin-left: 20px; padding: 6px 14px;
            font-family: var(--font-mono); font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--neon-blue); background: transparent; border: 1px solid rgba(0,240,255,0.3);
            border-radius: 2px; cursor: pointer; position: relative; overflow: hidden;
            transition: all 0.2s; will-change: transform; flex-shrink: 0;
            cursor: none; /* Que use el Aura magnética */
        }
        .copy-btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0,240,255,0.2), transparent);
            transition: left 0.4s;
        }
        .copy-btn:hover { background: rgba(0,240,255,0.1); border-color: var(--neon-blue); box-shadow: 0 0 10px rgba(0,240,255,0.2); }
        .copy-btn:hover::before { left: 100%; }
        
        .copy-btn.copied { color: var(--bg); background: var(--neon-green); border-color: var(--neon-green); box-shadow: 0 0 15px var(--neon-green); }

        /* ══════════════ FOOTER ══════════════ */
        footer {
            border-top: 1px solid var(--border); padding: 24px 30px;
            display: flex; align-items: center; justify-content: space-between;
            font-size: 10px; color: var(--text-sec); letter-spacing: 0.15em; text-transform: uppercase;
            visibility: hidden; background: transparent;
        }
        .footer-accent { width: 40px; height: 1px; background: var(--neon-pink); box-shadow: 0 0 8px var(--neon-pink); }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 768px) {
            .hero { grid-template-columns: 1fr; }
            .hero-main { border-right: none; border-bottom: 1px solid var(--border); padding: 32px; }
            .hero-stats { grid-template-rows: none; grid-template-columns: repeat(3, 1fr); }
            .stat-cell { border-bottom: none; border-right: 1px solid var(--border); padding: 20px; }
            .stat-cell:last-child { border-right: none; }
            .ep-desc { display: none; }
            .cursor-dot, .cursor-aura { display: none; } /* Disable custom cursor on mobile */
            body, .copy-btn, .dv.pa, .theme-btn { cursor: auto; }
        }
    </style>
    
    <!-- Evitar FOUC (Flash of Unstyled Content) -->
    <script>
        if (localStorage.getItem('uo-theme') === 'light') {
            document.documentElement.classList.add('light');
        }
    </script>

    <!-- GSAP CDN (Core & ScrollTrigger) no defer para que dispare de inmediato -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- Ambient / Background -->
    <div id="grid-bg"></div>
    <div class="ambient-light"></div>

    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-aura"></div>

    <nav>
        <div class="nav-brand">
            <span class="nav-brand-icon">⬡</span>
            <h1>UO API</h1>
        </div>
        <div class="nav-status">
            <span class="status-dot"></span> System Nominal
        </div>
        <div class="clock-wrapper">
            ENV: {{ app()->environment() }} <span style="margin:0 12px;color:var(--border)">|</span> <span id="clock">00:00:00</span>
        </div>
        <button class="theme-btn" id="themeToggle" aria-label="Toggle Theme">
            <span class="sun">☼</span>
            <span class="moon">☽</span>
        </button>
    </nav>

    <div class="container">

        {{-- ── HERO (3D TILT) ── --}}
        <div class="hero-wrapper">
            <div class="hero" id="hero-card">
                <div class="hero-main">
                    <div class="hero-tag">// Core Interface</div>
                    <h2 id="split-target">GATEWAY<em>ONLINE</em></h2>
                    <p class="hero-sub">Authentication layer secure. All microservices are responding accurately to inbound traffic requests.</p>
                </div>
                <div class="hero-stats">
                    <div class="stat-cell">
                        <span class="stat-label">Uptime</span>
                        <span class="stat-value accent" id="uptime">00:00:00</span>
                    </div>
                    <div class="stat-cell">
                        <span class="stat-label">Requests/s</span>
                        <span class="stat-value">2,048</span>
                    </div>
                    <div class="stat-cell">
                        <span class="stat-label">Latency</span>
                        <span class="stat-value">12ms</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── DEBUG ── --}}
        @if(config('app.debug'))
        <div class="debug-warn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            Security Warning: Debug mode active on UO API
        </div>

        <span class="section-label">Instance Variables</span>
        <div class="debug-grid">
            <div class="debug-row"><span class="dk">Framework</span><span class="dv hi">Lrvl {{ app()->version() }}</span></div>
            <div class="debug-row"><span class="dk">App Vers</span><span class="dv">v1.0{{ config('app.version') }}</span></div>
            <div class="debug-row"><span class="dk">Core/PHP</span><span class="dv">{{ phpversion() }}</span></div>
            <div class="debug-row"><span class="dk">Database</span><span class="dv wa">{{ config('database.default') }}</span></div>
            <div class="debug-row"><span class="dk">DB Host</span><span class="dv">{{ config('database.connections.mysql.host') }}</span></div>
            <div class="debug-row"><span class="dk">DB Port</span><span class="dv">{{ config('database.connections.mysql.port') }}</span></div>
            <div class="debug-row"><span class="dk">DB User</span><span class="dv">{{ config('database.connections.mysql.username') }}</span></div>
            <div class="debug-row"><span class="dk">DB Pass</span><span class="dv pa">[ENCRYPTED - HOVER TO REVEAL]</span></div>
        </div>
        @endif

        {{-- ── ENDPOINTS (3D Stagger) ── --}}
        <span class="section-label endpoints-label">Active Routes</span>
        <div class="endpoints-wrapper">
            <div class="endpoints">
                <div class="ep-row"><span class="ep-num">01</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/status</span><span class="ep-desc">Health Check</span><button class="copy-btn hover-target" data-path="/api/v1/status">Copy</button></div>
                <div class="ep-row"><span class="ep-num">02</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/users</span><span class="ep-desc">List Entity</span><button class="copy-btn hover-target" data-path="/api/v1/users">Copy</button></div>
                <div class="ep-row"><span class="ep-num">03</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/users/{id}</span><span class="ep-desc">Get Entity</span><button class="copy-btn hover-target" data-path="/api/v1/users/{id}">Copy</button></div>
                <div class="ep-row"><span class="ep-num">04</span><span class="badge POST">POST</span><span class="ep-path">/api/v1/users</span><span class="ep-desc">Create Entity</span><button class="copy-btn hover-target" data-path="/api/v1/users">Copy</button></div>
                <div class="ep-row"><span class="ep-num">05</span><span class="badge PUT">PUT</span><span class="ep-path">/api/v1/users/{id}</span><span class="ep-desc">Mutate Entity</span><button class="copy-btn hover-target" data-path="/api/v1/users/{id}">Copy</button></div>
                <div class="ep-row"><span class="ep-num">06</span><span class="badge DELETE">DEL</span><span class="ep-path">/api/v1/users/{id}</span><span class="ep-desc">Purge Entity</span><button class="copy-btn hover-target" data-path="/api/v1/users/{id}">Copy</button></div>
            </div>
        </div>

    </div>

    <footer>
        <span id="req-id"></span>
        <div class="footer-accent"></div>
        <span>UO API &copy; {{ date('Y') }}</span>
    </footer>

    <!-- ══════════════ MOTOR GSAP AVANZADO ══════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // -- Setup: Prevenir animaciones si el usuario lo pide
        const prefersR = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersR) gsap.globalTimeline.timeScale(0);

        // -- Simulación de SplitText para Título Hero
        const titleEl = document.getElementById('split-target');
        if(titleEl) {
            const textNodes = Array.from(titleEl.childNodes);
            let newContent = '';
            textNodes.forEach(node => {
                if(node.nodeType === 3) { // Text Node
                    const chars = node.nodeValue.split('');
                    chars.forEach(c => newContent += c !== ' ' ? `<span class="char">${c}</span>` : '&nbsp;');
                } else if(node.nodeName.toLowerCase() === 'em') { // <em> node
                    newContent += `<em>${node.innerHTML.split('').map(c => c !== ' ' ? `<span class="char">${c}</span>` : '&nbsp;').join('')}</em>`;
                }
            });
            titleEl.innerHTML = newContent;
        }

        // -- CUSTOM CURSOR MAGNÉTICO (quickTo for performance)
        const dot = document.querySelector('.cursor-dot');
        const aura = document.querySelector('.cursor-aura');
        
        let mouseX = window.innerWidth / 2, mouseY = window.innerHeight / 2;
        
        const xToDot = gsap.quickTo(dot, "x", {duration: 0.1, ease: "power3", clearProps: 'transform'});
        const yToDot = gsap.quickTo(dot, "y", {duration: 0.1, ease: "power3", clearProps: 'transform'});
        const xToAura = gsap.quickTo(aura, "x", {duration: 0.8, ease: "elastic.out(1, 0.4)", clearProps: 'transform'});
        const yToAura = gsap.quickTo(aura, "y", {duration: 0.8, ease: "elastic.out(1, 0.4)", clearProps: 'transform'});

        window.addEventListener("mousemove", e => {
            mouseX = e.clientX; mouseY = e.clientY;
            xToDot(mouseX); yToDot(mouseY);
            xToAura(mouseX); yToAura(mouseY);
            
            // Efecto Parallax muy sutil en el grid de fondo
            gsap.to('#grid-bg', {
                x: (mouseX - window.innerWidth/2) * -0.05,
                y: (mouseY - window.innerHeight/2) * -0.05,
                duration: 1, ease: 'power2.out'
            });
            // Parallax Luz Ambiental
            gsap.to('.ambient-light', {
                x: (mouseX - window.innerWidth/2) * 0.1,
                y: (mouseY - window.innerHeight/2) * 0.1,
                duration: 2, ease: 'power2.out'
            });
        });

        // Hovers del cursor magnético
        document.querySelectorAll('.hover-target, .ep-row, a, .dv.pa').forEach(el => {
            el.addEventListener('mouseenter', () => aura.classList.add('hovering'));
            el.addEventListener('mouseleave', () => aura.classList.remove('hovering'));
        });

        // -- 3D TILT EFFECT (HERO CARD)
        const heroCard = document.getElementById('hero-card');
        const heroWrap = document.querySelector('.hero-wrapper');
        if(heroWrap && heroCard) {
            heroWrap.addEventListener('mousemove', e => {
                const rect = heroWrap.getBoundingClientRect();
                const relX = e.clientX - rect.left;
                const relY = e.clientY - rect.top;
                const xVal = (relX / rect.width - 0.5) * 10; // Rota max 10deg
                const yVal = -(relY / rect.height - 0.5) * 10;
                
                gsap.to(heroCard, {
                    rotationY: xVal, rotationX: yVal, duration: 0.5, ease: 'power2.out', transformPerspective: 1000
                });
            });
            heroWrap.addEventListener('mouseleave', () => {
                gsap.to(heroCard, { rotationY: 0, rotationX: 0, duration: 1, ease: 'elastic.out(1,0.5)', clearProps: 'transform'});
            });
        }

        // ══════════════ TIMELINES & SCROLLTRIGGERS ══════════════

        // 1. CARGA INICIAL (Master Timeline)
        const loadTl = gsap.timeline({ defaults: { ease: 'power4.out' } });
        
        loadTl
            .from('nav', { yPercent: -100, autoAlpha: 0, duration: 0.8, clearProps: 'transform' })
            .from('.hero-wrapper', { autoAlpha: 0, y: 50, duration: 1.2 }, "-=0.4")
            // SplitText chars animation
            .from('.hero h2 .char', {
                y: 40, rotateX: -90, autoAlpha: 0,
                stagger: 0.03, duration: 0.8, ease: 'back.out(1.5)', clearProps: 'transform'
            }, "-=0.8")
            .from('.hero-tag', { x: -30, autoAlpha: 0, duration: 0.6 }, "-=0.5")
            .from('.hero-sub', { autoAlpha: 0, y: 10, duration: 0.6 }, "-=0.4")
            .from('.stat-cell', { x: 30, autoAlpha: 0, stagger: 0.1, duration: 0.6 }, "-=0.4");

        // 2. REVEAL SCROLL (Secciones y Debug)
        gsap.utils.toArray('.section-label').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: 'top 90%', toggleActions: 'play none none none' },
                x: -40, autoAlpha: 0, duration: 0.8, ease: 'power3.out'
            });
        });

        const debugWarn = document.querySelector('.debug-warn');
        if (debugWarn) {
            gsap.from(debugWarn, {
                scrollTrigger: { trigger: debugWarn, start: 'top 85%' },
                scale: 0.95, autoAlpha: 0, duration: 0.6, ease: 'back.out(1.2)', clearProps: 'transform'
            });
        }

        const debugGrid = document.querySelector('.debug-grid');
        if (debugGrid) {
            gsap.from(debugGrid, {
                scrollTrigger: { trigger: debugGrid, start: 'top 85%' },
                autoAlpha: 0, y: 30, duration: 0.6, clearProps: 'transform'
            });
            gsap.from('.debug-row', {
                scrollTrigger: { trigger: debugGrid, start: 'top 80%' },
                x: -20, autoAlpha: 0, stagger: 0.05, duration: 0.5, ease: 'power2.out', clearProps: 'transform'
            });
        }

        // 3. PERSPECTIVE 3D ENDPOINTS (El Plato Fuerte)
        const epWrap = document.querySelector('.endpoints');
        if (epWrap) {
            gsap.from(epWrap, {
                scrollTrigger: { trigger: ".endpoints-wrapper", start: 'top 85%' },
                autoAlpha: 0, rotationX: -15, y: 50, duration: 0.8, transformOrigin: "top center", ease: 'power3.out', clearProps: 'transform'
            });
            
            gsap.from('.ep-row', {
                scrollTrigger: { trigger: ".endpoints-wrapper", start: 'top 80%' },
                x: 80, rotationY: -10, autoAlpha: 0, stagger: 0.08, duration: 0.7, 
                ease: "back.out(1.2)", clearProps: 'transform'
            });

            // Badges scale independiente (sin romper el flujo)
            gsap.from('.badge', {
                 scrollTrigger: { trigger: ".endpoints-wrapper", start: 'top 80%' },
                 scale: 0, autoAlpha: 0, stagger: 0.08, duration: 0.5,
                 ease: "back.out(2)", immediateRender: false, clearProps: 'transform'
            });
        }

        // 4. FOOTER
        gsap.from('footer', {
            scrollTrigger: { trigger: 'footer', start: 'top 98%' },
            y: 20, autoAlpha: 0, duration: 0.8, ease: 'power2.out', clearProps: 'transform'
        });

        // ══════════════ LÓGICA DE BOTONES COPY (MAGNÉTICOS + FEEDBACK) ══════════════
        document.querySelectorAll('.copy-btn').forEach(b => {
            // Magnetic pull a los botones copy individuales, encima del aura genérica
            b.addEventListener('mousemove', e => {
                const r = b.getBoundingClientRect();
                const dX = e.clientX - (r.left + r.width/2);
                const dY = e.clientY - (r.top + r.height/2);
                gsap.to(b, { x: dX * 0.3, y: dY * 0.4, duration: 0.3, ease: 'power2.out' });
            });
            b.addEventListener('mouseleave', () => {
                gsap.to(b, { x: 0, y: 0, duration: 0.6, ease: 'elastic.out(1,0.3)', clearProps:'transform' });
            });
            
            b.addEventListener('click', (e) => {
                e.preventDefault();
                navigator.clipboard?.writeText(b.dataset.path);
                
                // Micro-animación de click
                gsap.timeline()
                    .to(b, { scale: 0.85, duration: 0.1, ease: 'power1.in' })
                    .to(b, { scale: 1, duration: 0.6, ease: 'elastic.out(1,0.4)', clearProps:'transform' });
                
                b.textContent = '// OK';
                b.classList.add('copied');
                
                // Efecto de pulso en el aura para feeback
                gsap.timeline()
                    .to(aura, { scale: 1.5, borderColor: 'var(--neon-green)', duration: 0.1 })
                    .to(aura, { scale: 1, borderColor: 'var(--neon-pink)', duration: 0.4, clearProps:'all' });

                setTimeout(() => { b.textContent = 'Copy'; b.classList.remove('copied'); }, 1500);
            });
        });

        // ══════════════ CLOCK & UPTIME LOGIC ══════════════
        const clockEl  = document.getElementById('clock');
        const uptimeEl = document.getElementById('uptime');
        const startTs  = Date.now();
        const pad      = n => String(n).padStart(2,'0');

        function updateTicks() {
            const now = new Date();
            clockEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            const s = Math.floor((Date.now() - startTs) / 1000);
            uptimeEl.textContent = `${pad(Math.floor(s/3600))}:${pad(Math.floor((s%3600)/60))}:${pad(s%60)}`;
        }
        updateTicks(); setInterval(updateTicks, 1000);

        // ══════════════ THEME TOGGLE ══════════════
        const themeBtn = document.getElementById('themeToggle');
        themeBtn.addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('light');
            
            // Animación sutil de click en GSAP
            gsap.fromTo(themeBtn, 
                { scale: 0.8 }, 
                { scale: 1, duration: 0.5, ease: 'elastic.out(1,0.5)', clearProps: 'transform' }
            );

            localStorage.setItem('uo-theme', html.classList.contains('light') ? 'light' : 'dark');
        });

        // ══════════════ IDS FAKE ══════════════
        const ri = document.getElementById('req-id');
        if (ri) ri.textContent = 'req_' + Math.random().toString(36).slice(2,12).toUpperCase();
        
    </script>
</body>
</html>