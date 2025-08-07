<?php
/**
 * 403 Error Page - Eloscope
 * Página de erro 403 - Acesso negado
 */

session_start();

// Set 403 header
http_response_code(403);

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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado - <?php echo $site_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Acesso negado. Você não tem permissão para acessar este recurso.">
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
        
        // Track 403 error
        gtag('event', 'exception', {
            'description': '403_error',
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
                
                <a href="/" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                    🏠 Voltar ao Início
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <!-- Background Effects -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-transparent to-orange-500/5 pointer-events-none"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <!-- 403 Animation -->
                <div class="mb-12">
                    <div class="text-8xl md:text-9xl font-syne font-bold mb-4">
                        <span class="bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent animate-pulse">
                            403
                        </span>
                    </div>
                    <div class="w-32 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto rounded-full"></div>
                </div>
                
                <!-- Lock Icon -->
                <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-orange-500 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                
                <h1 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Acesso 
                    <span class="bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent">
                        Negado
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto">
                    Você não tem permissão para acessar este recurso. 
                    Se você acredita que isso é um erro, entre em contato conosco.
                </p>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="/" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                        🏠 Página Inicial
                    </a>
                    <a href="/whatsapp" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                        💬 Falar Conosco
                    </a>
                </div>
                
                <!-- Security Info -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 max-w-2xl mx-auto">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl">Segurança em Primeiro Lugar</h3>
                    </div>
                    <p class="text-gray-300 leading-relaxed">
                        Protegemos nossos recursos e dados com as melhores práticas de segurança. 
                        Este bloqueio ajuda a manter a integridade do nosso sistema.
                    </p>
                </div>
                
                <!-- Possible Reasons -->
                <div class="mt-12">
                    <h3 class="font-syne font-bold text-xl mb-6">Possíveis Motivos</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Área Restrita</h4>
                            <p class="text-sm text-gray-400">Você tentou acessar uma área que requer permissões especiais</p>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Link Inválido</h4>
                            <p class="text-sm text-gray-400">O link que você seguiu pode estar quebrado ou expirado</p>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Manutenção</h4>
                            <p class="text-sm text-gray-400">Esta seção pode estar temporariamente indisponível</p>
                        </div>
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
</body>
</html>