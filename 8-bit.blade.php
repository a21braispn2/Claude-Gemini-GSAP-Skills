<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API - 8 BIT ARCADE</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES ARCADE NES ══════════════ */
        :root {
            --bg:          #000000;
            --text-pri:    #ffffff;
            --text-coin:   #f7d51d; /* Arcade Yellow */
            
            --border-ui:   #ffffff;
            --hover-row:   #111111;
            
            --c-get:       #20a6ff; /* Neon Blue */
            --c-post:      #38e338; /* Neon Green */
            --c-put:       #f7d51d; /* Arcade Yellow */
            --c-del:       #e52521; /* Arcade Red */
            
            --btn-red:     #e52521;
            --btn-red-dk:  #ab1815;

            --font-retro:  'Press Start 2P', system-ui, monospace;
        }

        /* ══════════════ TEMA LIGHT (OVERWORLD) ══════════════ */
        :root.light-mode {
            --bg:          #f4f4f4;
            --text-pri:    #000000;
            --text-coin:   #d13b2e; /* Red Coin para contraste en blanco */
            
            --border-ui:   #000000;
            --hover-row:   #e0e0e0;
            
            --c-get:       #0d6efd; /* Strong Blue */
            --c-post:      #198754; /* Strong Green */
            --c-put:       #b05d04; /* Strong Amber */
            --c-del:       #dc3545; /* Brick Red */
        }
        
        /* Outline Aserrado 8-Bits para Título en Modo Claro */
        :root.light-mode .arc-title {
            color: #ffffff;
            text-shadow: 
                4px 0 0 #000, -4px 0 0 #000, 0 4px 0 #000, 0 -4px 0 #000,
                4px 4px 0 #000, -4px -4px 0 #000, 4px -4px 0 #000, -4px 4px 0 #000,
                8px 8px 0 var(--c-get);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            background-color: var(--bg); color: var(--text-pri);
            font-family: var(--font-retro); font-size: 14px; line-height: 1.8;
            overflow-x: hidden; text-transform: uppercase;
            /* Patrón de scanlines MS-DOS transparente */
            background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(128,128,128,0.1) 2px, rgba(128,128,128,0.1) 4px);
            padding-bottom: 50px; transition: background-color 0.2s, color 0.2s;
        }

        .container {
            max-width: 900px; margin: 0 auto; padding: 0 20px;
        }

        /* ══════════════ HUD DEL JUGADOR (CABECERA) ══════════════ */
        .hud {
            display: flex; justify-content: space-between; padding: 40px 0;
            font-size: 18px; text-align: center; border-bottom: 4px dashed #333;
        }
        .hud-lbl { color: var(--text-pri); margin-bottom: 8px;}
        .hud-val { color: var(--text-coin); }

        /* Efectos Físicos Arcade (Parpadeos en Seco sin Ease) */
        .blink-fast { animation: blink 0.2s steps(1) infinite; }
        .blink-slow { animation: blink 0.8s steps(1) infinite; }
        .blink-1up { animation: blink 0.5s steps(1) infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        /* ══════════════ PANTALLA DE TÍTULO ══════════════ */
        .title-screen { text-align: center; margin: 60px 0; }
        .arc-title { 
            font-size: 50px; margin-bottom: 20px; color: var(--border-ui);
            text-shadow: 4px 4px 0 var(--c-get), -4px -4px 0 var(--c-del); 
            letter-spacing: 4px;
        }
        .sub-title { font-size: 16px; color: var(--text-coin); }

        /* ══════════════ NES CONTAINER (Ventana Pixel) ══════════════ */
        .nes-container {
            position: relative; border: 4px solid var(--border-ui);
            padding: 30px; background: var(--bg); margin-top: 60px;
            /* Sombra tipo bloque macizo 8-bits */
            box-shadow: 8px 8px 0 rgba(128,128,128,0.4);
            transition: background 0.2s, border-color 0.2s;
        }
        .nes-container.with-title > .title {
            position: absolute; top: -14px; left: 24px; background: var(--bg);
            padding: 0 16px; font-size: 20px; color: var(--border-ui);
            transition: background 0.2s, color 0.2s;
        }

        /* ══════════════ INVENTARIO (LISTA DE ENDPOINTS) ══════════════ */
        .ep-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 24px 0; border-bottom: 4px dashed #333; cursor: pointer;
            transition: 0s; /* Cero iteracion */
        }
        .ep-row:last-child { border-bottom: none; padding-bottom: 0; }
        
        /* El Puntero (Espada / Flecha Retro) */
        .ep-cursor {
            opacity: 0; color: var(--c-del); font-size: 20px; margin-right: 16px;
            width: 25px;
        }
        
        .ep-row:hover { background: var(--hover-row); }
        .ep-row:hover .ep-cursor { opacity: 1; animation: blink 0.3s steps(1) infinite; }
        .ep-row:hover .ep-path { color: var(--text-coin); }

        .ep-meth { font-size: 18px; width: 100px; }
        .get .ep-meth { color: var(--c-get); }
        .post .ep-meth { color: var(--c-post); }
        .put .ep-meth { color: var(--c-put); }
        .del .ep-meth { color: var(--c-del); }

        .ep-path { flex: 1; font-size: 16px; color: var(--border-ui); }

        /* BOTÓN FÍSICO ARCADE "A" */
        .nes-btn {
            border: 4px solid var(--border-ui); background: var(--btn-red); color: var(--border-ui);
            font-family: var(--font-retro); font-size: 18px; padding: 12px 20px; cursor: pointer;
            /* Relieve macizo Inset */
            box-shadow: inset -4px -4px 0 rgba(0,0,0,0.5), inset 4px 4px 0 rgba(255,255,255,0.5);
        }
        .nes-btn:hover { background: #ff4743; }
        /* Efecto Push: Elimino los relieves e invierto sombras hacia dentro */
        .nes-btn:active, .nes-btn.pushed {
            background: var(--btn-red-dk);
            box-shadow: inset 4px 4px 0 rgba(0,0,0,0.7);
            transform: translateY(4px); /* Hunde el boton fisicamente */
        }

        /* ══════════════ MACROS DE DEPURE / DEBUG ══════════════ */
        .debug-grid { display: flex; flex-direction: column; gap: 14px; font-size: 12px;}
        .d-row { display: flex; justify-content: space-between; border-bottom: 4px dashed #333; padding-bottom: 10px; }
        .d-row:last-child { border-bottom: none; padding-bottom: 0;}
        .d-lbl { color: #777; }
        .d-val { color: var(--border-ui); text-align: right;}
        
        :root.light-mode .d-lbl { color: #666; }
        :root.light-mode .d-row { border-bottom-color: #ccc; }

        /* ══════════════ MENSAJE SECRETO GSAP (Footer) ══════════════ */
        .secret-log {
            margin-top: 50px; font-size: 12px; color: #777; text-align: center;
        }

        .theme-btn-arc {
            position: absolute; top: 15px; right: 20px;
            background: transparent; color: var(--text-coin); border: none;
            font-family: var(--font-retro); font-size: 10px; cursor: pointer; text-transform: uppercase;
        }
        .theme-btn-arc:hover { color: var(--text-pri); }

        @media (max-width: 768px) {
            .hud { flex-direction: column; gap: 20px; }
            .arc-title { font-size: 30px; }
            .ep-row { flex-wrap: wrap; gap: 15px;}
            .ep-cursor { display: none; }
            .ep-path { width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 12px;}
            .nes-btn { width: 100%; }
            .nes-container { padding: 15px; }
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script>
        if(localStorage.getItem('uo-arcade-theme') === 'light') document.documentElement.classList.add('light-mode');
    </script>
</head>
<body>

    <button class="theme-btn-arc blink-slow" id="btnTheme">[SELECT] THEME</button>

    <div class="container">
        <!-- ══════════════ HUD DEL SISTEMA NES ══════════════ -->
        <div class="hud">
            <div><div class="hud-lbl blink-1up">1UP (VER)</div><div class="hud-val">10{{ str_replace('.', '', config('app.version')) }}</div></div>
            <div><div class="hud-lbl">ENV MATCH</div><div class="hud-val">{{ app()->environment() }}</div></div>
            @if(config('app.debug'))
            <div><div class="hud-lbl">DEBUG</div><div class="hud-val blink-slow" style="color:var(--c-del);">ON</div></div>
            @endif
            <div><div class="hud-lbl">TIME</div><div class="hud-val" id="clock">999</div></div>
        </div>

        <!-- ══════════════ PANTALLA TÍTULO ARCADE ══════════════ -->
        <div class="title-screen">
            <h1 class="arc-title blink-slow">UO_API</h1>
            <h2 class="sub-title blink-1up">PUSH SELECT STAGE</h2>
        </div>

        <!-- ══════════════ STACK DE ENDPOINTS (NIVELES) ══════════════ -->
        <div class="nes-container with-title stagger-box">
            <p class="title">STAGE_SELECT</p>
            
            <div class="endpoint-list">
                
                <div class="ep-row get" data-path="/api/v1/status">
                    <div class="ep-cursor">▶</div><div class="ep-meth">GET</div><div class="ep-path">/API/V1/STATUS</div>
                    <button class="nes-btn">A</button>
                </div>
                
                <div class="ep-row get" data-path="/api/v1/users">
                    <div class="ep-cursor">▶</div><div class="ep-meth">GET</div><div class="ep-path">/API/V1/USERS</div>
                    <button class="nes-btn">A</button>
                </div>
                
                <div class="ep-row get" data-path="/api/v1/users/{id}">
                    <div class="ep-cursor">▶</div><div class="ep-meth">GET</div><div class="ep-path">/API/USERS/{ID}</div>
                    <button class="nes-btn">A</button>
                </div>

                <div class="ep-row post" data-path="/api/v1/users">
                    <div class="ep-cursor">▶</div><div class="ep-meth">POST</div><div class="ep-path">/API/V1/USERS</div>
                    <button class="nes-btn">A</button>
                </div>

                <div class="ep-row put" data-path="/api/v1/users/{id}">
                    <div class="ep-cursor">▶</div><div class="ep-meth">PUT</div><div class="ep-path">/API/USERS/{ID}</div>
                    <button class="nes-btn">A</button>
                </div>

                <div class="ep-row del" data-path="/api/v1/users/{id}">
                    <div class="ep-cursor">▶</div><div class="ep-meth">DEL</div><div class="ep-path">/API/USERS/{ID}</div>
                    <button class="nes-btn">A</button>
                </div>

            </div>
        </div>

        <!-- ══════════════ MEMORY CARD (SYS_DEBUG) ══════════════ -->
        @if(config('app.debug'))
        <div class="nes-container with-title stagger-box" style="margin-top: 40px; border-color: var(--c-del); box-shadow: 8px 8px 0 rgba(229, 37, 33, 0.2);">
            <p class="title" style="color: var(--c-del); font-size: 14px;">[ SYS_DEBUG ]</p>
            <div class="blink-slow" style="font-size: 10px; margin-bottom: 25px; color: var(--c-del);">!! WARNING DEVS ONLY !!</div>
            
            <div class="debug-grid">
                <div class="d-row"><span class="d-lbl">ENGINE_LVL</span><span class="d-val">LARAVEL {{ app()->version() }}</span></div>
                <div class="d-row"><span class="d-lbl">CORE_CPU</span><span class="d-val">PHP {{ phpversion() }}</span></div>
                <div class="d-row"><span class="d-lbl">MEM_CARD P1</span><span class="d-val">{{ config('database.default') }}</span></div>
                <div class="d-row"><span class="d-lbl">HOST_IPV</span><span class="d-val">{{ config('database.connections.mysql.host', 'VOID') }}</span></div>
                <div class="d-row"><span class="d-lbl">PLAYER_1_ID</span><span class="d-val">{{ config('database.connections.mysql.username', 'GUEST') }}</span></div>
            </div>
        </div>
        @endif

        <div class="secret-log blink-slow">
            (C) {{ date('Y') }} UO DEVELOPERS INC.
        </div>
    </div>

    <!-- ══════════════ GSAP 8-BIT ENGINE (No Easing, Just Steps) ══════════════ -->
    <script>
        // ── 1. ANIMACIÓN DE ENTRADA CHUNKY (Fotogramas Rotos) ──
        // GSAP está pensado para fluidez, nosotros lo capamos para simular "Frames bajos" 
        // usando 'steps()' en el easin de la Y y opacity
        gsap.from('.stagger-box', {
            y: 40, opacity: 0, 
            ease: "steps(4)", // Solo dibuja 4 interrupciones en su camino, sin suavizar
            duration: 0.6
        });

        const rows = document.querySelectorAll('.ep-row');
        gsap.from(rows, {
            x: -20, opacity: 0,
            stagger: 0.1,
            ease: "steps(2)", // Animación tosca deliberada
            duration: 0.2,
            delay: 0.5
        });

        // ── 2. EL EFECTO DE CÓDIGO GAMING Y COPIA ──
        rows.forEach(r => {
            const btn = r.querySelector('.nes-btn');
            const path = r.getAttribute('data-path');

            // Efecto Hover Boton Click
            btn.addEventListener('mousedown', () => btn.classList.add('pushed'));
            btn.addEventListener('mouseup', () => btn.classList.remove('pushed'));
            btn.addEventListener('mouseleave', () => btn.classList.remove('pushed'));

            btn.addEventListener('click', (e) => {
                // Previene burbujeo a la fila
                e.stopPropagation();
                
                // Copy
                navigator.clipboard?.writeText(path);

                // Efecto Moneda (Coin / 1UP) usando GSAP stepped jump
                const originalStr = btn.innerText;
                btn.innerText = "COPIED";
                
                // Salto intermitente del botón (Estilo daño de jefe final, parpadeo)
                gsap.to(btn, {
                    opacity: 0,
                    repeat: 5, yoyo: true, duration: 0.05, ease: "steps(1)",
                    onComplete: () => {
                        btn.style.opacity = 1;
                        setTimeout(() => btn.innerText = originalStr, 1000);
                    }
                });
            });
        });

        // ── 3. RELOJ DE TIEMPO ESTILO SUPER MARIO ──
        // Cuenta atrás simbólica desde 999. Si llega a 0... HURRY UP
        let timeRem = 999;
        const clockEl = document.getElementById('clock');
        function tickTime() {
            if(timeRem > 0) {
                timeRem--;
                clockEl.textContent = timeRem.toString().padStart(3, '0');
            } else {
                clockEl.textContent = "000";
                clockEl.style.color = "var(--c-del)";
                clockEl.classList.add('blink-fast');
            }
        }
        setInterval(tickTime, 2000); // 1 tick cada 2 segundos reales (tiempo artificial)

        // ── 4. BOTÓN START (THEME TOGGLE) ──
        const tmBtn = document.getElementById('btnTheme');
        tmBtn.addEventListener('click', () => {
            const doc = document.documentElement;
            doc.classList.toggle('light-mode');
            localStorage.setItem('uo-arcade-theme', doc.classList.contains('light-mode') ? 'light' : 'dark');
            tmBtn.innerText = doc.classList.contains('light-mode') ? "[SELECT] OVERWORLD" : "[SELECT] UNDERWORLD";
            // Retro flash screen
            gsap.fromTo("body", {opacity: 0, backgroundColor: "#fff"}, {opacity: 1, backgroundColor: "var(--bg)", duration: 0.2, ease: "steps(1)"});
        });
        if(document.documentElement.classList.contains('light-mode')) tmBtn.innerText = "[SELECT] OVERWORLD";

    </script>
</body>
</html>