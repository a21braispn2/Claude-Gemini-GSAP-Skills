<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API - ROMAN PANTHEON</title>
    <!-- Modo Noche: Dominio de Plut\xF3n -->
    <script>
        if (localStorage.getItem('uo-theme-roman') === 'dark') {
            document.documentElement.classList.add('pluto'); 
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Sorts+Mill+Goudy:ital@0;1&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ MATERIALES Y METALES (ROMAN VARIABLES) ══════════════ */
        :root {
            /* M\xE1rmol de Carrara (Luz) */
            --bg:          #F5F5F0; 
            --stone:       #EAEAE4;
            --stone-dark:  #D5D5CF;
            
            --text-pri:    #2B1B17; /* Tinta ferrog\xE1lica */
            --text-sec:    #5E4A42;
            --purple:      #5D1229; /* P\xEArpura Imperial (Senado) */
            
            /* Oro Met\xE1lico Forjado */
            --gold-grad:   linear-gradient(135deg, #BF953F, #FCF6BA, #B38728, #FBF5B7, #AA771C);
            --gold-rim:    #B38728;
            --gold-glow:   0 10px 30px rgba(191, 149, 63, 0.4);

            --shadow-hvy:  15px 20px 40px rgba(0,0,0,0.2), inset 0 0 0 1px rgba(255,255,255,0.5);
            
            --font-head:   'Cinzel', serif;
            --font-text:   'Sorts Mill Goudy', serif;
            
            --statue-url:  url('https://images.unsplash.com/photo-1544365558-35aa4afcf11f?auto=format&fit=crop&q=80&w=2000');
        }

        :root.pluto {
            /* \xD3nice y Oro del Inframundo (Noche) */
            --bg:          #0C0C0C; 
            --stone:       #181818;
            --stone-dark:  #050505;
            
            --text-pri:    #EAEAE4; 
            --text-sec:    #A39B92;
            --purple:      #8B0000; /* Sangre Oscura */
            
            --gold-grad:   linear-gradient(135deg, #7A5B15, #D1B877, #5C410A, #BCA362, #473005);
            --gold-rim:    #7A5B15;
            --gold-glow:   0 10px 30px rgba(122, 91, 21, 0.4);

            --shadow-hvy:  15px 20px 40px rgba(0,0,0,0.8), inset 0 0 0 1px rgba(255,255,255,0.05);
            
            --statue-url:  url('https://images.unsplash.com/photo-1583020979844-469b76db4bf9?auto=format&fit=crop&q=80&w=2000'); /* Columnas dram\xE1ticas de noche */
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        html, body { overflow-x: hidden; width: 100%; position: relative; }

        body {
            background-color: var(--bg); color: var(--text-pri);
            font-family: var(--font-text); font-size: 20px; min-height: 100vh;
            transition: background-color 0.8s ease, color 0.8s ease;
        }

        /* ══════════════ ESCULTURAS Y PARALLAX DE FONDO ══════════════ */
        .statue-bg {
            position: fixed; top: -10%; left: 0; width: 100vw; height: 120vh;
            background-image: var(--statue-url);
            background-size: cover; background-position: center top;
            opacity: 0.15; z-index: -2; pointer-events: none;
            mix-blend-mode: multiply; filter: grayscale(50%) contrast(120%);
            /* GSAP lo mover\xE1 creando Parallax 3D masivo */
        }
        :root.pluto .statue-bg { mix-blend-mode: screen; opacity: 0.2; }

        .vignette {
            position: fixed; inset: 0; pointer-events: none; z-index: -1;
            background: radial-gradient(circle, transparent 40%, var(--bg) 100%);
        }

        /* ══════════════ UTILIDADES METÁLICAS ══════════════ */
        .gold-text {
            background: var(--gold-grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }

        .gold-plate {
            background: var(--gold-grad);
            border: 1px solid var(--gold-rim);
            box-shadow: var(--gold-glow), inset 0 0 10px rgba(255,255,255,0.5);
            color: #2B1B17; font-family: var(--font-head); text-transform: uppercase;
        }

        /* ══════════════ ARQUITECTURA DEL TEMPLO ══════════════ */
        .pantheon {
            max-width: 1200px; margin: 0 auto; padding: 0 40px;
            display: flex; gap: 40px; position: relative;
        }

        /* COLUMNAS ROMANAS LATERALES */
        .column-pillar {
            width: 80px; flex-shrink: 0; position: relative; margin-top: -60px;
            background: linear-gradient(90deg, 
                var(--stone) 0%, var(--stone-dark) 10%, var(--stone) 20%, 
                var(--stone-dark) 30%, var(--stone) 40%, var(--stone-dark) 50%, 
                var(--stone) 60%, var(--stone-dark) 70%, var(--stone) 80%, 
                var(--stone-dark) 90%, var(--stone) 100%);
            box-shadow: inset 10px 0 20px rgba(0,0,0,0.1), inset -10px 0 20px rgba(0,0,0,0.3), 20px 20px 50px rgba(0,0,0,0.2);
            border-radius: 4px;
        }
        /* Base de columna */
        .column-pillar::before {
            content: ''; position: absolute; bottom: 0; left: -10px; right: -10px; height: 30px;
            background: var(--stone); border-top: 2px solid var(--gold-rim);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3); border-radius: 2px;
        }
        /* Capitel (Arriba) */
        .column-pillar::after {
            content: ''; position: absolute; top: 0; left: -15px; right: -15px; height: 40px;
            background: var(--stone); border-bottom: 2px solid var(--gold-rim);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3); border-radius: 2px;
        }

        .core-content {
            flex: 1; padding: 120px 0; z-index: 10;
        }

        /* ══════════════ NAV (FRISO) ══════════════ */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 30px 50px; position: relative;
            border-bottom: 2px solid var(--gold-rim); margin-bottom: 60px;
        }
        nav::before {
            content:''; position: absolute; bottom: 4px; left:0; right:0; height: 1px; background: var(--gold-rim);
        }
        .nav-brand { font-family: var(--font-head); font-size: 32px; letter-spacing: 0.2em; text-transform: uppercase; }
        .nav-status { font-family: var(--font-head); font-size: 14px; letter-spacing: 0.1em; color: var(--text-sec); display: flex; align-items: center; gap: 10px; }
        .nav-status span.ind { width: 8px; height: 8px; background: var(--gold-rim); border-radius: 50%; box-shadow: var(--gold-glow); }
        
        .nav-tools { display: flex; justify-content: flex-end; font-size: 16px; align-items: center; font-family: var(--font-head); letter-spacing: 0.1em;}
        
        .theme-btn {
            background: transparent; color: var(--text-pri); border: none; cursor: pointer;
            font-family: var(--font-head); font-size: 14px; text-transform: uppercase; letter-spacing: 0.2em;
            position: relative; padding: 5px 0;
        }
        .theme-btn::after {
            content:''; position: absolute; bottom:0; left:50%; right:50%; height:1px; background:var(--gold-grad); transition: all 0.4s;
        }
        .theme-btn:hover::after { left:0; right:0; }
        .theme-btn .moon { display: none; }
        :root.pluto .theme-btn .sun { display: none; }
        :root.pluto .theme-btn .moon { display: inline-block; }

        /* ══════════════ EL APOLO (HERO SECTION) ══════════════ */
        .hero { margin-bottom: 100px; text-align: center; }
        .hero h2 {
            font-size: 80px; line-height: 1; letter-spacing: 0.1em;
            margin-bottom: 30px; text-transform: uppercase;
        }
        .hero p { font-size: 26px; font-style: italic; color: var(--text-sec); max-width: 700px; margin: 0 auto; }
        
        .hero-stats {
            display: flex; justify-content: center; gap: 30px; margin-top: 50px;
        }
        .stat-plinth {
            background: var(--stone); box-shadow: var(--shadow-hvy);
            padding: 30px 40px; flex: 1; border-top: 4px solid var(--gold-rim);
            position: relative;
        }
        /* Muescas del plinto */
        .stat-plinth::before { content:''; position: absolute; top: 5px; bottom: 5px; left: 5px; right: 5px; border: 1px solid rgba(0,0,0,0.05); }

        .stat-lbl { font-family: var(--font-head); font-size: 12px; letter-spacing: 0.3em; text-transform: uppercase; color: var(--purple); display: block; margin-bottom: 15px; }
        :root.pluto .stat-lbl { color: var(--gold-rim); }
        .stat-val { font-size: 40px; font-weight: 700; color: var(--text-pri); font-style: italic; }

        /* ══════════════ TABLAS DE LA LEY (DEBUG) ══════════════ */
        .section-engraving {
            font-family: var(--font-head); font-size: 32px; letter-spacing: 0.2em; text-align: center;
            text-transform: uppercase; margin-bottom: 50px; position: relative;
        }
        .section-engraving::after { content: '✤'; display: block; font-size: 20px; color: var(--gold-rim); margin-top: 15px; }

        .debug-warn {
            color: var(--text-pri); border-left: 5px solid var(--purple); background: var(--stone);
            padding: 25px; font-style: italic; font-size: 22px; margin-bottom: 60px; box-shadow: var(--shadow-hvy);
        }

        .marble-tablet {
            background: var(--stone); padding: 50px; margin-bottom: 100px; box-shadow: var(--shadow-hvy);
            border: 1px solid var(--stone-dark); position: relative;
        }
        .d-row {
            display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 20px 0; font-size: 22px;
        }
        :root.pluto .d-row { border-bottom-color: rgba(255,255,255,0.05); }
        .d-row:last-child { border-bottom: none; }
        .d-lbl { color: var(--text-sec); font-style: italic; }
        .d-val { font-family: var(--font-head); font-weight: 700; letter-spacing: 0.1em; }

        /* ══════════════ LOS DECRETOS (ENDPOINTS) ══════════════ */
        .ep-list { display: flex; flex-direction: column; gap: 30px; margin-bottom: 120px; }
        
        .ep-slab {
            background: var(--stone); display: flex; align-items: center; padding: 30px 40px;
            box-shadow: var(--shadow-hvy); position: relative; transition: transform 0.4s ease;
            border-left: 6px solid var(--border-col, var(--gold-rim));
        }
        .ep-slab:hover { transform: translateY(-5px) scale(1.02); }
        
        /* Colores de Púrpura y Oro por verbo HTTP */
        .ep-slab.get { --border-col: var(--gold-rim); }
        .ep-slab.post { --border-col: var(--purple); }
        .ep-slab.put { --border-col: #2E5A44; } /* Verde Lauro */
        .ep-slab.del { --border-col: #801515; } /* Rojo Escarlata */

        .ep-method {
            font-family: var(--font-head); font-weight: 900; font-size: 22px; width: 120px;
            color: var(--border-col); letter-spacing: 0.15em; text-transform: uppercase;
        }

        .ep-path { 
            flex: 1; font-family: var(--font-text); font-size: 28px;
            color: var(--text-pri); font-style: italic; padding: 0 30px;
        }

        .ep-copy {
            padding: 12px 25px; border: none; cursor: pointer; font-size: 14px; letter-spacing: 0.15em;
            transition: filter 0.3s, transform 0.1s;
        }
        .ep-copy:hover { filter: contrast(150%) brightness(120%); }
        .ep-copy:active { transform: translateY(2px); }

        /* ══════════════ FOOTER MONUMENTAL ══════════════ */
        footer {
            text-align: center; border-top: 2px solid var(--gold-rim); padding-top: 50px;
            font-family: var(--font-head); font-size: 14px; letter-spacing: 0.25em; text-transform: uppercase;
        }
        footer::before { content:''; display: block; width: 50px; height: 2px; background: var(--gold-grad); margin: 0 auto 30px; }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 1000px) {
            .column-pillar { display: none; } /* Ocultamos las columnas en movil para priorizar el texto */
            .pantheon { padding: 0 20px; }
            .hero h2 { font-size: 50px; }
            .hero-stats { flex-direction: column; }
            .ep-slab { flex-direction: column; text-align: center; gap: 20px;}
            .ep-method { width: 100%; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 20px;}
            .ep-path { padding: 20px 0; font-size: 24px; }
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- Escultura y Entorno de Parallax Profundo -->
    <div class="statue-bg"></div>
    <div class="vignette"></div>

    <nav class="anim-fade-down">
        <div class="nav-brand gold-text">API IMPERIUM</div>
        
        <div class="nav-status">
            <span class="ind"></span> ESTADO: <span style="font-weight:700;">PAX ROMANA</span>
        </div>

        <div class="nav-tools">
            <span style="color:var(--text-sec)">PROVINCIA:</span>&nbsp;<strong class="gold-text" style="text-transform:uppercase;">{{ app()->environment() }}</strong>
            <span style="color:var(--gold-rim); margin: 0 15px;">|</span>
            <span id="clock" style="font-style: italic;">00:00:00</span>
            <span style="color:var(--gold-rim); margin: 0 15px;">|</span>
            <button class="theme-btn gold-text" id="themeToggle">
                <span class="sun">Plutón</span>
                <span class="moon">Apolo</span>
            </button>
        </div>
    </nav>

    <div class="pantheon">
        <!-- COLUMNAS ESTRUCTURALES UI -->
        <div class="column-pillar anim-fade-up"></div>

        <div class="core-content">
            
            {{-- ── ESTATUA E HERO (EL SENADO) ── --}}
            <div class="hero anim-fade-up" style="animation-delay: 0.3s">
                <h2>El <span class="gold-text">Panteón</span> de Datos</h2>
                <p>Las leyes de nuestra lógica grabadas en piedra eterna. Toda la telemetría fluye arquitectónicamente bajo el escrutinio de los sabios.</p>
                
                <div class="hero-stats">
                    <div class="stat-plinth">
                        <span class="stat-lbl">Jurisdicción</span>
                        <span class="stat-val" style="text-transform: capitalize;">{{ app()->environment() }}</span>
                    </div>
                    <div class="stat-plinth">
                        <span class="stat-lbl">Vigilia (Uptime)</span>
                        <span class="stat-val" id="uptime">00:00:00</span>
                    </div>
                    <div class="stat-plinth">
                        <span class="stat-lbl">Salud</span>
                        <span class="stat-val" style="color:var(--purple); font-weight:900;">IMPERIUM</span>
                    </div>
                </div>
            </div>

            {{-- ── TABLA DE LEYES (DEBUG) ── --}}
            @if(config('app.debug'))
            <div class="debug-warn anim-slab">
                Privilegios del Senado activos (Modo Debug Expuesto al Pueblo).
            </div>

            <h3 class="section-engraving anim-slab">Cimientos de la Urbe</h3>
            <div class="marble-tablet anim-slab">
                <div class="d-row"><div class="d-lbl">Cantera (Framework)</div><div class="d-val">Laravel {{ app()->version() }}</div></div>
                <div class="d-row"><div class="d-lbl">Edicto Número</div><div class="d-val">v1.0{{ config('app.version') }}</div></div>
                <div class="d-row"><div class="d-lbl">Lengua Materna</div><div class="d-val">PHP {{ phpversion() }}</div></div>
                <div class="d-row"><div class="d-lbl">Ateneo (B.D.)</div><div class="d-val gold-text">{{ config('database.default') }}</div></div>
                <div class="d-row"><div class="d-lbl">Acueducto Host</div><div class="d-val">{{ config('database.connections.mysql.host') }}</div></div>
                <div class="d-row"><div class="d-lbl">Senador (User)</div><div class="d-val">{{ config('database.connections.mysql.username') }}</div></div>
            </div>
            @endif

            {{-- ── LOS EDICTOS (ENDPOINTS) ── --}}
            <h3 class="section-engraving anim-slab">Los Decretos Abiertos</h3>
            <div class="ep-list">
                <div class="ep-slab get anim-slab"><div class="ep-method">Auditar</div><div class="ep-path">/api/v1/status</div><button class="ep-copy gold-plate" data-path="/api/v1/status">Inscribir</button></div>
                <div class="ep-slab get anim-slab"><div class="ep-method">Auditar</div><div class="ep-path">/api/v1/users</div><button class="ep-copy gold-plate" data-path="/api/v1/users">Inscribir</button></div>
                <div class="ep-slab get anim-slab"><div class="ep-method">Auditar</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy gold-plate" data-path="/api/v1/users/{id}">Inscribir</button></div>
                <div class="ep-slab post anim-slab"><div class="ep-method">Forjar</div><div class="ep-path">/api/v1/users</div><button class="ep-copy gold-plate" data-path="/api/v1/users">Inscribir</button></div>
                <div class="ep-slab put anim-slab"><div class="ep-method">Alterar</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy gold-plate" data-path="/api/v1/users/{id}">Inscribir</button></div>
                <div class="ep-slab del anim-slab"><div class="ep-method">Condenar</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-copy gold-plate" data-path="/api/v1/users/{id}">Inscribir</button></div>
            </div>

            <footer class="anim-slab">
                <div style="margin-bottom:15px;">Sello Imperial: <span id="req-id" class="gold-text">SPQR_00</span></div>
                API IMPERIUM &copy; {{ date('Y') }} — Erigido para la Eternidad
            </footer>
        </div>

        <!-- RIGHT PILLAR -->
        <div class="column-pillar anim-fade-up"></div>
    </div>

    <!-- ══════════════ MOTOR GSAP PARALLAX ARQUITECTÓNICO ══════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);
        // Prevenir mareos
        const prefersR = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersR) gsap.globalTimeline.timeScale(0);

        // ── 1. PARALLAX MASIVO DE LA ESTATUA DE FONDO ──
        // Hace que la estatua de background se mueva mas lento que el scroll dando profunda perspectiva.
        gsap.to('.statue-bg', {
            y: "15vh", // Movimiento contenido y estabilizado en píxeles fijos en vez de porcentajes relativos
            ease: "none",
            scrollTrigger: {
                trigger: document.body,
                start: "top top",
                end: "bottom bottom",
                scrub: 1 // Suavizado magnético (1 seg de retraso de inercia) evitando temblores
            }
        });

        // ── 2. EDIFICACIÓN SUAVE ──
        gsap.from('.anim-fade-down', { y: -50, opacity: 0, duration: 1.5, ease: "power3.out" });
        gsap.from('.anim-fade-up', { y: 100, opacity: 0, duration: 2, ease: "power3.out", stagger: 0.3 });

        // ── 3. REVELÁNDO PIEDRA A PIEDRA LAS LOSAS (ENDPOINTS Y DEBUG) ──
        gsap.utils.toArray('.anim-slab').forEach(slab => {
            gsap.from(slab, {
                scrollTrigger: {
                    trigger: slab,
                    start: "top 90%"
                },
                y: 50, scale: 0.95, autoAlpha: 0,
                duration: 1.2, ease: "back.out(1.2)", 
                clearProps: "all"
            });
        });

        // ── 4. DESTELLO DE ORO FORJADO EN BOTONES (GSAP QUICK TIMELINE) ──
        document.querySelectorAll('.ep-copy').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const originalText = btn.innerText;
                navigator.clipboard?.writeText(btn.dataset.path);
                
                // Animación de peso material (como si apretaras mármol grueso)
                gsap.timeline()
                    .to(btn, { scale: 0.9, duration: 0.1 })
                    .to(btn, { scale: 1.05, duration: 0.2, ease: "power2.out" })
                    .to(btn, { scale: 1, duration: 0.2, ease: "power2.in" });
                
                // Resplandor dorado de acierto
                btn.innerText = 'Grabado Imperativo';
                gsap.to(btn, { boxShadow: "0 0 50px rgba(191,149,63,1)", yoyo: true, repeat: 1, duration: 0.5 });
                setTimeout(() => { btn.innerText = originalText; }, 1500);
            });
        });

        // ── 5. RELOJ ROMANO (NUMEROS ROMANOS PARCIALES / FORMATO CLASICO) ──
        const pad = n => String(n).padStart(2,'0');
        const startTs = Date.now();
        function updateTicks() {
            const now = new Date();
            const c = document.getElementById('clock');
            if(c) {
                // Reloj con formato monumental
                c.textContent = `${pad(now.getHours())} : ${pad(now.getMinutes())} : ${pad(now.getSeconds())}`;
            }
            const uptime = Math.floor((Date.now() - startTs) / 1000);
            const u = document.getElementById('uptime');
            if(u) u.textContent = `${pad(Math.floor(uptime/3600))}H ${pad(Math.floor((uptime%3600)/60))}M`;
        }
        updateTicks(); setInterval(updateTicks, 1000);

        // ── 6. CAÍDA DEL IMPERIO ROMANO (THEME PLUTO) ──
        const themeBtn = document.getElementById('themeToggle');
        themeBtn.addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('pluto');
            const isPluto = html.classList.contains('pluto');
            localStorage.setItem('uo-theme-roman', isPluto ? 'dark' : 'light');
            
            // Animacion transicional diurna-nocturna como un eclipse solar
            gsap.fromTo("body", 
                { opacity: 0, filter: "sepia(1) hue-rotate(-50deg)" }, 
                { opacity: 1, filter: "sepia(0) hue-rotate(0deg)", duration: 1.5, ease: "power3.inOut" }
            );
            
            // GSAP rebota la rotación del botón
            gsap.fromTo(themeBtn, { rotationX: -180 }, { rotationX: 0, duration: 0.8 });
        });

        const ri = document.getElementById('req-id');
        if(ri) ri.textContent = 'SPQR_' + Math.random().toString(16).slice(2,8).toUpperCase();

    </script>
</body>
</html>