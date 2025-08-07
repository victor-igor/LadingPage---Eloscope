<?php
/**
 * Newsletter API Endpoint
 * Handles newsletter subscription requests
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/validation.php';

try {
    // Get and validate input
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? '';
    $source = filter_input(INPUT_POST, 'source', FILTER_SANITIZE_STRING) ?? 'website';
    
    // Validation
    if (!$email) {
        throw new Exception('Email inválido');
    }
    
    if (empty($name)) {
        $name = explode('@', $email)[0]; // Use email prefix as fallback
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => true,
            'message' => 'Email já cadastrado em nossa newsletter!'
        ]);
        exit();
    }
    
    // Insert new subscriber
    $stmt = $pdo->prepare("
        INSERT INTO newsletter_subscribers (email, name, source, subscribed_at, status, ip_address) 
        VALUES (?, ?, ?, NOW(), 'active', ?)
    ");
    
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt->execute([$email, $name, $source, $ip_address]);
    
    $subscriber_id = $pdo->lastInsertId();
    
    // Log the subscription
    logActivity('newsletter_subscription', [
        'subscriber_id' => $subscriber_id,
        'email' => $email,
        'source' => $source
    ]);
    
    // Send welcome email (async)
    sendWelcomeEmail($email, $name);
    
    // Integrate with CRM (Go HighLevel)
    integrateCRM([
        'email' => $email,
        'name' => $name,
        'source' => 'newsletter_' . $source,
        'tags' => ['newsletter', 'lead']
    ]);
    
    // Trigger N8N automation
    triggerN8NWorkflow('newsletter_subscription', [
        'email' => $email,
        'name' => $name,
        'source' => $source,
        'subscriber_id' => $subscriber_id
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Inscrição realizada com sucesso! Verifique seu email.'
    ]);
    
} catch (Exception $e) {
    error_log('Newsletter API Error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Send welcome email to new subscriber
 */
function sendWelcomeEmail($email, $name) {
    $subject = 'Bem-vindo à Eloscope! 🚀';
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Inter', Arial, sans-serif; background: #0a0a0a; color: #ffffff; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { color: #00ffff; font-size: 24px; font-weight: bold; }
            .content { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 10px; }
            .cta-button { 
                display: inline-block; 
                background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); 
                color: #0a0a0a; 
                padding: 15px 30px; 
                text-decoration: none; 
                border-radius: 5px; 
                font-weight: bold;
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='logo'>ELOSCOPE</div>
                <p>Automação Inteligente para Negócios</p>
            </div>
            
            <div class='content'>
                <h2>Olá, {$name}! 👋</h2>
                
                <p>Bem-vindo à nossa comunidade exclusiva de líderes em automação empresarial!</p>
                
                <p>Você agora faz parte de um grupo seleto que recebe:</p>
                <ul>
                    <li>📊 Insights exclusivos sobre automação com IA</li>
                    <li>🎯 Cases de sucesso reais</li>
                    <li>🚀 Estratégias avançadas de otimização</li>
                    <li>💡 Tendências do mercado em primeira mão</li>
                </ul>
                
                <p>Para começar, que tal agendar uma consultoria gratuita?</p>
                
                <a href='https://eloscope.com/consultoria' class='cta-button'>Agendar Consultoria Gratuita</a>
                
                <p>Nos próximos dias, você receberá conteúdos exclusivos que vão transformar a forma como você vê a automação empresarial.</p>
                
                <p>Prepare-se para revolucionar seu negócio! 🚀</p>
                
                <hr style='border: 1px solid rgba(255,255,255,0.1); margin: 30px 0;'>
                
                <p style='font-size: 12px; color: rgba(255,255,255,0.7);'>
                    Você está recebendo este email porque se inscreveu em nossa newsletter.<br>
                    Se não deseja mais receber nossos emails, <a href='https://eloscope.com/unsubscribe?email={$email}' style='color: #00ffff;'>clique aqui para descadastrar</a>.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: Eloscope <noreply@eloscope.com>',
        'Reply-To: contato@eloscope.com',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Send email asynchronously
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }
    
    mail($email, $subject, $message, implode("\r\n", $headers));
}

/**
 * Integrate with CRM (Go HighLevel)
 */
function integrateCRM($data) {
    $api_key = $_ENV['GOHIGHLEVEL_API_KEY'] ?? '';
    
    if (empty($api_key)) {
        error_log('Go HighLevel API key not configured');
        return false;
    }
    
    $url = 'https://rest.gohighlevel.com/v1/contacts/';
    
    $payload = [
        'email' => $data['email'],
        'name' => $data['name'],
        'source' => $data['source'],
        'tags' => $data['tags'] ?? [],
        'customFields' => [
            'lead_source' => $data['source'],
            'subscription_date' => date('Y-m-d H:i:s')
        ]
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 10
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        error_log('CRM integration failed: ' . $response);
        return false;
    }
    
    return true;
}

/**
 * Trigger N8N automation workflow
 */
function triggerN8NWorkflow($workflow, $data) {
    $webhook_url = $_ENV['N8N_WEBHOOK_URL'] ?? '';
    
    if (empty($webhook_url)) {
        error_log('N8N webhook URL not configured');
        return false;
    }
    
    $payload = [
        'workflow' => $workflow,
        'data' => $data,
        'timestamp' => time(),
        'source' => 'eloscope_website'
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $webhook_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-Source: eloscope-website'
        ],
        CURLOPT_TIMEOUT => 5
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        error_log('N8N workflow trigger failed: ' . $response);
        return false;
    }
    
    return true;
}
?>