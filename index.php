<?php
/**
 * Homepage - Eloscope
 * Página principal do site Eloscope
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
$whatsapp_number = $_ENV['WHATSAPP_NUMBER'] ?? '';
$ga_id = $_ENV['GOOGLE_ANALYTICS_ID'] ?? '';
$fb_pixel_id = $_ENV['FACEBOOK_PIXEL_ID'] ?? '';
$hotjar_id = $_ENV['HOTJAR_ID'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?> - Transformação Digital com IA para Empresas</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Transforme sua empresa com soluções de IA personalizadas. Automação inteligente, chatbots avançados e integração de sistemas para maximizar resultados.">
    <meta name="keywords" content="inteligência artificial, automação, chatbots, transformação digital, IA empresarial, automação de processos">
    <meta name="author" content="Eloscope">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $site_url; ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $site_name; ?> - Transformação Digital com IA">
    <meta property="og:description" content="Transforme sua empresa com soluções de IA personalizadas. Automação inteligente, chatbots avançados e integração de sistemas.">
    <meta property="og:image" content="<?php echo $site_url; ?>/assets/images/og-image.jpg">
    <meta property="og:url" content="<?php echo $site_url; ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $site_name; ?> - Transformação Digital com IA">
    <meta name="twitter:description" content="Transforme sua empresa com soluções de IA personalizadas. Automação inteligente, chatbots avançados e integração de sistemas.">
    <meta name="twitter:image" content="<?php echo $site_url; ?>/assets/images/og-image.jpg">
    
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
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?php echo $site_name; ?>",
        "url": "<?php echo $site_url; ?>",
        "logo": "<?php echo $site_url; ?>/assets/images/logo.png",
        "description": "Transformação Digital com IA para Empresas",
        "sameAs": [
            "https://www.linkedin.com/company/eloscope",
            "https://www.instagram.com/eloscope"
        ]
    }
    </script>
</head>

<body class="bg-dark-bg text-white font-inter overflow-x-hidden">
    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-dark-bg z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center animate-pulse">
                <img src="assets/images/EloScope - Logo.png" alt="Eloscope Logo" class="h-12 w-auto">
            </div>
            <div class="loading-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-dark-bg/90 backdrop-blur-md border-b border-white/10 transition-all duration-300" id="header">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="assets/images/EloScope - Logo.png" alt="Eloscope Logo" class="h-10 w-auto group-hover:scale-110 transition-transform duration-300">
                    <span class="font-syne font-bold text-2xl">ELOSCOPE</span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="nav-link hover:text-neon-cyan transition-colors duration-300">Início</a>
                    <a href="#services" class="nav-link hover:text-neon-cyan transition-colors duration-300">Serviços</a>
                    <a href="#about" class="nav-link hover:text-neon-cyan transition-colors duration-300">Sobre</a>
                    <a href="#cases" class="nav-link hover:text-neon-cyan transition-colors duration-300">Casos</a>
                    <a href="#contact" class="nav-link hover:text-neon-cyan transition-colors duration-300">Contato</a>
                    <a href="/blog" class="nav-link hover:text-neon-cyan transition-colors duration-300">Blog</a>
                </nav>
                
                <!-- CTA Button -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/whatsapp" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 cta-button">
                        💬 Falar Conosco
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" id="mobile-menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="py-4 space-y-4">
                    <a href="#home" class="block nav-link hover:text-neon-cyan transition-colors duration-300">Início</a>
                    <a href="#services" class="block nav-link hover:text-neon-cyan transition-colors duration-300">Serviços</a>
                    <a href="#about" class="block nav-link hover:text-neon-cyan transition-colors duration-300">Sobre</a>
                    <a href="#cases" class="block nav-link hover:text-neon-cyan transition-colors duration-300">Casos</a>
                    <a href="#contact" class="block nav-link hover:text-neon-cyan transition-colors duration-300">Contato</a>
                    <a href="/blog" class="block nav-link hover:text-neon-cyan transition-colors duration-300">Blog</a>
                    <a href="/whatsapp" class="block bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-6 py-3 rounded-lg font-semibold text-center mt-4">
                        💬 Falar Conosco
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-neon-cyan/5 via-transparent to-neon-magenta/5"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-neon-cyan/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-neon-magenta/10 rounded-full blur-3xl animate-float-delayed"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Hero Badge -->
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full px-6 py-2 mb-8 border border-white/20">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-medium">🚀 Transformação Digital com IA</span>
                </div>
                
                <!-- Hero Title -->
                <h1 class="font-syne font-bold text-4xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                    Revolucione Seu 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Negócio
                    </span>
                    <br>com Inteligência Artificial
                </h1>
                
                <!-- Hero Subtitle -->
                <p class="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed max-w-3xl mx-auto">
                    Automatize processos, otimize resultados e transforme sua empresa com soluções de IA personalizadas. 
                    <span class="text-neon-cyan font-semibold">Resultados comprovados em 30 dias.</span>
                </p>
                
                <!-- Hero CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                    <a href="#contact" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300 cta-button">
                        🚀 Começar Agora
                    </a>
                    <a href="#services" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                        📋 Ver Soluções
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-neon-cyan mb-1" data-counter="500">0</div>
                        <div class="text-sm text-gray-400">Empresas Atendidas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-neon-magenta mb-1" data-counter="95">0</div>
                        <div class="text-sm text-gray-400">% Satisfação</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-neon-cyan mb-1" data-counter="24">0</div>
                        <div class="text-sm text-gray-400">Horas Suporte</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-neon-magenta mb-1" data-counter="30">0</div>
                        <div class="text-sm text-gray-400">Dias Resultado</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Elo Solutions Section -->
    <section id="services" class="py-20 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full px-6 py-2 mb-6 border border-white/20">
                    <span class="text-sm font-medium">⚡ Ecossistema Elo</span>
                </div>
                <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Soluções 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Inteligentes
                    </span>
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Agentes de IA especializados que trabalham 24/7 para transformar cada área do seu negócio
                </p>
            </div>
            
            <!-- Elo Cards Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Elo Marketing -->
                <div class="group relative">
                    <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-md rounded-3xl p-8 border border-gray-800/50 hover:border-yellow-500/30 transition-all duration-500 h-full">
                        <!-- Header -->
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold text-lg">⚡ Elo Marketing</h3>
                        </div>
                        
                        <!-- Content -->
                        <p class="text-gray-300 text-base leading-relaxed mb-8">
                            Alcance mais com menos esforço. Um Agente de IA aumentará a visibilidade da sua marca gerenciando o marketing.
                        </p>
                        
                        <!-- CTA -->
                        <div class="flex items-center text-yellow-400 hover:text-yellow-300 transition-colors duration-300 cursor-pointer group-hover:translate-x-1">
                            <span class="font-medium mr-2">Conhecer assinatura</span>
                            <div class="w-8 h-8 bg-yellow-400/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Decorative Element -->
                        <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-yellow-400/10 to-orange-500/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>
                
                <!-- Elo Sales -->
                <div class="group relative">
                    <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-md rounded-3xl p-8 border border-gray-800/50 hover:border-green-500/30 transition-all duration-500 h-full">
                        <!-- Header -->
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold text-lg">⚡ Elo Sales</h3>
                        </div>
                        
                        <!-- Content -->
                        <p class="text-gray-300 text-base leading-relaxed mb-8">
                            Melhore suas taxas de conversão e experiência do cliente com o suporte e atendimento de um Agente de IA.
                        </p>
                        
                        <!-- CTA -->
                        <div class="flex items-center text-green-400 hover:text-green-300 transition-colors duration-300 cursor-pointer group-hover:translate-x-1">
                            <span class="font-medium mr-2">Conhecer assinatura</span>
                            <div class="w-8 h-8 bg-green-400/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Decorative Element -->
                        <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-green-400/10 to-emerald-500/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>
                
                <!-- Elo Automation -->
                <div class="group relative">
                    <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-md rounded-3xl p-8 border border-gray-800/50 hover:border-blue-500/30 transition-all duration-500 h-full">
                        <!-- Header -->
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold text-lg">⚡ Elo Automation</h3>
                        </div>
                        
                        <!-- Content -->
                        <p class="text-gray-300 text-base leading-relaxed mb-8">
                            Simplifique operações e reduza erros. Um Agente de IA otimizará seu back office, processando tarefas rotineiras.
                        </p>
                        
                        <!-- CTA -->
                        <div class="flex items-center text-blue-400 hover:text-blue-300 transition-colors duration-300 cursor-pointer group-hover:translate-x-1">
                            <span class="font-medium mr-2">Conhecer assinatura</span>
                            <div class="w-8 h-8 bg-blue-400/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Decorative Element -->
                        <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-cyan-500/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Team Section -->
    <section id="ai-team" class="py-20 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full px-6 py-2 mb-6 border border-white/20">
                    <span class="text-sm font-medium">🤖 Nosso Time de IA</span>
                </div>
                <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Conheça Nossas 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Assistentes IA
                    </span>
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Cada assistente possui personalidade única e especialização específica para transformar seu negócio
                </p>
            </div>
            
            <!-- Imagem Unificada do Time de IA -->
            <div class="mt-12 flex justify-center items-center">
                <img src="assets/images/eloscope-ai-team.svg" alt="Time de IAs da Eloscope: Luna, Maya e Iris" class="max-w-full md:max-w-2xl lg:max-w-3xl h-auto">
                            <div class="flex items-center justify-center text-sm text-gray-400">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Otimização de Sistemas
                            </div>
                        </div>
                        
                        <!-- Decorative Element -->
                        <div class="absolute top-4 right-4 w-32 h-32 bg-gradient-to-br from-green-400/10 to-emerald-500/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="text-center mt-16">
                <div class="bg-gradient-to-r from-gray-900/90 to-black/90 backdrop-blur-md rounded-3xl p-8 border border-gray-800/50 max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold text-white mb-4">Pronto para conhecer nossas assistentes?</h3>
                    <p class="text-gray-300 mb-6">Cada uma delas pode transformar uma área específica do seu negócio. Vamos conversar sobre qual se encaixa melhor na sua empresa.</p>
                    <a href="#contact" class="inline-flex items-center bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                        Falar com o Time de IA
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 relative">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Content -->
                <div>
                    <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full px-6 py-2 mb-6 border border-white/20">
                        <span class="text-sm font-medium">🚀 Sobre a Eloscope</span>
                    </div>
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Pioneiros em 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            IA Empresarial
                        </span>
                    </h2>
                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        Somos especialistas em transformação digital com foco em inteligência artificial. 
                        Nossa missão é democratizar o acesso à IA, tornando-a acessível e eficaz para empresas de todos os tamanhos.
                    </p>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <div class="text-3xl font-bold text-neon-cyan mb-2" data-counter="5">0</div>
                            <div class="text-gray-400">Anos de Experiência</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-neon-magenta mb-2" data-counter="100">0</div>
                            <div class="text-gray-400">% Foco em Resultados</div>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-full flex items-center justify-center mr-4">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Soluções personalizadas para cada negócio</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-full flex items-center justify-center mr-4">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Implementação rápida e eficiente</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-full flex items-center justify-center mr-4">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Suporte técnico especializado 24/7</span>
                        </div>
                    </div>
                    
                    <a href="/about" class="inline-flex items-center bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                        Conheça Nossa História
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Visual -->
                <div class="relative">
                    <div class="bg-gradient-to-br from-neon-cyan/20 to-neon-magenta/20 rounded-3xl p-8 backdrop-blur-md border border-white/10">
                        <!-- Tech Stack Icons -->
                        <div class="grid grid-cols-3 gap-6">
                            <div class="bg-white/10 rounded-2xl p-6 text-center hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <span class="text-white font-bold">AI</span>
                                </div>
                                <div class="text-sm font-medium">Machine Learning</div>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-6 text-center hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <span class="text-white font-bold">NLP</span>
                                </div>
                                <div class="text-sm font-medium">Processamento de Linguagem</div>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-6 text-center hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <span class="text-white font-bold">CV</span>
                                </div>
                                <div class="text-sm font-medium">Visão Computacional</div>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-6 text-center hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <span class="text-white font-bold">RPA</span>
                                </div>
                                <div class="text-sm font-medium">Automação de Processos</div>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-6 text-center hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <span class="text-white font-bold">API</span>
                                </div>
                                <div class="text-sm font-medium">Integração de Sistemas</div>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-6 text-center hover:scale-105 transition-transform duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <span class="text-white font-bold">BI</span>
                                </div>
                                <div class="text-sm font-medium">Business Intelligence</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-neon-cyan/20 rounded-full blur-xl animate-pulse"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-neon-magenta/20 rounded-full blur-xl animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Cases Section -->
    <section id="cases" class="py-20 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full px-6 py-2 mb-6 border border-white/20">
                    <span class="text-sm font-medium">🏆 Casos de Sucesso</span>
                </div>
                <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Resultados que 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Impressionam
                    </span>
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Veja como nossas soluções de IA transformaram empresas reais, 
                    gerando resultados mensuráveis e impacto positivo nos negócios.
                </p>
            </div>
            
            <!-- Cases Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Case 1 -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-cyan/50 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="font-syne font-bold text-xl mb-4">E-commerce Fashion</h3>
                    <p class="text-gray-300 mb-6">Implementação de chatbot inteligente para atendimento ao cliente e recomendações personalizadas.</p>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Aumento nas Vendas</span>
                            <span class="text-neon-cyan font-bold">+180%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Redução Tempo Resposta</span>
                            <span class="text-neon-cyan font-bold">-85%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Satisfação Cliente</span>
                            <span class="text-neon-cyan font-bold">98%</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400">Setor: E-commerce | Tempo: 45 dias</div>
                </div>
                
                <!-- Case 2 -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-magenta/50 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="font-syne font-bold text-xl mb-4">Clínica Médica</h3>
                    <p class="text-gray-300 mb-6">Automação de agendamentos e triagem inicial de pacientes com IA conversacional.</p>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Redução Tempo Agendamento</span>
                            <span class="text-neon-magenta font-bold">-70%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Aumento Eficiência</span>
                            <span class="text-neon-magenta font-bold">+250%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Satisfação Pacientes</span>
                            <span class="text-neon-magenta font-bold">96%</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400">Setor: Saúde | Tempo: 30 dias</div>
                </div>
                
                <!-- Case 3 -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-green-500/50 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="font-syne font-bold text-xl mb-4">Indústria Logística</h3>
                    <p class="text-gray-300 mb-6">Sistema de análise preditiva para otimização de rotas e gestão de estoque inteligente.</p>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Redução Custos Logística</span>
                            <span class="text-green-500 font-bold">-40%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Otimização Rotas</span>
                            <span class="text-green-500 font-bold">+60%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Precisão Previsões</span>
                            <span class="text-green-500 font-bold">94%</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400">Setor: Logística | Tempo: 60 dias</div>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="text-center mt-16">
                <a href="#contact" class="inline-flex items-center bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                    Ver Mais Casos de Sucesso
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full px-6 py-2 mb-6 border border-white/20">
                    <span class="text-sm font-medium">💬 Entre em Contato</span>
                </div>
                <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Pronto para 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Transformar
                    </span>
                    <br>Seu Negócio?
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Agende uma consultoria gratuita e descubra como a IA pode revolucionar sua empresa. 
                    Nossos especialistas estão prontos para criar a solução perfeita para você.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10">
                    <h3 class="font-syne font-bold text-2xl mb-6">Solicite uma Consultoria Gratuita</h3>
                    <form id="contact-form" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2">Nome Completo *</label>
                                <input type="text" id="name" name="name" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 focus:border-neon-cyan focus:outline-none transition-colors duration-300">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2">E-mail *</label>
                                <input type="email" id="email" name="email" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 focus:border-neon-cyan focus:outline-none transition-colors duration-300">
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium mb-2">Telefone/WhatsApp *</label>
                                <input type="tel" id="phone" name="phone" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 focus:border-neon-cyan focus:outline-none transition-colors duration-300">
                            </div>
                            <div>
                                <label for="company" class="block text-sm font-medium mb-2">Empresa</label>
                                <input type="text" id="company" name="company" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 focus:border-neon-cyan focus:outline-none transition-colors duration-300">
                            </div>
                        </div>
                        
                        <div>
                            <label for="service" class="block text-sm font-medium mb-2">Serviço de Interesse</label>
                            <select id="service" name="service" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 focus:border-neon-cyan focus:outline-none transition-colors duration-300">
                                <option value="">Selecione um serviço</option>
                                <option value="chatbot">Chatbots Inteligentes</option>
                                <option value="automation">Automação de Processos</option>
                                <option value="analytics">Análise Preditiva</option>
                                <option value="integration">Integração de Sistemas</option>
                                <option value="personalization">Personalização IA</option>
                                <option value="consulting">Consultoria Estratégica</option>
                                <option value="other">Outro</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium mb-2">Mensagem *</label>
                            <textarea id="message" name="message" rows="4" required class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 focus:border-neon-cyan focus:outline-none transition-colors duration-300 resize-none" placeholder="Conte-nos sobre seu projeto e como podemos ajudar..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg py-4 rounded-lg font-semibold text-lg hover:shadow-2xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="submit-text">🚀 Solicitar Consultoria Gratuita</span>
                            <span class="loading-text hidden">Enviando...</span>
                        </button>
                        
                        <p class="text-sm text-gray-400 text-center">
                            Ao enviar este formulário, você concorda com nossa política de privacidade.
                        </p>
                    </form>
                </div>
                
                <!-- Contact Info -->
                <div class="space-y-8">
                    <!-- WhatsApp CTA -->
                    <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 backdrop-blur-md rounded-2xl p-8 border border-green-500/30">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                     <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.109"/>
                                 </svg>
                             </div>
                             <div>
                                 <h4 class="font-syne font-bold text-xl mb-2">Fale Conosco no WhatsApp</h4>
                                 <p class="text-gray-300 mb-4">Atendimento direto e personalizado. Resposta em até 5 minutos!</p>
                             </div>
                         </div>
                         <a href="/whatsapp" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white py-3 rounded-lg font-semibold text-center block hover:shadow-lg transition-all duration-300">
                             💬 Iniciar Conversa
                         </a>
                     </div>
                     
                     <!-- Contact Info Cards -->
                     <div class="space-y-6">
                         <!-- Email -->
                         <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                             <div class="flex items-center mb-3">
                                 <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                     <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                     </svg>
                                 </div>
                                 <div>
                                     <h4 class="font-semibold">E-mail</h4>
                                     <p class="text-gray-400 text-sm">contato@eloscope.com</p>
                                 </div>
                             </div>
                         </div>
                         
                         <!-- Phone -->
                         <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                             <div class="flex items-center mb-3">
                                 <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                     <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                     </svg>
                                 </div>
                                 <div>
                                     <h4 class="font-semibold">Telefone</h4>
                                     <p class="text-gray-400 text-sm">(17) 99999-9999</p>
                                 </div>
                             </div>
                         </div>
                         
                         <!-- Location -->
                         <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                             <div class="flex items-center mb-3">
                                 <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                     <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                     </svg>
                                 </div>
                                 <div>
                                     <h4 class="font-semibold">Localização</h4>
                                     <p class="text-gray-400 text-sm">São Paulo, Brasil</p>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <!-- Footer -->
     <footer class="bg-dark-card border-t border-white/10 py-16">
         <div class="container mx-auto px-4">
             <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                 <!-- Company Info -->
                 <div class="lg:col-span-2">
                     <div class="flex items-center space-x-3 mb-6">
                         <img src="assets/images/EloScope - Logo.png" alt="Eloscope Logo" class="h-10 w-auto">
                         <span class="font-syne font-bold text-2xl">ELOSCOPE</span>
                     </div>
                     <p class="text-gray-300 mb-6 max-w-md">
                         Transformamos empresas através da inteligência artificial, criando soluções personalizadas que automatizam processos e maximizam resultados.
                     </p>
                     <div class="flex space-x-4">
                         <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-neon-cyan/20 transition-colors duration-300">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                             </svg>
                         </a>
                         <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-neon-cyan/20 transition-colors duration-300">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                             </svg>
                         </a>
                         <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-neon-cyan/20 transition-colors duration-300">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                             </svg>
                         </a>
                         <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-neon-cyan/20 transition-colors duration-300">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M12.004 5.838c-3.403 0-6.158 2.758-6.158 6.158 0 3.403 2.758 6.158 6.158 6.158 3.403 0 6.158-2.758 6.158-6.158 0-3.403-2.758-6.158-6.158-6.158zm0 10.155c-2.209 0-3.997-1.789-3.997-3.997s1.789-3.997 3.997-3.997 3.997 1.789 3.997 3.997c0 2.208-1.789 3.997-3.997 3.997z"/>
                                 <path d="M16.948.076c-2.208-.103-7.677-.098-9.887 0-1.942.091-3.655.56-5.036 1.941C.344 3.678-.139 5.944.013 8.812c.06 1.17.067 1.511.067 4.188 0 2.677-.007 3.018-.067 4.188-.152 2.868.332 5.134 2.013 6.815 1.381 1.381 3.094 1.85 5.036 1.941 2.21.098 7.679.103 9.887 0 1.942-.091 3.655-.56 5.036-1.941C23.656 20.322 24.139 18.056 23.987 15.188c-.06-1.17-.067-1.511-.067-4.188 0-2.677.007-3.018.067-4.188.152-2.868-.332-5.134-2.013-6.815C20.603.635 18.89.166 16.948.076zM24.004 12c0 2.208.007 2.428.048 3.85.175 5.863-2.882 9.725-9.746 9.573-1.422-.041-1.642-.048-3.85-.048-2.208 0-2.428-.007-3.85-.048-5.863-.175-9.725 2.882-9.573-9.746.041-1.422.048-1.642.048-3.85 0-2.208-.007-2.428-.048-3.85-.175-5.863 2.882-9.725 9.746-9.573 1.422.041 1.642.048 3.85.048 2.208 0 2.428.007 3.85.048 5.863.175 9.725-2.882 9.573 9.746-.041 1.422-.048 1.642-.048 3.85z"/>
                             </svg>
                         </a>
                     </div>
                 </div>
                 
                 <!-- Quick Links -->
                 <div>
                     <h4 class="font-syne font-bold text-lg mb-6">Links Rápidos</h4>
                     <ul class="space-y-3">
                         <li><a href="#home" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Início</a></li>
                         <li><a href="#services" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Serviços</a></li>
                         <li><a href="#about" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Sobre</a></li>
                         <li><a href="#cases" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Casos</a></li>
                         <li><a href="/blog" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Blog</a></li>
                     </ul>
                 </div>
                 
                 <!-- Services -->
                 <div>
                     <h4 class="font-syne font-bold text-lg mb-6">Serviços</h4>
                     <ul class="space-y-3">
                         <li><a href="#" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Chatbots IA</a></li>
                         <li><a href="#" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Automação</a></li>
                         <li><a href="#" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Análise Preditiva</a></li>
                         <li><a href="#" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Integração</a></li>
                         <li><a href="#" class="text-gray-300 hover:text-neon-cyan transition-colors duration-300">Consultoria</a></li>
                     </ul>
                 </div>
             </div>
             
             <!-- Bottom Bar -->
             <div class="border-t border-white/10 pt-8">
                 <div class="flex flex-col md:flex-row justify-between items-center">
                     <p class="text-gray-400 text-sm mb-4 md:mb-0">
                         © 2024 Eloscope. Todos os direitos reservados.
                     </p>
                     <div class="flex space-x-6 text-sm">
                         <a href="/privacy" class="text-gray-400 hover:text-neon-cyan transition-colors duration-300">Política de Privacidade</a>
                         <a href="/terms" class="text-gray-400 hover:text-neon-cyan transition-colors duration-300">Termos de Uso</a>
                         <a href="/cookies" class="text-gray-400 hover:text-neon-cyan transition-colors duration-300">Cookies</a>
                     </div>
                 </div>
             </div>
         </div>
     </footer>

     <!-- WhatsApp Float Button -->
     <div class="fixed bottom-6 right-6 z-50">
         <a href="/whatsapp" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 animate-pulse">
             <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                 <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.109"/>
             </svg>
         </a>
     </div>

     <!-- Scripts -->
     <script src="/assets/js/main.js"></script>
     
     <!-- Custom JavaScript -->
     <script>
         // Loading screen
         window.addEventListener('load', function() {
             setTimeout(() => {
                 document.getElementById('loading-screen').style.opacity = '0';
                 setTimeout(() => {
                     document.getElementById('loading-screen').style.display = 'none';
                 }, 500);
             }, 1000);
         });

         // Mobile menu toggle
         document.getElementById('mobile-menu-btn').addEventListener('click', function() {
             const mobileMenu = document.getElementById('mobile-menu');
             mobileMenu.classList.toggle('hidden');
         });

         // Smooth scrolling for navigation links
         document.querySelectorAll('a[href^="#"]').forEach(anchor => {
             anchor.addEventListener('click', function (e) {
                 e.preventDefault();
                 const target = document.querySelector(this.getAttribute('href'));
                 if (target) {
                     target.scrollIntoView({
                         behavior: 'smooth',
                         block: 'start'
                     });
                 }
             });
         });

         // Counter animation
         function animateCounters() {
             const counters = document.querySelectorAll('[data-counter]');
             counters.forEach(counter => {
                 const target = parseInt(counter.getAttribute('data-counter'));
                 const duration = 2000;
                 const step = target / (duration / 16);
                 let current = 0;
                 
                 const timer = setInterval(() => {
                     current += step;
                     if (current >= target) {
                         counter.textContent = target;
                         clearInterval(timer);
                     } else {
                         counter.textContent = Math.floor(current);
                     }
                 }, 16);
             });
         }

         // Intersection Observer for animations
         const observerOptions = {
             threshold: 0.1,
             rootMargin: '0px 0px -50px 0px'
         };

         const observer = new IntersectionObserver((entries) => {
             entries.forEach(entry => {
                 if (entry.isIntersecting) {
                     entry.target.classList.add('animate-fade-in');
                     
                     // Trigger counter animation when hero section is visible
                     if (entry.target.id === 'home') {
                         animateCounters();
                     }
                 }
             });
         }, observerOptions);

         // Observe sections
         document.querySelectorAll('section').forEach(section => {
             observer.observe(section);
         });

         // Header scroll effect
         window.addEventListener('scroll', function() {
             const header = document.getElementById('header');
             if (window.scrollY > 100) {
                 header.classList.add('bg-dark-bg/95');
             } else {
                 header.classList.remove('bg-dark-bg/95');
             }
         });

         // Contact form handling
         document.getElementById('contact-form').addEventListener('submit', function(e) {
             e.preventDefault();
             
             const submitBtn = this.querySelector('button[type="submit"]');
             const submitText = submitBtn.querySelector('.submit-text');
             const loadingText = submitBtn.querySelector('.loading-text');
             
             // Show loading state
             submitText.classList.add('hidden');
             loadingText.classList.remove('hidden');
             submitBtn.disabled = true;
             
             // Simulate form submission (replace with actual form handling)
             setTimeout(() => {
                 alert('Obrigado! Entraremos em contato em breve.');
                 this.reset();
                 
                 // Reset button state
                 submitText.classList.remove('hidden');
                 loadingText.classList.add('hidden');
                 submitBtn.disabled = false;
             }, 2000);
         });

         // Track events
         function trackEvent(eventName, eventData = {}) {
             // Google Analytics
             if (typeof gtag !== 'undefined') {
                 gtag('event', eventName, eventData);
             }
             
             // Facebook Pixel
             if (typeof fbq !== 'undefined') {
                 fbq('track', eventName, eventData);
             }
         }

         // Track CTA clicks
         document.querySelectorAll('.cta-button').forEach(button => {
             button.addEventListener('click', function() {
                 trackEvent('cta_click', {
                     button_text: this.textContent.trim(),
                     button_location: this.closest('section')?.id || 'unknown'
                 });
             });
         });

         // Track scroll depth
         let maxScroll = 0;
         window.addEventListener('scroll', function() {
             const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
             if (scrollPercent > maxScroll && scrollPercent % 25 === 0) {
                 maxScroll = scrollPercent;
                 trackEvent('scroll_depth', {
                     scroll_percent: scrollPercent
                 });
             }
         });
     </script>
 </body>
 </html>