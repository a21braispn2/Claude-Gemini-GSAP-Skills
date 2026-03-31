<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU-TH-UR 6000 TERMINAL</title>
    <!-- Dark Mode forced in Alien System... but respecting structure -->
    <script>
        if (localStorage.getItem('uo-theme-alien') === 'light') {
            document.documentElement.classList.add('light'); // Daywalk Alien Mode
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES NOSTROMO ══════════════ */
        :root {
            /* Nostromo Base */
            --bg:          #020402;
            --text-pri:    #39ff14; /* Toxic Green */
            --text-sec:    #198c0b; /* Dark Green */
            --border-col:  #39ff14;
            --amber-alert: #ff9a00;
            --bg-glass:    rgba(57, 255, 20, 0.05);

            --font-mono:   'VT323', monospace;
        }

        :root.light {
            /* Weyland-Yutani Corporate Clean Room (Inverse) */
            --bg:          #e6e6e6;
            --text-pri:    #0a1b07; 
            --text-sec:    #246a15; 
            --border-col:  #0a1b07;
            --amber-alert: #c90000;
            --bg-glass:    rgba(10, 27, 7, 0.05);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
            background-color: var(--bg);
            color: var(--text-pri);
            font-family: var(--font-mono);
            font-size: 20px; /* VT323 is small, scale up */
            min-height: 100vh;
            text-shadow: 0 0 5px var(--text-pri);
            transition: background-color 0.4s, color 0.4s;
            cursor: crosshair; /* Hacker Target */
        }
        :root.light body { text-shadow: none; }

        /* ══════════════ CRT SCANLINES & FLICKER ══════════════ */
        .crt {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 9999; pointer-events: none;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.15) 50%), 
                        linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03));
            background-size: 100% 4px, 4px 100%;
            opacity: 0.8; mix-blend-mode: overlay;
        }
        :root.light .crt { opacity: 0.2; } /* Menos agresivo en luz corporativa */

        /* ══════════════ LAYOUT TERMINAL ══════════════ */
        .container {
            max-width: 900px; margin: 0 auto; padding: 40px 20px 100px;
        }

        /* ══════════════ NAV RADAR ══════════════ */
        nav {
            display: flex; justify-content: space-between; align-items: stretch;
            border-bottom: 2px dashed var(--border-col); padding-bottom: 10px; margin-bottom: 40px;
            text-transform: uppercase; letter-spacing: 0.1em;
        }
        .nav-sys {
            display: flex; flex-direction: column; gap: 4px;
        }
        .nav-sys h1 { font-size: 32px; font-weight: normal; margin: 0; line-height: 1; }
        .sys-id { font-size: 16px; color: var(--text-sec); }

        .nav-tools { display: flex; align-items: flex-end; gap: 20px; }
        .nav-stat {
            display: flex; align-items: center; gap: 8px; font-size: 16px;
        }
        .blinker {
            width: 12px; height: 12px; background: var(--border-col);
            box-shadow: 0 0 10px var(--border-col); 
        }

        .theme-btn {
            background: transparent; color: var(--text-pri); border: 1px solid var(--border-col);
            padding: 4px 12px; cursor: crosshair; font-family: var(--font-mono); font-size: 16px;
            text-transform: uppercase; text-shadow: inherit; transition: all 0.2s;
        }
        .theme-btn:hover { background: var(--text-pri); color: var(--bg); text-shadow: none; }
        
        /* ══════════════ HERO MAINFRAME ══════════════ */
        .hero {
            border: 1px solid var(--border-col); padding: 30px; margin-bottom: 60px;
            position: relative; background: var(--bg-glass);
        }
        /* Corners bracket effect */
        .hero::before, .hero::after {
            content: ''; position: absolute; width: 20px; height: 20px; border: 2px solid var(--border-col);
        }
        .hero::before { top: -1px; left: -1px; border-right: none; border-bottom: none; }
        .hero::after { bottom: -1px; right: -1px; border-left: none; border-top: none; }

        .h-tag { color: var(--amber-alert); font-size: 18px; margin-bottom: 10px; }
        .hero h2 { font-size: 64px; font-weight: normal; line-height: 0.9; margin-bottom: 20px; text-transform: uppercase; }
        
        .hero-data {
            display: flex; gap: 40px; border-top: 1px solid var(--text-sec); padding-top: 20px;
            font-size: 22px;
        }
        .hd-block { display: flex; flex-direction: column; }
        .hd-lbl { color: var(--text-sec); font-size: 15px; }

        /* ══════════════ DEBUG SECTOR ══════════════ */
        .sec-title {
            font-size: 28px; font-weight: normal; margin-bottom: 20px;
            border-bottom: 1px solid var(--text-sec); display: inline-block; padding-bottom: 4px;
        }

        .debug-warn {
            border: 1px solid var(--amber-alert); color: var(--amber-alert);
            background: rgba(255, 154, 0, 0.05); padding: 10px 20px; margin-bottom: 30px;
            text-shadow: 0 0 5px var(--amber-alert);
        }

        .debug-panel {
            border: 1px dashed var(--border-col); display: flex; flex-direction: column;
            margin-bottom: 60px;
        }
        .d-row {
            display: grid; grid-template-columns: 200px 1fr; border-bottom: 1px dashed var(--text-sec);
            padding: 10px 20px;
        }
        .d-row:last-child { border-bottom: none; }
        .d-lbl { color: var(--text-sec); }
        .d-val { color: var(--text-pri); }
        .d-red { color: var(--amber-alert); text-shadow: 0 0 5px var(--amber-alert); }

        /* ══════════════ ENDPOINTS TERMINAL ══════════════ */
        .ep-list { display: flex; flex-direction: column; gap: 5px; margin-bottom: 60px; }
        
        .ep-row {
            display: flex; align-items: center; border: 1px solid transparent; padding: 10px 15px;
            transition: border-color 0.2s, background 0.2s;
        }
        .ep-row:hover { border-color: var(--border-col); background: var(--bg-glass); }

        .ep-method {
            width: 80px; font-size: 22px;
        }
        .ep-row.get .ep-method { color: var(--text-pri); }
        .ep-row.post .ep-method { color: var(--amber-alert); text-shadow: 0 0 5px var(--amber-alert); }
        .ep-row.put .ep-method { color: #f9f01e; text-shadow: 0 0 5px #f9f01e; }
        .ep-row.del .ep-method { color: #ff1313; text-shadow: 0 0 5px #ff1313; }

        .ep-path { flex: 1; font-size: 24px; padding-left: 20px; }

        .ep-copy {
            background: transparent; color: var(--text-sec); border: 1px dotted var(--text-sec);
            padding: 4px 12px; cursor: crosshair; font-family: inherit; font-size: 18px; transition: 0.2s;
            text-transform: uppercase;
        }
        .ep-copy:hover { border-color: var(--text-pri); color: var(--text-pri); }
        
        /* ══════════════ FOOTER ══════════════ */
        footer {
            border-top: 1px solid var(--border-col); padding-top: 20px;
            display: flex; justify-content: space-between; color: var(--text-sec); font-size: 16px;
        }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 768px) {
            .hero-data { flex-direction: column; gap: 15px; }
            .ep-row { flex-wrap: wrap; padding: 15px; border-bottom: 1px dashed var(--text-sec); }
            .ep-path { padding-left: 0; width: 100%; font-size: 20px; margin: 10px 0; }
            .d-row { grid-template-columns: 1fr; gap: 5px; }
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- CRT Overlay Layer -->
    <div class="crt"></div>

    <div class="container">
        
        <!-- HEADER RADAR -->
        <nav class="anim-fade">
            <div class="nav-sys">
                <h1 class="scramble" data-text="MU-TH-UR 6000 CORE">INIT_SYST</h1>
                <span class="sys-id scramble" data-text="API GATEWAY // PORTAL_NOSTROMO">DECRYPTING...</span>
            </div>
            <div class="nav-tools">
                <div class="nav-stat">
                    <span>SYS_LINK</span><div class="blinker"></div>
                </div>
                <button class="theme-btn" id="themeToggle">OVRD_THEME</button>
            </div>
        </nav>

        {{-- ── HERO SECTOR ── --}}
        <div class="hero anim-fade">
            <div class="h-tag scramble" data-text="DIAGNOSTIC ACTIVE">CHK_SEQ...</div>
            <h2 class="scramble" data-text="MAIN SERVER">AWAITING</h2>
            <div class="hero-data">
                <div class="hd-block">
                    <span class="hd-lbl">ENV</span>
                    <span class="scramble" style="text-transform:uppercase" data-text="{{ app()->environment() }}">XYZ_12</span>
                </div>
                <div class="hd-block">
                    <span class="hd-lbl">UPTIME</span>
                    <span id="uptime">00:00:00</span>
                </div>
                <div class="hd-block">
                    <span class="hd-lbl">REQ/SEC</span>
                    <span class="scramble" data-text="2.04K/S">0.00</span>
                </div>
            </div>
        </div>

        {{-- ── DEBUG TERMINAL ── --}}
        @if(config('app.debug'))
        <div class="debug-warn anim-fade scramble" data-text="WARNING: ROOT DEBUG PROTOCOL ACTIVE // UNAUTHORIZED ACCESS PROHIBITED">!#%@%#%@^!^</div>

        <h3 class="sec-title anim-fade scramble" data-text="SYSTEM SPECS">SYS_DUMP</h3>
        <div class="debug-panel anim-fade">
            <div class="d-row"><div class="d-lbl">FMWK</div><div class="d-val scramble" data-text="LARAVEL {{ app()->version() }}">########</div></div>
            <div class="d-row"><div class="d-lbl">VRSN</div><div class="d-val scramble" data-text="1.0{{ config('app.version') }}">#####</div></div>
            <div class="d-row"><div class="d-lbl">ENGI</div><div class="d-val scramble" data-text="PHP {{ phpversion() }}">###</div></div>
            <div class="d-row"><div class="d-lbl">DBASE</div><div class="d-red scramble" data-text="{{ config('database.default') }}">XXXX</div></div>
            <div class="d-row"><div class="d-lbl">HOST</div><div class="d-val scramble" data-text="{{ config('database.connections.mysql.host') }}">0.0.0.0</div></div>
            <div class="d-row"><div class="d-lbl">USER</div><div class="d-val scramble" data-text="{{ config('database.connections.mysql.username') }}">GHOST</div></div>
            <div class="d-row">
                <div class="d-lbl">CODE</div>
                <button class="theme-btn" style="border-color:var(--amber-alert); color:var(--amber-alert);" onclick="this.innerText='ACCESS_DENIED'; this.disabled=true;">ATTEMPT_READ</button>
            </div>
        </div>
        @endif

        {{-- ── ENDPOINTS TRAY ── --}}
        <h3 class="sec-title anim-fade scramble" data-text="ACTIVE ROUTES (API_V1)">RUT_TABLE</h3>
        <div class="ep-list">
            <div class="ep-row get"><div class="ep-method">GET</div><div class="ep-path scramble" data-text="/api/v1/status">///########</div><button class="ep-copy" data-path="/api/v1/status">EXTRACT</button></div>
            <div class="ep-row get"><div class="ep-method">GET</div><div class="ep-path scramble" data-text="/api/v1/users">///########</div><button class="ep-copy" data-path="/api/v1/users">EXTRACT</button></div>
            <div class="ep-row get"><div class="ep-method">GET</div><div class="ep-path scramble" data-text="/api/v1/users/{id}">///#############</div><button class="ep-copy" data-path="/api/v1/users/{id}">EXTRACT</button></div>
            <div class="ep-row post"><div class="ep-method">POST</div><div class="ep-path scramble" data-text="/api/v1/users">///########</div><button class="ep-copy" data-path="/api/v1/users">EXTRACT</button></div>
            <div class="ep-row put"><div class="ep-method">PUT</div><div class="ep-path scramble" data-text="/api/v1/users/{id}">///#############</div><button class="ep-copy" data-path="/api/v1/users/{id}">EXTRACT</button></div>
            <div class="ep-row del"><div class="ep-method">DEL</div><div class="ep-path scramble" data-text="/api/v1/users/{id}">///#############</div><button class="ep-copy" data-path="/api/v1/users/{id}">EXTRACT</button></div>
        </div>

        <footer>
            <span id="req-id">SESSION_ID_XYZ</span>
            <span class="scramble" data-text="MU-TH-UR TERMINAL &copy; {{ date('Y') }}">COPYRIGHT WY_CORP</span>
        </footer>

    </div>

    <!-- ══════════════ ALIEN MOTHER GSAP ENGINE ══════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (rm) gsap.globalTimeline.timeScale(0);

        // ── 1. BLINKER RADAR FLASH (CSS no lo paraba con GSAP así que puro GSAP) ──
        gsap.to('.blinker', {
            opacity: 0.1, duration: 0.8, repeat: -1, yoyo: true, ease: "steps(2)"
        });

        // ── 2. SCRAMBLE TEXT (DECODER ALIEN) ──
        const sChars = "—=+*^?#!<>-_\\/[]{}01X";
        function alienScramble(element, dur = 1.0) {
            const orig = element.getAttribute('data-text');
            if(!orig) return; // Si no hay data-text saltamos
            
            const obj = { v: 0 };
            gsap.to(obj, {
                v: orig.length,
                duration: dur,
                ease: "none",
                onUpdate: () => {
                    // Texto revelado
                    const revealed = orig.substring(0, Math.floor(obj.v));
                    let trailing = '';
                    const rem = orig.length - Math.floor(obj.v);
                    
                    // Solo 4 carácteres alien ensuciando
                    for(let i=0; i < Math.min(rem, 4); i++) {
                        trailing += sChars[Math.floor(Math.random() * sChars.length)];
                    }
                    element.innerText = revealed + trailing;
                },
                onComplete: () => { element.innerText = orig; }
            });
        }

        // Cargar Scrambler en todos los que tengan la clase .scramble mediante el Scroll
        gsap.utils.toArray('.scramble').forEach(el => {
            // Un fade rapido con el scrambler sincrono
            ScrollTrigger.create({
                trigger: el,
                start: "top 95%", // arranca un poco antes
                onEnter: () => alienScramble(el, gsap.utils.random(0.8, 1.8))
            });
        });

        // ── 3. FADE DE CAJAS MAIN (Sin transformaciones raras, estilo terminal duro) ──
        gsap.utils.toArray('.anim-fade').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: "top 90%" },
                autoAlpha: 0, duration: 0.5, ease: "steps(5)", clearProps: "all" // Pasmado a steps para estilo obsoleto
            });
        });

        // ── 4. ENDPOINTS (Línea por línea) ──
        const epRows = gsap.utils.toArray('.ep-row');
        if(epRows.length > 0) {
            gsap.from(epRows, {
                scrollTrigger: {
                    trigger: ".ep-list",
                    start: "top 80%"
                },
                x: -30, autoAlpha: 0,
                duration: 0.4, stagger: 0.1,
                ease: "steps(4)", clearProps: "transform"
            });
        }

        // ── FUNCIONALIDAD MISC ──
        document.querySelectorAll('.ep-copy').forEach(btn => {
            btn.addEventListener('click', () => {
                navigator.clipboard?.writeText(btn.dataset.path);
                const oldText = btn.innerText;
                btn.innerText = 'EXTRACTED';
                btn.style.color = "var(--bg)";
                btn.style.background = "var(--border-col)";
                setTimeout(() => { 
                    btn.innerText = oldText; 
                    btn.style.background = 'transparent';
                    btn.style.color = "var(--text-sec)";
                }, 1000);
            });
        });

        const pad = n => String(n).padStart(2,'0');
        const startTs = Date.now();
        function updateTicks() {
            const s = Math.floor((Date.now() - startTs) / 1000);
            const u = document.getElementById('uptime');
            if(u) u.textContent = `${pad(Math.floor(s/3600))}:${pad(Math.floor((s%3600)/60))}:${pad(s%60)}`;
        }
        updateTicks(); setInterval(updateTicks, 1000);

        // Theme Toggle OVRD_THEME
        const themeBtn = document.getElementById('themeToggle');
        themeBtn.addEventListener('click', () => {
            const root = document.documentElement;
            root.classList.toggle('light'); // Daywalk Mode
            localStorage.setItem('uo-theme-alien', root.classList.contains('light') ? 'light' : 'dark');
            
            // "Corto circuito" Visual Al Cambiar
            gsap.fromTo("body", {opacity: 0}, {opacity: 1, duration: 0.4, ease: "steps(4)", clearProps: "all"});
        });

        const ri = document.getElementById('req-id');
        if(ri) ri.textContent = 'SESSION_' + Math.random().toString(16).slice(2,10).toUpperCase();

    </script>
</body>
</html>