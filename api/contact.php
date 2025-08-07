<?php
/**
 * Contact API Endpoint
 * Handles contact form submissions
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
    // Get and sanitize input data
    $data = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'company' => $_POST['company'] ?? '',
        'message' => $_POST['message'] ?? '',
        'source' => $_POST['source'] ?? 'contact_form'
    ];
    
    // Sanitize data
    $data = sanitizeFormData($data);
    
    // Validate data
    $validator = validateContact($data);
    
    if (!$validator->isValid()) {
        echo json_encode([
            'success' => false,
            'message' => 'Dados inválidos',
            'errors' => $validator->getErrors()
        ]);
        exit();
    }
    
    // Check rate limiting
    if (!checkFormRateLimit('contact', getClientIP())) {
        echo json_encode([
            'success' => false,
            'message' => 'Muitas tentativas. Tente novamente mais tarde.'
        ]);
        exit();
    }
    
    // Check for spam
    if (isSpam($data)) {
        logActivity('spam_detected', $data);
        echo json_encode([
            'success' => false,
            'message' => 'Mensagem rejeitada pelo filtro de spam.'
        ]);
        exit();
    }
    
    // Insert contact submission
    $stmt = $pdo->prepare("
        INSERT INTO contact_submissions (name, email, phone, company, message, source, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['company'],
        $data['message'],
        $data['source'],
        getClientIP(),
        getUserAgent()
    ]);
    
    $submission_id = $pdo->lastInsertId();
    
    // Log the contact submission
    logActivity('contact_submission', [
        'submission_id' => $submission_id,
        'email' => $data['email'],
        'source' => $data['source']
    ]);
    
    // Send notification email to admin
    sendContactNotification($data, $submission_id);
    
    // Send auto-reply to user
    sendContactAutoReply($data);
    
    // Integrate with CRM
    integrateCRM([
        'email' => $data['email'],
        'name' => $data['name'],
        'phone' => $data['phone'],
        'company' => $data['company'],
        'source' => 'contact_' . $data['source'],
        'tags' => ['contact', 'lead'],
        'notes' => $data['message']
    ]);
    
    // Trigger N8N automation
    triggerN8NWorkflow('contact_submission', [
        'submission_id' => $submission_id,
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'company' => $data['company'],
        'message' => $data['message'],
        'source' => $data['source']
    ]);
    
    // Track analytics event
    trackEvent('contact_form_submission', [
        'source' => $data['source'],
        'has_phone' => !empty($data['phone']),
        'has_company' => !empty($data['company'])
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
    ]);
    
} catch (Exception $e) {
    error_log('Contact API Error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno. Tente novamente mais tarde.'
    ]);
}

/**
 * Send notification email to admin
 */
function sendContactNotification($data, $submission_id) {
    $admin_email = $_ENV['NOTIFICATION_EMAIL'] ?? 'admin@eloscope.com';
    $subject = '🔔 Nova mensagem de contato - Eloscope';
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Inter', Arial, sans-serif; background: #f5f5f5; color: #333; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
            .header { background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0; text-align: center; }
            .content { padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #555; }
            .value { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 5px; }
            .footer { background: #f8f9fa; padding: 15px; border-radius: 0 0 10px 10px; text-align: center; font-size: 12px; color: #666; }
            .urgent { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>🔔 Nova Mensagem de Contato</h2>
                <p>ID: #{$submission_id}</p>
            </div>
            
            <div class='content'>
                <div class='urgent'>
                    ⚡ <strong>Ação necessária:</strong> Responder em até 2 horas para maximizar conversão
                </div>
                
                <div class='field'>
                    <div class='label'>👤 Nome:</div>
                    <div class='value'>{$data['name']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>📧 Email:</div>
                    <div class='value'><a href='mailto:{$data['email']}'>{$data['email']}</a></div>
                </div>
                
                <div class='field'>
                    <div class='label'>📱 Telefone:</div>
                    <div class='value'>{$data['phone']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🏢 Empresa:</div>
                    <div class='value'>{$data['company']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>💬 Mensagem:</div>
                    <div class='value'>{$data['message']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>📊 Origem:</div>
                    <div class='value'>{$data['source']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🕒 Data/Hora:</div>
                    <div class='value'>" . date('d/m/Y H:i:s') . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🌐 IP:</div>
                    <div class='value'>" . getClientIP() . "</div>
                </div>
            </div>
            
            <div class='footer'>
                <p>📈 <strong>Próximos passos sugeridos:</strong></p>
                <p>1. Responder por email em até 2 horas</p>
                <p>2. Agendar call de qualificação</p>
                <p>3. Adicionar ao pipeline de vendas</p>
                <hr>
                <p>Sistema de Automação Eloscope | " . date('Y') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    sendEmail($admin_email, $subject, $message);
    
    // Send Slack notification if configured
    if (!empty($_ENV['SLACK_WEBHOOK_URL'])) {
        sendSlackNotification([
            'text' => '🔔 Nova mensagem de contato recebida!',
            'attachments' => [[
                'color' => 'good',
                'fields' => [
                    ['title' => 'Nome', 'value' => $data['name'], 'short' => true],
                    ['title' => 'Email', 'value' => $data['email'], 'short' => true],
                    ['title' => 'Empresa', 'value' => $data['company'], 'short' => true],
                    ['title' => 'Telefone', 'value' => $data['phone'], 'short' => true],
                    ['title' => 'Mensagem', 'value' => substr($data['message'], 0, 100) . '...', 'short' => false]
                ]
            ]]
        ]);
    }
}

/**
 * Send auto-reply to user
 */
function sendContactAutoReply($data) {
    $subject = 'Recebemos sua mensagem! - Eloscope';
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Inter', Arial, sans-serif; background: #0a0a0a; color: #ffffff; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { color: #00ffff; font-size: 24px; font-weight: bold; }
            .content { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 10px; }
            .highlight { background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold; }
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
            .timeline { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px; margin: 20px 0; }
            .timeline-item { margin: 10px 0; padding-left: 20px; border-left: 2px solid #00ffff; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='logo'>ELOSCOPE</div>
                <p>Automação Inteligente para Negócios</p>
            </div>
            
            <div class='content'>
                <h2>Olá, {$data['name']}! 👋</h2>
                
                <p>Recebemos sua mensagem e ficamos <span class='highlight'>muito empolgados</span> em ajudar você a revolucionar seu negócio!</p>
                
                <div class='timeline'>
                    <h3>📋 O que acontece agora:</h3>
                    <div class='timeline-item'>
                        <strong>✅ Agora:</strong> Sua mensagem foi recebida e está sendo analisada
                    </div>
                    <div class='timeline-item'>
                        <strong>⏰ Em até 2 horas:</strong> Nossa equipe entrará em contato
                    </div>
                    <div class='timeline-item'>
                        <strong>🎯 Próximo passo:</strong> Agendaremos uma consultoria personalizada
                    </div>
                </div>
                
                <p>Enquanto isso, que tal agendar uma <strong>consultoria gratuita</strong> para acelerar o processo?</p>
                
                <a href='https://eloscope.com/consultoria' class='cta-button'>🚀 Agendar Consultoria Gratuita</a>
                
                <h3>💡 Recursos que podem te interessar:</h3>
                <ul>
                    <li>📊 <a href='https://eloscope.com/cases' style='color: #00ffff;'>Cases de Sucesso</a> - Veja resultados reais</li>
                    <li>🎓 <a href='https://eloscope.com/blog' style='color: #00ffff;'>Blog</a> - Conteúdo exclusivo sobre automação</li>
                    <li>📱 <a href='https://wa.me/5511999999999' style='color: #00ffff;'>WhatsApp</a> - Fale conosco agora</li>
                </ul>
                
                <p>Estamos ansiosos para mostrar como a <span class='highlight'>automação inteligente</span> pode transformar seu negócio!</p>
                
                <p>Atenciosamente,<br>
                <strong>Equipe Eloscope</strong> 🚀</p>
                
                <hr style='border: 1px solid rgba(255,255,255,0.1); margin: 30px 0;'>
                
                <p style='font-size: 12px; color: rgba(255,255,255,0.7);'>
                    📧 Sua mensagem original:<br>
                    <em>\"{$data['message']}\"</em>
                </p>
                
                <p style='font-size: 12px; color: rgba(255,255,255,0.7);'>
                    Se você não enviou esta mensagem, pode ignorar este email.<br>
                    Para dúvidas, responda este email ou entre em contato: contato@eloscope.com
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    sendEmail($data['email'], $subject, $message);
}

/**
 * Send Slack notification
 */
function sendSlackNotification($payload) {
    $webhook_url = $_ENV['SLACK_WEBHOOK_URL'] ?? '';
    
    if (empty($webhook_url)) {
        return false;
    }
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $webhook_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 5
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $http_code === 200;
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
        'phone' => $data['phone'] ?? '',
        'companyName' => $data['company'] ?? '',
        'source' => $data['source'],
        'tags' => $data['tags'] ?? [],
        'customFields' => [
            'lead_source' => $data['source'],
            'contact_date' => date('Y-m-d H:i:s'),
            'initial_message' => $data['notes'] ?? ''
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