<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UO_API :: TERMINAL v2.1</title>
    <meta name="description" content="UO API Gateway — Systems Online">

    <!-- Prevent FOUC for terminal theme -->
    <script>
        const _t = localStorage.getItem('uo-term-scheme') ?? 'green';
        document.documentElement.setAttribute('data-scheme', _t);
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon_api.ico') }}" type="image/x-icon">

    <style>
        /* ═══════════════════════════════════════════════════
           TERMINAL COLOUR SCHEMES
           data-scheme="green" | "cyan" | "amber"
        ═══════════════════════════════════════════════════ */
        :root {
            --bg:        #020c02;
            --bg2:       #060f06;
            --panel:     #081008;
            --neon:      #00ff41;
            --neon-dim:  #00c832;
            --neon-dark: #004d14;
            --neon-glow: rgba(0,255,65,0.6);
            --text:      #b0ffb8;
            --text-dim:  #4d9954;
            --font:      'Share Tech Mono', monospace;
            --font-hd:   'Orbitron', monospace;
            --cursor-col:#00ff41;
        }
        [data-scheme="cyan"] {
            --bg:        #020c12;
            --bg2:       #060f18;
            --panel:     #081018;
            --neon:      #00e5ff;
            --neon-dim:  #00b8cc;
            --neon-dark: #003d4d;
            --neon-glow: rgba(0,229,255,0.6);
            --text:      #b0f4ff;
            --text-dim:  #4d8a99;
            --cursor-col:#00e5ff;
        }
        [data-scheme="amber"] {
            --bg:        #0e0800;
            --bg2:       #120c00;
            --panel:     #160e00;
            --neon:      #ff9900;
            --neon-dim:  #cc7a00;
            --neon-dark: #3d2400;
            --neon-glow: rgba(255,153,0,0.6);
            --text:      #ffe0b0;
            --text-dim:  #996633;
            --cursor-col:#ff9900;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; width: 100%; min-height: 100vh; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            font-size: 14px;
            line-height: 1.6;
        }

        /* ═══════════════ CRT SCANLINES OVERLAY ═══════════════ */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0,0,0,0.15) 2px,
                rgba(0,0,0,0.15) 4px
            );
            animation: scanlines 8s linear infinite;
        }
        @keyframes scanlines {
            0%   { background-position: 0 0; }
            100% { background-position: 0 100vh; }
        }

        /* ═══════════════ CRT PHOSPHOR BLOOM ═══════════════ */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 9998;
            pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 40%, var(--bg) 100%);
        }

        /* ═══════════════ MATRIX RAIN CANVAS ═══════════════ */
        #matrix-canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            opacity: 0.06;
            pointer-events: none;
        }

        /* ═══════════════ GLOBAL NEON TYPOGRAPHY ═══════════════ */
        .neon {
            color: var(--neon);
            text-shadow: 0 0 7px var(--neon), 0 0 21px var(--neon-glow);
        }
        .neon-heading {
            font-family: var(--font-hd);
            color: var(--neon);
            text-shadow: 0 0 10px var(--neon), 0 0 30px var(--neon-glow), 0 0 60px var(--neon-glow);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .dim { color: var(--text-dim); }

        /* ═══════════════ GLITCH TEXT EFFECT ═══════════════ */
        .glitch {
            position: relative;
            display: inline-block;
        }
        .glitch::before,
        .glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
        }
        .glitch::before {
            color: #ff00ff;
            text-shadow: -2px 0 #ff00ff;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-1 4s infinite linear alternate-reverse;
        }
        .glitch::after {
            color: #00ffff;
            text-shadow: 2px 0 #00ffff;
            clip: rect(24px, 450px, 30px, 0);
            animation: glitch-2 4s infinite linear alternate;
        }
        @keyframes glitch-1 {
            0%   { clip: rect(42px, 9999px, 44px, 0); transform: skew(0.5deg); }
            5%   { clip: rect(12px, 9999px, 59px, 0); transform: skew(0.8deg); }
            10%  { clip: rect(48px, 9999px, 29px, 0); transform: skew(-0.2deg); }
            15%  { clip: rect(82px, 9999px, 88px, 0); transform: skew(0.3deg); }
            20%  { clip: rect(64px, 9999px, 68px, 0); transform: skew(0.6deg); }
            25%  { clip: rect(2px,  9999px, 5px,  0); transform: skew(-0.1deg); }
            30%  { clip: rect(0px,  9999px, 0px,  0); transform: skew(0deg); }
            100% { clip: rect(0px,  9999px, 0px,  0); transform: skew(0deg); }
        }
        @keyframes glitch-2 {
            0%   { clip: rect(65px, 9999px, 100px, 0); transform: skew(-0.5deg); }
            5%   { clip: rect(19px, 9999px, 24px,  0); transform: skew(0.3deg); }
            10%  { clip: rect(55px, 9999px, 60px,  0); transform: skew(-0.6deg); }
            15%  { clip: rect(37px, 9999px, 40px,  0); transform: skew(0.1deg); }
            20%  { clip: rect(91px, 9999px, 95px,  0); transform: skew(-0.3deg); }
            25%  { clip: rect(0px,  9999px, 0px,   0); transform: skew(0deg); }
            100% { clip: rect(0px,  9999px, 0px,   0); transform: skew(0deg); }
        }

        /* ═══════════════ BLINKING CURSOR ═══════════════ */
        .cursor::after {
            content: '█';
            color: var(--cursor-col);
            animation: blink 1s step-start infinite;
            text-shadow: 0 0 8px var(--neon-glow);
        }
        @keyframes blink { 50% { opacity: 0; } }

        /* ═══════════════ TOP BAR / STATUS ═══════════════ */
        .top-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--bg2);
            border-bottom: 1px solid var(--neon-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0;
            overflow: hidden;
        }
        .top-bar::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: var(--neon);
            box-shadow: 0 0 12px var(--neon-glow);
        }

        .tb-logo {
            padding: 10px 24px;
            border-right: 1px solid var(--neon-dark);
            font-family: var(--font-hd);
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 0.15em;
            white-space: nowrap;
        }

        .tb-ticker {
            flex: 1;
            overflow: hidden;
            padding: 0 20px;
            font-size: 12px;
            color: var(--text-dim);
        }
        .tb-ticker-inner {
            display: flex;
            gap: 40px;
            white-space: nowrap;
            will-change: transform;
        }

        .tb-right {
            display: flex;
            align-items: stretch;
            border-left: 1px solid var(--neon-dark);
        }
        .tb-stat {
            padding: 10px 20px;
            border-right: 1px solid var(--neon-dark);
            font-size: 12px;
            color: var(--text-dim);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }
        .tb-stat strong { color: var(--neon); font-size: 13px; }

        .scheme-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
        }
        .scheme-dot {
            width: 14px; height: 14px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: transform 0.2s, border-color 0.2s;
        }
        .scheme-dot:hover { transform: scale(1.3); }
        .scheme-dot.active { border-color: #fff; }
        .scheme-dot[data-s="green"]  { background: #00ff41; box-shadow: 0 0 8px #00ff4188; }
        .scheme-dot[data-s="cyan"]   { background: #00e5ff; box-shadow: 0 0 8px #00e5ff88; }
        .scheme-dot[data-s="amber"]  { background: #ff9900; box-shadow: 0 0 8px #ff990088; }

        /* ═══════════════ MAIN LAYOUT ═══════════════ */
        .terminal-wrap {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px 80px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* ═══════════════ SIDEBAR / SIDENAV ═══════════════ */
        .sidebar {
            position: sticky;
            top: 70px;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--neon-dark);
            box-shadow: 0 0 20px rgba(0,0,0,0.6), inset 0 0 30px rgba(0,0,0,0.4);
        }
        .panel-head {
            padding: 10px 16px;
            border-bottom: 1px solid var(--neon-dark);
            font-size: 11px;
            color: var(--text-dim);
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .panel-head::before {
            content: '';
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--neon);
            box-shadow: 0 0 8px var(--neon-glow);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        .sidebar-nav {
            padding: 12px 0;
        }
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            font-size: 13px;
            color: var(--text-dim);
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            border-left: 2px solid transparent;
        }
        .sidebar-nav-item:hover,
        .sidebar-nav-item.active {
            background: rgba(0,255,65,0.05);
            color: var(--neon);
            border-left-color: var(--neon);
            text-shadow: 0 0 8px var(--neon-glow);
        }
        .sidebar-nav-item .icon { width: 16px; text-align: center; opacity: 0.7; }

        .sys-info {
            padding: 16px;
            border-top: 1px solid var(--neon-dark);
            font-size: 12px;
        }
        .sys-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .sys-row:last-child { border-bottom: none; }
        .sys-key { color: var(--text-dim); }
        .sys-val { color: var(--neon); }

        /* ═══════════════ MAIN CONTENT ═══════════════ */
        .main-content { min-width: 0; }

        /* ═══════════════ HERO TERMINAL WINDOW ═══════════════ */
        .term-window {
            background: #000;
            border: 1px solid var(--neon-dark);
            border-radius: 6px;
            margin-bottom: 28px;
            overflow: hidden;
            box-shadow: 0 0 40px rgba(0,0,0,0.8), 0 0 1px var(--neon);
        }
        .term-titlebar {
            background: var(--panel);
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--neon-dark);
        }
        .ttb-dot {
            width: 12px; height: 12px;
            border-radius: 50%;
        }
        .ttb-red    { background: #ff5f57; }
        .ttb-yellow { background: #febc2e; }
        .ttb-green  { background: #28c840; }
        .ttb-title {
            flex: 1;
            text-align: center;
            font-size: 12px;
            color: var(--text-dim);
            letter-spacing: 0.15em;
        }

        .term-body {
            padding: 24px;
            font-size: 13px;
            line-height: 1.8;
        }

        .term-line {
            display: flex;
            gap: 10px;
            color: var(--text-dim);
            margin-bottom: 4px;
        }
        .term-line .prompt { color: var(--neon); user-select: none; flex-shrink: 0; }
        .term-line .cmd    { color: var(--text); }
        .term-line.output .cmd  { color: var(--text-dim); padding-left: 0; }

        .hero-ascii {
            color: var(--neon);
            font-size: 11px;
            line-height: 1.2;
            text-shadow: 0 0 6px var(--neon-glow);
            margin: 16px 0;
            letter-spacing: 0.05em;
        }

        .hero-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 20px;
        }
        .h-stat {
            background: var(--panel);
            border: 1px solid var(--neon-dark);
            padding: 12px;
            text-align: center;
        }
        .h-stat-label {
            font-size: 10px;
            letter-spacing: 0.15em;
            color: var(--text-dim);
            text-transform: uppercase;
            display: block;
            margin-bottom: 6px;
        }
        .h-stat-value {
            font-family: var(--font-hd);
            font-size: 18px;
            color: var(--neon);
            text-shadow: 0 0 10px var(--neon-glow);
        }

        /* ═══════════════ SECTION HEADERS ═══════════════ */
        .sec-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            font-family: var(--font-hd);
            font-size: 13px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--neon);
        }
        .sec-header::before {
            content: '//';
            color: var(--text-dim);
            font-family: var(--font);
        }
        .sec-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, var(--neon-dark), transparent);
        }

        /* ═══════════════ DEBUG PANEL ═══════════════ */
        .debug-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(255,60,60,0.08);
            border: 1px solid rgba(255,60,60,0.4);
            color: #ff6060;
            font-size: 13px;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(255,0,0,0.1);
        }
        .debug-alert .blink-warn {
            animation: blink 0.7s step-start infinite;
            color: #ff3c3c;
            font-size: 16px;
        }
        .debug-table {
            background: var(--panel);
            border: 1px solid var(--neon-dark);
            margin-bottom: 28px;
            overflow: hidden;
        }
        .d-tr {
            display: grid;
            grid-template-columns: 180px 1fr;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .d-tr:last-child { border-bottom: none; }
        .d-key, .d-val {
            padding: 10px 16px;
            font-size: 13px;
        }
        .d-key {
            font-size: 11px;
            color: var(--text-dim);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-right: 1px solid var(--neon-dark);
            background: rgba(0,0,0,0.3);
        }
        .d-val {
            color: var(--neon);
        }

        /* ═══════════════ ENDPOINTS ═══════════════ */
        .ep-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 40px;
        }

        .ep-card {
            background: var(--panel);
            border: 1px solid var(--neon-dark);
            display: grid;
            grid-template-columns: 100px 1fr auto;
            align-items: stretch;
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .ep-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 0;
            background: var(--neon);
            opacity: 0.06;
            transition: width 0.3s ease;
            pointer-events: none;
        }
        .ep-card:hover::before { width: 100%; }
        .ep-card:hover {
            border-color: var(--neon-dim);
            box-shadow: 0 0 15px rgba(0,255,65,0.1), inset 0 0 5px rgba(0,255,65,0.03);
        }

        .ep-method-tag {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-hd);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            border-right: 1px solid var(--neon-dark);
            padding: 14px 10px;
        }
        .ep-card.get    .ep-method-tag { color: #00ff41; background: rgba(0,255,65,0.05);  }
        .ep-card.post   .ep-method-tag { color: #00cfff; background: rgba(0,207,255,0.05); }
        .ep-card.put    .ep-method-tag { color: #ffb700; background: rgba(255,183,0,0.05); }
        .ep-card.delete .ep-method-tag { color: #ff4040; background: rgba(255,64,64,0.05); }

        .ep-path-label {
            padding: 14px 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text);
        }
        .ep-path-label span.ep-prefix { color: var(--text-dim); }
        .ep-path-label span.ep-param  { color: var(--neon-dim); font-style: italic; }

        .ep-actions {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .ep-copy-btn {
            background: transparent;
            border: none;
            border-left: 1px solid var(--neon-dark);
            color: var(--text-dim);
            padding: 0 16px;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            letter-spacing: 0.08em;
            height: 100%;
            transition: background 0.15s, color 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .ep-copy-btn:hover {
            background: rgba(0,255,65,0.1);
            color: var(--neon);
        }

        /* ═══════════════ RESPONSE PREVIEW ═══════════════ */
        .resp-preview {
            background: #000;
            border: 1px solid var(--neon-dark);
            padding: 0;
            margin-bottom: 28px;
            overflow: hidden;
        }
        .resp-head {
            padding: 8px 16px;
            border-bottom: 1px solid var(--neon-dark);
            font-size: 11px;
            color: var(--text-dim);
            letter-spacing: 0.12em;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .resp-status { color: #00ff41; }
        .resp-body {
            padding: 20px;
            font-size: 12px;
            line-height: 1.7;
        }
        .json-key    { color: #79b8ff; }
        .json-str    { color: #9ecbff; }
        .json-num    { color: var(--neon); }
        .json-bool   { color: #ff9900; }
        .json-null   { color: #6b6b6b; }
        .json-punct  { color: var(--text-dim); }

        /* ═══════════════ FOOTER ═══════════════ */
        .term-footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid var(--neon-dark);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: var(--text-dim);
            background: var(--bg2);
            letter-spacing: 0.08em;
        }
        .term-footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: var(--neon);
            box-shadow: 0 0 10px var(--neon-glow);
        }

        /* ═══════════════ MOBILE RESPONSIVE ═══════════════ */
        @media (max-width: 860px) {
            .terminal-wrap {
                grid-template-columns: 1fr;
            }
            .sidebar { position: static; }
            .hero-stat-grid { grid-template-columns: repeat(3,1fr); }
            .ep-card { grid-template-columns: 70px 1fr auto; }
        }
        @media (max-width: 600px) {
            .tb-stat  { display: none; }
            .hero-ascii { display: none; }
            .ep-card { grid-template-columns: 60px 1fr; }
            .ep-actions { display: none; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
</head>
<body>

    <!-- ═══ MATRIX RAIN CANVAS ═══ -->
    <canvas id="matrix-canvas"></canvas>

    <!-- ═══════════ TOP STATUS BAR ═══════════ -->
    <div class="top-bar">
        <div class="tb-logo neon-heading" style="font-size:14px;">
            <span class="glitch" data-text="UO_API">UO_API</span>
        </div>

        <div class="tb-ticker">
            <div class="tb-ticker-inner" id="ticker-track">
                <span>[ SYS:ONLINE ] GATEWAY ACTIVE :: ALL NODES RESPONSIVE :: LATENCY &lt;2ms :: ENV:{{ app()->environment() }} :: UPTIME OK :: TLS 1.3 :: CORS ENABLED :: RATE LIMIT 1000/min ::</span>
                <span>[ SYS:ONLINE ] GATEWAY ACTIVE :: ALL NODES RESPONSIVE :: LATENCY &lt;2ms :: ENV:{{ app()->environment() }} :: UPTIME OK :: TLS 1.3 :: CORS ENABLED :: RATE LIMIT 1000/min ::</span>
            </div>
        </div>

        <div class="tb-right">
            <div class="tb-stat">
                <span>CLOCK</span>
                <strong id="clock">--:--:--</strong>
            </div>
            <div class="tb-stat">
                <span>UPTIME</span>
                <strong id="uptime">00:00</strong>
            </div>
            <div class="tb-stat">
                <span>STATUS</span>
                <strong class="neon">ONLINE</strong>
            </div>
            <div class="scheme-switcher">
                <div class="scheme-dot" data-s="green"  title="Matrix Green"></div>
                <div class="scheme-dot" data-s="cyan"   title="Cyber Cyan"></div>
                <div class="scheme-dot" data-s="amber"  title="Retro Amber"></div>
            </div>
        </div>
    </div>

    <!-- ═══════════ TERMINAL LAYOUT ═══════════ -->
    <div class="terminal-wrap">

        <!-- ── SIDEBAR ── -->
        <aside class="sidebar" id="sidebar">
            <div class="panel" id="nav-panel">
                <div class="panel-head">NAVIGATION</div>
                <nav class="sidebar-nav">
                    <div class="sidebar-nav-item active" data-target="hero">
                        <span class="icon">></span> SYSTEM STATUS
                    </div>
                    @if(config('app.debug'))
                    <div class="sidebar-nav-item" data-target="debug-section">
                        <span class="icon">!</span> DEBUG INFO
                    </div>
                    @endif
                    <div class="sidebar-nav-item" data-target="ep-section">
                        <span class="icon">#</span> ENDPOINTS
                    </div>
                    <div class="sidebar-nav-item" data-target="resp-section">
                        <span class="icon">~</span> RESPONSE SCHEMA
                    </div>
                </nav>

                <div class="sys-info">
                    <div class="sys-row"><span class="sys-key">FRAMEWORK</span><span class="sys-val">Laravel {{ app()->version() }}</span></div>
                    <div class="sys-row"><span class="sys-key">RUNTIME</span><span class="sys-val">PHP {{ phpversion() }}</span></div>
                    <div class="sys-row"><span class="sys-key">ENV</span><span class="sys-val" style="text-transform:uppercase;">{{ app()->environment() }}</span></div>
                    <div class="sys-row"><span class="sys-key">REQ-ID</span><span class="sys-val" id="req-id" style="font-size:11px;">──────</span></div>
                </div>
            </div>
        </aside>

        <!-- ── MAIN CONTENT ── -->
        <main class="main-content">

            {{-- ── HERO TERMINAL WINDOW ── --}}
            <div class="term-window" id="hero">
                <div class="term-titlebar">
                    <div class="ttb-dot ttb-red"></div>
                    <div class="ttb-dot ttb-yellow"></div>
                    <div class="ttb-dot ttb-green"></div>
                    <span class="ttb-title">root@uo-api:~  [SSH] [256 bit]</span>
                </div>
                <div class="term-body">
                    <div class="hero-ascii" id="ascii-art">
██╗   ██╗ ██████╗      █████╗ ██████╗ ██╗
██║   ██║██╔═══██╗    ██╔══██╗██╔══██╗██║
██║   ██║██║   ██║    ███████║██████╔╝██║
██║   ██║██║   ██║    ██╔══██║██╔═══╝ ██║
╚██████╔╝╚██████╔╝    ██║  ██║██║     ██║
 ╚═════╝  ╚═════╝     ╚═╝  ╚═╝╚═╝     ╚═╝</div>

                    <div class="term-line">
                        <span class="prompt">root@uo-api:~$</span>
                        <span class="cmd" id="typed-cmd"></span><span class="cursor" id="cursor-span"></span>
                    </div>
                    <div class="term-line output" id="boot-output" style="display:none;">
                        <span class="cmd">> Gateway initialised. All services responding. Auth: JWT/Bearer. Version: v1.0</span>
                    </div>
                    <div class="term-line output" id="boot-output2" style="display:none;">
                        <span class="cmd dim">> Type <span class="neon">help</span> to list available commands. Type <span class="neon">--version</span> for build info.</span>
                    </div>

                    <div class="hero-stat-grid">
                        <div class="h-stat ep-anim">
                            <span class="h-stat-label">Environment</span>
                            <span class="h-stat-value" style="font-size:14px; text-transform:uppercase;">{{ app()->environment() }}</span>
                        </div>
                        <div class="h-stat ep-anim">
                            <span class="h-stat-label">Session Uptime</span>
                            <span class="h-stat-value" id="uptime2" style="font-size:14px;">00:00</span>
                        </div>
                        <div class="h-stat ep-anim">
                            <span class="h-stat-label">Health</span>
                            <span class="h-stat-value" style="font-size:14px; color:#00ff41;">NOMINAL</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── DEBUG SECTION ── --}}
            @if(config('app.debug'))
            <div id="debug-section">
                <div class="debug-alert ep-anim">
                    <span class="blink-warn">⚠</span>
                    <span>DEBUG MODE ACTIVE — Sensitive server data exposed. Disable in production.</span>
                </div>

                <div class="sec-header ep-anim">Server Diagnostics</div>
                <div class="debug-table ep-anim">
                    <div class="d-tr"><div class="d-key">Framework</div><div class="d-val">Laravel {{ app()->version() }}</div></div>
                    <div class="d-tr"><div class="d-key">App Version</div><div class="d-val">v1.0{{ config('app.version') }}</div></div>
                    <div class="d-tr"><div class="d-key">PHP Runtime</div><div class="d-val">{{ phpversion() }}</div></div>
                    <div class="d-tr"><div class="d-key">Database Driver</div><div class="d-val" style="color:#ffb700;">{{ config('database.default') }}</div></div>
                    <div class="d-tr"><div class="d-key">DB Host</div><div class="d-val">{{ config('database.connections.mysql.host') }}</div></div>
                    <div class="d-tr"><div class="d-key">DB User</div><div class="d-val">{{ config('database.connections.mysql.username') }}</div></div>
                </div>
            </div>
            @endif

            {{-- ── ENDPOINTS ── --}}
            <div id="ep-section">
                <div class="sec-header ep-anim">Available Endpoints</div>
                <div class="ep-list">

                    <div class="ep-card get ep-anim">
                        <div class="ep-method-tag">GET</div>
                        <div class="ep-path-label"><span class="ep-prefix">/api/v1</span>/status</div>
                        <div class="ep-actions">
                            <button class="ep-copy-btn" data-path="/api/v1/status"><span>⎘</span> COPY</button>
                        </div>
                    </div>

                    <div class="ep-card get ep-anim">
                        <div class="ep-method-tag">GET</div>
                        <div class="ep-path-label"><span class="ep-prefix">/api/v1</span>/users</div>
                        <div class="ep-actions">
                            <button class="ep-copy-btn" data-path="/api/v1/users"><span>⎘</span> COPY</button>
                        </div>
                    </div>

                    <div class="ep-card get ep-anim">
                        <div class="ep-method-tag">GET</div>
                        <div class="ep-path-label"><span class="ep-prefix">/api/v1</span>/users/<span class="ep-param">{id}</span></div>
                        <div class="ep-actions">
                            <button class="ep-copy-btn" data-path="/api/v1/users/{id}"><span>⎘</span> COPY</button>
                        </div>
                    </div>

                    <div class="ep-card post ep-anim">
                        <div class="ep-method-tag">POST</div>
                        <div class="ep-path-label"><span class="ep-prefix">/api/v1</span>/users</div>
                        <div class="ep-actions">
                            <button class="ep-copy-btn" data-path="/api/v1/users"><span>⎘</span> COPY</button>
                        </div>
                    </div>

                    <div class="ep-card put ep-anim">
                        <div class="ep-method-tag">PUT</div>
                        <div class="ep-path-label"><span class="ep-prefix">/api/v1</span>/users/<span class="ep-param">{id}</span></div>
                        <div class="ep-actions">
                            <button class="ep-copy-btn" data-path="/api/v1/users/{id}"><span>⎘</span> COPY</button>
                        </div>
                    </div>

                    <div class="ep-card delete ep-anim">
                        <div class="ep-method-tag">DEL</div>
                        <div class="ep-path-label"><span class="ep-prefix">/api/v1</span>/users/<span class="ep-param">{id}</span></div>
                        <div class="ep-actions">
                            <button class="ep-copy-btn" data-path="/api/v1/users/{id}"><span>⎘</span> COPY</button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── RESPONSE SCHEMA ── --}}
            <div id="resp-section">
                <div class="sec-header ep-anim">Sample Response</div>
                <div class="resp-preview ep-anim">
                    <div class="resp-head">
                        <span>GET /api/v1/status → HTTP/1.1</span>
                        <span class="resp-status">200 OK ✓</span>
                    </div>
                    <div class="resp-body">
<span class="json-punct">{</span>
  <span class="json-key">"status"</span><span class="json-punct">:</span> <span class="json-str">"online"</span><span class="json-punct">,</span>
  <span class="json-key">"version"</span><span class="json-punct">:</span> <span class="json-str">"1.0"</span><span class="json-punct">,</span>
  <span class="json-key">"timestamp"</span><span class="json-punct">:</span> <span class="json-str" id="ts-val">"2024-01-01T00:00:00Z"</span><span class="json-punct">,</span>
  <span class="json-key">"uptime_seconds"</span><span class="json-punct">:</span> <span class="json-num" id="ts-up">0</span><span class="json-punct">,</span>
  <span class="json-key">"environment"</span><span class="json-punct">:</span> <span class="json-str">"{{ app()->environment() }}"</span><span class="json-punct">,</span>
  <span class="json-key">"auth_required"</span><span class="json-punct">:</span> <span class="json-bool">true</span><span class="json-punct">,</span>
  <span class="json-key">"rate_limit"</span><span class="json-punct">:</span> <span class="json-num">1000</span>
<span class="json-punct">}</span>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- ═══════════ FOOTER ═══════════ -->
    <footer class="term-footer">
        <span>UO_API v1.0 &copy; {{ date('Y') }} — All systems operational</span>
        <span id="req-id-foot" class="neon" style="font-size:12px;">──────────</span>
        <span>LATENCY: <span id="latency" class="neon">&#60;1ms</span></span>
    </footer>

    <!-- ═══════════════════════════════════════
         SCRIPTS: MATRIX + GSAP + INTERACTIONS
    ════════════════════════════════════════ -->
    <script>
        gsap.registerPlugin(ScrollTrigger);
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ══ 1. MATRIX RAIN ══ */
        (function () {
            const canvas = document.getElementById('matrix-canvas');
            const ctx = canvas.getContext('2d');
            let W, H, cols, drops;
            const CHARS = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン0123456789ABCDEF{}[];:><=';

            function resize() {
                W = canvas.width  = window.innerWidth;
                H = canvas.height = window.innerHeight;
                cols  = Math.floor(W / 16);
                drops = new Array(cols).fill(1);
            }
            resize();
            window.addEventListener('resize', resize);

            function getSchemeColor() {
                const s = document.documentElement.getAttribute('data-scheme') ?? 'green';
                if (s === 'cyan')  return 'rgba(0,229,255,';
                if (s === 'amber') return 'rgba(255,153,0,';
                return 'rgba(0,255,65,';
            }

            function draw() {
                ctx.fillStyle = 'rgba(0,0,0,0.05)';
                ctx.fillRect(0, 0, W, H);
                ctx.font = '14px "Share Tech Mono", monospace';
                const col = getSchemeColor();
                drops.forEach((y, i) => {
                    ctx.fillStyle = col + '1)';
                    ctx.fillText(CHARS[Math.floor(Math.random() * CHARS.length)], i * 16, y * 16);
                    ctx.fillStyle = col + '0.05)';
                    ctx.fillText(CHARS[Math.floor(Math.random() * CHARS.length)], i * 16, (y-1) * 16);
                    if (y * 16 > H && Math.random() > 0.975) drops[i] = 0;
                    drops[i]++;
                });
            }
            if (!rm) setInterval(draw, 55);
        })();

        /* ══ 2. TICKER ══ */
        const tickerTrack = document.getElementById('ticker-track');
        gsap.to(tickerTrack, {
            x: '-50%',
            duration: 20,
            repeat: -1,
            ease: 'none'
        });

        /* ══ 3. TYPING ANIMATION ══ */
        const CMD = 'api:status --verbose --json';
        const cmdEl   = document.getElementById('typed-cmd');
        const cursorEl = document.getElementById('cursor-span');

        const typeTimeline = gsap.timeline({ delay: 0.5 });
        typeTimeline.call(() => {}, [], 0);

        let typed = '';
        CMD.split('').forEach((char, i) => {
            typeTimeline.call(() => {
                typed += char;
                cmdEl.textContent = typed;
            }, [], i * 0.06);
        });

        typeTimeline
            .call(() => { cursorEl.classList.remove('cursor'); }, [], '+=0.2')
            .call(() => {
                document.getElementById('boot-output').style.display  = 'flex';
                document.getElementById('boot-output2').style.display = 'flex';
                gsap.from('#boot-output',  { autoAlpha: 0, y: 5, duration: 0.4 });
                gsap.from('#boot-output2', { autoAlpha: 0, y: 5, duration: 0.4, delay: 0.3 });
            });

        /* ══ 4. ENTRANCE ANIMATIONS ══ */
        gsap.from('#sidebar', {
            x: -40, autoAlpha: 0, duration: 1, ease: 'power3.out'
        });
        gsap.from('.term-window', {
            y: 30, autoAlpha: 0, duration: 0.8, ease: 'power3.out', delay: 0.2
        });

        gsap.utils.toArray('.ep-anim').forEach(el => {
            gsap.from(el, {
                scrollTrigger: { trigger: el, start: 'top 90%' },
                y: 20,
                autoAlpha: 0,
                duration: 0.5,
                ease: 'power2.out',
                clearProps: 'all'
            });
        });

        /* ══ 5. ENDPOINT HOVER — GSAP QUICKSETTER ══ */
        document.querySelectorAll('.ep-card').forEach(card => {
            const setBox = gsap.quickSetter(card, 'boxShadow');
            card.addEventListener('mouseenter', () => {
                gsap.to(card, { x: 4, duration: 0.2, ease: 'power2.out' });
            });
            card.addEventListener('mouseleave', () => {
                gsap.to(card, { x: 0, duration: 0.3, ease: 'power2.inOut' });
            });
        });

        /* ══ 6. COPY BUTTONS ══ */
        document.querySelectorAll('.ep-copy-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                navigator.clipboard?.writeText(btn.dataset.path);
                const prev = btn.innerHTML;

                const tl = gsap.timeline();
                tl.to(btn, { scale: 0.88, duration: 0.08 })
                  .to(btn, { scale: 1, duration: 0.2, ease: 'back.out(2)' });

                btn.innerHTML = '<span>✓</span> COPIED';
                btn.style.color = 'var(--neon)';
                setTimeout(() => {
                    btn.innerHTML = prev;
                    btn.style.color = '';
                }, 1400);
            });
        });

        /* ══ 7. SCHEME SWITCHER ══ */
        const scheme = localStorage.getItem('uo-term-scheme') ?? 'green';
        document.querySelector(`.scheme-dot[data-s="${scheme}"]`)?.classList.add('active');

        document.querySelectorAll('.scheme-dot').forEach(dot => {
            dot.addEventListener('click', () => {
                const s = dot.dataset.s;
                document.documentElement.setAttribute('data-scheme', s);
                localStorage.setItem('uo-term-scheme', s);
                document.querySelectorAll('.scheme-dot').forEach(d => d.classList.remove('active'));
                dot.classList.add('active');

                // Flash transition
                gsap.fromTo('body', { filter: 'brightness(3)' }, { filter: 'brightness(1)', duration: 0.6, ease: 'power2.out' });
            });
        });

        /* ══ 8. CLOCK + UPTIME ══ */
        const pad = n => String(n).padStart(2, '0');
        const startTs = Date.now();
        function tick() {
            const now = new Date();
            const clockEls = document.querySelectorAll('#clock');
            clockEls.forEach(el => el && (el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`));

            const secs = Math.floor((Date.now() - startTs) / 1000);
            const uptimeStr = `${pad(Math.floor(secs/3600))}:${pad(Math.floor((secs%3600)/60))}:${pad(secs%60)}`;
            ['#uptime','#uptime2'].forEach(sel => {
                const el = document.querySelector(sel);
                if (el) el.textContent = uptimeStr;
            });

            // live JSON preview
            const tsEl = document.getElementById('ts-val');
            const upEl = document.getElementById('ts-up');
            if (tsEl) tsEl.textContent = `"${new Date().toISOString()}"`;
            if (upEl) upEl.textContent = secs;

            // fake latency jitter
            const latEl = document.getElementById('latency');
            if (latEl) latEl.textContent = `${(Math.random()*2 + 0.5).toFixed(1)}ms`;
        }
        tick(); setInterval(tick, 1000);

        /* ══ 9. REQ-ID ══ */
        const rid = 'TXN_' + Math.random().toString(36).slice(2,10).toUpperCase();
        const r1 = document.getElementById('req-id');
        const r2 = document.getElementById('req-id-foot');
        if (r1) r1.textContent = rid;
        if (r2) r2.textContent = rid;

        /* ══ 10. SIDEBAR NAV SCROLLSPY ══ */
        document.querySelectorAll('.sidebar-nav-item').forEach(item => {
            item.addEventListener('click', () => {
                const target = document.getElementById(item.dataset.target);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.querySelectorAll('.sidebar-nav-item').forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    gsap.from(item, { x: 6, duration: 0.3, ease: 'power2.out' });
                }
            });
        });
    </script>
</body>
</html>