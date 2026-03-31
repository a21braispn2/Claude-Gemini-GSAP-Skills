<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO API - NODE GRID</title>
    <!-- Theme Switcher (Dark/Light Nuke Styles) -->
    <script>
        if (localStorage.getItem('uo-theme-node') === 'light') {
            document.documentElement.classList.add('light-grid'); 
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    
    <style>
        /* ══════════════ VARIABLES BLUEPRINT (Dark Graph) ══════════════ */
        :root {
            --bg:          #0b0c10; 
            --grid-col:    rgba(255, 255, 255, 0.05);
            --node-bg:     #17181f; /* Grafito */
            --node-head:   #1e1f29;
            --border:      #303342;
            
            --text-pri:    #e0e2eb;
            --text-sec:    #7e8499;
            
            /* Colores LED de Puertos y Verbos */
            --port-idle:   #ffb86c; /* Warning/Idle Orange */
            --port-act:    #50fa7b; /* Data Green */
            --c-get:       #8be9fd; /* Cyan */
            --c-post:      #50fa7b; /* Green */
            --c-put:       #f1fa8c; /* Yellow */
            --c-del:       #ff5555; /* Red */
            
            --cable-base:  #2f3241;
            --cable-glow:  #50fa7b;
            
            --font-mono:   'JetBrains Mono', monospace;
        }

        :root.light-grid {
            /* Modo Blueprint Técnico (Fondo Claro) */
            --bg:          #e6ebf0; /* Fondo ligeramente azulado para destacar papel blanco */
            --grid-col:    rgba(0, 0, 0, 0.08); /* Puntos mas rigidos */
            --node-bg:     #ffffff; 
            --node-head:   #f1f3f5;
            --border:      #adb5bd;
            
            --text-pri:    #212529;
            --text-sec:    #6c757d;
            
            --port-idle:   #d97706; 
            --port-act:    #0f5132; 
            
            /* Colores de Cables para Light Mode (Tonos oscuros y puros para MACRO contraste visual) */
            --c-get:       #0d6efd; /* Azul Puro brillante */
            --c-post:      #198754; /* Verde puro duro */
            --c-put:       #fd7e14; /* Naranja agresivo */
            --c-del:       #dc3545; /* Rojo peligro puro */
            
            --cable-base:  #ced4da; /* Cable base ultra pálido */
            --cable-glow:  #198754;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            background-color: var(--bg); color: var(--text-pri);
            font-family: var(--font-mono); font-size: 14px; min-height: 100vh;
            overflow-x: hidden; position: relative;
            /* La cuadrícula Node Blueprint Infinita */
            background-image: radial-gradient(circle, var(--grid-col) 1px, transparent 1px);
            background-size: 20px 20px;
            transition: background-color 0.4s;
        }

        /* ══════════════ CAPA SVG DE CABLES ══════════════ */
        #cables-layer {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none; /* Atraviesa los clicks */
        }
        .cable-bg { fill: none; stroke: var(--cable-base); stroke-width: 3px; stroke-linecap: round; transition: stroke 0.4s; }
        .cable-pulse { 
            fill: none; stroke: var(--cable-glow); stroke-width: 3px; stroke-linecap: round; 
            stroke-dasharray: 8 20; /* Paquetes de datos viajando (Data marching ants) */
            /* Animado por CSS Keyframes */
            animation: flowData 1s linear infinite; opacity: 0; transition: opacity 0.3s, stroke 0.4s;
        }
        @keyframes flowData {
            from { stroke-dashoffset: 28; } to { stroke-dashoffset: 0; }
        }

        /* ══════════════ LAYOUT DEL WORKSPACE ══════════════ */
        .workspace {
            position: relative; z-index: 10; padding: 100px 40px 120px; /* Separado del navbar */
            max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; gap: 80px;
            align-items: center;
        }

        /* ══════════════ COMPONENTES NODOS (Cajas base) ══════════════ */
        .node {
            background: var(--node-bg); border: 2px solid var(--border);
            border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative; width: 100%; transition: border-color 0.3s, box-shadow 0.3s;
        }
        .node:hover { border-color: var(--text-sec); box-shadow: 0 10px 40px rgba(0,0,0,0.4); }
        :root.light-grid .node:hover { box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        
        .node-header {
            background: var(--node-head); padding: 12px 20px; border-bottom: 2px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            border-top-left-radius: 6px; border-top-right-radius: 6px;
        }
        .n-title { font-weight: 800; font-size: 15px; letter-spacing: 0.1em; text-transform: uppercase; display: flex; align-items: center; gap: 10px;}
        .n-icon { width: 12px; height: 12px; background: var(--text-sec); display: inline-block; border-radius: 2px; }

        .node-body { padding: 25px 30px; }

        /* ══════════════ PUERTOS DE CONEXIÓN (PINES) ══════════════ */
        .port {
            width: 16px; height: 16px; background: var(--node-bg); border: 3px solid var(--port-idle);
            border-radius: 50%; position: absolute; z-index: 5;
            box-shadow: 0 0 10px rgba(0,0,0,0.5); transition: border-color 0.4s, box-shadow 0.4s;
        }
        .port.active { border-color: var(--port-act); box-shadow: 0 0 15px var(--port-act); }
        
        /* Puerto de Salida del Core (Sur) */
        .port-out { bottom: -9px; left: 50%; transform: translateX(-50%); }
        /* Puertos de Entrada de Endpoints (Oeste/Izquierda) */
        .port-in { top: 50%; left: -9px; transform: translateY(-50%); }

        /* ══════════════ NODO CENTRAL (CORE SERVER) ══════════════ */
        .core-node { max-width: 600px; text-align: center; }
        .core-stats { display: flex; justify-content: center; gap: 40px; margin-top: 20px;}
        .c-stat span { display: block; }
        .cs-lbl { color: var(--text-sec); font-size: 11px; text-transform: uppercase; margin-bottom: 5px; }
        .cs-val { font-weight: 700; font-size: 20px; color: var(--port-idle); text-transform: uppercase;}

        /* Modificadores Debug */
        .debug-node { max-width: 600px; border-color: var(--c-put); }
        .debug-node .node-header { background: rgba(241, 250, 140, 0.1); }
        .debug-node .n-icon { background: var(--c-put); }
        .debug-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed var(--border); }
        .debug-row:last-child { border-bottom: none; }
        .d-lbl { color: var(--text-sec); }
        .d-val { font-weight: 700; }

        /* ══════════════ RED DE ENDPOINTS ══════════════ */
        .endpoints-grid {
            display: grid; grid-template-columns: 1fr; gap: 25px; width: 100%; max-width: 700px;
        }

        .ep-node { display: flex; align-items: center; justify-content: space-between; padding: 0;}
        .ep-node .node-body { padding: 15px 20px; display: flex; align-items: center; width: 100%; }
        
        .ep-method { font-weight: 800; width: 70px; font-size: 16px; margin-right: 15px;}
        .get .ep-method { color: var(--c-get); }
        .post .ep-method { color: var(--c-post); }
        .put .ep-method { color: var(--c-put); }
        .del .ep-method { color: var(--c-del); }

        .ep-path { flex: 1; font-size: 15px; color: var(--text-pri); }

        .ep-btn {
            background: var(--node-head); color: var(--text-pri); border: 2px solid var(--border);
            padding: 8px 16px; border-radius: 4px; font-family: var(--font-mono); font-weight: 700;
            cursor: pointer; transition: all 0.2s; font-size: 13px; text-transform: uppercase;
        }
        .ep-btn:hover { background: var(--text-pri); color: var(--bg); border-color: var(--text-pri); }
        .ep-btn:active { transform: scale(0.95); }

        /* ══════════════ NAVBAR TÉCNICA ══════════════ */
        .top-nav {
            position: fixed; top: 0; left: 0; width: 100%; z-index: 100;
            background: var(--node-bg); border-bottom: 2px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            font-family: var(--font-mono); font-size: 13px; font-weight: 700;
        }
        .nav-brand { font-size: 18px; color: var(--text-pri); display: flex; align-items: center; gap: 10px;}
        .nav-status { position: absolute; left: 50%; transform: translateX(-50%); color: var(--text-sec); border-left: 2px dashed var(--border); border-right: 2px dashed var(--border); padding: 0 30px;}
        .nav-tools { display: flex; align-items: center; gap: 15px; }
        .nav-chip {
            background: rgba(0,0,0,0.2); border: 1px solid var(--border); padding: 6px 12px; border-radius: 4px;
            color: var(--text-sec);
        }
        :root.light-grid .nav-chip { background: rgba(0,0,0,0.05); }
        .nav-chip span { color: var(--text-pri); font-weight: 800; }
        .nav-chip.btn-tgl {
            cursor: pointer; color: var(--text-pri); transition: all 0.2s; font-family: var(--font-mono); font-weight: 700;
        }
        .nav-chip.btn-tgl:hover { border-color: var(--port-idle); color: var(--port-idle); }

        footer {
            margin-top: 50px; text-align: center; color: var(--text-sec); font-size: 12px;
            letter-spacing: 0.1em; width: 100%; padding: 20px; background: var(--node-bg); border-top: 2px solid var(--border);
        }

        @media (max-width: 768px) {
            .workspace { padding: 140px 20px 100px; }
            .top-nav { flex-wrap: wrap; gap: 15px; padding: 15px 20px;}
            .nav-status { border: none; padding: 0; width: 100%; position: static; transform: none; text-align: center; margin: 5px 0;}
            .nav-tools { width: 100%; justify-content: space-between;}
            .ep-node .node-body { flex-direction: column; align-items: flex-start; gap: 10px;}
            .ep-method { border-bottom: 1px dashed var(--border); width: 100%; padding-bottom: 10px; }
            .ep-btn { width: 100%; margin-top: 10px;}
            .port-in { display: none; } /* En movil ocultamos los cables físicos si la resolucion falla, o los dejamos arriba */
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
</head>
<body>

    <!-- Capa de Cables (Se pinta con JS via coordenadas) -->
    <svg id="cables-layer"></svg>

    <nav class="top-nav">
        <div class="nav-brand">
            <span class="n-icon" style="background: var(--port-act);"></span> 
            NODE_BLUEPRINT
        </div>
        
        <div class="nav-status">
            STATUS: <span style="color:var(--port-act);">[ SYS_OPTIMAL ]</span>
        </div>

        <div class="nav-tools">
            <div class="nav-chip">ENV: <span>{{ app()->environment() }}</span></div>
            <div class="nav-chip" id="clock">00:00:00</div>
            <button class="nav-chip btn-tgl" id="themeToggle">TOGGLE_THEME</button>
        </div>
    </nav>

    <div class="workspace">
        
        {{-- ── NODO 0: SERVER CORE ── --}}
        <div class="node core-node anim-spawn" id="core-node">
            <div class="node-header">
                <div class="n-title"><span class="n-icon"></span> MAIN_SYS_ROUTER</div>
                <div style="font-size:11px; color:var(--text-sec);">ID: SRV-01</div>
            </div>
            <div class="node-body">
                <p style="color:var(--text-sec); margin-bottom: 20px; line-height: 1.5;">API Gateway Activo. Nodos perimetrales listos para ingesta y modificación de datos maestros.</p>
                <div class="core-stats">
                    <div class="c-stat"><span class="cs-lbl">Deploy</span><span class="cs-val">{{ app()->environment() }}</span></div>
                    <div class="c-stat"><span class="cs-lbl">Uptime</span><span class="cs-val" id="uptime" style="color:var(--port-act);">00:00:00</span></div>
                    <div class="c-stat"><span class="cs-lbl">Status</span><span class="cs-val" style="color:var(--c-post);">ONLINE</span></div>
                </div>
            </div>
            <!-- El Puerto Maestro de Salida de Datos -->
            <div class="port port-out" id="master-out"></div>
        </div>

        {{-- ── NODO DE DEPURE (DEBUG) ── --}}
        @if(config('app.debug'))
        <div class="node debug-node anim-spawn" style="max-width:600px;">
            <div class="node-header">
                <div class="n-title"><span class="n-icon"></span> DEBUG_MATRIX [WARNING]</div>
            </div>
            <div class="node-body">
                <div class="debug-row"><div class="d-lbl">Engine</div><div class="d-val" style="color:var(--port-idle)">Laravel {{ app()->version() }}</div></div>
                <div class="debug-row"><div class="d-lbl">Core PHP</div><div class="d-val">PHP {{ phpversion() }}</div></div>
                <div class="debug-row"><div class="d-lbl">Node DB</div><div class="d-val">{{ config('database.default') }}</div></div>
                <div class="debug-row"><div class="d-lbl">Host IPv</div><div class="d-val">{{ config('database.connections.mysql.host') }}</div></div>
                <div class="debug-row"><div class="d-lbl">Root Usr</div><div class="d-val">{{ config('database.connections.mysql.username') }}</div></div>
            </div>
        </div>
        @endif

        {{-- ── GRID DE NODOS ENDPOINTS ── --}}
        <div class="endpoints-grid">
            
            <div class="node ep-node get anim-spawn">
                <div class="port port-in"></div> <!-- Receptor del cable -->
                <div class="node-body"><div class="ep-method">GET</div><div class="ep-path">/api/v1/status</div><button class="ep-btn" data-path="/api/v1/status">Execute</button></div>
            </div>
            
            <div class="node ep-node get anim-spawn">
                <div class="port port-in"></div>
                <div class="node-body"><div class="ep-method">GET</div><div class="ep-path">/api/v1/users</div><button class="ep-btn" data-path="/api/v1/users">Execute</button></div>
            </div>
            
            <div class="node ep-node get anim-spawn">
                <div class="port port-in"></div>
                <div class="node-body"><div class="ep-method">GET</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-btn" data-path="/api/v1/users/{id}">Execute</button></div>
            </div>

            <div class="node ep-node post anim-spawn">
                <div class="port port-in"></div>
                <div class="node-body"><div class="ep-method">POST</div><div class="ep-path">/api/v1/users</div><button class="ep-btn" data-path="/api/v1/users">Execute</button></div>
            </div>

            <div class="node ep-node put anim-spawn">
                <div class="port port-in"></div>
                <div class="node-body"><div class="ep-method">PUT</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-btn" data-path="/api/v1/users/{id}">Execute</button></div>
            </div>

            <div class="node ep-node del anim-spawn">
                <div class="port port-in"></div>
                <div class="node-body"><div class="ep-method">DEL</div><div class="ep-path">/api/v1/users/{id}</div><button class="ep-btn" data-path="/api/v1/users/{id}">Execute</button></div>
            </div>

        </div>

    </div>

    <footer>
        <div style="font-weight:800; color:var(--text-pri); margin-bottom: 5px;">GRAPH-ID: <span id="req-id">NODE_00</span></div>
        UO DATA NETWORK &copy; {{ date('Y') }}
    </footer>

    <!-- ══════════════ MOTOR DE GRAFO DE NODOS Y CABLES ══════════════ -->
    <script>
        // ── 1. CÁLCULO FÍSICO Y DIBUJADO DE LAS ESPLINES (CABLES SVG) ──
        const svgLayer = document.getElementById('cables-layer');
        const masterOut = document.getElementById('master-out');
        const endNodes = document.querySelectorAll('.ep-node');
        let cables = [];

        function drawCables() {
            if(window.innerWidth < 768) { svgLayer.innerHTML = ''; return; } // No cables en móvil chato
            
            svgLayer.innerHTML = ''; // Reset
            cables = [];
            
            // Pillar la coord global del Master Port Out
            const mRect = masterOut.getBoundingClientRect();
            // Sumar scrollY por si la página bajó (posiciones absolutas en el document)
            const sx = mRect.left + mRect.width / 2;
            const sy = (mRect.top + window.scrollY) + mRect.height / 2;

            endNodes.forEach((node, index) => {
                const portIn = node.querySelector('.port-in');
                if(!portIn) return;
                const pRect = portIn.getBoundingClientRect();
                const ex = pRect.left + pRect.width / 2;
                const ey = (pRect.top + window.scrollY) + pRect.height / 2;

                // Calcular curva Bezier suave tipo "S" (desde abajo hacia la izquierda)
                // Usamos un control point que baje vertical desde el server y entre horizontalmente en el endpoint
                const cp1x = sx;
                const cp1y = sy + (ey - sy) / 2;
                const cp2x = ex - 150; // Tira del vector hacia la izquierda
                const cp2y = ey;

                const pathData = `M ${sx} ${sy} C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${ex} ${ey}`;

                // Base Oscura Muelle
                const baseCable = document.createElementNS("http://www.w3.org/2000/svg", "path");
                baseCable.setAttribute("class", "cable-bg");
                baseCable.setAttribute("d", pathData);
                svgLayer.appendChild(baseCable);

                // Señal Luminosa de Datos (Stroke Dash)
                const sigCable = document.createElementNS("http://www.w3.org/2000/svg", "path");
                sigCable.setAttribute("class", "cable-pulse");
                sigCable.setAttribute("d", pathData);
                sigCable.setAttribute("id", `sig-${index}`);
                
                // Extraer el color del HTTP Method para pintar el láser del cable
                let cableCol = "var(--cable-glow)";
                if(node.classList.contains('get')) cableCol = "var(--c-get)";
                if(node.classList.contains('post')) cableCol = "var(--c-post)";
                if(node.classList.contains('put')) cableCol = "var(--c-put)";
                if(node.classList.contains('del')) cableCol = "var(--c-del)";
                sigCable.style.stroke = cableCol;
                sigCable.style.opacity = '1'; // Fuerza a que la señal lumínica exista y cicle independientemente de re-renderizados de GSAP o Temas

                svgLayer.appendChild(sigCable);
                cables.push({ bas: baseCable, sig: sigCable, col: cableCol, domNode: node, port: portIn });
            });
        }

        // Llamar en primera pintura y si cambiamos tamaño de ventana
        window.addEventListener('load', drawCables);
        window.addEventListener('resize', drawCables);

        // ── 2. ANIMACIÓN DE ARRANQUE GSAP ──
        gsap.from('.anim-spawn', {
            opacity: 0, scale: 0.9, y: 30, duration: 0.8, stagger: 0.1, ease: "back.out(1)",
            onComplete: () => {
                // Al terminar de spawnear las cajas, pintamos los cables para coordinar y encendemos
                drawCables();
                masterOut.classList.add('active');
            }
        });

        // ── 3. INTERACCIONES UX DE RED (Hovering en nodos inyecta datos) ──
        endNodes.forEach((node, index) => {
            node.addEventListener('mouseenter', () => {
                const port = node.querySelector('.port-in');
                if(port) port.classList.add('active');
                if(cables[index]) {
                    cables[index].sig.style.strokeWidth = "5px"; // Flujo denso de cable
                    cables[index].bas.style.stroke = cables[index].col; // El fondo se tiñe del color de su método
                }
            });
            node.addEventListener('mouseleave', () => {
                const port = node.querySelector('.port-in');
                if(port) port.classList.remove('active');
                if(cables[index]) {
                    cables[index].sig.style.strokeWidth = "3px";
                    cables[index].bas.style.stroke = "var(--cable-base)";
                }
            });

            // Botón de Ejecutar
            const btn = node.querySelector('.ep-btn');
            if(btn) {
                btn.addEventListener('click', () => {
                    const originalStr = btn.innerText;
                    navigator.clipboard?.writeText(btn.dataset.path);
                    btn.innerText = 'FETCHING...'; // Hacker text
                    gsap.fromTo(btn, {backgroundColor: "var(--port-idle)", color: "#000"}, {backgroundColor: "var(--node-head)", color: "var(--text-pri)", duration: 1.5});
                    setTimeout(() => { btn.innerText = originalStr; }, 1500);
                });
            }
        });

        // ── 4. THEME TOGGLE (Diagrama Oscuro / Claro) ──
        const tBtn = document.getElementById('themeToggle');
        tBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('light-grid');
            localStorage.setItem('uo-theme-node', document.documentElement.classList.contains('light-grid') ? 'light' : 'dark');
            drawCables(); // Repintar porque cambian los colores CSS Var de los stroke
        });

        // ── 5. RELOJ E INFOS ──
        const pad = n => String(n).padStart(2,'0');
        const startTs = Date.now();
        function updateTicks() {
            const now = new Date();
            const c = document.getElementById('clock');
            if(c) c.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            const u = document.getElementById('uptime');
            if(u) { const uptime = Math.floor((Date.now() - startTs) / 1000); u.textContent = `${pad(Math.floor(uptime/3600))}:${pad(Math.floor((uptime%3600)/60))}:${pad(uptime%60)}`; }
        }
        updateTicks(); setInterval(updateTicks, 1000);

        const ri = document.getElementById('req-id');
        if(ri) ri.textContent = 'ND_' + Math.floor(Math.random()*9999).toString().padStart(4,'0');

    </script>
</body>
</html>