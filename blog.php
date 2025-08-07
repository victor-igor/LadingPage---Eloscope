<?php
/**
 * Blog Page - Eloscope
 * Página de artigos e recursos
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

// Sample blog posts (in production, this would come from database)
$featured_posts = [
    [
        'id' => 1,
        'title' => 'Como a IA está Revolucionando o Atendimento ao Cliente',
        'excerpt' => 'Descubra como chatbots inteligentes podem aumentar sua conversão em até 300% e proporcionar atendimento 24/7.',
        'image' => '/assets/images/blog/ai-customer-service.jpg',
        'category' => 'Inteligência Artificial',
        'date' => '2024-01-15',
        'read_time' => '8 min',
        'author' => 'Lucas Negreiro',
        'featured' => true
    ],
    [
        'id' => 2,
        'title' => '5 Automações que Todo E-commerce Precisa Ter',
        'excerpt' => 'Automatize carrinho abandonado, follow-up pós-venda e muito mais para aumentar suas vendas exponencialmente.',
        'image' => '/assets/images/blog/ecommerce-automation.jpg',
        'category' => 'E-commerce',
        'date' => '2024-01-12',
        'read_time' => '6 min',
        'author' => 'Ana Silva',
        'featured' => true
    ]
];

$recent_posts = [
    [
        'id' => 3,
        'title' => 'WhatsApp Business API: Guia Completo 2024',
        'excerpt' => 'Tudo que você precisa saber para implementar o WhatsApp Business API no seu negócio.',
        'image' => '/assets/images/blog/whatsapp-api.jpg',
        'category' => 'WhatsApp',
        'date' => '2024-01-10',
        'read_time' => '12 min',
        'author' => 'Carlos Santos'
    ],
    [
        'id' => 4,
        'title' => 'N8N vs Zapier: Qual Escolher para Sua Empresa?',
        'excerpt' => 'Comparativo detalhado entre as principais plataformas de automação do mercado.',
        'image' => '/assets/images/blog/n8n-vs-zapier.jpg',
        'category' => 'Automação',
        'date' => '2024-01-08',
        'read_time' => '10 min',
        'author' => 'Marina Costa'
    ],
    [
        'id' => 5,
        'title' => 'ROI de Automação: Como Medir Resultados',
        'excerpt' => 'Métricas essenciais para avaliar o retorno do investimento em automação.',
        'image' => '/assets/images/blog/roi-automation.jpg',
        'category' => 'Estratégia',
        'date' => '2024-01-05',
        'read_time' => '7 min',
        'author' => 'Roberto Lima'
    ],
    [
        'id' => 6,
        'title' => 'Tendências de IA para 2024: O que Esperar',
        'excerpt' => 'As principais inovações em inteligência artificial que vão impactar os negócios.',
        'image' => '/assets/images/blog/ai-trends-2024.jpg',
        'category' => 'Inteligência Artificial',
        'date' => '2024-01-03',
        'read_time' => '9 min',
        'author' => 'Fernanda Oliveira'
    ],
    [
        'id' => 7,
        'title' => 'Segurança em Automações: Melhores Práticas',
        'excerpt' => 'Como proteger seus dados e processos automatizados contra ameaças.',
        'image' => '/assets/images/blog/automation-security.jpg',
        'category' => 'Segurança',
        'date' => '2024-01-01',
        'read_time' => '11 min',
        'author' => 'Lucas Negreiro'
    ],
    [
        'id' => 8,
        'title' => 'Lead Scoring com IA: Qualifique Melhor seus Leads',
        'excerpt' => 'Use inteligência artificial para identificar os leads com maior potencial de conversão.',
        'image' => '/assets/images/blog/lead-scoring-ai.jpg',
        'category' => 'Marketing',
        'date' => '2023-12-28',
        'read_time' => '8 min',
        'author' => 'Ana Silva'
    ]
];

$categories = ['Todos', 'Inteligência Artificial', 'Automação', 'WhatsApp', 'E-commerce', 'Estratégia', 'Marketing', 'Segurança'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - <?php echo $site_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Artigos sobre automação, IA e tecnologia para negócios. Aprenda com nossos especialistas e transforme sua empresa.">
    <meta name="keywords" content="blog automação, artigos IA, tecnologia negócios, whatsapp business, n8n, chatbots">
    <meta name="author" content="<?php echo $site_name; ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $site_url; ?>/blog">
    <meta property="og:title" content="Blog - <?php echo $site_name; ?>">
    <meta property="og:description" content="Artigos sobre automação, IA e tecnologia para negócios.">
    <meta property="og:image" content="<?php echo $site_url; ?>/assets/images/og-blog.jpg">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $site_url; ?>/blog">
    <meta property="twitter:title" content="Blog - <?php echo $site_name; ?>">
    <meta property="twitter:description" content="Artigos sobre automação, IA e tecnologia para negócios.">
    <meta property="twitter:image" content="<?php echo $site_url; ?>/assets/images/og-blog.jpg">
    
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
                    <a href="/about" class="hover:text-neon-cyan transition-colors">Sobre</a>
                    <a href="/#cases" class="hover:text-neon-cyan transition-colors">Cases</a>
                    <a href="/blog" class="text-neon-cyan font-semibold">Blog</a>
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
                        <span class="font-medium">Conhecimento que Transforma Negócios</span>
                    </div>
                    
                    <h1 class="font-syne font-bold text-4xl md:text-6xl lg:text-7xl mb-8 leading-tight">
                        Blog 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Eloscope
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed">
                        Artigos, guias e insights sobre <strong class="text-neon-cyan">automação inteligente</strong>, 
                        <strong class="text-neon-magenta">IA para negócios</strong> e as últimas tendências em tecnologia.
                    </p>
                    
                    <!-- Newsletter Signup -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 max-w-2xl mx-auto">
                        <h3 class="font-syne font-bold text-xl mb-4">📧 Receba nossos artigos por email</h3>
                        <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4">
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="Seu melhor email" 
                                required 
                                class="flex-1 px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-neon-cyan transition-colors"
                            >
                            <button 
                                type="submit" 
                                class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 whitespace-nowrap"
                            >
                                Inscrever-se
                            </button>
                        </form>
                        <p class="text-sm text-gray-400 mt-3">✨ Conteúdo exclusivo + sem spam</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Featured Posts Section -->
        <section class="py-20 bg-gradient-to-b from-transparent to-dark-card/50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Artigos em 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Destaque
                        </span>
                    </h2>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        Os conteúdos mais relevantes para transformar seu negócio
                    </p>
                </div>
                
                <div class="grid lg:grid-cols-2 gap-8">
                    <?php foreach ($featured_posts as $post): ?>
                    <article class="bg-white/5 backdrop-blur-md rounded-2xl overflow-hidden border border-white/10 hover:border-neon-cyan/50 transition-all duration-300 group">
                        <div class="aspect-video bg-gradient-to-br from-neon-cyan/20 to-neon-magenta/20 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-neon-cyan text-dark-bg px-3 py-1 rounded-full text-sm font-semibold">
                                    <?php echo $post['category']; ?>
                                </span>
                            </div>
                            <div class="absolute bottom-4 right-4 text-sm text-white/80">
                                📖 <?php echo $post['read_time']; ?>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="flex items-center text-sm text-gray-400 mb-4">
                                <span>👤 <?php echo $post['author']; ?></span>
                                <span class="mx-2">•</span>
                                <span>📅 <?php echo date('d/m/Y', strtotime($post['date'])); ?></span>
                            </div>
                            
                            <h3 class="font-syne font-bold text-xl mb-4 group-hover:text-neon-cyan transition-colors">
                                <?php echo $post['title']; ?>
                            </h3>
                            
                            <p class="text-gray-300 mb-6 leading-relaxed">
                                <?php echo $post['excerpt']; ?>
                            </p>
                            
                            <a href="/blog/<?php echo $post['id']; ?>" class="inline-flex items-center text-neon-cyan hover:text-neon-magenta transition-colors font-semibold">
                                Ler artigo completo
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Categories Filter -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap justify-center gap-4">
                    <?php foreach ($categories as $index => $category): ?>
                    <button 
                        class="category-filter px-6 py-3 rounded-full border border-white/20 hover:border-neon-cyan transition-all duration-300 <?php echo $index === 0 ? 'bg-neon-cyan text-dark-bg' : 'text-white hover:bg-white/10'; ?>" 
                        data-category="<?php echo $category; ?>"
                    >
                        <?php echo $category; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Recent Posts Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                        Artigos 
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                            Recentes
                        </span>
                    </h2>
                </div>
                
                <div id="posts-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($recent_posts as $post): ?>
                    <article class="blog-post bg-white/5 backdrop-blur-md rounded-2xl overflow-hidden border border-white/10 hover:border-neon-magenta/50 transition-all duration-300 group" data-category="<?php echo $post['category']; ?>">
                        <div class="aspect-video bg-gradient-to-br from-purple-500/20 to-blue-500/20 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/20 backdrop-blur-md text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    <?php echo $post['category']; ?>
                                </span>
                            </div>
                            <div class="absolute bottom-4 right-4 text-sm text-white/80">
                                📖 <?php echo $post['read_time']; ?>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-400 mb-3">
                                <span>👤 <?php echo $post['author']; ?></span>
                                <span class="mx-2">•</span>
                                <span>📅 <?php echo date('d/m/Y', strtotime($post['date'])); ?></span>
                            </div>
                            
                            <h3 class="font-syne font-bold text-lg mb-3 group-hover:text-neon-magenta transition-colors">
                                <?php echo $post['title']; ?>
                            </h3>
                            
                            <p class="text-gray-300 mb-4 text-sm leading-relaxed">
                                <?php echo $post['excerpt']; ?>
                            </p>
                            
                            <a href="/blog/<?php echo $post['id']; ?>" class="inline-flex items-center text-neon-magenta hover:text-neon-cyan transition-colors font-semibold text-sm">
                                Ler mais
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More Button -->
                <div class="text-center mt-12">
                    <button id="load-more" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Carregar mais artigos
                    </button>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-neon-cyan/10 via-transparent to-neon-magenta/10">
            <div class="container mx-auto px-4 text-center">
                <h2 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Pronto para 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Implementar
                    </span>
                    essas Estratégias?
                </h2>
                
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Transforme o conhecimento em resultados reais. Nossa equipe está pronta para automatizar seu negócio.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/whatsapp" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                        💬 Falar com Especialista
                    </a>
                    <a href="/#contact" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                        📧 Solicitar Consultoria
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
                    <h3 class="font-syne font-bold text-lg mb-4">Categorias</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">Inteligência Artificial</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">Automação</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">WhatsApp Business</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">E-commerce</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-neon-cyan transition-colors">Marketing</a></li>
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
        // Category filtering
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilters = document.querySelectorAll('.category-filter');
            const blogPosts = document.querySelectorAll('.blog-post');
            
            categoryFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    // Update active filter
                    categoryFilters.forEach(f => {
                        f.classList.remove('bg-neon-cyan', 'text-dark-bg');
                        f.classList.add('text-white');
                    });
                    this.classList.add('bg-neon-cyan', 'text-dark-bg');
                    this.classList.remove('text-white');
                    
                    // Filter posts
                    blogPosts.forEach(post => {
                        if (category === 'Todos' || post.getAttribute('data-category') === category) {
                            post.style.display = 'block';
                            post.classList.add('animate-fade-in');
                        } else {
                            post.style.display = 'none';
                        }
                    });
                    
                    // Track filter usage
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'blog_filter', {
                            'category': category
                        });
                    }
                });
            });
            
            // Load more functionality
            const loadMoreBtn = document.getElementById('load-more');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    // In a real implementation, this would load more posts via AJAX
                    this.textContent = 'Carregando...';
                    
                    setTimeout(() => {
                        this.textContent = 'Todos os artigos carregados';
                        this.disabled = true;
                        this.classList.add('opacity-50', 'cursor-not-allowed');
                    }, 1000);
                    
                    // Track load more
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'blog_load_more');
                    }
                });
            }
        });
        
        // Track page view
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'Blog',
                'page_location': window.location.href
            });
        }
        
        if (typeof fbq !== 'undefined') {
            fbq('track', 'ViewContent', {
                content_name: 'Blog Page',
                content_category: 'blog'
            });
        }
    </script>
</body>
</html>