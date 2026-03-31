<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API - BRUTAL</title>
    <!-- Dark Mode Control initially (we'll avoid FOUC for Brutal theme) -->
    <script>
        if (localStorage.getItem('uo-theme-brutal') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES NEO-BRUTALISM ══════════════ */
        :root {
            /* Modo Claro (Default): Crema Industrial */
            --bg:         #F4F0EA;
            --text-pri:   #000000;
            --border-col: #000000;
            --shadow-col: #000000;

            --bg-card:    #FFFFFF;
            
            --col-yellow: #FFE600;
            --col-pink:   #FF007F;
            --col-cyan:   #00E5FF;
            --col-lime:   #00FF41;

            --font-head:  'Archivo Black', sans-serif;
            --font-mono:  'Space Mono', monospace;
            
            --border-size: 3px;
        }

        :root.dark {
            /* Inverse Brutalism */
            --bg:         #111111;
            --text-pri:   #FFFFFF;
            --border-col: #FFFFFF;
            --shadow-col: #FFFFFF;

            --bg-card:    #222222;
            
            --col-yellow: #CCCC00;
            --col-pink:   #D9006C;
            --col-cyan:   #00B3CC;
            --col-lime:   #00CC33;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        body {
            background-color: var(--bg);
            color: var(--text-pri);
            font-family: var(--font-mono);
            font-size: 15px;
            min-height: 100vh;
            transition: background-color 0.1s, color 0.1s;
        }

        /* ══════════════ REUSABLES: BRUTAL BOX ══════════════ */
        .brutal-box {
            background: var(--bg-card);
            border: var(--border-size) solid var(--border-col);
            box-shadow: 6px 6px 0px var(--shadow-col);
            border-radius: 0;
            color: var(--text-pri);
        }

        .brutal-btn {
            background: var(--bg-card);
            border: var(--border-size) solid var(--border-col);
            box-shadow: 4px 4px 0px var(--shadow-col);
            cursor: pointer;
            transition: box-shadow 0.1s, transform 0.1s;
            font-family: var(--font-head);
            text-transform: uppercase;
        }
        
        .brutal-btn:hover {
            box-shadow: 0px 0px 0px var(--shadow-col);
            transform: translate(4px, 4px);
        }

        /* ══════════════ INFINITE MARQUEE ══════════════ */
        .marquee-container {
            width: 100%;
            background: var(--col-yellow);
            border-top: var(--border-size) solid var(--border-col);
            border-bottom: var(--border-size) solid var(--border-col);
            color: #000;
            padding: 8px 0;
            overflow: hidden;
            display: flex;
            font-family: var(--font-head);
            font-size: 24px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        :root.dark .marquee-container { background: #000; color: var(--col-yellow); }
        .marquee-track {
            display: flex; gap: 2vw;
            will-change: transform;
        }

        /* ══════════════ NAV BRUTAL ══════════════ */
        nav {
            display: flex; align-items: stretch; justify-content: space-between;
            background: var(--col-cyan);
            border-bottom: var(--border-size) solid var(--border-col);
            height: 60px;
        }
        :root.dark nav { background: var(--bg-card); }

        .nav-brand {
            display: flex; align-items: center; gap: 12px; padding: 0 24px;
            border-right: var(--border-size) solid var(--border-col);
            background: var(--col-pink); color: #000;
        }
        .nav-brand h1 { font-family: var(--font-head); font-size: 20px; line-height: 1; }

        .nav-clock {
            display: flex; align-items: center; padding: 0 24px;
            font-weight: 700; color: #000;
            border-left: var(--border-size) solid var(--border-col);
        }
        :root.dark .nav-clock { color: var(--text-pri); }
        .nav-clock span.clk { background: #000; color: #fff; padding: 4px 8px; margin-left: 10px; }
        :root.dark .nav-clock span.clk { background: #fff; color: #000; }

        .theme-btn {
            padding: 0 24px; font-size: 20px; 
            border-left: var(--border-size) solid var(--border-col);
            border-right: none; border-top: none; border-bottom: none;
            background: var(--col-yellow); color: #000;
            cursor: pointer; display: flex; align-items: center;
        }
        .theme-btn:hover { background: #000; color: var(--col-yellow); }
        .theme-btn .moon { display: block; }
        :root.dark .theme-btn .sun { display: block; }
        :root.dark .theme-btn .moon { display: none; }

        /* ══════════════ LAYOUT ══════════════ */
        .container {
            max-width: 960px; margin: 60px auto 100px; padding: 0 20px;
        }
        h2.section-label {
            font-family: var(--font-head); font-size: 32px;
            letter-spacing: -0.02em; margin-bottom: 24px; text-transform: uppercase;
            width: fit-content; background: var(--col-cyan); color: #000; padding: 4px 12px;
            border: var(--border-size) solid var(--border-col);
            box-shadow: 4px 4px 0 var(--border-col);
        }

        /* ══════════════ HERO BRUTAL ══════════════ */
        .hero {
            padding: 40px; margin-bottom: 80px; position: relative;
        }
        .hero::before {
            content:''; position: absolute; inset: -10px; background: repeating-linear-gradient(45deg, var(--border-col) 0, var(--border-col) 2px, transparent 2px, transparent 10px);
            z-index: -1;
        }

        .hero h2 {
            font-size: 70px; line-height: 0.9; text-transform: uppercase; margin-bottom: 24px; color: var(--text-pri);
        }
        .hero-sub {
            font-size: 16px; max-width: 500px; line-height: 1.5; font-weight: 700; margin-bottom: 30px;
            background: var(--col-yellow); color: #000; padding: 10px; border: 2px solid #000; box-shadow: 3px 3px 0 #000;
        }

        .stats-grid { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-block {
            flex: 1; padding: 16px; display: flex; flex-direction: column;
            background: var(--col-cyan); color: #000;
        }
        .stat-block:nth-child(2) { background: var(--col-pink); color: #fff; }
        .stat-block:nth-child(3) { background: var(--col-lime); color: #000; }
        .stat-block span { font-family: var(--font-head); font-size: 28px; }
        .stat-block small { font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }

        /* ══════════════ DEBUG TAPE ══════════════ */
        .debug-warn {
            background: var(--col-yellow); color: #000;
            padding: 16px 24px; font-weight: 700; font-size: 16px; margin-bottom: 30px;
            display: flex; align-items: center; gap: 16px; text-transform: uppercase;
        }
        .debug-panel { margin-bottom: 80px; padding: 0; }
        .debug-row {
            display: flex; border-bottom: var(--border-size) solid var(--border-col);
        }
        .debug-row:last-child { border-bottom: none; }
        .debug-row > div { padding: 16px 20px; }
        .debug-row > div:first-child { 
            width: 200px; border-right: var(--border-size) solid var(--border-col); 
            font-weight: 700; text-transform: uppercase; background: var(--col-pink); color: #000; 
        }
        .debug-row > div:last-child { font-weight: 700; }

        /* ══════════════ DEAL OF CARDS (ENDPOINTS) ══════════════ */
        .endpoints-grid {
            display: flex; flex-direction: column; gap: 20px;
            perspective: 1200px;
        }

        .ep-row {
            display: flex; align-items: stretch; height: 64px;
            /* Se les aplica el brutal-box */
        }
        
        .ep-method {
            padding: 0 20px; display: flex; align-items: center; justify-content: center;
            font-family: var(--font-head); font-size: 16px;
            border-right: var(--border-size) solid var(--border-col);
            background: #fff; color: #000; width: 100px;
        }
        .ep-row.get .ep-method { background: var(--col-cyan); }
        .ep-row.post .ep-method { background: var(--col-pink); color: #fff; }
        .ep-row.put .ep-method { background: var(--col-yellow); }
        .ep-row.delete .ep-method { background: #000; color: #fff; }

        .ep-path {
            padding: 0 20px; display: flex; align-items: center; font-weight: 700; flex: 1;
        }

        .ep-copy {
            padding: 0 24px; border-left: var(--border-size) solid var(--border-col);
            background: transparent; color: var(--text-pri); border-top: none; border-bottom: none; border-right: none;
            font-family: var(--font-head); font-size: 14px; text-transform: uppercase; cursor: pointer;
            transition: background 0.1s;
        }
        .ep-copy:hover { background: var(--col-yellow); color: #000; }
        .ep-copy:active { background: var(--col-lime); color: #000; }

        /* ══════════════ FOOTER ══════════════ */
        footer {
            margin: 0 20px 40px; padding: 24px; display: flex; justify-content: space-between;
            background: var(--bg-card); border: var(--border-size) solid var(--border-col);
            font-family: var(--font-head); font-size: 16px; text-transform: uppercase;
        }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 768px) {
            .hero h2 { font-size: 40px; }
            .debug-row { flex-direction: column; }
            .debug-row > div:first-child { width: 100%; border-right: none; border-bottom: 2px solid var(--border-col); }
            .ep-row { height: auto; flex-wrap: wrap; }
            .ep-method { width: 100%; border-right: none; border-bottom: var(--border-size) solid var(--border-col); padding: 12px; }
            .ep-path { padding: 16px; width: 100%; }
            .ep-copy { border-left: none; border-top: var(--border-size) solid var(--border-col); width: 100%; padding: 16px; }
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- MARQUEE TAPE -->
    <div class="marquee-container">
        <div class="marquee-track">
            <span>UO API SERVICES // GATEWAY ONLINE // DO NOT CROSS // </span>
            <span>UO API SERVICES // GATEWAY ONLINE // DO NOT CROSS // </span>
            <span>UO API SERVICES // GATEWAY ONLINE // DO NOT CROSS // </span>
            <span>UO API SERVICES // GATEWAY ONLINE // DO NOT CROSS // </span>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav>
        <div class="nav-brand">
            <h1>UO_API</h1>
        </div>
        <div style="display: flex;">
            <div class="nav-clock">
                ENV: {{ app()->environment() }} <span class="clk" id="clock">00:00:00</span>
            </div>
            <button class="theme-btn" id="themeToggle" aria-label="Toggle Theme">
                <span class="sun" style="display:none;">☀</span>
                <span class="moon" style="display:none;">☾</span>
            </button>
        </div>
    </nav>

    <div class="container">

        {{-- ── HERO BLOCK ── --}}
        <div class="hero brutal-box anim-hero">
            <h2>Gateway<br>Online</h2>
            <div class="hero-sub">Welcome to the Neo-Brutal API Hub. Raw data, no compromises. All systems executing smoothly.</div>
            
            <div class="stats-grid">
                <div class="stat-block brutal-box">
                    <small>Uptime</small>
                    <span id="uptime">00:00:00</span>
                </div>
                <div class="stat-block brutal-box">
                    <small>Requests</small>
                    <span>2.04k</span>
                </div>
                <div class="stat-block brutal-box">
                    <small>Status</small>
                    <span>NOMINAL</span>
                </div>
            </div>
        </div>

        {{-- ── DEBUG PANEL ── --}}
        @if(config('app.debug'))
        <div class="debug-warn brutal-box anim-fade">
            ⚠ Debug mode explicitly active
        </div>

        <h2 class="section-label anim-fade">Server Detail</h2>
        <div class="debug-panel brutal-box anim-fade">
            <div class="debug-row"><div>Framework</div><div>Lrvl {{ app()->version() }}</div></div>
            <div class="debug-row"><div>App Version</div><div>v1.0{{ config('app.version') }}</div></div>
            <div class="debug-row"><div>PHP Engine</div><div>{{ phpversion() }}</div></div>
            <div class="debug-row"><div>Database</div><div style="color:var(--col-pink)">{{ config('database.default') }}</div></div>
            <div class="debug-row"><div>Host</div><div>{{ config('database.connections.mysql.host') }}</div></div>
            <div class="debug-row"><div>Username</div><div>{{ config('database.connections.mysql.username') }}</div></div>
            <div class="debug-row">
                <div>Password</div>
                <button class="brutal-btn" style="padding:4px 12px; font-size:12px;" onclick="this.innerText='HIDDEN_FOR_SECURITY'; this.disabled=true;">REVEAL DATA</button>
            </div>
        </div>
        @endif

        {{-- ── ENDPOINTS TRAY ── --}}
        <h2 class="section-label anim-fade">Endpoints</h2>
        <div class="endpoints-grid">
            <div class="ep-row brutal-box get"><div class="ep-method">GET</div><div class="ep-path">/api/v1/status</div><button class="ep-copy" data-path="/api/v1/status">COPY</button></div>
            <div class="ep-row brutal-box get"><div class="ep-method">GET</div><div class="ep-path">/api/v1/users</div><button class="ep-copy" data-path="/api/v1/users">COPY</button></div>
            <div class="ep-row brutal-box get"><div class="ep-method">GET</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy" data-path="/api/v1/users/{id}">COPY</button></div>
            <div class="ep-row brutal-box post"><div class="ep-method">POST</div><div class="ep-path">/api/v1/users</div><button class="ep-copy" data-path="/api/v1/users">COPY</button></div>
            <div class="ep-row brutal-box put"><div class="ep-method">PUT</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy" data-path="/api/v1/users/{id}">COPY</button></div>
            <div class="ep-row brutal-box delete"><div class="ep-method">DEL</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy" data-path="/api/v1/users/{id}">COPY</button></div>
        </div>

    </div>

    <footer>
        <span id="req-id">REQ_000</span>
        <span>BRUTAL API &copy; {{ date('Y') }}</span>
    </footer>

    <!-- ══════════════ MOTOR GSAP NEO-BRUTAL ══════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (rm) gsap.globalTimeline.timeScale(0);

        // ── 1. INFINITE MARQUEE ──
        // Desplazamiento lineal infinito del track text
        const track = document.querySelector('.marquee-track');
        // Usamos el width total dividido por los duplicados aproximados para un loop suave
        // -50% asegura que cuando el original llega a la mitad, se resetea.
        gsap.to(track, {
            x: "-50%",
            duration: 15,
            repeat: -1,
            ease: "none"
        });

        // ── 2. ANIMACIÓN "DEAL OF CARDS" ──
        // En lugar de aparecer suavemente, los endpoints vuela físicamente como cartas lanzadas.
        const epRows = gsap.utils.toArray('.ep-row');
        if(epRows.length > 0) {
            gsap.from(epRows, {
                scrollTrigger: {
                    trigger: ".endpoints-grid",
                    start: "top 80%"
                },
                x: () => window.innerWidth / 1.5, // Vuelan desde la derecha fuera de pantalla
                y: -300,                          // Caen desde arriba
                rotation: () => Math.random() * 90 - 45, // Rotación aleatoria simulando tirada humana
                scale: 1.2,                       // Perspective illusion
                autoAlpha: 0,
                duration: 1,
                stagger: 0.15,
                ease: "bounce.out",               // El choque seco 'brutal' contra los bordes
                clearProps: "transform"           // Limpiamos transforms nativos al caer para no joder la UI
            });
        }

        // ── 3. FADES DE COMPONENTES SECUNDARIOS ──
        // Fades mecánicos sin blur
        gsap.utils.toArray('.anim-fade').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: "top 85%" },
                y: 50, autoAlpha: 0, duration: 0.6, ease: "power4.out", clearProps: "transform"
            });
        });

        // Hero Init pop
        gsap.from('.anim-hero', {
            y: 100, autoAlpha: 0, rotation: -2, duration: 0.8, ease: "back.out(1.5)", clearProps: "all"
        });

        // ── FUNCIONALIDADES JS BÁSICAS ──
        document.querySelectorAll('.ep-copy').forEach(btn => {
            btn.addEventListener('click', () => {
                navigator.clipboard?.writeText(btn.dataset.path);
                const oldText = btn.innerText;
                btn.innerText = 'COPIED';
                btn.style.background = 'var(--col-lime)';
                setTimeout(() => { 
                    btn.innerText = oldText; 
                    btn.style.background = ''; // Revert to css
                }, 1000);
            });
        });

        const pad = n => String(n).padStart(2,'0');
        const startTs = Date.now();
        function updateTicks() {
            const now = new Date();
            const c = document.getElementById('clock');
            if(c) c.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            const s = Math.floor((Date.now() - startTs) / 1000);
            const u = document.getElementById('uptime');
            if(u) u.textContent = `${pad(Math.floor(s/3600))}:${pad(Math.floor((s%3600)/60))}:${pad(s%60)}`;
        }
        updateTicks(); setInterval(updateTicks, 1000);

        const themeBtn = document.getElementById('themeToggle');
        // Inicializar icono
        if (document.documentElement.classList.contains('dark')) {
            document.querySelector('.sun').style.display = 'block';
            document.querySelector('.moon').style.display = 'none';
        } else {
            document.querySelector('.sun').style.display = 'none';
            document.querySelector('.moon').style.display = 'block';
        }
        
        themeBtn.addEventListener('click', () => {
            const root = document.documentElement;
            root.classList.toggle('dark');
            const isDark = root.classList.contains('dark');
            localStorage.setItem('uo-theme-brutal', isDark ? 'dark' : 'light');
            
            document.querySelector('.sun').style.display = isDark ? 'block' : 'none';
            document.querySelector('.moon').style.display = isDark ? 'none' : 'block';
        });

        const ri = document.getElementById('req-id');
        if(ri) ri.textContent = 'REQ_' + Math.random().toString(16).slice(2,8).toUpperCase();
        
    </script>
</body>
</html>