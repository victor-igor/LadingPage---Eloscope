<?php
/**
 * 404 Error Page - Eloscope
 * Página de erro 404 - Página não encontrada
 */

session_start();

// Set 404 header
http_response_code(404);

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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada - <?php echo $site_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="A página que você procura não foi encontrada. Volte para o início ou explore nossos serviços de automação.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_id; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $ga_id; ?>');
        
        // Track 404 error
        gtag('event', 'exception', {
            'description': '404_error',
            'fatal': false,
            'page_location': window.location.href
        });
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
    <main class="pt-20 min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <!-- Background Effects -->
            <div class="absolute inset-0 bg-gradient-to-br from-neon-cyan/5 via-transparent to-neon-magenta/5 pointer-events-none"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-neon-cyan/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-neon-magenta/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <!-- 404 Animation -->
                <div class="mb-12">
                    <div class="text-8xl md:text-9xl font-syne font-bold mb-4">
                        <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent animate-pulse">
                            404
                        </span>
                    </div>
                    <div class="w-32 h-1 bg-gradient-to-r from-neon-cyan to-neon-magenta mx-auto rounded-full"></div>
                </div>
                
                <h1 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Oops! Página 
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-magenta bg-clip-text text-transparent">
                        Não Encontrada
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto">
                    A página que você está procurando não existe ou foi movida. 
                    Mas não se preocupe, temos muito conteúdo incrível para você explorar!
                </p>
                
                <!-- Quick Actions -->
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <a href="/" class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-neon-cyan/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-neon-cyan to-blue-500 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">Página Inicial</h3>
                        <p class="text-gray-400 text-sm">Volte para o início e descubra nossos serviços</p>
                    </a>
                    
                    <a href="/blog" class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-neon-magenta/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-neon-magenta to-purple-500 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">Blog</h3>
                        <p class="text-gray-400 text-sm">Leia nossos artigos sobre automação e IA</p>
                    </a>
                    
                    <a href="/whatsapp" class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-green-500/50 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-lg mb-2">WhatsApp</h3>
                        <p class="text-gray-400 text-sm">Fale conosco diretamente pelo WhatsApp</p>
                    </a>
                </div>
                
                <!-- Search Box -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 max-w-2xl mx-auto mb-12">
                    <h3 class="font-syne font-bold text-xl mb-4">🔍 Procurando algo específico?</h3>
                    <form class="flex flex-col sm:flex-row gap-4">
                        <input 
                            type="text" 
                            placeholder="Digite o que você procura..." 
                            class="flex-1 px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-neon-cyan transition-colors"
                        >
                        <button 
                            type="submit" 
                            class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 whitespace-nowrap"
                        >
                            Buscar
                        </button>
                    </form>
                </div>
                
                <!-- Popular Links -->
                <div class="text-center">
                    <h3 class="font-syne font-bold text-xl mb-6">Links Populares</h3>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="/#services" class="bg-white/10 backdrop-blur-md text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                            Nossos Serviços
                        </a>
                        <a href="/about" class="bg-white/10 backdrop-blur-md text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                            Sobre Nós
                        </a>
                        <a href="/#cases" class="bg-white/10 backdrop-blur-md text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                            Cases de Sucesso
                        </a>
                        <a href="/#contact" class="bg-white/10 backdrop-blur-md text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                            Contato
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-card border-t border-white/10 py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-2 mb-4">
                <div class="w-6 h-6 bg-gradient-to-r from-neon-cyan to-neon-magenta rounded-lg flex items-center justify-center">
                    <span class="text-dark-bg font-bold text-xs">E</span>
                </div>
                <span class="font-syne font-bold text-lg">ELOSCOPE</span>
            </div>
            <p class="text-gray-400">
                &copy; <?php echo date('Y'); ?> Eloscope. Todos os direitos reservados.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate the 404 number
            const number404 = document.querySelector('.text-8xl');
            if (number404) {
                number404.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1) rotate(5deg)';
                });
                
                number404.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) rotate(0deg)';
                });
            }
            
            // Search functionality
            const searchForm = document.querySelector('form');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const searchTerm = this.querySelector('input').value;
                    if (searchTerm.trim()) {
                        // Redirect to homepage with search parameter
                        window.location.href = '/?search=' + encodeURIComponent(searchTerm);
                    }
                });
            }
        });
    </script>
</body>
</html>