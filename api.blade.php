<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Fragment+Mono:ital@0;1&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">
    <title>UO API</title>
    <style>
        /* ══════════════ TOKENS ══════════════ */
        :root {
            --principal:  #E6FB04;
            --cta1:       #0CE4E8;
            --cta2:       #FFA341;
            --estado1:    #748F45;
            --estado2r:   #B64F4F;
            --estado2y:   #E2B952;
            --sec1:       #F5F5F3;
            --sec2:       #416060;

            /* dark theme (default) */
            --bg:         #0c1616;
            --bg2:        #122020;
            --surface:    #162626;
            --border:     #1e3535;
            --text:       #eef2f2;
            --muted:      #547070;
            --accent:     var(--principal);
            --accent2:    var(--cta1);
            --shadow:     var(--principal);
            --nav-bg:     rgba(12,22,22,.94);

            --font-display: 'Syne', sans-serif;
            --font-mono:    'Fragment Mono', monospace;
        }

        :root.light {
            --bg:         #F5F5F3;
            --bg2:        #ebebea;
            --surface:    #ffffff;
            --border:     #c8ccc8;
            --text:       #1a2e2e;
            --muted:      #7a9090;
            --accent:     var(--sec2);
            --accent2:    var(--estado1);
            --shadow:     var(--sec2);
            --nav-bg:     rgba(245,245,243,.94);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-mono);
            font-size: 13px;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background .3s, color .3s;
        }

        /* ── diagonal stripe bg ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 28px,
                rgba(255,255,255,.012) 28px,
                rgba(255,255,255,.012) 29px
            );
            pointer-events: none;
            z-index: 0;
        }

        :root.light body::before {
            background-image: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 28px,
                rgba(0,0,0,.025) 28px,
                rgba(0,0,0,.025) 29px
            );
        }

        /* ══════════════ NAV ══════════════ */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: stretch;
            height: 56px;
            background: var(--nav-bg);
            backdrop-filter: blur(16px);
            border-bottom: 2px solid var(--border);
            transition: background .3s, border-color .3s;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 24px;
            border-right: 2px solid var(--border);
        }

        .nav-brand-icon {
            width: 40px;
            height: 30px;
            background: var(--principal);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 13px;
            color: #0c1616;
            flex-shrink: 0;
        }

        .nav-brand h1 {
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .04em;
            color: var(--text);
            text-transform: uppercase;
            transition: color .3s;
        }

        .nav-status {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 0 20px;
            border-right: 2px solid var(--border);
            font-size: 10px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--estado1);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--estado1);
            animation: blink 2.2s ease-in-out infinite;
        }

        @keyframes blink {
            0%,100% { opacity:1; }
            50%      { opacity:.35; }
        }

        nav ul {
            display: flex;
            list-style: none;
            margin-left: auto;
        }

        nav ul li a {
            display: flex;
            align-items: center;
            height: 56px;
            padding: 0 20px;
            color: var(--muted);
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 10px;
            letter-spacing: .12em;
            text-transform: uppercase;
            border-left: 2px solid var(--border);
            transition: color .2s, background .2s;
        }

        nav ul li a:hover, nav ul li a.active {
            color: var(--text);
            background: var(--surface);
        }
        
        nav ul li a.active {
            color: var(--principal);
        }
        
        :root.light nav ul li a.active {
            color: var(--sec2);
        }

        /* ── theme toggle btn ── */
        .theme-btn {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 0 20px;
            border: none;
            border-left: 2px solid var(--border);
            background: transparent;
            color: var(--muted);
            font-family: var(--font-mono);
            font-size: 10px;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            transition: color .2s, background .2s;
        }

        .theme-btn:hover { color: var(--text); background: var(--surface); }

        .tb-track {
            width: 34px;
            height: 18px;
            border-radius: 3px;
            border: 2px solid var(--border);
            background: var(--bg);
            position: relative;
            transition: background .3s, border-color .3s;
        }

        .tb-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 10px;
            height: 10px;
            background: var(--muted);
            border-radius: 1px;
            transition: transform .3s cubic-bezier(.34,1.56,.64,1), background .3s;
        }

        :root.light .tb-track { background: var(--principal); border-color: var(--sec2); }
        :root.light .tb-thumb { transform: translateX(14px); background: var(--sec2); }

        /* ══════════════ LAYOUT ══════════════ */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 52px 24px 80px;
            position: relative;
            z-index: 1;
        }

        /* ══════════════ HERO ══════════════ */
        .hero {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0;
            border: 2px solid var(--border);
            background: var(--surface);
            margin-bottom: 28px;
            box-shadow: 5px 5px 0 var(--shadow);
            transition: background .3s, border-color .3s, box-shadow .3s;
            visibility: hidden;
        }

        @keyframes slideIn {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .hero-main {
            padding: 36px 40px;
            border-right: 2px solid var(--border);
        }

        .hero-tag {
            display: inline-block;
            font-size: 9px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #0c1616;
            background: var(--principal);
            padding: 3px 10px;
            margin-bottom: 20px;
            font-family: var(--font-mono);
        }

        .hero h2 {
            font-family: var(--font-display);
            font-size: 42px;
            font-weight: 800;
            line-height: .95;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: -.02em;
            margin-bottom: 14px;
            transition: color .3s;
        }

        .hero h2 em {
            font-style: normal;
            color: var(--cta1);
            display: block;
        }

        :root.light .hero h2 em { color: var(--sec2); }

        .hero-sub {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 400px;
        }

        .hero-stats {
            display: grid;
            grid-template-rows: 1fr 1fr 1fr;
            min-width: 200px;
        }

        .stat-cell {
            padding: 0 28px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            border-bottom: 2px solid var(--border);
            transition: border-color .3s;
        }

        .stat-cell:last-child { border-bottom: none; }

        .stat-label {
            font-size: 9px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .stat-value {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--cta2);
            transition: color .3s;
        }

        :root.light .stat-value { color: var(--estado2r); }

        /* ══════════════ SECTIONS ══════════════ */
        .section-label {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 16px;
            visibility: hidden;
        }

        .section-label span {
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--text);
            background: var(--bg2);
            border: 2px solid var(--border);
            padding: 6px 16px;
            transition: all .3s;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--border);
            transition: background .3s;
        }



        /* ══════════════ ENDPOINTS ══════════════ */
        .endpoints {
            border: 2px solid var(--border);
            background: var(--surface);
            box-shadow: 5px 5px 0 var(--border);
            margin-bottom: 28px;
            transition: background .3s, border-color .3s, box-shadow .3s;
            visibility: hidden;
        }

        .ep-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            border-bottom: 2px solid var(--border);
            cursor: default;
            transition: background .15s, border-color .3s;
        }

        .ep-row:last-child { border-bottom: none; }

        .ep-row:hover { background: var(--bg2); }

        .badge {
            font-family: var(--font-display);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .1em;
            padding: 4px 10px;
            border-radius: 2px;
            min-width: 56px;
            text-align: center;
            border: 2px solid;
            flex-shrink: 0;
        }

        .badge.GET    { color: var(--cta1);    border-color: var(--cta1);    background: rgba(12,228,232,.1); }
        .badge.POST   { color: var(--estado1); border-color: var(--estado1); background: rgba(116,143,69,.1); }
        .badge.PUT    { color: var(--cta2);    border-color: var(--cta2);    background: rgba(255,163,65,.1); }
        .badge.DELETE { color: var(--estado2r);border-color: var(--estado2r);background: rgba(182,79,79,.1); }

        .ep-path {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--text);
            transition: color .3s;
        }

        .ep-desc {
            margin-left: auto;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: .08em;
        }

        .ep-num {
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 700;
            color: var(--border);
            transition: color .3s;
            min-width: 20px;
        }

        /* ══════════════ FOOTER ══════════════ */
        footer {
            border-top: 2px solid var(--border);
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: .1em;
            position: relative;
            z-index: 1;
            transition: border-color .3s;
            visibility: hidden;
        }

        .footer-accent {
            width: 28px;
            height: 4px;
            background: var(--principal);
            border-radius: 1px;
        }

        /* ══════════════ COPY BUTTON ══════════════ */
        .copy-btn {
            margin-left: auto;
            padding: 5px 12px;
            font-family: var(--font-mono);
            font-size: 9px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            background: transparent;
            border: 2px solid var(--border);
            cursor: pointer;
            position: relative;
            transition: color .2s, border-color .2s;
            will-change: transform;
            flex-shrink: 0;
        }

        .copy-btn:hover {
            color: var(--principal);
            border-color: var(--principal);
        }

        :root.light .copy-btn:hover {
            color: var(--sec2);
            border-color: var(--sec2);
        }

        .copy-btn.copied {
            color: var(--estado1);
            border-color: var(--estado1);
        }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 640px) {
            .hero { grid-template-columns: 1fr; }
            .hero-main { border-right: none; border-bottom: 2px solid var(--border); }
            .hero-stats { grid-template-rows: none; grid-template-columns: repeat(3,1fr); }
            .stat-cell { border-bottom: none; border-right: 2px solid var(--border); padding: 16px; }
            .stat-cell:last-child { border-right: none; }
            .theme-btn span:last-child { display: none; }
            nav ul li:not(:last-child) a { display: none; }
            .copy-btn { display: none; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-brand">
            <div class="nav-brand-icon">UO</div>
            <h1>API</h1>
        </div>
        <div class="nav-status">
            <span class="status-dot"></span>
            Operational
        </div>
        <ul>
            <li><a href="/api" class="active">API</a></li>
            <li><a href="/docs">Docs</a></li>
            <li><a href="/debug">Debug</a></li>
        </ul>
        <button class="theme-btn" id="themeBtn">
            <span>☽</span>
            <div class="tb-track"><div class="tb-thumb"></div></div>
            <span>☀</span>
        </button>
    </nav>

    <div class="container">

        {{-- ── HERO ── --}}
        <div class="hero">
            <div class="hero-main">
                <div class="hero-tag">// system status</div>
                <h2>Gateway<em>Online</em></h2>
                <p class="hero-sub">All services nominal. Requests are being processed and authenticated.</p>
            </div>
            <div class="hero-stats">
                <div class="stat-cell">
                    <span class="stat-label">Uptime</span>
                    <span class="stat-value" id="uptime">00:00:00</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-label">Local time</span>
                    <span class="stat-value" id="clock">--:--:--</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-label">Environment</span>
                    <span class="stat-value">{{ app()->environment() }}</span>
                </div>
            </div>
        </div>



        {{-- ── ENDPOINTS ── --}}
        <div class="section-label"><span>Endpoints</span></div>
        <div class="endpoints">
            <div class="ep-row"><span class="ep-num">01</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/status</span><span class="ep-desc">Health check</span><button class="copy-btn" data-path="/api/v1/status">Copy</button></div>
        </div>
        <div class="section-label"><span>Users</span></div>
        <div class="endpoints">
            <div class="ep-row"><span class="ep-num">01</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/users</span><span class="ep-desc">List users</span><button class="copy-btn" data-path="/api/v1/users">Copy</button></div>
            <div class="ep-row"><span class="ep-num">02</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/users/{id}</span><span class="ep-desc">Get user</span><button class="copy-btn" data-path="/api/v1/users/{id}">Copy</button></div>
            <div class="ep-row"><span class="ep-num">03</span><span class="badge POST">POST</span><span class="ep-path">/api/v1/users</span><span class="ep-desc">Create user</span><button class="copy-btn" data-path="/api/v1/users">Copy</button></div>
            <div class="ep-row"><span class="ep-num">04</span><span class="badge PUT">PUT</span><span class="ep-path">/api/v1/users/{id}</span><span class="ep-desc">Update user</span><button class="copy-btn" data-path="/api/v1/users/{id}">Copy</button></div>
            <div class="ep-row"><span class="ep-num">05</span><span class="badge DELETE">DELETE</span><span class="ep-path">/api/v1/users/{id}</span><span class="ep-desc">Delete user</span><button class="copy-btn" data-path="/api/v1/users/{id}">Copy</button></div>
        </div>

        <div class="section-label"><span>Projects</span></div>
        <div class="endpoints">
            <div class="ep-row"><span class="ep-num">01</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/projects</span><span class="ep-desc">List projects</span><button class="copy-btn" data-path="/api/v1/projects">Copy</button></div>
            <div class="ep-row"><span class="ep-num">02</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/projects/{id}</span><span class="ep-desc">Get project</span><button class="copy-btn" data-path="/api/v1/projects/{id}">Copy</button></div>
            <div class="ep-row"><span class="ep-num">03</span><span class="badge POST">POST</span><span class="ep-path">/api/v1/projects</span><span class="ep-desc">Create project</span><button class="copy-btn" data-path="/api/v1/projects">Copy</button></div>
            <div class="ep-row"><span class="ep-num">04</span><span class="badge PUT">PUT</span><span class="ep-path">/api/v1/projects/{id}</span><span class="ep-desc">Update project</span><button class="copy-btn" data-path="/api/v1/projects/{id}">Copy</button></div>
            <div class="ep-row"><span class="ep-num">05</span><span class="badge DELETE">DELETE</span><span class="ep-path">/api/v1/projects/{id}</span><span class="ep-desc">Delete project</span><button class="copy-btn" data-path="/api/v1/projects/{id}">Copy</button></div>
        </div>
            <div class="section-label"><span>Bookings</span></div>
        <div class="endpoints">
            <div class="ep-row"><span class="ep-num">01</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/bookings</span><span class="ep-desc">List bookings</span><button class="copy-btn" data-path="/api/v1/bookings">Copy</button></div>
            <div class="ep-row"><span class="ep-num">02</span><span class="badge GET">GET</span><span class="ep-path">/api/v1/bookings/{id}</span><span class="ep-desc">Get booking</span><button class="copy-btn" data-path="/api/v1/bookings/{id}">Copy</button></div>
            <div class="ep-row"><span class="ep-num">03</span><span class="badge POST">POST</span><span class="ep-path">/api/v1/bookings</span><span class="ep-desc">Create booking</span><button class="copy-btn" data-path="/api/v1/bookings">Copy</button></div>
            <div class="ep-row"><span class="ep-num">04</span><span class="badge PUT">PUT</span><span class="ep-path">/api/v1/bookings/{id}</span><span class="ep-desc">Update booking</span><button class="copy-btn" data-path="/api/v1/bookings/{id}">Copy</button></div>
            <div class="ep-row"><span class="ep-num">05</span><span class="badge DELETE">DELETE</span><span class="ep-path">/api/v1/bookings/{id}</span><span class="ep-desc">Delete booking</span><button class="copy-btn" data-path="/api/v1/bookings/{id}">Copy</button></div>
        </div>

    </div>

    <footer>
        <span>UO API &copy; {{ date('Y') }}</span>
        <div class="footer-accent"></div>
        <span id="req-id"></span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
    <script>
        // ══════════════ THEME ══════════════
        const html = document.documentElement;
        const btn  = document.getElementById('themeBtn');
        if (localStorage.getItem('uo-theme') === 'light') html.classList.add('light');
        btn.addEventListener('click', () => {
            html.classList.toggle('light');
            localStorage.setItem('uo-theme', html.classList.contains('light') ? 'light' : 'dark');
        });

        // ══════════════ CLOCK + UPTIME ══════════════
        const clockEl  = document.getElementById('clock');
        const uptimeEl = document.getElementById('uptime');
        const startTs  = Date.now();
        const pad      = n => String(n).padStart(2,'0');

        function tick() {
            const now = new Date();
            clockEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            const s = Math.floor((Date.now() - startTs) / 1000);
            uptimeEl.textContent = `${pad(Math.floor(s/3600))}:${pad(Math.floor((s%3600)/60))}:${pad(s%60)}`;
        }
        tick(); setInterval(tick, 1000);

        // ══════════════ REQ ID ══════════════
        const ri = document.getElementById('req-id');
        if (ri) ri.textContent = 'req_' + Math.random().toString(36).slice(2,10).toUpperCase();

        // ══════════════ GSAP ENGINE ══════════════
        gsap.registerPlugin(ScrollTrigger);

        // ── reduced-motion guard ──
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduced) gsap.globalTimeline.timeScale(0);

        // ── 1. NAV reveal ──
        gsap.from('nav', {
            yPercent: -100,
            autoAlpha: 0,
            duration: .6,
            ease: 'expo.out',
            clearProps: 'all'
        });

        // ── 2. HERO timeline ──
        const heroTl = gsap.timeline({ defaults: { ease: 'expo.out' } });
        heroTl
            .from('.hero',      { y: 32, autoAlpha: 0, duration: .7 })
            .from('.hero-tag',  { x: -20, autoAlpha: 0, duration: .5 }, '<.15')
            .from('.hero h2',   { y: 20, autoAlpha: 0, duration: .6 }, '<.1')
            .from('.hero-sub',  { y: 12, autoAlpha: 0, duration: .5 }, '<.1')
            .from('.stat-cell', { y: 16, autoAlpha: 0, stagger: .1, duration: .5 }, '<.2');

        // ── 3. SECTION LABELS — ScrollTrigger reveal ──
        gsap.utils.toArray('.section-label').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' },
                x: -24, autoAlpha: 0, duration: .55, ease: 'power3.out'
            });
        });



        // ── 6. ENDPOINTS — stagger reveal ──
        gsap.utils.toArray('.endpoints').forEach(wrap => {
            gsap.from(wrap, {
                scrollTrigger: { trigger: wrap, start: 'top 85%', toggleActions: 'play none none none' },
                autoAlpha: 0, y: 20, duration: .5, ease: 'power2.out'
            });
            
            const rows = wrap.querySelectorAll('.ep-row');
            if (rows.length) {
                gsap.from(rows, {
                    scrollTrigger: { trigger: wrap, start: 'top 82%', toggleActions: 'play none none none' },
                    x: 40, autoAlpha: 0, stagger: .08, duration: .5, ease: 'power3.out',
                    clearProps: 'transform'
                });
            }
            
            // badges micro-scale
            const badges = wrap.querySelectorAll('.badge');
            if (badges.length) {
                gsap.from(badges, {
                    scrollTrigger: { trigger: wrap, start: 'top 82%', toggleActions: 'play none none none' },
                    scale: .7, autoAlpha: 0, stagger: .08, duration: .4,
                    ease: 'back.out(1.4)',
                    immediateRender: false,
                    clearProps: 'transform'
                });
            }
        });

        // ── 7. FOOTER fade ──
        gsap.from('footer', {
            scrollTrigger: { trigger: 'footer', start: 'top 95%', toggleActions: 'play none none none' },
            autoAlpha: 0, y: 12, duration: .6, ease: 'power2.out'
        });

        // ══════════════ MAGNETIC COPY BUTTONS ══════════════
        document.querySelectorAll('.copy-btn').forEach(b => {
            // magnetic hover
            b.addEventListener('mousemove', e => {
                const rect = b.getBoundingClientRect();
                const dx = e.clientX - (rect.left + rect.width  / 2);
                const dy = e.clientY - (rect.top  + rect.height / 2);
                gsap.to(b, { x: dx * .25, y: dy * .35, duration: .3, ease: 'power2.out' });
            });
            b.addEventListener('mouseleave', () => {
                gsap.to(b, { x: 0, y: 0, duration: .6, ease: 'elastic.out(1,.4)' });
            });
            // click: copy path + ripple
            b.addEventListener('click', () => {
                const path = b.dataset.path;
                navigator.clipboard?.writeText(path);
                gsap.timeline()
                    .to(b, { scale: .9, duration: .1, ease: 'power1.in' })
                    .to(b, { scale: 1,  duration: .55, ease: 'elastic.out(1,.4)' });
                b.textContent = '✓';
                b.classList.add('copied');
                setTimeout(() => { b.textContent = 'Copy'; b.classList.remove('copied'); }, 1400);
            });
        });

        // ══════════════ KONAMI CODE ══════════════
        const konamiSequence = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
        let konamiIndex = 0;
        let konamiTimer;

        window.addEventListener('keydown', (e) => {
            clearTimeout(konamiTimer);
            konamiTimer = setTimeout(() => { konamiIndex = 0; }, 1200);

            if (e.key === konamiSequence[konamiIndex] || e.key.toLowerCase() === konamiSequence[konamiIndex]) {
                konamiIndex++;
                if (konamiIndex === konamiSequence.length) {
                    konamiIndex = 0;
                    triggerTheVoid();
                }
            } else {
                konamiIndex = 0;
            }
        });

        function triggerTheVoid() {
            // Optional audio (coin or 1up)
            // new Audio('https://www.myinstants.com/media/sounds/1up.mp3').play();

            const tl = gsap.timeline({
                onComplete: () => window.location.href = '/the-void' // Redirección al terminar
            });

            // Glitch and vibration effect
            tl.to(document.body, {
                x: () => gsap.utils.random(-15, 15),
                y: () => gsap.utils.random(-15, 15),
                duration: 0.05,
                repeat: 30,
                yoyo: true,
                ease: 'none',
                onStart: () => {
                    document.body.style.filter = 'invert(1)';
                }
            })
            // Tunnel effect: scale to 5 and fade to enter the portal
            .to(document.body, {
                scale: 5,
                autoAlpha: 0,
                duration: 1.5,
                ease: 'power4.in'
            }, "-=0.3");
        }
        // ══════════════ EVIL LOGO EASTER EGG ══════════════
        const navLogo = document.querySelector('.nav-brand');
        const navCube = document.querySelector('.nav-brand-icon');
        let logoClicks = 0;
        let evilThemeActive = false;
        let isFinalAttackPhase = false;
        let currentMouseX = 0;
        let currentMouseY = 0;
        let fakeCursorContainer = null;

        // Track mouse globally
        window.addEventListener('mousemove', (e) => {
            currentMouseX = e.clientX;
            currentMouseY = e.clientY;
            if (fakeCursorContainer) {
                fakeCursorContainer.style.left = currentMouseX + 'px';
                fakeCursorContainer.style.top = currentMouseY + 'px';
            }
        });

        if (navLogo && navCube) {
            navLogo.style.cursor = 'pointer';
            navLogo.addEventListener('click', (e) => {
                e.preventDefault();
                
                if (isFinalAttackPhase) return; // Block clicks during final attack

                logoClicks++;

                // 1. Threshold 1 (0-9 clicks) - Glitch Effect (only on Cube)
                if (logoClicks < 10) {
                    gsap.to(navCube, {
                        x: () => gsap.utils.random(-10, 10),
                        y: () => gsap.utils.random(-10, 10),
                        scale: () => gsap.utils.random(0.85, 1.15),
                        skewX: () => gsap.utils.random(-25, 25),
                        filter: `hue-rotate(${gsap.utils.random(90, 270)}deg) invert(1)`,
                        duration: 0.04,
                        repeat: 5,
                        yoyo: true,
                        ease: 'none',
                        onComplete: () => {
                            gsap.set(navCube, { clearProps: 'all' });
                        }
                    });
                }
                
                // Evil Theme Activation at 5 clicks
                if (logoClicks === 5 && !evilThemeActive) {
                    evilThemeActive = true;
                    activateEvilTheme();
                }

                // 2. Threshold 2 (10 clicks) - Aggressive Attack
                if (logoClicks === 10) {
                    isFinalAttackPhase = true;
                    triggerLogoAttack();
                }
            });
        }

        function activateEvilTheme() {
            // Smoothly transition all main CSS theme properties to "evil" schema
            const evilColors = {
                '--bg': '#030000',
                '--surface': '#080000',
                '--bg2': '#0f0000',
                '--border': '#2a0000',
                '--nav-bg': 'rgba(3,0,0,0.96)',
                '--text': '#ff1a1a',
                '--muted': '#aa0000',
                '--principal': '#ff0000',
                '--cta1': '#bb0000',
                '--cta2': '#880000',
                '--estado1': '#4a0000',
                '--shadow': '#ff0000'
            };

            gsap.to(document.documentElement, {
                ...evilColors,
                duration: 1,
                ease: 'power2.inOut',
                onStart: () => {
                    document.documentElement.classList.remove('light');
                    gsap.set(document.body, { boxShadow: 'inset 0 0 200px rgba(255,0,0,0.15)' });
                    // Prevent transforming body to avoid breaking position: fixed!
                    // Tremor individual sections instead:
                    gsap.to('nav, .container, footer', {
                        x: () => gsap.utils.random(-1.5, 1.5),
                        y: () => gsap.utils.random(-1.5, 1.5),
                        duration: 0.08,
                        repeat: -1,
                        yoyo: true,
                        ease: 'none'
                    });
                }
            });
        }

        // --- Logo Attack Functions --- //
        function triggerLogoAttack() {
            // STEP A: Charge up targetting the Cube
            const rect = navCube.getBoundingClientRect();
            // Start beam from center of logo box
            const startX = rect.left + rect.width / 2;
            const startY = rect.top + rect.height / 2;

            const chargeTl = gsap.timeline();
            chargeTl.to(navCube, {
                color: '#000000',
                textShadow: '0 0 20px #ff0000',
                // Add a bright red blur
                filter: 'drop-shadow(0 0 10px red) drop-shadow(0 0 20px red)',
                duration: 1.5,
                ease: 'power2.in',
                onStart: () => {
                    // Get angry early
                    navCube.textContent = '◣_◢';
                    navCube.style.fontSize = '12px';
                    navCube.style.color = '#000000';
                    navCube.style.letterSpacing = '0px';

                    // Intense vibration
                    gsap.to(navCube, { x: 3, y: -2, duration: 0.04, repeat: 36, yoyo: true, ease: 'none' });
                }
            })
            // STEP B: The Launch
            .to(navCube, {
                scale: 1.5,
                duration: 0.1,
                ease: 'power4.in',
                onStart: () => {
                    // Effort / Straining face while shooting
                    navCube.textContent = '◣o◢';
                    navCube.style.fontSize = '14px';
                },
                onComplete: () => {
                    fireBeamAndBreakCursor(startX, startY);
                    gsap.to(navCube, { scale: 1, duration: 0.8, ease: 'bounce.out' });
                }
            });
        }

        function fireBeamAndBreakCursor(startX, startY) {
            // Beam particle
            const beam = document.createElement('div');
            beam.style.position = 'fixed';
            beam.style.left = startX + 'px';
            beam.style.top = startY + 'px';
            beam.style.width = '10px';
            beam.style.height = '10px';
            beam.style.borderRadius = '50%';
            beam.style.backgroundColor = '#fff';
            beam.style.boxShadow = '0 0 20px 5px #ff0000, 0 0 40px 10px #ff0000';
            beam.style.zIndex = 9999;
            beam.style.pointerEvents = 'none';
            document.body.appendChild(beam);

            gsap.to(beam, {
                x: currentMouseX - startX,
                y: currentMouseY - startY,
                duration: 0.15, // hyperfast beam
                ease: 'power1.in',
                onComplete: () => {
                    beam.remove();
                    executeCursorDestruction();
                }
            });
        }

        function executeCursorDestruction() {
            // STEP A: Hide real cursor
            const style = document.createElement('style');
            style.innerHTML = `* { cursor: none !important; }`;
            document.head.appendChild(style);

            // STEP B: Create fake cursor container
            fakeCursorContainer = document.createElement('div');
            fakeCursorContainer.style.position = 'fixed';
            fakeCursorContainer.style.pointerEvents = 'none';
            fakeCursorContainer.style.zIndex = 10000;
            // Native mac-like arrow
            const normalCursor = `<svg width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="1.5"><path d="M4 2 L16 13 L12 14 L15 20 L12 21 L9 15 L4 19 Z"/></svg>`;
            // Fragmented cursor
            const brokenCursor = `<svg width="24" height="24" viewBox="0 0 24 24" fill="#ff5555" stroke="#cc0000" stroke-width="1.5" stroke-dasharray="2,1"><path d="M4 2 L12 9 M16 13 L12 14 L15 20 M12 21 L9 15 L4 19 Z"/></svg>`;
            
            fakeCursorContainer.innerHTML = normalCursor;
            fakeCursorContainer.style.left = currentMouseX + 'px';
            fakeCursorContainer.style.top = currentMouseY + 'px';
            // Offset logic so tip is at mouse X/Y
            fakeCursorContainer.style.transformOrigin = '0 0';
            document.body.appendChild(fakeCursorContainer);

            createSparks(currentMouseX, currentMouseY);

            // STEP C: GSAP complex timeline for destruction
            const tl = gsap.timeline();
            tl.to(fakeCursorContainer, {
                scale: 1.8,
                rotation: () => gsap.utils.random(45, 90),
                duration: 0.1,
                ease: 'power4.out',
                onComplete: () => {
                    // Break it
                    fakeCursorContainer.innerHTML = brokenCursor;
                    createSparks(currentMouseX, currentMouseY); // more sparks
                    
                    // Transformation to angry face
                    navCube.textContent = '◣_◢';
                    navCube.style.fontSize = '12px';
                    navCube.style.color = '#000000';
                    navCube.style.letterSpacing = '0px';
                }
            })
            // Violent shake (absolute randoms instead of += to avoid drift)
            .to(fakeCursorContainer, {
                rotation: () => gsap.utils.random(-90, 90),
                x: () => gsap.utils.random(-40, 40),
                y: () => gsap.utils.random(-40, 40),
                duration: 0.05,
                repeat: 8,
                yoyo: true,
                ease: 'none'
            })
            // STEP D: Final state
            .to(fakeCursorContainer, {
                scale: 1.1,
                rotation: -25,
                x: 0,
                y: 0, // Resolve safely back to cursor base
                filter: 'invert(1) hue-rotate(90deg)',
                duration: 0.6,
                ease: 'bounce.out',
                onComplete: () => {
                    // Continuous jitter (absolute random near 0 to avoid drifting off screen completely)
                    gsap.to(fakeCursorContainer, {
                        x: () => gsap.utils.random(-2, 2),
                        y: () => gsap.utils.random(-2, 2),
                        duration: 0.08,
                        repeat: -1,
                        yoyo: true,
                        ease: 'none'
                    });
                }
            });
        }

        function createSparks(cx, cy) {
            for (let i = 0; i < 15; i++) {
                const spark = document.createElement('div');
                spark.style.position = 'fixed';
                spark.style.left = cx + 'px';
                spark.style.top = cy + 'px';
                spark.style.width = '4px';
                spark.style.height = '4px';
                spark.style.backgroundColor = i % 2 === 0 ? '#ffaa00' : '#ff0000';
                spark.style.borderRadius = '50%';
                spark.style.pointerEvents = 'none';
                spark.style.zIndex = 9999;
                document.body.appendChild(spark);

                gsap.to(spark, {
                    x: () => gsap.utils.random(-120, 120),
                    y: () => gsap.utils.random(-120, 120),
                    opacity: 0,
                    scale: () => gsap.utils.random(0.5, 2.5),
                    duration: () => gsap.utils.random(0.4, 1),
                    ease: 'power3.out',
                    onComplete: () => spark.remove()
                });
            }
        }
    </script>
</body>
</html>