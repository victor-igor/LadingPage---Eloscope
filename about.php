<?php
/**
 * About Us Page - Eloscope
 * Página sobre a empresa e equipe
 */

session_start();

// Load environment variables
if (file_exists('.env')) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, '"\' ');
        }
    }
}

// Site configuration
$site_name = $_ENV['SITE_NAME'] ?? 'Eloscope';
$site_url = $_ENV['SITE_URL'] ?? 'https://eloscope.com';
$ga_id = $_ENV['GOOGLE_ANALYTICS_ID'] ?? '';
$fb_pixel_id = $_ENV['FACEBOOK_PIXEL_ID'] ?? '';
$hotjar_id = $_ENV['HOTJAR_ID'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - <?php echo $site_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Conheça a Eloscope: especialistas em automação inteligente com IA. Nossa missão é transformar negócios através da tecnologia e inovação.">
    <meta name="keywords" content="sobre eloscope, equipe, automação, IA, inteligência artificial, empresa, missão, visão">
    <meta name="author" content="<?php echo $site_name; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $site_url; ?>/about">
    <meta property="og:title" content="Sobre Nós - <?php echo $site_name; ?>">
    <meta property="og:description" content="Conheça a Eloscope: especialistas em automação inteligente com IA.">
    <meta property="og:image" content="<?php echo $site_url; ?>/assets/images/og-about.jpg">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $site_url; ?>/about">
    <meta property="twitter:title" content="Sobre Nós - <?php echo $site_name; ?>">
    <meta property="twitter:description" content="Conheça a Eloscope: especialistas em automação inteligente com IA.">
    <meta property="twitter:image" content="<?php echo $site_url; ?>/assets/images/og-about.jpg">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'syne': ['Syne', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        'neon-cyan': '#00ffff',
                        'neon-magenta': '#ff00ff',
                        'dark-bg': '#0a0a0a',
                        'dark-card': '#1a1a1a'
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Analytics -->
    <?php if ($ga_id): ?>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_id; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $ga_id; ?>');
    </script>
    <?php endif; ?>
    
    <?php if ($fb_pixel_id): ?>
    <!-- Facebook Pixel -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?php echo $fb_pixel_id; ?>');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_pixel_id; ?>&ev=PageView&noscript=1"/></noscript>
    <?php endif; ?>
    
    <?php if ($hotjar_id): ?>
    <!-- Hotjar -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:<?php echo $hotjar_id; ?>,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    <?php endif; ?>
</head>

<body class="bg-dark-bg text-white font-inter overflow-x-hidden">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-dark-bg/90 backdrop-blur-md border-b border-white/10">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-lg flex items-center justify-center">
                        <span class="text-dark-bg font-bold text-sm">E</span>
                    </div>
                    <span class="font-syne font-bold text-xl">ELOSCOPE</span>
                </a>
                
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="/" class="hover:text-neon-cyan transition-colors">Início</a>
                    <a href="/about" class="text-neon-cyan font-semibold">Sobre</a>
                    <a href="/#cases" class="hover:text-neon-cyan transition-colors">Cases</a>
                    <a href="/blog" class="hover:text-neon-cyan transition-colors">Blog</a>
                    <a href="/#contact" class="hover:text-neon-cyan transition-colors">Contato</a>
                </nav>
                
                <a href="/whatsapp" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                    💬 WhatsApp
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20">
        <!-- Hero Section -->
        <section class="py-20 relative overflow-hidden">
            <!-- Background Effects -->
            <div class="absolute inset-0 bg-gradient-to-br from-neon-cyan/5 via-transparent to-neon-magenta/5"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-neon-cyan/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-neon-magenta/10 rounded-full blur-3xl"></div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center max-w-4xl mx-auto">
                    <div class="inline-flex items-center bg-gradient-to-r from-neon-cyan/20 to-neon-magenta/20 rounded-full px-6 py-3 mb-8">
                        <span class="w-2 h-2 bg-neon-cyan rounded-full mr-3 animate-pulse"></span>
                        <span class="font-medium">Especialistas em Automação Inteligente</span>
                    </div>
                    
                    <h1 class="font-syne font-bold text-4xl md:text-6xl lg:text-7xl mb-8 leading-tight">
                        Somos a 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Eloscope
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed">
                        Transformamos negócios através da <strong class="text-neon-cyan">automação inteligente</strong>, 
                        combinando IA avançada com estratégias personalizadas para gerar 
                        <strong class="text-neon-magenta">resultados extraordinários</strong>.
                    </p>
                    
                    <div class="grid md:grid-cols-3 gap-8 mt-16">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-neon-cyan mb-2" data-counter="500">0</div>
                            <div class="text-gray-400">Empresas Transformadas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-neon-magenta mb-2" data-counter="300">0</div>
                            <div class="text-gray-400">% Aumento Médio em Vendas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-neon-cyan mb-2" data-counter="98">0</div>
                            <div class="text-gray-400">% Taxa de Satisfação</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Mission & Vision Section -->
        <section class="py-20 bg-gradient-to-b from-transparent to-dark-card/50">
            <div class="container mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Mission -->
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-8 border border-white/10">
                        <div class="w-16 h-16 bg-gradient-to-r from-neon-cyan to-blue-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h2 class="font-syne font-bold text-3xl mb-6">
                            Nossa 
                            <span class="bg-gradient-to-r from-neon-cyan to-blue-500 bg-clip-text text-transparent">
                                Missão
                            </span>
                        </h2>
                        <p class="text-lg text-gray-300 leading-relaxed">
                            Democratizar o acesso à automação inteligente, capacitando empresas de todos os tamanhos 
                            a alcançarem <strong class="text-neon-cyan">liberdade operacional</strong> e 
                            <strong class="text-neon-cyan">crescimento exponencial</strong> através da tecnologia de IA.
                        </p>
                    </div>
                    
                    <!-- Vision -->
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-8 border border-white/10">
                        <div class="w-16 h-16 bg-gradient-to-r from-neon-magenta to-purple-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h2 class="font-syne font-bold text-3xl mb-6">
                            Nossa 
                            <span class="bg-gradient-to-r from-neon-magenta to-purple-500 bg-clip-text text-transparent">
                                Visão
                            </span>
                        </h2>
                        <p class="text-lg text-gray-300 leading-relaxed">
                            Ser a referência global em automação inteligente para negócios, criando um futuro onde 
                            <strong class="text-neon-magenta">toda empresa</strong> tenha acesso a soluções de IA 
                            <strong class="text-neon-magenta">personalizadas e eficientes</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Values Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Nossos 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Valores
                        </span>
                    </h2>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        Os princípios que guiam cada decisão e moldam nossa cultura organizacional
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Value 1 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-cyan/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Excelência</h3>
                        <p class="text-gray-300">Buscamos a perfeição em cada projeto, entregando soluções que superam expectativas e geram resultados extraordinários.</p>
                    </div>
                    
                    <!-- Value 2 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-magenta/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Colaboração</h3>
                        <p class="text-gray-300">Trabalhamos como parceiros dos nossos clientes, construindo relacionamentos duradouros baseados em confiança mútua.</p>
                    </div>
                    
                    <!-- Value 3 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-purple-500/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Inovação</h3>
                        <p class="text-gray-300">Estamos sempre na vanguarda da tecnologia, explorando novas possibilidades para revolucionar negócios.</p>
                    </div>
                    
                    <!-- Value 4 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-yellow-500/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Transparência</h3>
                        <p class="text-gray-300">Mantemos comunicação clara e honesta, garantindo que nossos clientes estejam sempre informados sobre cada etapa.</p>
                    </div>
                    
                    <!-- Value 5 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-red-500/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-400 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Resultados</h3>
                        <p class="text-gray-300">Nosso foco é entregar valor real e mensurável, transformando investimento em automação em crescimento sustentável.</p>
                    </div>
                    
                    <!-- Value 6 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-indigo-500/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Paixão</h3>
                        <p class="text-gray-300">Amamos o que fazemos e isso se reflete na qualidade das nossas soluções e no cuidado com cada cliente.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Team Section -->
        <section class="py-20 bg-gradient-to-b from-transparent to-dark-card/50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Nossa 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Equipe
                        </span>
                    </h2>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        Especialistas apaixonados por tecnologia e comprometidos com o sucesso dos nossos clientes
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Team Member 1 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-cyan/50 transition-all duration-300 text-center group">
                        <div class="w-24 h-24 bg-gradient-to-r from-neon-cyan to-blue-500 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl font-bold text-white">CEO</span>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-2">Lucas Negreiro</h3>
                        <p class="text-neon-cyan font-semibold mb-4">CEO & Founder</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Visionário em automação e IA, com mais de 10 anos transformando negócios através da tecnologia. 
                            Especialista em estratégias de crescimento exponencial.
                        </p>
                    </div>
                    
                    <!-- Team Member 2 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-magenta/50 transition-all duration-300 text-center group">
                        <div class="w-24 h-24 bg-gradient-to-r from-neon-magenta to-purple-500 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl font-bold text-white">CTO</span>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-2">Ana Silva</h3>
                        <p class="text-neon-magenta font-semibold mb-4">CTO & Co-founder</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Especialista em IA e machine learning, responsável por desenvolver as soluções mais avançadas 
                            em automação inteligente do mercado.
                        </p>
                    </div>
                    
                    <!-- Team Member 3 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-green-500/50 transition-all duration-300 text-center group">
                        <div class="w-24 h-24 bg-gradient-to-r from-green-400 to-green-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl font-bold text-white">CS</span>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-2">Carlos Santos</h3>
                        <p class="text-green-400 font-semibold mb-4">Head of Customer Success</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Dedicado ao sucesso dos clientes, garantindo que cada implementação gere os resultados 
                            esperados e supere as expectativas.
                        </p>
                    </div>
                    
                    <!-- Team Member 4 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-yellow-500/50 transition-all duration-300 text-center group">
                        <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl font-bold text-white">DEV</span>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-2">Marina Costa</h3>
                        <p class="text-yellow-400 font-semibold mb-4">Lead Developer</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Arquiteta das soluções técnicas mais complexas, transformando ideias em sistemas 
                            robustos e escaláveis.
                        </p>
                    </div>
                    
                    <!-- Team Member 5 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-blue-500/50 transition-all duration-300 text-center group">
                        <div class="w-24 h-24 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl font-bold text-white">AI</span>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-2">Roberto Lima</h3>
                        <p class="text-blue-400 font-semibold mb-4">AI Specialist</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Especialista em inteligência artificial, criando agentes inteligentes que revolucionam 
                            a forma como empresas interagem com clientes.
                        </p>
                    </div>
                    
                    <!-- Team Member 6 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-purple-500/50 transition-all duration-300 text-center group">
                        <div class="w-24 h-24 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl font-bold text-white">UX</span>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-2">Fernanda Oliveira</h3>
                        <p class="text-purple-400 font-semibold mb-4">UX/UI Designer</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Criadora de experiências excepcionais, garantindo que cada interação seja intuitiva 
                            e envolvente para os usuários finais.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Technology Stack Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Nossa 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Stack Tecnológica
                        </span>
                    </h2>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        Utilizamos as tecnologias mais avançadas para criar soluções robustas e escaláveis
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Tech 1 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-neon-cyan/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-400 to-orange-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">N8N</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">N8N</h3>
                        <p class="text-gray-400 text-sm">Automação de workflows</p>
                    </div>
                    
                    <!-- Tech 2 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-neon-magenta/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">AI</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">OpenAI</h3>
                        <p class="text-gray-400 text-sm">Inteligência Artificial</p>
                    </div>
                    
                    <!-- Tech 3 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-green-500/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-700 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">WA</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">WhatsApp API</h3>
                        <p class="text-gray-400 text-sm">Comunicação automatizada</p>
                    </div>
                    
                    <!-- Tech 4 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-blue-500/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">GHL</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">Go HighLevel</h3>
                        <p class="text-gray-400 text-sm">CRM e automação</p>
                    </div>
                    
                    <!-- Tech 5 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-purple-500/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">EVO</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">Evolution API</h3>
                        <p class="text-gray-400 text-sm">Integração WhatsApp</p>
                    </div>
                    
                    <!-- Tech 6 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-yellow-500/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">JS</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">JavaScript</h3>
                        <p class="text-gray-400 text-sm">Desenvolvimento web</p>
                    </div>
                    
                    <!-- Tech 7 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-red-500/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-400 to-red-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">PY</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">Python</h3>
                        <p class="text-gray-400 text-sm">Machine Learning</p>
                    </div>
                    
                    <!-- Tech 8 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-indigo-500/50 transition-all duration-300 text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">AWS</span>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">AWS Cloud</h3>
                        <p class="text-gray-400 text-sm">Infraestrutura escalável</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-neon-cyan/10 via-transparent to-neon-magenta/10">
            <div class="container mx-auto px-4 text-center">
                <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Pronto para 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Transformar
                    </span>
                    seu Negócio?
                </h2>
                
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Junte-se às centenas de empresas que já revolucionaram seus processos com nossa automação inteligente.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/whatsapp" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                        💬 Conversar no WhatsApp
                    </a>
                    <a href="/#contact" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                        📧 Enviar Email
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-card border-t border-white/10 py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-lg flex items-center justify-center">
                            <span class="text-dark-bg font-bold text-sm">E</span>
                        </div>
                        <span class="font-syne font-bold text-xl">ELOSCOPE</span>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        Transformamos negócios através da automação inteligente com IA, 
                        gerando resultados extraordinários e liberdade operacional.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-syne font-bold text-lg mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-neon-cyan transition-colors">Início</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-neon-cyan transition-colors">Sobre</a></li>
                        <li><a href="/#cases" class="text-gray-400 hover:text-neon-cyan transition-colors">Cases</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-neon-cyan transition-colors">Blog</a></li>
                        <li><a href="/#contact" class="text-gray-400 hover:text-neon-cyan transition-colors">Contato</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-syne font-bold text-lg mb-4">Contato</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>📧 contato@eloscope.com</li>
                        <li>📱 (11) 99999-9999</li>
                        <li>🌐 eloscope.com</li>
                        <li>📍 São Paulo, SP</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Eloscope. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="/assets/js/main.js"></script>
    
    <script>
        // Counter animation
        function animateCounters() {
            const counters = document.querySelectorAll('[data-counter]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-counter'));
                const duration = 2000; // 2 seconds
                const step = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current);
                }, 16);
            });
        }
        
        // Trigger counter animation when in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.disconnect();
                }
            });
        });
        
        const statsSection = document.querySelector('[data-counter]')?.closest('section');
        if (statsSection) {
            observer.observe(statsSection);
        }
        
        // Track page view
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'About Us',
                'page_location': window.location.href
            });
        }
        
        if (typeof fbq !== 'undefined') {
            fbq('track', 'ViewContent', {
                content_name: 'About Us Page',
                content_category: 'company_info'
            });
        }
    </script>
</body>
</html>