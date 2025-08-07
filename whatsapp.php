<?php
/**
 * WhatsApp Landing Page - Eloscope
 * Página dedicada para automação via WhatsApp
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
$whatsapp_number = $_ENV['WHATSAPP_NUMBER'] ?? '5511999999999';
$ga_id = $_ENV['GOOGLE_ANALYTICS_ID'] ?? '';
$fb_pixel_id = $_ENV['FACEBOOK_PIXEL_ID'] ?? '';
$hotjar_id = $_ENV['HOTJAR_ID'] ?? '';

// UTM parameters
$utm_source = $_GET['utm_source'] ?? 'direct';
$utm_medium = $_GET['utm_medium'] ?? 'organic';
$utm_campaign = $_GET['utm_campaign'] ?? 'whatsapp_landing';
$utm_content = $_GET['utm_content'] ?? '';
$utm_term = $_GET['utm_term'] ?? '';

// WhatsApp message templates
$whatsapp_messages = [
    'consultation' => 'Olá! Vim do site da Eloscope e gostaria de agendar uma consultoria gratuita para automatizar meu negócio. Podem me ajudar?',
    'demo' => 'Oi! Quero conhecer melhor a plataforma Eloscope. Podem me mostrar uma demonstração?',
    'support' => 'Olá! Preciso de ajuda com automação para meu negócio. Podem me orientar?',
    'pricing' => 'Oi! Gostaria de saber mais sobre os preços e planos da Eloscope.',
    'custom' => 'Olá! Vim do site da Eloscope e gostaria de conversar sobre automação para meu negócio.'
];

$selected_message = $whatsapp_messages[$_GET['msg'] ?? 'consultation'];
$whatsapp_link = "https://wa.me/{$whatsapp_number}?text=" . urlencode($selected_message);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Automação - <?php echo $site_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Converse conosco via WhatsApp e descubra como automatizar seu negócio com IA. Consultoria gratuita e demonstração personalizada.">
    <meta name="keywords" content="whatsapp, automação, chatbot, IA, consultoria, eloscope">
    <meta name="author" content="<?php echo $site_name; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $site_url; ?>/whatsapp">
    <meta property="og:title" content="WhatsApp Automação - <?php echo $site_name; ?>">
    <meta property="og:description" content="Converse conosco via WhatsApp e descubra como automatizar seu negócio com IA.">
    <meta property="og:image" content="<?php echo $site_url; ?>/assets/images/og-whatsapp.jpg">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $site_url; ?>/whatsapp">
    <meta property="twitter:title" content="WhatsApp Automação - <?php echo $site_name; ?>">
    <meta property="twitter:description" content="Converse conosco via WhatsApp e descubra como automatizar seu negócio com IA.">
    <meta property="twitter:image" content="<?php echo $site_url; ?>/assets/images/og-whatsapp.jpg">
    
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
    
    <style>
        .whatsapp-gradient {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        }
        
        .whatsapp-pulse {
            animation: whatsapp-pulse 2s infinite;
        }
        
        @keyframes whatsapp-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .floating-whatsapp {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .chat-bubble {
            position: relative;
        }
        
        .chat-bubble::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 20px;
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid #25d366;
        }
        
        .typing-animation {
            animation: typing 1.5s infinite;
        }
        
        @keyframes typing {
            0%, 60%, 100% { opacity: 1; }
            30% { opacity: 0.5; }
        }
    </style>
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
                    <a href="/#about" class="hover:text-neon-cyan transition-colors">Sobre</a>
                    <a href="/#cases" class="hover:text-neon-cyan transition-colors">Cases</a>
                    <a href="/blog" class="hover:text-neon-cyan transition-colors">Blog</a>
                    <a href="/#contact" class="hover:text-neon-cyan transition-colors">Contato</a>
                </nav>
                
                <a href="<?php echo $whatsapp_link; ?>" target="_blank" class="whatsapp-gradient text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 whatsapp-pulse">
                    💬 WhatsApp
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20">
        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center relative overflow-hidden">
            <!-- Background Effects -->
            <div class="absolute inset-0 bg-gradient-to-br from-neon-cyan/5 via-transparent to-neon-magenta/5"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-neon-cyan/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-neon-magenta/10 rounded-full blur-3xl"></div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Column - Content -->
                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center bg-gradient-to-r from-neon-cyan/20 to-neon-magenta/20 rounded-full px-4 py-2 mb-6">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            <span class="text-sm font-medium">Atendimento Online 24/7</span>
                        </div>
                        
                        <h1 class="font-syne font-bold text-4xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                            Converse Conosco no
                            <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                                WhatsApp
                            </span>
                        </h1>
                        
                        <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                            Descubra como <strong class="text-neon-cyan">automatizar seu negócio</strong> 
                            e aumentar suas vendas em até <strong class="text-neon-magenta">300%</strong> 
                            com nossa IA especializada.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 mb-8">
                            <a href="<?php echo $whatsapp_link; ?>" target="_blank" 
                               class="whatsapp-gradient text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300 whatsapp-pulse flex items-center justify-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.386"/>
                                </svg>
                                Iniciar Conversa
                            </a>
                            
                            <button onclick="showMessageOptions()" 
                                    class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                                📝 Escolher Mensagem
                            </button>
                        </div>
                        
                        <!-- Trust Indicators -->
                        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Resposta em até 5 minutos
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                Consultoria 100% gratuita
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                                Sem compromisso
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - WhatsApp Mockup -->
                    <div class="relative">
                        <div class="floating-whatsapp">
                            <!-- Phone Mockup -->
                            <div class="bg-gray-900 rounded-3xl p-4 max-w-sm mx-auto shadow-2xl">
                                <!-- Phone Header -->
                                <div class="bg-gray-800 rounded-2xl p-4 mb-4">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold">E</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-white">Eloscope</h3>
                                            <p class="text-green-400 text-sm flex items-center">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                online
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Chat Messages -->
                                <div class="space-y-4 mb-4">
                                    <!-- Incoming Message -->
                                    <div class="flex">
                                        <div class="bg-gray-700 rounded-2xl rounded-bl-md p-3 max-w-xs chat-bubble">
                                            <p class="text-white text-sm">Olá! 👋 Como posso ajudar você a automatizar seu negócio hoje?</p>
                                            <span class="text-xs text-gray-400 mt-1 block">14:32</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Outgoing Message -->
                                    <div class="flex justify-end">
                                        <div class="whatsapp-gradient rounded-2xl rounded-br-md p-3 max-w-xs">
                                            <p class="text-white text-sm">Quero saber mais sobre automação para meu negócio!</p>
                                            <span class="text-xs text-green-100 mt-1 block">14:33 ✓✓</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Typing Indicator -->
                                    <div class="flex">
                                        <div class="bg-gray-700 rounded-2xl rounded-bl-md p-3">
                                            <div class="flex space-x-1 typing-animation">
                                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 bg-green-500 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold animate-bounce">
                            💬
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center text-sm animate-pulse">
                            🤖
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Benefits Section -->
        <section class="py-20 bg-gradient-to-b from-transparent to-dark-card/50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Por que escolher o 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            WhatsApp?
                        </span>
                    </h2>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        A forma mais rápida e eficiente de descobrir como nossa IA pode transformar seu negócio
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-cyan/50 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Resposta Instantânea</h3>
                        <p class="text-gray-300">Nossa equipe responde em até 5 minutos, 24 horas por dia, 7 dias por semana.</p>
                    </div>
                    
                    <!-- Benefit 2 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-neon-magenta/50 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Consultoria Gratuita</h3>
                        <p class="text-gray-300">Análise completa do seu negócio sem custo algum. Sem pegadinhas ou compromissos.</p>
                    </div>
                    
                    <!-- Benefit 3 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-purple-500/50 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Solução Personalizada</h3>
                        <p class="text-gray-300">Cada negócio é único. Criamos uma estratégia específica para suas necessidades.</p>
                    </div>
                    
                    <!-- Benefit 4 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-yellow-500/50 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Resultados Comprovados</h3>
                        <p class="text-gray-300">Mais de 500 empresas já aumentaram suas vendas em até 300% com nossa automação.</p>
                    </div>
                    
                    <!-- Benefit 5 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-red-500/50 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-400 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Implementação Rápida</h3>
                        <p class="text-gray-300">Sua automação funcionando em até 7 dias. Sem complicações técnicas.</p>
                    </div>
                    
                    <!-- Benefit 6 -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 hover:border-indigo-500/50 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 109.75 9.75A9.75 9.75 0 0012 2.25z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl mb-4">Suporte Contínuo</h3>
                        <p class="text-gray-300">Acompanhamento completo e suporte técnico especializado sempre que precisar.</p>
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
                        Revolucionar
                    </span>
                    seu Negócio?
                </h2>
                
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Não perca mais tempo com processos manuais. Comece sua transformação digital agora mesmo!
                </p>
                
                <a href="<?php echo $whatsapp_link; ?>" target="_blank" 
                   class="inline-flex items-center whatsapp-gradient text-white px-12 py-6 rounded-2xl font-bold text-xl hover:shadow-2xl transition-all duration-300 whatsapp-pulse">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.386"/>
                    </svg>
                    Conversar Agora no WhatsApp
                </a>
                
                <p class="text-sm text-gray-400 mt-4">
                    ⚡ Resposta garantida em até 5 minutos • 🎯 Consultoria 100% gratuita
                </p>
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
                        <li><a href="/#about" class="text-gray-400 hover:text-neon-cyan transition-colors">Sobre</a></li>
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

    <!-- Message Options Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-dark-card rounded-2xl p-8 max-w-md w-full border border-white/20">
            <h3 class="font-syne font-bold text-2xl mb-6 text-center">Escolha sua mensagem</h3>
            
            <div class="space-y-3">
                <button onclick="sendWhatsAppMessage('consultation')" class="w-full text-left p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 hover:border-neon-cyan/50 transition-all">
                    <div class="font-semibold mb-1">🎯 Consultoria Gratuita</div>
                    <div class="text-sm text-gray-400">Quero agendar uma consultoria para automatizar meu negócio</div>
                </button>
                
                <button onclick="sendWhatsAppMessage('demo')" class="w-full text-left p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 hover:border-neon-magenta/50 transition-all">
                    <div class="font-semibold mb-1">🖥️ Demonstração</div>
                    <div class="text-sm text-gray-400">Quero ver a plataforma funcionando</div>
                </button>
                
                <button onclick="sendWhatsAppMessage('pricing')" class="w-full text-left p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 hover:border-purple-500/50 transition-all">
                    <div class="font-semibold mb-1">💰 Preços e Planos</div>
                    <div class="text-sm text-gray-400">Quero saber sobre investimento e planos</div>
                </button>
                
                <button onclick="sendWhatsAppMessage('support')" class="w-full text-left p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 hover:border-blue-500/50 transition-all">
                    <div class="font-semibold mb-1">🆘 Suporte</div>
                    <div class="text-sm text-gray-400">Preciso de ajuda com automação</div>
                </button>
                
                <button onclick="sendWhatsAppMessage('custom')" class="w-full text-left p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-all">
                    <div class="font-semibold mb-1">💬 Mensagem Personalizada</div>
                    <div class="text-sm text-gray-400">Quero conversar sobre meu caso específico</div>
                </button>
            </div>
            
            <button onclick="closeMessageModal()" class="w-full mt-6 bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-xl transition-colors">
                Cancelar
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/main.js"></script>
    
    <script>
        // WhatsApp message options
        const whatsappMessages = <?php echo json_encode($whatsapp_messages); ?>;
        const whatsappNumber = '<?php echo $whatsapp_number; ?>';
        
        function showMessageOptions() {
            document.getElementById('messageModal').classList.remove('hidden');
            document.getElementById('messageModal').classList.add('flex');
        }
        
        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
            document.getElementById('messageModal').classList.remove('flex');
        }
        
        function sendWhatsAppMessage(type) {
            const message = whatsappMessages[type] || whatsappMessages.custom;
            const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
            
            // Track event
            if (typeof gtag !== 'undefined') {
                gtag('event', 'whatsapp_click', {
                    'event_category': 'engagement',
                    'event_label': type,
                    'value': 1
                });
            }
            
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Contact', {
                    content_name: 'WhatsApp ' + type
                });
            }
            
            // Open WhatsApp
            window.open(whatsappUrl, '_blank');
            closeMessageModal();
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMessageModal();
            }
        });
        
        // Close modal on backdrop click
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMessageModal();
            }
        });
        
        // Track page view
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'WhatsApp Landing Page',
                'page_location': window.location.href,
                'utm_source': '<?php echo $utm_source; ?>',
                'utm_medium': '<?php echo $utm_medium; ?>',
                'utm_campaign': '<?php echo $utm_campaign; ?>'
            });
        }
        
        if (typeof fbq !== 'undefined') {
            fbq('track', 'ViewContent', {
                content_name: 'WhatsApp Landing Page',
                content_category: 'landing_page'
            });
        }
    </script>
</body>
</html>