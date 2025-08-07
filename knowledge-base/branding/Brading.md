
<html lang="pt-BR" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Eloscope - Sistema de Identidade Visual</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@300;400&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: "Inter", sans-serif;
      background: #0a0a0a;
      color: #ffffff;
      line-height: 1.6;
    }
    .logo-wordmark {
      font-family: "Syne", sans-serif;
      letter-spacing: 0.08em;
      background: linear-gradient(90deg, #00ffff 0%, #ffffff 50%, #ff00ff 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .section-title {
      font-family: "Syne", sans-serif;
      position: relative;
      padding-left: 1.5rem;
    }
    .section-title::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 0.25rem;
      background: linear-gradient(180deg, #00ffff, #ff00ff);
      border-radius: 9999px;
    }
    .font-name {
      font-family: "JetBrains Mono", monospace;
      color: #00ffff;
    }
    .font-weight {
      font-family: "JetBrains Mono", monospace;
      color: #666666;
      min-width: 5rem;
    }
    .variant-label {
      font-family: "JetBrains Mono", monospace;
      font-size: 0.75rem;
      color: #666666;
      margin-top: 0.375rem;
      user-select: none;
    }
    .app-label {
      font-family: "JetBrains Mono", monospace;
      font-size: 0.75rem;
      color: #666666;
      position: absolute;
      top: 0.375rem;
      left: 0.375rem;
      user-select: none;
    }
    .orbit-ring {
      position: absolute;
      border: 2px solid;
      border-radius: 9999px;
      opacity: 0.3;
      animation-timing-function: linear;
      animation-iteration-count: infinite;
      animation-name: rotate;
    }
    .orbit-ring:nth-child(1) {
      inset: 0;
      border-color: #00ffff;
      animation-duration: 20s;
      animation-direction: normal;
    }
    .orbit-ring:nth-child(2) {
      inset: 1.25rem;
      border-color: #ff00ff;
      animation-duration: 15s;
      animation-direction: reverse;
    }
    .orbit-ring:nth-child(3) {
      inset: 2.5rem;
      border-color: #00ff00;
      animation-duration: 10s;
      animation-direction: normal;
    }
    .logo-center {
      position: absolute;
      inset: 3.75rem;
      background: linear-gradient(135deg, #00ffff, #ff00ff);
      border-radius: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Syne", sans-serif;
      font-size: 3rem;
      font-weight: 600;
      color: #0a0a0a;
      user-select: none;
    }
    @keyframes rotate {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>
<body>
  <main>
    <section
      class="relative flex min-h-screen items-center justify-center overflow-hidden bg-black/0 text-center"
    >
      <div
        class="absolute inset-0 -z-10 animate-pulse scale-[1] opacity-50 blur-[100px] sm:scale-[1.1] sm:opacity-80"
        style="
          background:
            radial-gradient(circle at 30% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 70% 50%, rgba(255, 0, 255, 0.1) 0%, transparent 50%);
          animation: pulse 8s ease-in-out infinite;
        "
      ></div>
      <div class="relative z-10 max-w-4xl px-6">
        <h1
          class="logo-wordmark text-6xl font-medium leading-tight sm:text-[4.5rem] md:text-[5.5rem] lg:text-[6rem]"
          style="margin-bottom: 0.25rem;"
        >
          ELOSCOPE
        </h1>
        <p class="text-lg font-light text-white/80 sm:text-xl">
          Sistema de Identidade Visual
        </p>
      </div>
    </section>

    <section class="container mx-auto px-6 py-16 max-w-[1400px]">
      <!-- Logo Concept -->
      <section
        class="mb-24 rounded-3xl border border-white/10 bg-white/5 p-16 text-center"
      >
        <h2
          class="section-title mb-12 text-4xl font-semibold sm:text-3xl"
          style="line-height: 1.1;"
        >
          Conceito Final da Logo
        </h2>

        <div
          class="mb-8 flex flex-col items-center justify-center gap-6 sm:flex-row sm:gap-12"
        >
          <svg
            class="h-20 w-20 flex-shrink-0"
            viewBox="0 0 100 100"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-label="Logotipo Eloscope com hexágono central e elos interconectados"
            role="img"
          >
            <defs>
              <linearGradient
                id="gradient-main"
                x1="0%"
                y1="0%"
                x2="100%"
                y2="100%"
              >
                <stop offset="0%" stop-color="#00ffff" stop-opacity="1" />
                <stop offset="50%" stop-color="#ffffff" stop-opacity="1" />
                <stop offset="100%" stop-color="#ff00ff" stop-opacity="1" />
              </linearGradient>
            </defs>
            <polygon
              points="50,10 80,30 80,70 50,90 20,70 20,30"
              stroke="url(#gradient-main)"
              stroke-width="2"
              fill="none"
            />
            <circle
              cx="50"
              cy="30"
              r="12"
              stroke="#00ffff"
              stroke-width="1.5"
              fill="none"
              opacity="0.7"
            />
            <circle
              cx="35"
              cy="50"
              r="12"
              stroke="#ff00ff"
              stroke-width="1.5"
              fill="none"
              opacity="0.7"
            />
            <circle
              cx="65"
              cy="50"
              r="12"
              stroke="#00ff00"
              stroke-width="1.5"
              fill="none"
              opacity="0.7"
            />
            <circle
              cx="50"
              cy="70"
              r="12"
              stroke="#ffff00"
              stroke-width="1.5"
              fill="none"
              opacity="0.7"
            />
            <circle cx="50" cy="50" r="8" fill="url(#gradient-main)" />
          </svg>
          <span
            class="logo-wordmark text-5xl font-medium sm:text-4xl"
            style="user-select:none;"
            >ELOSCOPE</span
          >
        </div>

        <p class="mx-auto max-w-xl text-white/70 text-base sm:text-lg leading-relaxed">
          A logo combina a geometria hexagonal (visão de 360°) com elos
          interconectados, representando a união de múltiplas inteligências em
          uma visão unificada.
        </p>
      </section>

      <!-- Typography -->
      <section class="mb-24">
        <h2
          class="section-title mb-12 text-4xl font-semibold sm:text-3xl"
          style="line-height: 1.1;"
        >
          Sistema Tipográfico
        </h2>

        <div class="grid gap-12 md:grid-cols-3">
          <!-- Primary Font -->
          <article
            class="rounded-xl border border-white/10 bg-white/5 p-8"
            aria-label="Fonte primária Syne"
          >
            <div class="font-name mb-4 text-sm font-semibold">FONTE PRIMÁRIA: Syne</div>
            <div class="space-y-6">
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Display</span>
                <span
                  class="select-text"
                  style="font-family: 'Syne', sans-serif; font-size: 3rem; font-weight: 700;"
                  >Eloscope Platform</span
                >
              </div>
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Headline</span>
                <span
                  class="select-text"
                  style="font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 600;"
                  >Conectando Inteligências</span
                >
              </div>
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Regular</span>
                <span
                  class="select-text"
                  style="font-family: 'Syne', sans-serif; font-size: 1.125rem; font-weight: 400;"
                  >Transforme dados em decisões estratégicas</span
                >
              </div>
            </div>
            <p class="mt-4 text-sm text-white/70">
              Uso: Logo, títulos, headlines. Transmite modernidade e tecnologia
              com personalidade única.
            </p>
          </article>

          <!-- Secondary Font -->
          <article
            class="rounded-xl border border-white/10 bg-white/5 p-8"
            aria-label="Fonte secundária Inter"
          >
            <div class="font-name mb-4 text-sm font-semibold">FONTE SECUNDÁRIA: Inter</div>
            <div class="space-y-6">
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Light</span>
                <span
                  class="select-text"
                  style="font-family: 'Inter', sans-serif; font-size: 1.5rem; font-weight: 300;"
                  >Visão amplificada do seu negócio</span
                >
              </div>
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Regular</span>
                <span
                  class="select-text"
                  style="font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 400;"
                  >A plataforma que integra todas as dimensões do seu negócio através de inteligência artificial avançada.</span
                >
              </div>
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Medium</span>
                <span
                  class="select-text"
                  style="font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 500;"
                  >Descubra conexões. Amplie resultados.</span
                >
              </div>
            </div>
            <p class="mt-4 text-sm text-white/70">
              Uso: Corpo de texto, interfaces, documentos. Altíssima legibilidade
              em todos os tamanhos.
            </p>
          </article>

          <!-- Monospace Font -->
          <article
            class="rounded-xl border border-white/10 bg-white/5 p-8"
            aria-label="Fonte técnica JetBrains Mono"
          >
            <div class="font-name mb-4 text-sm font-semibold">FONTE TÉCNICA: JetBrains Mono</div>
            <div class="space-y-6">
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Light</span>
                <span
                  class="select-text"
                  style="font-family: 'JetBrains Mono', monospace; font-size: 1rem; font-weight: 300;"
                  >system.connect(intelligence)</span
                >
              </div>
              <div class="flex items-baseline gap-8">
                <span class="font-weight select-none">Regular</span>
                <span
                  class="select-text"
                  style="font-family: 'JetBrains Mono', monospace; font-size: 0.875rem; font-weight: 400;"
                  >ROI: +347% | Conversão: 89% | Tempo: -73%</span
                >
              </div>
            </div>
            <p class="mt-4 text-sm text-white/70">
              Uso: Dados, métricas, elementos técnicos. Reforça o aspecto
              tecnológico e preciso.
            </p>
          </article>
        </div>
      </section>

      <!-- Color System -->
      <section class="mb-24">
        <h2
          class="section-title mb-12 text-4xl font-semibold sm:text-3xl"
          style="line-height: 1.1;"
        >
          Sistema de Cores
        </h2>

        <div
          class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6"
        >
          <article
            class="group rounded-xl overflow-hidden transition-transform hover:-translate-y-2"
            aria-label="Cor Deep Space Black"
          >
            <div
              class="color-swatch flex h-30 items-center justify-center rounded-t-xl border border-[#333] bg-[#0a0a0a] font-mono text-sm font-light text-white"
              style="height: 7.5rem;"
            >
              #0A0A0A
            </div>
            <div
              class="color-info rounded-b-xl bg-white/5 p-6 text-sm text-white/80"
            >
              <h3 class="color-name mb-1 font-semibold">Deep Space Black</h3>
              <p class="color-usage">Background principal, textos em negativo</p>
            </div>
          </article>

          <article
            class="group rounded-xl overflow-hidden transition-transform hover:-translate-y-2"
            aria-label="Cor Pure White"
          >
            <div
              class="color-swatch flex h-30 items-center justify-center rounded-t-xl border border-white bg-white font-mono text-sm font-light text-[#0a0a0a]"
              style="height: 7.5rem;"
            >
              #FFFFFF
            </div>
            <div
              class="color-info rounded-b-xl bg-white/5 p-6 text-sm text-white/80"
            >
              <h3 class="color-name mb-1 font-semibold">Pure White</h3>
              <p class="color-usage">Textos principais, elementos de destaque</p>
            </div>
          </article>

          <article
            class="group rounded-xl overflow-hidden transition-transform hover:-translate-y-2"
            aria-label="Cor Quantum Cyan"
          >
            <div
              class="color-swatch flex h-30 items-center justify-center rounded-t-xl bg-cyan-400 font-mono text-sm font-light text-[#0a0a0a]"
              style="height: 7.5rem;"
            >
              #00FFFF
            </div>
            <div
              class="color-info rounded-b-xl bg-white/5 p-6 text-sm text-white/80"
            >
              <h3 class="color-name mb-1 font-semibold">Quantum Cyan</h3>
              <p class="color-usage">Cor primária, CTAs, destaques tech</p>
            </div>
          </article>

          <article
            class="group rounded-xl overflow-hidden transition-transform hover:-translate-y-2"
            aria-label="Cor Fusion Magenta"
          >
            <div
              class="color-swatch flex h-30 items-center justify-center rounded-t-xl bg-pink-500 font-mono text-sm font-light text-white"
              style="height: 7.5rem;"
            >
              #FF00FF
            </div>
            <div
              class="color-info rounded-b-xl bg-white/5 p-6 text-sm text-white/80"
            >
              <h3 class="color-name mb-1 font-semibold">Fusion Magenta</h3>
              <p class="color-usage">Acentos, gradientes, elementos criativos</p>
            </div>
          </article>

          <article
            class="group rounded-xl overflow-hidden transition-transform hover:-translate-y-2"
            aria-label="Signature Gradient"
          >
            <div
              class="color-swatch flex h-30 items-center justify-center rounded-t-xl bg-gradient-to-r from-cyan-400 to-pink-500 font-mono text-sm font-light text-white"
              style="height: 7.5rem;"
            >
              Gradient
            </div>
            <div
              class="color-info rounded-b-xl bg-white/5 p-6 text-sm text-white/80"
            >
              <h3 class="color-name mb-1 font-semibold">Signature Gradient</h3>
              <p class="color-usage">Logo, elementos premium, hover states</p>
            </div>
          </article>

          <article
            class="group rounded-xl overflow-hidden transition-transform hover:-translate-y-2"
            aria-label="Glass Effect"
          >
            <div
              class="color-swatch flex h-30 items-center justify-center rounded-t-xl border border-white/20 bg-white/10 font-mono text-sm font-light text-white"
              style="height: 7.5rem;"
            >
              10% White
            </div>
            <div
              class="color-info rounded-b-xl bg-white/5 p-6 text-sm text-white/80"
            >
              <h3 class="color-name mb-1 font-semibold">Glass Effect</h3>
              <p class="color-usage">Backgrounds sutis, cards, overlays</p>
            </div>
          </article>
        </div>
      </section>

      <!-- Logo Variations -->
      <section class="mb-24">
        <h2
          class="section-title mb-12 text-4xl font-semibold sm:text-3xl"
          style="line-height: 1.1;"
        >
          Variações da Logo
        </h2>

        <div
          class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3"
          role="list"
        >
          <article
            class="logo-variant flex flex-col items-center justify-center rounded-xl border border-white/10 bg-white/5 p-8 transition hover:bg-white/10 hover:border-cyan-400/50"
            role="listitem"
            aria-label="Ícone mínimo da logo Eloscope"
          >
            <svg
              width="60"
              height="60"
              viewBox="0 0 100 100"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true"
              focusable="false"
            >
              <polygon
                points="50,10 80,30 80,70 50,90 20,70 20,30"
                stroke="#ffffff"
                stroke-width="2"
                fill="none"
              />
              <circle cx="50" cy="50" r="8" fill="#ffffff" />
            </svg>
            <span class="variant-label select-none">Ícone Mínimo</span>
          </article>

          <article
            class="logo-variant flex flex-col items-center justify-center rounded-xl border border-white/10 bg-white/5 p-8 transition hover:bg-white/10 hover:border-cyan-400/50"
            role="listitem"
            aria-label="Wordmark solo da logo Eloscope"
          >
            <span
              class="select-none"
              style="
                font-family: 'Syne', sans-serif;
                font-size: 2rem;
                font-weight: 500;
                letter-spacing: 0.08em;
                user-select:none;
              "
              >ELOSCOPE</span
            >
            <span class="variant-label select-none">Wordmark Solo</span>
          </article>

          <article
            class="logo-variant flex flex-col items-center justify-center rounded-xl border border-white/10 bg-white p-8 transition hover:bg-white/90 hover:border-cyan-400/50"
            role="listitem"
            aria-label="Versão invertida da logo Eloscope"
          >
            <svg
              width="60"
              height="60"
              viewBox="0 0 100 100"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true"
              focusable="false"
            >
              <polygon
                points="50,10 80,30 80,70 50,90 20,70 20,30"
                stroke="#0a0a0a"
                stroke-width="2"
                fill="none"
              />
              <circle cx="50" cy="50" r="8" fill="#0a0a0a" />
            </svg>
            <span
              class="variant-label select-none text-[#0a0a0a]"
              >Versão Invertida</span
            >
          </article>
        </div>
      </section>

      <!-- Applications -->
      <section class="mb-24">
        <h2
          class="section-title mb-12 text-4xl font-semibold sm:text-3xl"
          style="line-height: 1.1;"
        >
          Aplicações
        </h2>

        <div
          class="app-grid grid grid-cols-1 gap-8 sm:grid-cols-2 max-w-5xl mx-auto"
          role="list"
        >
          <article
            class="app-mockup relative flex h-72 flex-col items-center justify-center rounded-xl border border-white/10 bg-white/5 p-8"
            role="listitem"
            aria-label="Mockup de cartão de visita Eloscope"
          >
            <span class="app-label">Business Card</span>
            <div
              class="flex h-[180px] w-[300px] flex-col justify-between rounded-lg border border-white/10 bg-[#0a0a0a] p-6"
            >
              <span
                class="font-syne text-2xl font-medium tracking-widest text-white"
                >ELOSCOPE</span
              >
              <div class="space-y-1 text-white/70">
                <div class="text-base font-normal">João Silva</div>
                <div
                  class="font-jetbrains-mono text-xs font-normal text-cyan-400"
                  >Growth Architect</div
                >
              </div>
            </div>
          </article>

          <article
            class="app-mockup relative flex h-72 flex-col items-center justify-center rounded-xl border border-white/10 bg-white/5 p-8"
            role="listitem"
            aria-label="Mockup de ícone de app Eloscope"
          >
            <span class="app-label">App Icon</span>
            <div
              class="flex h-30 w-30 items-center justify-center rounded-[28px] bg-gradient-to-br from-cyan-400 to-pink-500"
              style="width: 120px; height: 120px;"
            >
              <span
                class="font-syne text-[60px] font-semibold text-[#0a0a0a]"
                >E</span
              >
            </div>
          </article>
        </div>
      </section>

      <!-- Final Logo Animation -->
      <section aria-label="Animação final da logo Eloscope" class="mb-24">
        <div
          class="final-logo relative mx-auto h-[200px] w-[200px]"
          role="img"
          aria-labelledby="finalLogoTitle"
        >
          <h3 id="finalLogoTitle" class="sr-only">Animação final da logo Eloscope</h3>
          <div class="orbit-ring"></div>
          <div class="orbit-ring"></div>
          <div class="orbit-ring"></div>
          <div class="logo-center select-none">E</div>
        </div>
      </section>
    </section>
  </main>
</body>
</html>