<?php
/**
 * 500 Error Page - Eloscope
 * Página de erro 500 - Erro interno do servidor
 */

session_start();

// Set 500 header
http_response_code(500);

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
    <title>Erro Interno - <?php echo $site_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Erro interno do servidor. Estamos trabalhando para resolver o problema.">
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
        
        // Track 500 error
        gtag('event', 'exception', {
            'description': '500_error',
            'fatal': true,
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
            <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 via-transparent to-purple-600/5 pointer-events-none"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-red-600/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <!-- 500 Animation -->
                <div class="mb-12">
                    <div class="text-8xl md:text-9xl font-syne font-bold mb-4">
                        <span class="bg-gradient-to-r from-red-600 to-purple-600 bg-clip-text text-transparent animate-pulse">
                            500
                        </span>
                    </div>
                    <div class="w-32 h-1 bg-gradient-to-r from-red-600 to-purple-600 mx-auto rounded-full animate-pulse"></div>
                </div>
                
                <!-- Server Error Icon -->
                <div class="w-24 h-24 bg-gradient-to-r from-red-600 to-purple-600 rounded-full mx-auto mb-8 flex items-center justify-center animate-bounce">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <h1 class="font-syne font-bold text-3xl md:text-5xl mb-6">
                    Erro Interno do 
                    <span class="bg-gradient-to-r from-red-600 to-purple-600 bg-clip-text text-transparent">
                        Servidor
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto">
                    Oops! Algo deu errado em nossos servidores. 
                    Nossa equipe técnica já foi notificada e está trabalhando para resolver o problema.
                </p>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="/" class="bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300">
                        🏠 Página Inicial
                    </a>
                    <button onclick="location.reload()" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                        🔄 Tentar Novamente
                    </button>
                    <a href="/whatsapp" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300 border border-white/20">
                        💬 Reportar Problema
                    </a>
                </div>
                
                <!-- Status Info -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-8 border border-white/10 max-w-2xl mx-auto mb-12">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-syne font-bold text-xl">Estamos Resolvendo</h3>
                    </div>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Nossa equipe técnica foi automaticamente notificada sobre este problema 
                        e está trabalhando para restaurar o serviço o mais rápido possível.
                    </p>
                    <div class="flex items-center justify-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-green-400">Sistema de monitoramento ativo</span>
                    </div>
                </div>
                
                <!-- What You Can Do -->
                <div class="mt-12">
                    <h3 class="font-syne font-bold text-xl mb-6">O que você pode fazer</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Aguarde um Momento</h4>
                            <p class="text-sm text-gray-400">Tente recarregar a página em alguns minutos</p>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Entre em Contato</h4>
                            <p class="text-sm text-gray-400">Se o problema persistir, nos informe via WhatsApp</p>
                        </div>
                        
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Verifique Status</h4>
                            <p class="text-sm text-gray-400">Acompanhe atualizações em nossas redes sociais</p>
                        </div>
                    </div>
                </div>
                
                <!-- Error ID -->
                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-500">
                        ID do Erro: <span class="font-mono bg-white/10 px-2 py-1 rounded"><?php echo uniqid('ERR_'); ?></span>
                        <br>
                        Timestamp: <span class="font-mono"><?php echo date('Y-m-d H:i:s T'); ?></span>
                    </p>
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
    
    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh after 30 seconds
        setTimeout(() => {
            const refreshBtn = document.createElement('div');
            refreshBtn.className = 'fixed bottom-4 right-4 bg-gradient-to-r from-neon-cyan to-neon-magenta text-dark-bg px-4 py-2 rounded-lg font-semibold cursor-pointer hover:shadow-lg transition-all duration-300';
            refreshBtn.innerHTML = '🔄 Tentar Novamente';
            refreshBtn.onclick = () => location.reload();
            document.body.appendChild(refreshBtn);
        }, 30000);
    </script>
</body>
</html>