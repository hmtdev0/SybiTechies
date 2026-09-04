{{-- Custom software-development illustration: Web + Mobile + Desktop.
     Default hero visual, shown whenever Home Page CMS > Hero > Image is empty. --}}
<div class="hero__illustration" role="img" aria-label="{{ $siteSettings->company_name ?? 'SysbiTechies' }} builds web, mobile and desktop software">
    <svg viewBox="0 0 620 480" xmlns="http://www.w3.org/2000/svg" fill="none">
        <defs>
            <linearGradient id="hgBrand" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#2563EB"/>
                <stop offset="0.55" stop-color="#4f46e5"/>
                <stop offset="1" stop-color="#7C3AED"/>
            </linearGradient>
            <linearGradient id="hgAccent" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#06B6D4"/>
                <stop offset="1" stop-color="#2563EB"/>
            </linearGradient>
            <linearGradient id="hgArea" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#2563EB" stop-opacity="0.35"/>
                <stop offset="1" stop-color="#2563EB" stop-opacity="0"/>
            </linearGradient>
            <filter id="hSoft" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="18" stdDeviation="24" flood-color="#1e293b" flood-opacity="0.18"/>
            </filter>
            <filter id="hSoft2" x="-30%" y="-30%" width="160%" height="160%">
                <feDropShadow dx="0" dy="12" stdDeviation="16" flood-color="#2563EB" flood-opacity="0.22"/>
            </filter>
        </defs>

        {{-- backdrop glow shape --}}
        <rect x="120" y="60" width="420" height="360" rx="60" fill="url(#hgBrand)" opacity="0.08"/>
        {{-- ground shadow --}}
        <ellipse cx="330" cy="430" rx="220" ry="26" fill="#0F172A" opacity="0.08"/>

        {{-- Desktop-app window (back, right) --}}
        <g filter="url(#hSoft)" transform="rotate(4 470 150)">
            <rect x="360" y="70" width="220" height="150" rx="14" fill="#ffffff"/>
            <rect x="360" y="70" width="220" height="30" rx="14" fill="#0F172A"/>
            <rect x="360" y="88" width="220" height="12" fill="#0F172A"/>
            <circle cx="376" cy="85" r="4" fill="#FB7185"/>
            <circle cx="390" cy="85" r="4" fill="#FBBF24"/>
            <circle cx="404" cy="85" r="4" fill="#34D399"/>
            <rect x="376" y="116" width="70" height="10" rx="5" fill="#e2e8f0"/>
            <rect x="376" y="134" width="130" height="8" rx="4" fill="#eef2ff"/>
            <rect x="376" y="150" width="118" height="8" rx="4" fill="#eef2ff"/>
            <rect x="376" y="166" width="150" height="8" rx="4" fill="#eef2ff"/>
            <rect x="376" y="186" width="60" height="18" rx="9" fill="url(#hgAccent)"/>
        </g>

        {{-- Main web dashboard browser window --}}
        <g filter="url(#hSoft)">
            <rect x="150" y="120" width="330" height="240" rx="18" fill="#ffffff"/>
            {{-- top bar --}}
            <path d="M150 138a18 18 0 0 1 18-18h294a18 18 0 0 1 18 18v20H150z" fill="#f1f5f9"/>
            <circle cx="172" cy="148" r="5" fill="#FB7185"/>
            <circle cx="190" cy="148" r="5" fill="#FBBF24"/>
            <circle cx="208" cy="148" r="5" fill="#34D399"/>
            <rect x="240" y="142" width="180" height="12" rx="6" fill="#e2e8f0"/>
            {{-- sidebar --}}
            <rect x="150" y="158" width="74" height="202" fill="#0F172A"/>
            <rect x="166" y="180" width="42" height="8" rx="4" fill="url(#hgAccent)"/>
            <rect x="166" y="200" width="34" height="7" rx="3.5" fill="#334155"/>
            <rect x="166" y="218" width="40" height="7" rx="3.5" fill="#334155"/>
            <rect x="166" y="236" width="30" height="7" rx="3.5" fill="#334155"/>
            <rect x="166" y="254" width="38" height="7" rx="3.5" fill="#334155"/>
            {{-- stat cards --}}
            <rect x="238" y="174" width="106" height="52" rx="10" fill="#eff4ff"/>
            <rect x="252" y="188" width="40" height="8" rx="4" fill="#94a3b8"/>
            <rect x="252" y="202" width="60" height="12" rx="6" fill="url(#hgBrand)"/>
            <rect x="356" y="174" width="106" height="52" rx="10" fill="#f5f0ff"/>
            <rect x="370" y="188" width="40" height="8" rx="4" fill="#94a3b8"/>
            <rect x="370" y="202" width="52" height="12" rx="6" fill="#7C3AED"/>
            {{-- chart card with area line --}}
            <rect x="238" y="238" width="224" height="106" rx="10" fill="#f8fafc"/>
            <path d="M256 320 L288 300 L318 308 L348 280 L380 292 L410 262 L444 274 L444 330 L256 330 Z" fill="url(#hgArea)"/>
            <polyline points="256,320 288,300 318,308 348,280 380,292 410,262 444,274" stroke="url(#hgBrand)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="410" cy="262" r="5" fill="#7C3AED" stroke="#fff" stroke-width="2.5"/>
        </g>

        {{-- Mobile app (front, left) --}}
        <g filter="url(#hSoft2)">
            <rect x="70" y="200" width="128" height="236" rx="26" fill="#0F172A"/>
            <rect x="80" y="210" width="108" height="216" rx="18" fill="#ffffff"/>
            <rect x="104" y="216" width="40" height="7" rx="3.5" fill="#0F172A"/>
            {{-- app header --}}
            <path d="M80 228a0 0 0 0 1 0 0h108v44H80z" fill="url(#hgBrand)"/>
            <rect x="80" y="228" width="108" height="46" fill="url(#hgBrand)"/>
            <circle cx="102" cy="251" r="11" fill="#ffffff" opacity="0.9"/>
            <rect x="120" y="244" width="52" height="7" rx="3.5" fill="#ffffff" opacity="0.95"/>
            <rect x="120" y="256" width="38" height="6" rx="3" fill="#ffffff" opacity="0.6"/>
            {{-- list rows --}}
            <rect x="92" y="288" width="84" height="24" rx="8" fill="#eef2ff"/>
            <circle cx="104" cy="300" r="7" fill="url(#hgAccent)"/>
            <rect x="118" y="296" width="48" height="8" rx="4" fill="#c7d2fe"/>
            <rect x="92" y="320" width="84" height="24" rx="8" fill="#f5f3ff"/>
            <circle cx="104" cy="332" r="7" fill="#7C3AED"/>
            <rect x="118" y="328" width="48" height="8" rx="4" fill="#ddd6fe"/>
            <rect x="92" y="352" width="84" height="24" rx="8" fill="#eef2ff"/>
            <circle cx="104" cy="364" r="7" fill="#2563EB"/>
            <rect x="118" y="360" width="48" height="8" rx="4" fill="#c7d2fe"/>
            {{-- bottom nav --}}
            <rect x="80" y="396" width="108" height="30" fill="#ffffff"/>
            <circle cx="104" cy="411" r="5" fill="url(#hgBrand)"/>
            <circle cx="134" cy="411" r="5" fill="#cbd5e1"/>
            <circle cx="164" cy="411" r="5" fill="#cbd5e1"/>
        </g>

        {{-- Floating code chip --}}
        <g filter="url(#hSoft)">
            <rect x="410" y="300" width="150" height="86" rx="14" fill="#0F172A"/>
            <circle cx="426" cy="316" r="3.5" fill="#FB7185"/>
            <circle cx="438" cy="316" r="3.5" fill="#FBBF24"/>
            <circle cx="450" cy="316" r="3.5" fill="#34D399"/>
            <rect x="424" y="332" width="20" height="6" rx="3" fill="#7C3AED"/>
            <rect x="450" y="332" width="46" height="6" rx="3" fill="#38bdf8"/>
            <rect x="424" y="346" width="60" height="6" rx="3" fill="#334155"/>
            <rect x="438" y="360" width="34" height="6" rx="3" fill="#22c55e"/>
            <rect x="478" y="360" width="40" height="6" rx="3" fill="#334155"/>
        </g>

        {{-- decorative sparkles --}}
        <g fill="url(#hgAccent)">
            <circle cx="540" cy="250" r="6" opacity="0.5"/>
            <circle cx="120" cy="160" r="5" opacity="0.4"/>
            <circle cx="300" cy="96" r="4" opacity="0.5"/>
        </g>
        <path d="M556 300l4 10 10 4-10 4-4 10-4-10-10-4 10-4z" fill="#7C3AED" opacity="0.5"/>
        <path d="M96 400l3 8 8 3-8 3-3 8-3-8-8-3 8-3z" fill="#06B6D4" opacity="0.5"/>
    </svg>
</div>
