<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VANTHEX ENGINE | Key Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #00f0ff;
            --secondary: #7b2cbf;
            --accent: #ff006e;
            --success: #00ff88;
            --warning: #ffbe0b;
            --danger: #ff006e;
            --telegram: #0088cc;
            --youtube: #ff0000;
            --instagram: #e4405f;
            --dark: #0a0a1a;
            --darker: #050510;
            --card: rgba(255,255,255,0.03);
            --border: rgba(0,240,255,0.1);
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--darker);
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        /* ===== BACKGROUND ANIMATIONS ===== */
        .bg-animation {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            width: 200%; height: 200%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0,240,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(123,44,191,0.05) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255,0,110,0.03) 0%, transparent 50%);
            animation: bgMove 20s ease-in-out infinite;
        }

        @keyframes bgMove {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(-5%, -5%); }
            66% { transform: translate(5%, 5%); }
        }

        .grid-lines {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: 
                linear-gradient(rgba(0,240,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,240,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            pointer-events: none;
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0;
            animation: floatParticle 8s ease-in-out infinite;
        }

        @keyframes floatParticle {
            0% { opacity: 0; transform: translateY(100vh) scale(0); }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { opacity: 0; transform: translateY(-10vh) scale(1); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 6px;
            text-transform: uppercase;
            animation: glow 3s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { filter: drop-shadow(0 0 20px rgba(0,240,255,0.3)); }
            to { filter: drop-shadow(0 0 40px rgba(123,44,191,0.5)); }
        }

        .tagline {
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            margin-top: 8px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* ===== SOCIAL LINKS ===== */
        .social-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .social-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .social-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .social-btn:hover::before { left: 100%; }

        .social-btn i { font-size: 18px; }

        .social-btn.telegram {
            background: rgba(0,136,204,0.1);
            border-color: rgba(0,136,204,0.3);
            color: #00aced;
        }
        .social-btn.telegram:hover {
            background: rgba(0,136,204,0.2);
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,136,204,0.3);
        }

        .social-btn.youtube {
            background: rgba(255,0,0,0.1);
            border-color: rgba(255,0,0,0.3);
            color: #ff4444;
        }
        .social-btn.youtube:hover {
            background: rgba(255,0,0,0.2);
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(255,0,0,0.3);
        }

        .social-btn.instagram {
            background: rgba(228,64,95,0.1);
            border-color: rgba(228,64,95,0.3);
            color: #e4405f;
        }
        .social-btn.instagram:hover {
            background: rgba(228,64,95,0.2);
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(228,64,95,0.3);
        }

        .owner-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            padding: 10px 25px;
            background: linear-gradient(135deg, rgba(0,240,255,0.1), rgba(123,44,191,0.1));
            border: 1px solid var(--border);
            border-radius: 50px;
            font-size: 14px;
            color: var(--primary);
            animation: badgePulse 2s ease-in-out infinite;
        }

        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 0 5px rgba(0,240,255,0.1); }
            50% { box-shadow: 0 0 20px rgba(0,240,255,0.3); }
        }

        /* ===== MAIN CARD ===== */
        .main-card {
            background: var(--card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 35px;
            margin: 15px auto;
            max-width: 650px;
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: cardAppear 0.6s ease-out;
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), var(--secondary), transparent);
            animation: scanline 3s linear infinite;
        }

        @keyframes scanline {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* ===== STATUS BAR ===== */
        .status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid var(--border);
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 10px var(--success);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        /* ===== PROGRESS SECTION ===== */
        .progress-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .progress-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 16px;
            color: var(--primary);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .progress-track {
            width: 100%;
            height: 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 10px;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            border-radius: 6px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .progress-text {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
        }

        /* ===== CHANNEL CARDS ===== */
        .channel-card {
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        .channel-card:nth-child(1) { animation-delay: 0.1s; }
        .channel-card:nth-child(2) { animation-delay: 0.2s; }
        .channel-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .channel-card:hover {
            border-color: var(--primary);
            transform: translateX(5px);
            box-shadow: 0 0 20px rgba(0,240,255,0.1);
        }

        .channel-card.completed {
            opacity: 0.7;
            border-color: var(--success);
            background: rgba(0,255,136,0.05);
        }

        .channel-card.completed::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%; right: 15px;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--success);
            animation: checkPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes checkPop {
            0% { transform: translateY(-50%) scale(0); }
            100% { transform: translateY(-50%) scale(1); }
        }

        .channel-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .channel-icon.telegram { background: rgba(0,136,204,0.2); color: #00aced; }
        .channel-icon.youtube { background: rgba(255,0,0,0.2); color: #ff4444; }
        .channel-icon.instagram { background: rgba(228,64,95,0.2); color: #e4405f; }

        .channel-card:hover .channel-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .channel-info { flex: 1; }

        .channel-name {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 3px;
        }

        .channel-handle {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }

        .btn-join-channel {
            padding: 8px 20px;
            border: none;
            border-radius: 10px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 4px 20px rgba(0,240,255,0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-join-channel::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-join-channel:hover::before { left: 100%; }

        .btn-join-channel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0,240,255,0.5);
        }

        .btn-join-channel:disabled {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.2);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-join-channel:disabled::before { display: none; }

        /* ===== DURATION SECTION ===== */
        .duration-section {
            display: none;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
        }

        .duration-section.active {
            display: block;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .duration-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 16px;
            color: var(--primary);
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .duration-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .duration-card {
            background: rgba(0,0,0,0.2);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .duration-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,240,255,0.1);
        }

        .duration-card.selected {
            border-color: var(--primary);
            background: rgba(0,240,255,0.05);
            box-shadow: 0 0 25px rgba(0,240,255,0.15), inset 0 0 20px rgba(0,240,255,0.05);
            animation: selectPulse 2s ease-in-out infinite;
        }

        @keyframes selectPulse {
            0%, 100% { box-shadow: 0 0 25px rgba(0,240,255,0.15), inset 0 0 20px rgba(0,240,255,0.05); }
            50% { box-shadow: 0 0 40px rgba(0,240,255,0.3), inset 0 0 30px rgba(0,240,255,0.1); }
        }

        .duration-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .duration-label {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        /* ===== GENERATE BUTTON ===== */
        .btn-generate {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, var(--success), #00cc6a);
            color: #000;
            box-shadow: 0 4px 30px rgba(0,255,136,0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(0,255,136,0.5);
        }

        .btn-generate:disabled {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.2);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-generate::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-generate:hover::before { left: 100%; }

        /* ===== KEY DISPLAY ===== */
        .key-display {
            background: rgba(0,240,255,0.05);
            border: 2px dashed var(--primary);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            margin-top: 20px;
            display: none;
            position: relative;
            overflow: hidden;
        }

        .key-display.active {
            display: block;
            animation: keyAppear 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes keyAppear {
            0% { transform: scale(0.5) rotate(-5deg); opacity: 0; }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }

        .key-display::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(0,240,255,0.1), transparent, rgba(123,44,191,0.1), transparent);
            animation: rotateBorder 4s linear infinite;
        }

        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .key-label {
            font-size: 13px;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .key-code {
            font-family: 'Orbitron', monospace;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 2px;
            padding: 12px;
            background: rgba(0,0,0,0.5);
            border-radius: 10px;
            word-break: break-all;
            text-shadow: 0 0 15px rgba(0,240,255,0.3);
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            cursor: pointer;
            transition: all 0.3s;
            user-select: all;
        }

        .key-code:hover {
            background: rgba(0,0,0,0.7);
            box-shadow: 0 0 20px rgba(0,240,255,0.2);
        }

        .key-meta {
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .btn-copy {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: #fff;
            padding: 10px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(0,240,255,0.3);
            position: relative;
            z-index: 1;
        }

        .btn-copy:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0,240,255,0.5);
        }

        .btn-copy.copied {
            background: linear-gradient(135deg, var(--success), #00cc6a);
            box-shadow: 0 4px 20px rgba(0,255,136,0.3);
        }

        /* ===== cURL SECTION ===== */
        .curl-section {
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            margin-top: 20px;
        }

        .curl-title {
            font-size: 13px;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .curl-code {
            background: rgba(0,0,0,0.5);
            border-radius: 8px;
            padding: 12px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: var(--success);
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
            line-height: 1.5;
            cursor: pointer;
            transition: all 0.3s;
            user-select: all;
        }

        .curl-code:hover {
            background: rgba(0,0,0,0.7);
            box-shadow: 0 0 15px rgba(0,255,136,0.1);
        }

        .btn-copy-curl {
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--border);
            color: #fff;
            padding: 6px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-size: 12px;
            margin-top: 8px;
            transition: all 0.3s;
        }

        .btn-copy-curl:hover {
            background: rgba(0,240,255,0.1);
            border-color: var(--primary);
        }

        /* ===== ALERT ===== */
        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 18px;
            display: none;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            animation: alertSlide 0.4s ease-out;
        }

        @keyframes alertSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert.active { display: flex; }

        .alert-success {
            background: rgba(0,255,136,0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }

        .alert-error {
            background: rgba(255,0,110,0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
        }

        .alert-warning {
            background: rgba(255,190,11,0.1);
            border: 1px solid var(--warning);
            color: var(--warning);
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding: 25px;
            color: rgba(255,255,255,0.3);
            font-size: 12px;
            letter-spacing: 1px;
        }

        /* ===== LOADING OVERLAY ===== */
        .loading-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(5,5,16,0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .loading-overlay.active { display: flex; }

        .loader-container {
            text-align: center;
        }

        .loader {
            width: 60px; height: 60px;
            border: 3px solid rgba(0,240,255,0.1);
            border-top-color: var(--primary);
            border-right-color: var(--secondary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .loader-text {
            margin-top: 20px;
            font-family: 'Orbitron', sans-serif;
            font-size: 12px;
            color: var(--primary);
            letter-spacing: 3px;
            animation: textPulse 1s ease-in-out infinite;
        }

        @keyframes textPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ===== SPARKLE EFFECT ===== */
        .sparkle {
            position: fixed;
            pointer-events: none;
            z-index: 999;
            animation: sparkleAnim 1s ease-out forwards;
        }

        @keyframes sparkleAnim {
            0% { opacity: 1; transform: translate(0, 0) scale(1); }
            100% { opacity: 0; transform: translate(var(--tx), var(--ty)) scale(0); }
        }

        /* ===== TOAST NOTIFICATION ===== */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            animation: toastIn 0.4s ease-out, toastOut 0.4s ease-in 2.5s forwards;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(50px); }
        }

        .toast-success { background: rgba(0,255,136,0.15); border: 1px solid var(--success); color: var(--success); }
        .toast-error { background: rgba(255,0,110,0.15); border: 1px solid var(--danger); color: var(--danger); }
        .toast-info { background: rgba(0,240,255,0.15); border: 1px solid var(--primary); color: var(--primary); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .logo-text { font-size: 28px; letter-spacing: 3px; }
            .main-card { padding: 20px; }
            .duration-grid { grid-template-columns: 1fr; }
            .social-bar { flex-direction: column; align-items: center; }
            .channel-card { flex-direction: column; text-align: center; }
            .key-code { font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    <div class="grid-lines"></div>
    <div class="particles" id="particles"></div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="logo-text">VANTHEX ENGINE</h1>
            <p class="tagline">Free License Key Generator</p>

            <div class="social-bar">
                <a href="https://t.me/sagarxmodder" target="_blank" class="social-btn telegram">
                    <i class="fab fa-telegram"></i> @sagarxmodder
                </a>
                <a href="https://youtube.com/@VANTHEXCORE" target="_blank" class="social-btn youtube">
                    <i class="fab fa-youtube"></i> @VANTHEXCORE
                </a>
                <a href="https://instagram.com/sagar_db_20" target="_blank" class="social-btn instagram">
                    <i class="fab fa-instagram"></i> @sagar_db_20
                </a>
            </div>

            <div class="owner-badge">
                <i class="fas fa-crown" style="color: var(--warning);"></i> Owner @SagarXModder
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="status-bar">
                <div class="status-indicator">
                    <div class="status-dot"></div>
                    <span>System Online</span>
                </div>
                <div style="font-size: 12px; color: rgba(255,255,255,0.5);">v2.0</div>
            </div>

            <div class="alert" id="alertBox"></div>

            <div class="progress-section">
                <div class="progress-title"><i class="fas fa-link"></i> Join Progress</div>
                <div class="progress-track">
                    <div class="progress-fill" id="progressBar"></div>
                </div>
                <div class="progress-text" id="progressText">0/3 Channels Joined</div>
            </div>

            <div class="channel-card" id="channel1">
                <div class="channel-icon telegram"><i class="fab fa-telegram"></i></div>
                <div class="channel-info">
                    <div class="channel-name">Telegram Channel</div>
                    <div class="channel-handle">@sagarxmodder</div>
                </div>
                <button class="btn-join-channel" id="btnJoin1" onclick="doJoin(1)">Join</button>
            </div>

            <div class="channel-card" id="channel2">
                <div class="channel-icon youtube"><i class="fab fa-youtube"></i></div>
                <div class="channel-info">
                    <div class="channel-name">YouTube Channel</div>
                    <div class="channel-handle">@VANTHEXCORE</div>
                </div>
                <button class="btn-join-channel" id="btnJoin2" onclick="doJoin(2)" disabled>Join</button>
            </div>

            <div class="channel-card" id="channel3">
                <div class="channel-icon instagram"><i class="fab fa-instagram"></i></div>
                <div class="channel-info">
                    <div class="channel-name">Instagram</div>
                    <div class="channel-handle">@sagar_db_20</div>
                </div>
                <button class="btn-join-channel" id="btnJoin3" onclick="doJoin(3)" disabled>Join</button>
            </div>

            <div class="duration-section" id="durationSection">
                <div class="duration-title"><i class="fas fa-clock"></i> Select Key Duration</div>
                <div class="duration-grid">
                    <div class="duration-card" onclick="selectDuration(1)" id="dur1">
                        <div class="duration-value">1</div>
                        <div class="duration-label">Day</div>
                    </div>
                    <div class="duration-card" onclick="selectDuration(2)" id="dur2">
                        <div class="duration-value">2</div>
                        <div class="duration-label">Days</div>
                    </div>
                </div>
                <button class="btn-generate" id="generateBtn" onclick="generateKey()" disabled>
                    <i class="fas fa-dice"></i> Generate License Key
                </button>
            </div>

            <div class="key-display" id="keyDisplay">
                <div class="key-label"><i class="fas fa-key"></i> Your License Key</div>
                <div class="key-code" id="keyCode" onclick="copyKeyFromCode()" title="Click to copy">VANTHEX-XXXX-XXXXXXXX</div>
                <div class="key-meta">
                    <span><i class="fas fa-hourglass-half"></i> <span id="keyDuration">1 Day</span></span>
                    <span><i class="fas fa-calendar-alt"></i> <span id="keyExpiry">--</span></span>
                </div>
                <button class="btn-copy" id="btnCopyKey" onclick="copyKey()">
                    <i class="fas fa-copy"></i> Copy Key
                </button>
            </div>

            <div class="curl-section">
                <div class="curl-title"><i class="fas fa-plug"></i> cURL Verify (For IMGUI Mod Menu)</div>
                <div class="curl-code" id="curlCode" onclick="copyCurlFromCode()" title="Click to copy">curl -X POST "YOUR_DOMAIN/api.php" \\
  -d "action=verify" \\
  -d "key=YOUR_KEY_HERE" \\
  -d "device_id=DEVICE_ID"</div>
                <button class="btn-copy-curl" onclick="copyCurl()">
                    <i class="fas fa-copy"></i> Copy cURL
                </button>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2026 VANTHEX ENGINE | Created by @SagarXModder</p>
            <p style="margin-top: 5px;">Telegram: @sagarxmodder | YouTube: @VANTHEXCORE | Instagram: @sagar_db_20</p>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader-container">
            <div class="loader"></div>
            <div class="loader-text" id="loadingText">PROCESSING...</div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        const API_URL = 'api.php';
        const channelLinks = {
            1: 'https://t.me/sagarxmodder',
            2: 'https://youtube.com/@VANTHEXCORE',
            3: 'https://instagram.com/sagar_db_20'
        };

        let selectedDuration = 0;
        let generatedKey = '';
        let joinCount = 0;

        // Create floating particles
        function createParticles() {
            const container = document.getElementById('particles');
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (6 + Math.random() * 4) + 's';
                particle.style.width = (2 + Math.random() * 4) + 'px';
                particle.style.height = particle.style.width;
                const colors = ['var(--primary)', 'var(--secondary)', 'var(--accent)', 'var(--success)'];
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                container.appendChild(particle);
            }
        }

        // Sparkle effect on success
        function createSparkles(x, y) {
            for (let i = 0; i < 12; i++) {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.innerHTML = '<i class="fas fa-star" style="color: var(--success); font-size: 10px;"></i>';
                sparkle.style.left = x + 'px';
                sparkle.style.top = y + 'px';
                const angle = (Math.PI * 2 * i) / 12;
                const distance = 50 + Math.random() * 50;
                sparkle.style.setProperty('--tx', Math.cos(angle) * distance + 'px');
                sparkle.style.setProperty('--ty', Math.sin(angle) * distance + 'px');
                document.body.appendChild(sparkle);
                setTimeout(() => sparkle.remove(), 1000);
            }
        }

        // Toast notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            const icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
            toast.innerHTML = '<i class="fas ' + icons[type] + '"></i> ' + message;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Check existing joins on load
        window.onload = function() {
            createParticles();
            checkJoins();
        };

        function showAlert(message, type) {
            const alert = document.getElementById('alertBox');
            alert.className = 'alert alert-' + type + ' active';
            const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle' };
            alert.innerHTML = '<i class="fas ' + icons[type] + '"></i> ' + message;
            setTimeout(() => alert.classList.remove('active'), 5000);
        }

        function showLoading(text) {
            document.getElementById('loadingText').textContent = text;
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        function updateProgress() {
            const percent = (joinCount / 3) * 100;
            document.getElementById('progressBar').style.width = percent + '%';
            document.getElementById('progressText').textContent = `${joinCount}/3 Channels Joined`;
        }

        function updateUI() {
            for (let i = 1; i <= 3; i++) {
                const btn = document.getElementById('btnJoin' + i);
                const card = document.getElementById('channel' + i);

                if (i <= joinCount) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Done';
                    btn.disabled = true;
                    card.classList.add('completed');
                } else if (i === joinCount + 1) {
                    btn.innerHTML = '<i class="fas fa-external-link-alt"></i> Join';
                    btn.disabled = false;
                    card.classList.remove('completed');
                } else {
                    btn.innerHTML = '<i class="fas fa-lock"></i> Locked';
                    btn.disabled = true;
                    card.classList.remove('completed');
                }
            }

            updateProgress();

            if (joinCount >= 3) {
                document.getElementById('durationSection').classList.add('active');
                showAlert('All channels joined! Select key duration.', 'success');
                showToast('All channels joined! Select duration.', 'success');
            }
        }

        async function checkJoins() {
            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=check_joins'
                });
                const data = await response.json();

                if (data.status === 'success') {
                    joinCount = data.join_count || 0;
                    updateUI();
                }
            } catch (e) {
                console.log('No previous joins');
            }
        }

        function doJoin(channelNum) {
            window.open(channelLinks[channelNum], '_blank');
            showLoading('VERIFYING JOIN...');

            setTimeout(async () => {
                try {
                    const response = await fetch(API_URL, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `action=join&channel=${channelNum}`
                    });

                    const data = await response.json();
                    hideLoading();

                    if (data.status === 'success') {
                        joinCount = data.join_count;
                        updateUI();
                        showAlert(`Channel ${channelNum} joined successfully!`, 'success');
                        showToast(`Channel ${channelNum} joined!`, 'success');
                    } else {
                        showAlert(data.message, 'error');
                        showToast(data.message, 'error');
                    }
                } catch (e) {
                    hideLoading();
                    joinCount++;
                    updateUI();
                    showAlert(`Channel ${channelNum} joined!`, 'success');
                    showToast(`Channel ${channelNum} joined!`, 'success');
                }
            }, 3000);
        }

        function selectDuration(days) {
            selectedDuration = days;
            document.querySelectorAll('.duration-card').forEach(card => card.classList.remove('selected'));
            document.getElementById('dur' + days).classList.add('selected');
            document.getElementById('generateBtn').disabled = false;
            showToast(`${days} Day${days > 1 ? 's' : ''} selected!`, 'info');
        }

        async function generateKey() {
            if (!selectedDuration) {
                showAlert('Please select duration first!', 'error');
                showToast('Select duration first!', 'error');
                return;
            }

            showLoading('GENERATING KEY...');

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=generate_key&duration=${selectedDuration}`
                });

                const data = await response.json();
                hideLoading();

                if (data.status === 'success') {
                    generatedKey = data.key;
                    document.getElementById('keyCode').textContent = data.key;
                    document.getElementById('keyDuration').textContent = data.duration_days + ' Day' + (data.duration_days > 1 ? 's' : '');
                    document.getElementById('keyExpiry').textContent = new Date(data.expires_at).toLocaleDateString();
                    document.getElementById('keyDisplay').classList.add('active');

                    document.getElementById('curlCode').textContent = `curl -X POST "${window.location.origin}/api.php" \\\n  -d "action=verify" \\\n  -d "key=${data.key}" \\\n  -d "device_id=YOUR_DEVICE_ID"`;

                    showAlert('Key generated! Use in your IMGUI mod menu.', 'success');
                    showToast('Key generated successfully!', 'success');

                    const keyDisplay = document.getElementById('keyDisplay');
                    const rect = keyDisplay.getBoundingClientRect();
                    createSparkles(rect.left + rect.width / 2, rect.top + rect.height / 2);
                } else {
                    showAlert(data.message, 'error');
                    showToast(data.message, 'error');
                }
            } catch (e) {
                hideLoading();
                const demoKey = 'VANTHEX-' + (selectedDuration === 1 ? '1DAY' : '2DAY') + '-' + Math.random().toString(36).substring(2, 10).toUpperCase();
                generatedKey = demoKey;
                document.getElementById('keyCode').textContent = demoKey;
                document.getElementById('keyDuration').textContent = selectedDuration + ' Day' + (selectedDuration > 1 ? 's' : '');
                document.getElementById('keyExpiry').textContent = new Date(Date.now() + selectedDuration * 86400000).toLocaleDateString();
                document.getElementById('keyDisplay').classList.add('active');

                document.getElementById('curlCode').textContent = `curl -X POST "${window.location.origin}/api.php" \\\n  -d "action=verify" \\\n  -d "key=${demoKey}" \\\n  -d "device_id=YOUR_DEVICE_ID"`;

                showAlert('Demo key generated! Use in your IMGUI mod menu.', 'success');
                showToast('Key generated!', 'success');

                const keyDisplay = document.getElementById('keyDisplay');
                const rect = keyDisplay.getBoundingClientRect();
                createSparkles(rect.left + rect.width / 2, rect.top + rect.height / 2);
            }
        }

        // ===== FIXED COPY FUNCTIONS =====
        function copyKey() {
            if (!generatedKey) {
                showToast('No key to copy!', 'error');
                return;
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(generatedKey).then(() => {
                    onCopySuccess();
                }).catch(err => {
                    fallbackCopy(generatedKey);
                });
            } else {
                fallbackCopy(generatedKey);
            }
        }

        function copyKeyFromCode() {
            if (!generatedKey) return;
            copyKey();
        }

        function fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            textArea.style.top = '0';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    onCopySuccess();
                } else {
                    showToast('Failed to copy key', 'error');
                }
            } catch (err) {
                showToast('Failed to copy key', 'error');
            }

            document.body.removeChild(textArea);
        }

        function onCopySuccess() {
            const btn = document.getElementById('btnCopyKey');
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btn.classList.add('copied');
            showToast('Key copied to clipboard!', 'success');

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy"></i> Copy Key';
                btn.classList.remove('copied');
            }, 2000);
        }

        function copyCurl() {
            const curlText = document.getElementById('curlCode').textContent;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(curlText).then(() => {
                    showToast('cURL copied to clipboard!', 'success');
                }).catch(err => {
                    fallbackCopyCurl(curlText);
                });
            } else {
                fallbackCopyCurl(curlText);
            }
        }

        function copyCurlFromCode() {
            copyCurl();
        }

        function fallbackCopyCurl(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');
                showToast('cURL copied to clipboard!', 'success');
            } catch (err) {
                showToast('Failed to copy cURL', 'error');
            }

            document.body.removeChild(textArea);
        }
    </script>
</body>
</html>