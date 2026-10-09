<?php /** Ilustrasi hero: gedung bergaya kolonial dengan dua menara, awan, dan pepohonan. */ ?>
<div class="hero-art" aria-hidden="true">
    <span class="hero-script">Jelajahi<br>Semarang</span>
    <svg viewBox="0 0 460 250" class="hero-svg" role="presentation" focusable="false">
        <defs>
            <linearGradient id="gSky" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#dcebff"/>
            </linearGradient>
        </defs>
        <path d="M30 190c-22-8-22-40 4-48 2-30 40-44 66-26 10-34 62-44 90-20 18-24 66-22 80 8 30-14 66 4 66 36 28 2 46 28 28 48 16 14 8 38-14 40H70c-26 0-50-10-40-38Z" fill="url(#gSky)"/>
        <path d="M70 168c40-26 80-36 120-34 50 2 90-10 150 8 30 9 52 20 70 30v38H70Z" fill="#cfe3d3"/>
        <!-- sayap bangunan -->
        <rect x="108" y="146" width="244" height="46" fill="#f4eee4"/>
        <path d="M100 148 230 118 360 148v8H100Z" fill="#c9482f"/>
        <path d="M100 148 230 118v6L106 154Z" fill="#a83a25" opacity=".55"/>
        <g fill="#8c6a52" opacity=".75">
            <rect x="118" y="162" width="8" height="22" rx="4"/><rect x="138" y="162" width="8" height="22" rx="4"/>
            <rect x="158" y="162" width="8" height="22" rx="4"/><rect x="178" y="162" width="8" height="22" rx="4"/>
            <rect x="274" y="162" width="8" height="22" rx="4"/><rect x="294" y="162" width="8" height="22" rx="4"/>
            <rect x="314" y="162" width="8" height="22" rx="4"/><rect x="334" y="162" width="8" height="22" rx="4"/>
        </g>
        <!-- bagian tengah -->
        <rect x="196" y="116" width="68" height="76" fill="#fffaf2"/>
        <path d="M188 118 230 82l42 36Z" fill="#c9482f"/>
        <rect x="224" y="96" width="12" height="14" rx="6" fill="#8c6a52" opacity=".75"/>
        <path d="M214 192v-26a16 16 0 0 1 32 0v26Z" fill="#8c6a52" opacity=".8"/>
        <!-- menara kiri dan kanan -->
        <g>
            <rect x="148" y="86" width="34" height="66" fill="#fffaf2"/>
            <path d="M143 88 165 40l22 48Z" fill="#c9482f"/>
            <rect x="158" y="96" width="14" height="26" rx="7" fill="#8c6a52" opacity=".75"/>
            <path d="M165 40v-12" stroke="#7a5a45" stroke-width="2.4" stroke-linecap="round"/>
        </g>
        <g>
            <rect x="278" y="86" width="34" height="66" fill="#fffaf2"/>
            <path d="M273 88 295 40l22 48Z" fill="#c9482f"/>
            <rect x="288" y="96" width="14" height="26" rx="7" fill="#8c6a52" opacity=".75"/>
            <path d="M295 40v-12" stroke="#7a5a45" stroke-width="2.4" stroke-linecap="round"/>
        </g>
        <!-- pepohonan -->
        <g fill="#3f9b5a"><circle cx="92" cy="196" r="22"/><circle cx="122" cy="204" r="18"/><circle cx="372" cy="198" r="22"/><circle cx="344" cy="206" r="17"/></g>
        <g fill="#2f8049"><circle cx="104" cy="200" r="13"/><circle cx="360" cy="202" r="13"/></g>
        <rect x="70" y="208" width="320" height="10" rx="5" fill="#2f8049" opacity=".35"/>
    </svg>
    <div class="hero-bubble">
        <span class="hero-bubble-ico"><?= ikon('sparkle', 22) ?></span>
        <p>Temukan destinasi terbaik dengan rekomendasi yang sesuai untukmu</p>
    </div>
</div>
