<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f3f4f6">
    <meta name="description"
        content="Undangan digital Laravel dengan tampilan handphone, floral, dan elegan warna putih-keabu.">
    <title>@yield('title', 'Undangan Digital')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <style>
        :root {
            --bg: #f7f7f8;
            --bg-soft: #eef0f2;
            --surface: rgba(255, 255, 255, 0.94);
            --surface-strong: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --line: rgba(17, 24, 39, 0.12);
            --accent: #374151;
            --accent-soft: #d1d5db;
            --accent-2: #9ca3af;
            --shadow: 0 18px 46px rgba(17, 24, 39, 0.12);
            --radius-lg: 30px;
            --radius-md: 22px;
            --radius-sm: 16px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.95), transparent 32%),
                radial-gradient(circle at top right, rgba(209, 213, 219, 0.42), transparent 40%),
                linear-gradient(180deg, #fbfbfc 0%, #f1f2f4 100%);
            font-family: "Plus Jakarta Sans", sans-serif;
            min-width: 320px;
        }

        body.cover-active {
            overflow: hidden;
        }

        img {
            display: block;
            max-width: 100%;
        }

        button,
        input,
        textarea {
            font: inherit;
        }

        .container {
            width: min(100%, 450px);
            margin: 0 auto;
            padding: 16px 14px 56px;
        }

        .device-shell {
            position: relative;
            border-radius: 36px;
            padding: 14px 12px 18px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background:
                linear-gradient(165deg, rgba(255, 255, 255, 0.78), rgba(241, 242, 244, 0.92));
            box-shadow:
                0 26px 70px rgba(17, 24, 39, 0.18),
                inset 0 1px 0 rgba(255, 255, 255, 0.72);
        }

        .device-shell::before {
            content: "";
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 94px;
            height: 16px;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.12);
        }

        .device-shell::after {
            content: "";
            position: absolute;
            inset: 28px 6px 6px;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.62);
            pointer-events: none;
        }

        .cover {
            position: fixed;
            inset: 0;
            z-index: 20;
            display: grid;
            place-items: center;
            padding: 20px;
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.92), transparent 24%),
                radial-gradient(circle at 86% 10%, rgba(209, 213, 219, 0.42), transparent 34%),
                linear-gradient(180deg, rgba(250, 250, 251, 0.98), rgba(239, 240, 242, 0.98));
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        .cover.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .cover-card,
        .section {
            position: relative;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: var(--surface);
            box-shadow: var(--shadow);
            overflow: visible;
        }

        .floral-surface::before,
        .floral-surface::after {
            content: "";
            position: absolute;
            pointer-events: none;
            opacity: 0.92;
            background-repeat: no-repeat;
            background-size: contain;
            z-index: -1;
            filter: drop-shadow(0 18px 26px rgba(17, 24, 39, 0.08));
        }

        .floral-surface::before {
            top: -56px;
            left: -56px;
            width: 190px;
            height: 190px;
            background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%20viewBox%3D%270%200%20200%20200%27%3E%3Cg%20fill%3D%27none%27%20stroke%3D%27%23b7ecd2%27%20stroke-width%3D%273%27%20stroke-linecap%3D%27round%27%20stroke-linejoin%3D%27round%27%3E%3Cpath%20d%3D%27M34%20150%20c30-50%2070-84%20132-104%27/%3E%3Cpath%20d%3D%27M62%20118%20c10%203%2017%2011%2020%2022%20c-12-2-21-9-27-18%27/%3E%3Cpath%20d%3D%27M90%2098%20c9%203%2016%2012%2018%2023%20c-12-2-20-10-25-19%27/%3E%3Cpath%20d%3D%27M120%2082%20c9%204%2015%2014%2016%2025%20c-12-3-19-12-22-22%27/%3E%3Cpath%20d%3D%27M146%2066%20c8%204%2013%2014%2013%2024%20c-11-4-17-13-19-22%27/%3E%3C/g%3E%3Cg%20fill%3D%27%23dff9ee%27%20opacity%3D%270.95%27%3E%3Ccircle%20cx%3D%2764%27%20cy%3D%27118%27%20r%3D%273%27/%3E%3Ccircle%20cx%3D%2790%27%20cy%3D%2796%27%20r%3D%272.6%27/%3E%3Ccircle%20cx%3D%27122%27%20cy%3D%2780%27%20r%3D%272.6%27/%3E%3C/g%3E%3C/svg%3E");
        }

        .floral-surface::after {
            right: -58px;
            bottom: -58px;
            width: 200px;
            height: 200px;
            transform: rotate(180deg);
            background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%20viewBox%3D%270%200%20200%20200%27%3E%3Cg%20fill%3D%27none%27%20stroke%3D%27%23b7ecd2%27%20stroke-width%3D%273%27%20stroke-linecap%3D%27round%27%20stroke-linejoin%3D%27round%27%3E%3Cpath%20d%3D%27M34%20150%20c30-50%2070-84%20132-104%27/%3E%3Cpath%20d%3D%27M62%20118%20c10%203%2017%2011%2020%2022%20c-12-2-21-9-27-18%27/%3E%3Cpath%20d%3D%27M90%2098%20c9%203%2016%2012%2018%2023%20c-12-2-20-10-25-19%27/%3E%3Cpath%20d%3D%27M120%2082%20c9%204%2015%2014%2016%2025%20c-12-3-19-12-22-22%27/%3E%3Cpath%20d%3D%27M146%2066%20c8%204%2013%2014%2013%2024%20c-11-4-17-13-19-22%27/%3E%3C/g%3E%3Cg%20fill%3D%27%23dff9ee%27%20opacity%3D%270.95%27%3E%3Ccircle%20cx%3D%2764%27%20cy%3D%27118%27%20r%3D%273%27/%3E%3Ccircle%20cx%3D%2790%27%20cy%3D%2796%27%20r%3D%272.6%27/%3E%3Ccircle%20cx%3D%27122%27%20cy%3D%2780%27%20r%3D%272.6%27/%3E%3C/g%3E%3C/svg%3E");
        }

        .verse {
            margin-top: 16px;
            padding: 14px 14px 12px;
            border-radius: 18px;
            border: 1px solid rgba(17, 24, 39, 0.1);
            background: rgba(255, 255, 255, 0.82);
            text-align: center;
        }

        .verse-text {
            margin: 0;
            font-family: "Cormorant Garamond", serif;
            font-size: 1.05rem;
            line-height: 1.8;
            color: rgba(17, 24, 39, 0.92);
        }

        .verse-divider {
            width: 64px;
            height: 2px;
            margin: 12px auto 10px;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.18);
        }

        .verse-ref {
            margin: 0;
            font-size: 0.86rem;
            letter-spacing: 0.12em;
            color: rgba(17, 24, 39, 0.72);
            font-weight: 700;
        }

        .people {
            margin-top: 16px;
            display: grid;
            gap: 14px;
        }

        .person-card {
            border-radius: 18px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.86);
            overflow: hidden;
        }

        .person-photo {
            width: 100%;
            aspect-ratio: 16 / 11;
            object-fit: cover;
        }

        .person-body {
            padding: 14px;
        }

        .person-role {
            margin: 0;
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .person-name {
            margin: 8px 0 6px;
            font-family: "Cormorant Garamond", serif;
            font-size: 1.5rem;
            line-height: 1.05;
        }

        .person-parents {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--muted);
        }

        .person-ig {
            display: inline-flex;
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            font-size: 0.86rem;
            color: rgba(17, 24, 39, 0.88);
        }

        .section-desc {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.75;
            text-align: center;
        }

        .bank-list {
            margin-top: 14px;
            display: grid;
            gap: 12px;
        }

        .bank-card {
            padding: 14px;
            border-radius: 18px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.86);
            text-align: center;
        }

        .bank-name {
            margin: 0;
            font-size: 0.74rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(17, 24, 39, 0.72);
            font-weight: 700;
        }

        .bank-number {
            margin: 10px 0 6px;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: rgba(17, 24, 39, 0.92);
        }

        .bank-owner {
            margin: 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .btn-small {
            margin-top: 12px;
            min-height: 46px;
            font-size: 0.9rem;
        }

        .thanks-text {
            margin: 14px 0 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.8;
        }

        .thanks-sign {
            margin-top: 16px;
            display: grid;
            gap: 4px;
            text-align: center;
        }

        .thanks-sign span {
            font-family: "Cormorant Garamond", serif;
            font-size: 1.55rem;
            color: rgba(17, 24, 39, 0.9);
        }

        .thanks-sign small {
            font-size: 0.88rem;
            color: var(--muted);
        }

        .site-footer {
            margin-top: 16px;
            padding: 18px 12px 10px;
            border-radius: 24px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.9);
            text-align: center;
            position: relative;
            overflow: visible;
        }

        .footer-inner {
            padding: 8px 10px 12px;
        }

        .footer-title {
            font-family: "Cormorant Garamond", serif;
            font-size: 1.7rem;
            line-height: 1.05;
        }

        .footer-sub {
            margin-top: 6px;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .footer-line {
            width: 84px;
            height: 2px;
            margin: 14px auto 12px;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.16);
        }

        .footer-meta {
            display: grid;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .footer-meta span:first-child {
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(17, 24, 39, 0.72);
            font-weight: 700;
        }

        .cover-ornaments {
            margin: 14px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .cover-ornaments span {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            box-shadow: 0 0 0 4px rgba(209, 213, 219, 0.45);
        }

        .cover-card {
            width: min(100%, 420px);
            padding: 28px 20px 24px;
            text-align: center;
        }

        .cover-glow {
            position: absolute;
            inset: -36% -22% auto;
            height: 52%;
            background: radial-gradient(circle, rgba(209, 213, 219, 0.6), transparent 62%);
            pointer-events: none;
        }

        .cover-eyebrow,
        .section-kicker,
        .card-label,
        .timeline-year {
            margin: 0;
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .cover-title,
        .hero-title,
        .section-head h3,
        .timeline-item h4 {
            margin: 0;
            font-family: "Cormorant Garamond", serif;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .cover-title {
            margin-top: 8px;
            font-size: 2.35rem;
            line-height: 0.92;
        }

        .cover-subtitle,
        .hero-desc,
        .card-sub,
        .timeline-item p,
        .address,
        .wish-status,
        .footer p {
            line-height: 1.7;
            color: var(--muted);
        }

        .cover-subtitle {
            margin: 14px 0 18px;
            font-size: 0.9rem;
        }

        .cover-image {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            border-radius: calc(var(--radius-md) + 2px);
            border: 1px solid rgba(17, 24, 39, 0.12);
        }

        .cover-date {
            margin: 16px 0 18px;
            font-size: 0.94rem;
            color: var(--text);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 999px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #374151, #111827);
            color: #f9fafb;
            box-shadow: 0 14px 30px rgba(17, 24, 39, 0.22);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.85);
            color: var(--text);
            border: 1px solid var(--line);
        }

        .section {
            margin-top: 16px;
            padding: 22px 18px;
        }

        .hero {
            padding-top: 20px;
        }

        .hero-frame {
            text-align: center;
        }

        .hero-title {
            margin-top: 10px;
            font-size: 2.5rem;
            line-height: 0.9;
        }

        .hero-desc {
            margin: 14px auto 0;
            max-width: 30ch;
            font-size: 0.9rem;
        }

        .hero-chip {
            display: inline-flex;
            margin-top: 16px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--line);
            font-size: 0.8rem;
        }

        .hero-badges {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }

        .hero-badges span {
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.86);
            font-size: 0.68rem;
            letter-spacing: 0.03em;
        }

        .hero-photo-stack {
            margin-top: 18px;
            display: grid;
            gap: 12px;
        }

        .couple-cards {
            margin-top: 18px;
            display: grid;
            gap: 12px;
        }

        .couple-card {
            border-radius: 18px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.8);
            padding: 14px;
            text-align: center;
        }

        .couple-avatar {
            width: 58px;
            height: 58px;
            margin: 0 auto;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #9ca3af, #111827);
            color: #ffffff;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.2);
        }

        .couple-card h4 {
            margin: 10px 0 6px;
            font-family: "Cormorant Garamond", serif;
            font-size: 1.35rem;
        }

        .couple-card p {
            margin: 0;
            color: var(--muted);
            font-size: 0.86rem;
            line-height: 1.7;
        }

        .hero-image {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            border-radius: var(--radius-md);
            border: 1px solid rgba(17, 24, 39, 0.12);
        }

        .quote-card {
            border: 1px solid rgba(17, 24, 39, 0.12);
            border-radius: 16px;
            padding: 12px 14px;
            background: linear-gradient(130deg, rgba(255, 255, 255, 0.96), rgba(238, 240, 242, 0.92));
        }

        .quote-card p {
            margin: 0;
            font-size: 0.84rem;
            line-height: 1.6;
            color: var(--text);
        }

        .quote-card span {
            display: inline-block;
            margin-top: 6px;
            font-size: 0.74rem;
            color: var(--muted);
        }

        .section-head {
            text-align: center;
        }

        .section-head h3 {
            margin-top: 8px;
            font-size: 1.75rem;
            line-height: 0.98;
        }

        .cards,
        .timeline,
        .gallery-grid {
            display: grid;
            gap: 14px;
            margin-top: 18px;
        }

        .card,
        .timeline-item {
            position: relative;
            padding: 18px 16px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.72);
        }

        .card-main {
            margin: 10px 0 6px;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }

        .card-sub,
        .address,
        .timeline-item p,
        .wish-status,
        .footer p {
            margin: 0;
            font-size: 0.92rem;
        }

        .address {
            margin: 18px 0 16px;
            text-align: center;
        }

        .timeline-item h4 {
            margin-top: 8px;
            font-size: 1.28rem;
            color: var(--text);
        }

        .gallery-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .gallery-item {
            position: relative;
            padding: 0;
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            background: transparent;
            cursor: pointer;
            box-shadow: 0 14px 34px rgba(17, 24, 39, 0.16);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            min-height: 168px;
            object-fit: cover;
            transition: transform 0.28s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.04);
        }

        .gallery-item span {
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 10px;
            padding: 7px 10px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.86);
            font-size: 0.74rem;
            color: var(--text);
        }

        .countdown-card {
            margin-top: 16px;
            padding: 14px;
            border-radius: 18px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: linear-gradient(130deg, rgba(255, 255, 255, 0.96), rgba(238, 240, 242, 0.92));
        }

        .countdown-label {
            margin: 0;
            text-align: center;
            font-size: 0.78rem;
            letter-spacing: 0.11em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .countdown {
            margin-top: 10px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }

        .count-item {
            text-align: center;
            border-radius: 14px;
            padding: 9px 6px;
            border: 1px solid rgba(17, 24, 39, 0.12);
            background: rgba(255, 255, 255, 0.84);
        }

        .count-item strong {
            display: block;
            font-size: 0.98rem;
            color: var(--text);
        }

        .count-item span {
            display: block;
            margin-top: 2px;
            font-size: 0.68rem;
            color: var(--muted);
        }

        .wish-form {
            display: grid;
            gap: 10px;
            margin-top: 18px;
        }

        .wish-form label {
            font-size: 0.84rem;
            color: var(--muted);
        }

        .wish-form input,
        .wish-form textarea {
            width: 100%;
            border: 1px solid rgba(17, 24, 39, 0.12);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text);
            padding: 14px 16px;
            outline: none;
        }

        .wish-form input:focus,
        .wish-form textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.08);
        }

        .wish-form textarea {
            resize: vertical;
            min-height: 132px;
        }

        .wish-status {
            min-height: 24px;
        }

        .footer {
            padding: 28px 0 18px;
            text-align: center;
        }

        .gallery-modal {
            position: fixed;
            inset: 0;
            z-index: 30;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(17, 24, 39, 0.72);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.24s ease, visibility 0.24s ease;
        }

        .gallery-modal.is-open {
            opacity: 1;
            visibility: visible;
        }

        .gallery-modal img {
            width: min(100%, 480px);
            max-height: 72vh;
            border-radius: 24px;
            object-fit: cover;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.24);
        }

        .gallery-close {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 10px 16px;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--text);
            cursor: pointer;
        }

        @media (min-width: 768px) {
            .container {
                width: min(100%, 430px);
                padding: 22px 0 72px;
            }

            .hero {
                display: block;
            }

            .cards,
            .timeline,
            .couple-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>

<body>

    <div id="cover" class="cover" aria-label="Welcome Screen">
        <div class="cover-card floral-surface">
            <div class="cover-glow"></div>
            <p class="cover-eyebrow">Undangan Pernikahan</p>
            <h1 class="cover-title">{{ $couple['display_name'] }}</h1>
            <p class="cover-subtitle">
                Kepada Yth. <strong>{{ $guestName }}</strong><br>
                Dengan penuh suka cita kami mengundang Anda.
            </p>
            <img class="cover-image" src="{{ $coupleImage }}" alt="Foto mempelai {{ $couple['display_name'] }}">
            <div class="cover-ornaments">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <p class="cover-date">{{ $event['date_label'] }}</p>
            <button id="openInvitation" class="btn btn-primary" type="button">Buka Undangan</button>
        </div>
    </div>

    <main id="invitationContent" class="container" aria-hidden="true">
        <div class="device-shell">
        <header class="hero section floral-surface" id="hero" data-aos="zoom-in-up" data-aos-duration="900">
            <div class="hero-frame">
                <p class="section-kicker">The Wedding Of</p>
                <h2 class="hero-title">{{ $couple['display_name'] }}</h2>
                <p class="hero-desc">Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir dan memberikan doa restu pada hari bahagia kami.</p>
                <div class="hero-chip">{{ $event['date_label'] }}</div>
            </div>
            <div class="hero-photo-stack">
                <img class="hero-image" src="{{ $coupleImage }}" alt="Foto mempelai utama">
            </div>
        </header>

        <section class="section floral-surface" id="ayat" data-aos="fade-up" data-aos-delay="40" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">Arti Ayat Al-Qur'an</p>
                <h3>{{ $quran['source'] }}</h3>
            </div>
            <div class="verse">
                <p class="verse-text">"{{ $quran['translation'] }}"</p>
                <div class="verse-divider"></div>
                <p class="verse-ref">{{ $quran['source'] }}</p>
            </div>
        </section>

        <section class="section floral-surface" id="mempelai" data-aos="fade-up" data-aos-delay="60" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">Mempelai</p>
                <h3>Profil Mempelai</h3>
            </div>
            <div class="people">
                <article class="person-card">
                    <img class="person-photo" src="{{ $groomPhoto }}" alt="Foto mempelai pria {{ $couple['groom'] }}">
                    <div class="person-body">
                        <p class="person-role">Mempelai Pria</p>
                        <h4 class="person-name">{{ $couple['groom'] }}</h4>
                        <p class="person-parents">{{ $couple['groom_parents'] }}</p>
                        <a class="person-ig" href="https://instagram.com/{{ $couple['groom_ig'] }}" target="_blank" rel="noopener">
                            Instagram: @{{ $couple['groom_ig'] }}
                        </a>
                    </div>
                </article>

                <article class="person-card">
                    <img class="person-photo" src="{{ $bridePhoto }}" alt="Foto mempelai wanita {{ $couple['bride'] }}">
                    <div class="person-body">
                        <p class="person-role">Mempelai Wanita</p>
                        <h4 class="person-name">{{ $couple['bride'] }}</h4>
                        <p class="person-parents">{{ $couple['bride_parents'] }}</p>
                        <a class="person-ig" href="https://instagram.com/{{ $couple['bride_ig'] }}" target="_blank" rel="noopener">
                            Instagram: @{{ $couple['bride_ig'] }}
                        </a>
                    </div>
                </article>
            </div>
        </section>

        <section class="section floral-surface" id="acara" data-aos="fade-up" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">Detail Acara</p>
                <h3>Jadwal Pernikahan</h3>
            </div>
            <div class="countdown-card">
                <p class="countdown-label">Menuju Hari Bahagia</p>
                <div class="countdown" data-event-date="{{ $event['datetime_iso'] }}">
                    <div class="count-item"><strong data-count="days">00</strong><span>Hari</span></div>
                    <div class="count-item"><strong data-count="hours">00</strong><span>Jam</span></div>
                    <div class="count-item"><strong data-count="minutes">00</strong><span>Menit</span></div>
                    <div class="count-item"><strong data-count="seconds">00</strong><span>Detik</span></div>
                </div>
            </div>
            <div class="cards">
                <article class="card">
                    <p class="card-label">Akad Nikah</p>
                    <p class="card-main">{{ $event['akad'] }}</p>
                    <p class="card-sub">{{ $event['date_label'] }}</p>
                </article>
                <article class="card">
                    <p class="card-label">Resepsi</p>
                    <p class="card-main">{{ $event['resepsi'] }}</p>
                    <p class="card-sub">{{ $event['date_label'] }}</p>
                </article>
            </div>
            <p class="address">{{ $event['address'] }}</p>
            <a class="btn btn-secondary" href="{{ $event['maps_url'] }}" target="_blank" rel="noopener">Buka Lokasi</a>
        </section>

        <section class="section floral-surface" id="cerita" data-aos="fade-up" data-aos-delay="80" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">Cerita Kami</p>
                <h3>Perjalanan Singkat</h3>
            </div>
            <div class="timeline">
                @foreach ($story as $item)
                    <article class="timeline-item">
                        <p class="timeline-year">{{ $item['year'] }}</p>
                        <h4>{{ $item['title'] }}</h4>
                        <p>{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section floral-surface" id="galeri" data-aos="fade-up" data-aos-delay="120" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">Momen Kami</p>
                <h3>Galeri Mempelai</h3>
            </div>
            <div class="gallery-grid">
                @foreach ($gallery as $image)
                    <button type="button" class="gallery-item" data-gallery-image="{{ $image['src'] }}">
                        <img src="{{ $image['src'] }}" alt="Galeri prewedding {{ $couple['display_name'] }}">
                        <span>{{ $image['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        <section class="section floral-surface" id="doa" data-aos="fade-up" data-aos-delay="160" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">Doa & Ucapan</p>
                <h3>Tulis Doa Terbaik Anda</h3>
            </div>
            <form id="wishForm" class="wish-form" method="post" action="">
                @csrf
                <label for="name">Nama</label>
                <input id="name" name="name" type="text" maxlength="100" placeholder="Nama Anda" required>

                <label for="message">Doa / Ucapan</label>
                <textarea id="message" name="message" maxlength="1000" rows="4" placeholder="Tulis doa dan ucapan terbaik..." required></textarea>

                <button class="btn btn-primary" type="submit">Kirim Doa</button>
                <p id="wishStatus" class="wish-status" role="status" aria-live="polite"></p>
            </form>
        </section>

        <section class="section floral-surface" id="angpau" data-aos="fade-up" data-aos-delay="180" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">{{ $angpau['title'] }}</p>
                <h3>Informasi Rekening</h3>
            </div>
            <p class="section-desc">{{ $angpau['desc'] }}</p>
            <div class="bank-list">
                @foreach ($angpau['accounts'] as $acc)
                    <article class="bank-card">
                        <p class="bank-name">Transfer Bank {{ $acc['bank'] }}</p>
                        <p class="bank-number">{{ $acc['account_number'] }}</p>
                        <p class="bank-owner">Atas Nama: {{ $acc['account_name'] }}</p>
                        <button class="btn btn-secondary btn-small" type="button" data-copy="{{ $acc['account_number'] }}">
                            Salin Nomor Rekening {{ $acc['bank'] }}
                        </button>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section floral-surface" id="thanks" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
            <div class="section-head">
                <p class="section-kicker">{{ $thanks['title'] }}</p>
                <h3>Terima Kasih</h3>
            </div>
            <p class="thanks-text">{{ $thanks['text'] }}</p>
            <div class="thanks-sign">
                <span>{{ $couple['display_name'] }}</span>
                <small>{{ $event['date_label'] }}</small>
            </div>
        </section>

        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-title">{{ $couple['display_name'] }}</div>
                <div class="footer-sub">{{ $event['date_label'] }}</div>
                <div class="footer-line"></div>
                <div class="footer-meta">
                    <span>Lokasi</span>
                    <span>{{ $event['address'] }}</span>
                </div>
            </div>
        </footer>
        </div>
    </main>

    <div id="galleryModal" class="gallery-modal" aria-hidden="true" role="dialog" aria-label="Pratinjau galeri">
        <button id="closeGallery" class="gallery-close" type="button" aria-label="Tutup galeri">Tutup</button>
        <img id="galleryPreview" src="" alt="Preview galeri mempelai">
    </div>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js" defer></script>
    {{-- <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(function () {
                });
            });
        }
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var body = document.body;
            var cover = document.getElementById('cover');
            var content = document.getElementById('invitationContent');
            var openButton = document.getElementById('openInvitation');
            var form = document.getElementById('wishForm');
            var status = document.getElementById('wishStatus');
            var galleryModal = document.getElementById('galleryModal');
            var galleryPreview = document.getElementById('galleryPreview');
            var closeGallery = document.getElementById('closeGallery');
            var galleryButtons = document.querySelectorAll('[data-gallery-image]');
            var countdown = document.querySelector('.countdown');
            var copyButtons = document.querySelectorAll('[data-copy]');

            body.classList.add('cover-active');

            if (content) {
                content.setAttribute('aria-hidden', 'true');
            }

            if (openButton && cover && content) {
                openButton.addEventListener('click', function() {
                    cover.classList.add('is-hidden');
                    body.classList.remove('cover-active');
                    content.setAttribute('aria-hidden', 'false');

                    if (window.AOS) {
                        window.AOS.refreshHard();
                    }
                });
            }

            galleryButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    if (!galleryModal || !galleryPreview) {
                        return;
                    }

                    var image = button.getAttribute('data-gallery-image');

                    if (!image) {
                        return;
                    }

                    galleryPreview.setAttribute('src', image);
                    galleryModal.classList.add('is-open');
                    galleryModal.setAttribute('aria-hidden', 'false');
                    body.classList.add('cover-active');
                });
            });

            function closeGalleryModal() {
                if (!galleryModal) {
                    return;
                }

                galleryModal.classList.remove('is-open');
                galleryModal.setAttribute('aria-hidden', 'true');

                if (cover && !cover.classList.contains('is-hidden')) {
                    body.classList.add('cover-active');
                    return;
                }

                body.classList.remove('cover-active');
            }

            if (closeGallery) {
                closeGallery.addEventListener('click', closeGalleryModal);
            }

            if (galleryModal) {
                galleryModal.addEventListener('click', function(event) {
                    if (event.target === galleryModal) {
                        closeGalleryModal();
                    }
                });
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeGalleryModal();
                }
            });

            if (form && status) {
                form.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    status.textContent = 'Sedang mengirim doa...';

                    try {
                        var formData = new FormData(form);
                        var response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            throw new Error('Gagal menyimpan doa.');
                        }

                        form.reset();
                        status.textContent = 'Doa berhasil dikirim. Terima kasih atas ucapan baiknya.';
                    } catch (error) {
                        status.textContent = 'Doa belum terkirim. Silakan coba lagi.';
                    }
                });
            }

            copyButtons.forEach(function(button) {
                button.addEventListener('click', async function() {
                    var value = button.getAttribute('data-copy') || '';

                    if (!value) {
                        return;
                    }

                    var original = button.textContent;

                    try {
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            await navigator.clipboard.writeText(value);
                        } else {
                            var input = document.createElement('input');
                            input.value = value;
                            document.body.appendChild(input);
                            input.select();
                            document.execCommand('copy');
                            input.remove();
                        }

                        button.textContent = 'Tersalin';
                        window.setTimeout(function() {
                            button.textContent = original;
                        }, 900);
                    } catch (e) {
                        button.textContent = 'Gagal';
                        window.setTimeout(function() {
                            button.textContent = original;
                        }, 900);
                    }
                });
            });

            function updateCountdown() {
                if (!countdown) {
                    return;
                }

                var eventDate = countdown.getAttribute('data-event-date');

                if (!eventDate) {
                    return;
                }

                var target = new Date(eventDate).getTime();
                var now = Date.now();
                var diff = Math.max(target - now, 0);

                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                var minutes = Math.floor((diff / (1000 * 60)) % 60);
                var seconds = Math.floor((diff / 1000) % 60);

                var values = {
                    days: days,
                    hours: hours,
                    minutes: minutes,
                    seconds: seconds,
                };

                Object.keys(values).forEach(function(key) {
                    var node = countdown.querySelector('[data-count="' + key + '"]');

                    if (!node) {
                        return;
                    }

                    node.textContent = String(values[key]).padStart(2, '0');
                });
            }

            if (window.AOS) {
                window.AOS.init({
                    once: false,
                    mirror: true,
                    duration: 800,
                    easing: 'ease-out-cubic',
                });
            }

            updateCountdown();
            window.setInterval(updateCountdown, 1000);
        });
    </script>
</body>

</html>
