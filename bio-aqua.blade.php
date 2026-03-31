<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API - ABYSSAL BIO</title>
    <!-- Dark Mode forced in Alien System... but respecting structure -->
    <script>
        if (localStorage.getItem('uo-theme-bio') === 'light') {
            document.documentElement.classList.add('light'); // Tropical pool Mode
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&family=Comfortaa:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES BIO/AQUA ══════════════ */
        :root {
            /* Fosa Oceánica / Bioluminescence Base */
            --bg:          #010B13;
            --bg-radial:   radial-gradient(ellipse at top, rgba(0, 240, 255, 0.15), transparent 70%);
            
            --bubble:      rgba(0, 240, 255, 0.05);
            --bubble-bord: rgba(0, 240, 255, 0.15);
            
            --text-pri:    #E0F7FA;
            --text-sec:    #80DEEA;
            --text-glow:   0 0 10px rgba(0, 240, 255, 0.3);

            --col-cyan:    #00F0FF;
            --col-green:   #00FF88;
            --col-yel:     #FFDD00;
            --col-red:     #FF0055;

            --font-head:   'Comfortaa', cursive;
            --font-body:   'Quicksand', sans-serif;
        }

        :root.light {
            /* Piscina Tropical Orgánica (Día) */
            --bg:          #E0F7FA;
            --bg-radial:   radial-gradient(ellipse at top, rgba(255, 255, 255, 0.8), transparent 70%);
            
            --bubble:      rgba(255, 255, 255, 0.5);
            --bubble-bord: rgba(0, 180, 220, 0.2);
            
            --text-pri:    #003344;
            --text-sec:    #006688;
            --text-glow:   none;

            --col-cyan:    #00AABB;
            --col-green:   #00AA55;
            --col-yel:     #DDAA00;
            --col-red:     #DD0033;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        html, body {
            overflow-x: hidden; width: 100%; position: relative;
        }

        body {
            background-color: var(--bg);
            background-image: var(--bg-radial);
            background-size: 100vw 100vh;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text-pri);
            font-family: var(--font-body);
            font-size: 16px; min-height: 100vh;
            text-shadow: var(--text-glow);
            transition: background-color 0.8s ease, color 0.8s ease;
            cursor: none; /* Cursor de Gota */
        }
        :root.light body { text-shadow: none; }

        /* ══════════════ MORPHING GOTA DE AGUA (CURSOR) ══════════════ */
        .cursor-drop {
            position: fixed; top: 0; left: 0;
            width: 30px; height: 30px;
            background: var(--col-cyan);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; /* Forma amorfica inicial */
            pointer-events: none; z-index: 9999;
            transform: translate(-50%, -50%);
            mix-blend-mode: screen; 
            filter: blur(4px);
            animation: morph-drop 4s ease-in-out infinite;
            transition: width 0.3s, height 0.3s, background-color 0.3s;
        }
        :root.light .cursor-drop { mix-blend-mode: multiply; filter: blur(2px); }

        .cursor-drop.hovering {
            width: 70px; height: 70px; background: var(--col-green);
        }

        /* Ambient Bio Particles */
        .particles { position: absolute; inset: 0; z-index: -1; pointer-events: none; overflow: hidden; }
        .particle {
            position: absolute; border-radius: 50%;
            background: radial-gradient(circle, var(--text-sec) 0%, transparent 60%);
            opacity: 0.3; filter: blur(2px);
        }

        /* ══════════════ CSS ANIMATIONS ══════════════ */
        @keyframes morph-drop {
            0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            50% { border-radius: 60% 40% 30% 70% / 60% 50% 40% 50%; }
            100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
        }

        /* ══════════════ COMPONENTES BIOLUMBRES (BIO-CELLS) ══════════════ */
        .bio-cell {
            background: var(--bubble);
            border: 1px solid var(--bubble-bord);
            backdrop-filter: blur(12px);
            /* Morph irregular en c\xE9lulas mas grandes */
            border-radius: 40px; 
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2), inset 0 0 15px rgba(0, 240, 255, 0.05);
            padding: 40px;
        }

        .bio-pill {
            background: rgba(0, 240, 255, 0.03); border: 1px solid var(--bubble-bord);
            border-radius: 30px; padding: 10px 20px;
        }

        /* ══════════════ NAV ACUATICO ══════════════ */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 24px 40px; margin-bottom: 30px;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 12px;
        }
        .nav-icon {
            font-size: 28px; filter: drop-shadow(0 0 8px var(--col-cyan));
            animation: float-icon 4s ease-in-out infinite;
        }
        .nav-brand h1 { font-family: var(--font-head); font-size: 24px; color: var(--text-pri); font-weight: 700; }

        .nav-tools { display: flex; align-items: center; gap: 30px; color: var(--text-sec); font-weight: 600; }
        .clock { letter-spacing: 0.05em; font-variant-numeric: tabular-nums; }

        .theme-btn {
            background: transparent; color: var(--col-cyan); border: 2px solid var(--bubble-bord);
            border-radius: 50%; width: 44px; height: 44px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 18px;
            transition: all 0.3s ease; box-shadow: inset 0 0 10px transparent;
        }
        .theme-btn:hover { background: var(--bubble); box-shadow: inset 0 0 20px var(--bubble-bord); transform: scale(1.1); }
        .theme-btn .moon { display: block; }
        :root.light .theme-btn .sun { display: block; }
        :root.light .theme-btn .moon { display: none; }

        /* ══════════════ LAYOUT ══════════════ */
        .container {
            max-width: 900px; margin: 0 auto; padding: 0 20px 100px;
        }

        /* ══════════════ ORGANO PRINCIPAL (HERO) ══════════════ */
        .hero {
            display: flex; flex-direction: column; gap: 24px; position: relative; margin-bottom: 60px;
            border-radius: 50px 70px 60px 40px / 60px 40px 50px 70px; /* Morph estatico gigante */
            animation: morph-drop 20s linear infinite;
        }
        .hero h2 { font-family: var(--font-head); font-size: 50px; font-weight: 700; color: var(--col-cyan); }
        .hero p { font-size: 18px; line-height: 1.6; color: var(--text-sec); max-width: 600px; font-weight: 600; }
        
        .hero-stats {
            display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap;
        }
        .stat {
            display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 140px;
        }
        .stat-lbl { font-size: 13px; text-transform: uppercase; color: var(--text-sec); letter-spacing: 0.05em; font-weight: 700;}
        .stat-val { font-size: 28px; font-weight: 700; color: var(--text-pri); }

        /* ══════════════ ENTORNO DEBUG (TÓXICO) ══════════════ */
        h3.section-label {
            font-family: var(--font-head); font-size: 24px; color: var(--col-cyan); margin-bottom: 24px;
            padding-left: 20px; font-weight: 700;
        }

        .debug-warn {
            color: var(--col-yel); border-color: rgba(255, 221, 0, 0.3); background: rgba(255, 221, 0, 0.05);
            margin-bottom: 30px; font-weight: 700; border-radius: 30px; display: flex; align-items: center;
        }

        .debug-panel {
            border-radius: 35px; margin-bottom: 70px; padding: 25px;
        }
        .d-row {
            display: flex; flex-wrap: wrap; padding: 15px 20px;
            border-bottom: 1px solid var(--bubble-bord);
        }
        .d-row:last-child { border-bottom: none; }
        .d-lbl { flex: 0 0 160px; color: var(--text-sec); font-weight: 700; }
        .d-val { flex: 1; font-weight: 600; color: var(--text-pri); }
        .d-crit { color: var(--col-yel); }
        
        /* ══════════════ ENDPOINTS COMO BURBUJAS ══════════════ */
        .ep-list {
            display: flex; flex-direction: column; gap: 16px; margin-bottom: 80px;
        }
        
        .ep-row {
            display: flex; align-items: center; padding: 16px 24px; cursor: pointer;
            background: var(--bubble); border: 1px solid var(--bubble-bord);
            border-radius: 100px; /* Completamente pastilla/c\xE9lula alargada */
            backdrop-filter: blur(8px); transition: border-color 0.4s;
        }
        .ep-row:hover { border-color: var(--col-cyan); }

        .ep-method {
            font-family: var(--font-head); font-weight: 700; font-size: 15px; width: 70px;
            letter-spacing: 0.1em;
        }
        .ep-row.get .ep-method { color: var(--col-cyan); }
        .ep-row.post .ep-method { color: var(--col-green); }
        .ep-row.put .ep-method { color: var(--col-yel); }
        .ep-row.del .ep-method { color: var(--col-red); }

        .ep-path { flex: 1; font-size: 16px; font-weight: 600; padding: 0 20px; color: var(--text-pri); }

        .ep-copy {
            background: transparent; color: var(--text-sec); border: none; font-family: var(--font-head);
            font-size: 14px; font-weight: 700; cursor: pointer; text-transform: uppercase;
        }

        /* ══════════════ FOOTER BIO ══════════════ */
        footer {
            display: flex; justify-content: space-between; align-items: center;
            border-top: 1px solid var(--bubble-bord); padding-top: 30px; font-weight: 600; color: var(--text-sec);
        }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 768px) {
            nav { padding: 20px; }
            .hero-stats { flex-direction: column; }
            .ep-row { border-radius: 30px; flex-wrap: wrap; text-align: center; justify-content: center; padding: 20px; gap: 10px;}
            .ep-method { width: 100%; border-bottom: 1px solid var(--bubble-bord); padding-bottom: 10px;}
            .ep-path { width: 100%; padding: 10px 0; }
            .cursor-drop { display: none; }
            body { cursor: auto; }
        }
    </style>
    
    <!-- Evitamos flash bloqueando script al head -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- Ambiente Océanico Fluido -->
    <div class="particles">
        <!-- Generado x JS -->
    </div>

    <div class="cursor-drop"></div>

    <div class="container">
        
        <nav class="anim-float">
            <div class="nav-brand">
                <div class="nav-icon">💧</div>
                <h1>UO API</h1>
            </div>
            <div class="nav-tools">
                <span class="clock" id="clock">00:00:00</span>
                <button class="theme-btn" id="themeToggle">
                    <span class="sun" style="display:none;">☀</span>
                    <span class="moon" style="display:none;">☾</span>
                </button>
            </div>
        </nav>

        {{-- ── ORGANO CENTRAL ── --}}
        <div class="hero bio-cell anim-breathe">
            <h2>Gateway Oasis</h2>
            <p>Welcome to the biological data pool. Deep organic currents are processing your requests seamlessly across the neural clusters.</p>
            <div class="hero-stats">
                <div class="stat bio-pill">
                    <span class="stat-lbl">Cluster Env</span>
                    <span class="stat-val" style="text-transform: capitalize;">{{ app()->environment() }}</span>
                </div>
                <div class="stat bio-pill">
                    <span class="stat-lbl">Uptime Cyc.</span>
                    <span class="stat-val" id="uptime">00:00:00</span>
                </div>
                <div class="stat bio-pill">
                    <span class="stat-lbl">Vital Flow</span>
                    <span class="stat-val" style="color:var(--col-green)">Healthy</span>
                </div>
            </div>
        </div>

        {{-- ── TOXIC DEBUG SECTOR ── --}}
        @if(config('app.debug'))
        <div class="debug-warn bio-pill anim-bubble">
            🦠 Symbiosis warning: Root debug exposed.
        </div>

        <h3 class="section-label anim-bubble">Neural Node Data</h3>
        <div class="debug-panel bio-cell anim-breathe-sub">
            <div class="d-row"><div class="d-lbl">Framework Base</div><div class="d-val">Laravel {{ app()->version() }}</div></div>
            <div class="d-row"><div class="d-lbl">Organism Ver.</div><div class="d-val">v1.0{{ config('app.version') }}</div></div>
            <div class="d-row"><div class="d-lbl">Enzyme Engine</div><div class="d-val">PHP {{ phpversion() }}</div></div>
            <div class="d-row"><div class="d-lbl">Data Pool</div><div class="d-crit">{{ config('database.default') }}</div></div>
            <div class="d-row"><div class="d-lbl">Receptor (Host)</div><div class="d-val">{{ config('database.connections.mysql.host') }}</div></div>
            <div class="d-row"><div class="d-lbl">Genome Host</div><div class="d-val">{{ config('database.connections.mysql.username') }}</div></div>
            <div class="d-row">
                <div class="d-lbl">DNA Key</div>
                <button class="bio-pill" style="border-color:var(--col-red); color:var(--col-red); cursor:pointer; background:transparent;" onclick="this.innerText='SECURELY ENCRYPTED'; this.disabled=true;">EXTRACT</button>
            </div>
        </div>
        @endif

        {{-- ── ENDPOINTS (WATER BUBBLES) ── --}}
        <h3 class="section-label anim-bubble" style="margin-top:20px;">Membrane Receptors</h3>
        <div class="ep-list">
            <div class="ep-row get bubble-up"><div class="ep-method">GET</div><div class="ep-path">/api/v1/status</div><button class="ep-copy" data-path="/api/v1/status">COPY CELL</button></div>
            <div class="ep-row get bubble-up"><div class="ep-method">GET</div><div class="ep-path">/api/v1/users</div><button class="ep-copy" data-path="/api/v1/users">COPY CELL</button></div>
            <div class="ep-row get bubble-up"><div class="ep-method">GET</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy" data-path="/api/v1/users/{id}">COPY CELL</button></div>
            <div class="ep-row post bubble-up"><div class="ep-method">POST</div><div class="ep-path">/api/v1/users</div><button class="ep-copy" data-path="/api/v1/users">COPY CELL</button></div>
            <div class="ep-row put bubble-up"><div class="ep-method">PUT</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy" data-path="/api/v1/users/{id}">COPY CELL</button></div>
            <div class="ep-row del bubble-up"><div class="ep-method">DEL</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy" data-path="/api/v1/users/{id}">COPY CELL</button></div>
        </div>

        <footer class="anim-float">
            <span id="req-id">BIO_TKN_00</span>
            <span>UO ABYSSAL POOL &copy; {{ date('Y') }}</span>
        </footer>

    </div>

    <!-- ══════════════ AQUA & BIO GSAP ENGINE ══════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (rm) gsap.globalTimeline.timeScale(0);

        // ── 1. LIQUID CURSOR QUICKTO ──
        const liquid = document.querySelector('.cursor-drop');
        const xToJelly = gsap.quickTo(liquid, "x", {duration: 0.4, ease: "power3.out"});
        // Y es un toque mas lento para simular viscosidad en el fluido
        const yToJelly = gsap.quickTo(liquid, "y", {duration: 0.6, ease: "power3.out"});

        window.addEventListener("mousemove", (e) => {
            xToJelly(e.clientX); yToJelly(e.clientY);
        });

        // Mutar burbuja al pasar por botones o endpoints enteros
        document.querySelectorAll('.ep-copy, .theme-btn, .bio-pill, .ep-row').forEach(el => {
            el.addEventListener('mouseenter', () => liquid.classList.add('hovering'));
            el.addEventListener('mouseleave', () => liquid.classList.remove('hovering'));
        });

        // ── 2. ESTIRAMIENTOS JELLY EN ENDPOINTS AL HACER HOVER ──
        document.querySelectorAll('.ep-row').forEach(row => {
            row.addEventListener('mouseenter', () => {
                // Al pasar el ratón la burbuja se expande hidrodinamicamente
                gsap.to(row, { scaleX: 1.05, scaleY: 0.95, duration: 0.4, ease: "elastic.out(1, 0.4)" });
            });
            row.addEventListener('mouseleave', () => {
                gsap.to(row, { scaleX: 1, scaleY: 1, duration: 0.8, ease: "elastic.out(1, 0.3)" });
            });
        });

        // ── 3. CORRIENTE OCEÁNICA CONSTANTE (RESPIRACIONES GLOBALES) ──
        // Organo principal (Hero) baja y sube lentamente
        gsap.to('.anim-breathe', {
            y: 15, duration: 4, repeat: -1, yoyo: true, ease: "sine.inOut"
        });
        gsap.to('.anim-breathe-sub', {
            y: -10, duration: 3.5, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 1
        });
        gsap.to('.anim-float', {
            y: 8, duration: 5, repeat: -1, yoyo: true, ease: "sine.inOut", delay: 2
        });

        // ── 4. REVELADO ACUÁTICO DE BURBUJAS ──
        // Bubbly Entrances (Sin usar clearProps para evitar glitchs de visibilidad)
        gsap.utils.toArray('.anim-bubble').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: "top 95%" },
                autoAlpha: 0, scale: 0.7, y: 30, duration: 1.2, ease: "elastic.out(1, 0.5)", clearProps: "transform"
            });
        });

        const epBlbs = gsap.utils.toArray('.bubble-up');
        if(epBlbs.length > 0) {
            gsap.from(epBlbs, {
                scrollTrigger: {
                    trigger: ".ep-list",
                    start: "top 85%"
                },
                y: 100, scale: 0, autoAlpha: 0, // Crecen desde 0 absoluto imitando burbujas generadas
                duration: 1.2, stagger: 0.1,
                ease: "elastic.out(0.8, 0.4)", clearProps: "transform"
            });
        }

        // ── 5. CREACIÓN DE PLANCTON (BURBUJAS DE FONDO) LIGERAS ──
        const partsContainer = document.querySelector('.particles');
        for(let i=0; i<30; i++) {
            const p = document.createElement('div');
            p.classList.add('particle');
            const size = Math.random() * 20 + 5;
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top = Math.random() * 100 + 'vh';
            partsContainer.appendChild(p);
            
            // Animacion erratica "Medusa"
            gsap.to(p, {
                x: `+=${Math.random() * 60 - 30}`,
                y: `-=${Math.random() * 100 + 50}`,
                opacity: 0, // Mueren subiendo
                duration: Math.random() * 10 + 10,
                repeat: -1,
                ease: "sine.inOut",
                delay: Math.random() * -10
            });
        }

        // ── FUNCIONALIDADES MENORES ──
        document.querySelectorAll('.ep-copy').forEach(btn => {
            btn.addEventListener('click', (req) => {
                req.stopPropagation(); // Evitamos doble hover bug
                navigator.clipboard?.writeText(btn.dataset.path);
                const oldText = btn.innerText;
                btn.innerText = 'ABSORBED 🦠';
                btn.style.color = "var(--col-cyan)";
                setTimeout(() => { 
                    btn.innerText = oldText; 
                    btn.style.color = "";
                }, 1500);
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

        // BOTON DE TEMA TROPICAL (Día) vs ABISAL (Noche)
        const themeBtn = document.getElementById('themeToggle');
        if (document.documentElement.classList.contains('light')) {
            document.querySelector('.sun').style.display = 'block';
            document.querySelector('.moon').style.display = 'none';
        } else {
            document.querySelector('.sun').style.display = 'none';
            document.querySelector('.moon').style.display = 'block';
        }
        
        themeBtn.addEventListener('click', () => {
            const root = document.documentElement;
            root.classList.toggle('light');
            const isLight = root.classList.contains('light');
            localStorage.setItem('uo-theme-bio', isLight ? 'light' : 'dark');
            
            document.querySelector('.sun').style.display = isLight ? 'block' : 'none';
            document.querySelector('.moon').style.display = isLight ? 'none' : 'block';
            
            // Onda expansiva biologica
            gsap.fromTo("body", {filter:"blur(10px)", scale:1.02}, {filter:"blur(0px)", scale: 1, duration: 1, ease:"elastic.out"});
        });

        const ri = document.getElementById('req-id');
        if(ri) ri.textContent = 'BIO_SEQ_' + Math.random().toString(16).slice(2,8).toUpperCase();

    </script>
</body>
</html>