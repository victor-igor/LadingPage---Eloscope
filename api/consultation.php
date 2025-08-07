<?php
/**
 * Consultation API Endpoint
 * Handles consultation booking requests
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
        'business_type' => $_POST['business_type'] ?? '',
        'monthly_revenue' => $_POST['monthly_revenue'] ?? '',
        'main_challenge' => $_POST['main_challenge'] ?? '',
        'preferred_date' => $_POST['preferred_date'] ?? '',
        'preferred_time' => $_POST['preferred_time'] ?? '',
        'source' => $_POST['source'] ?? 'consultation_form',
        'utm_source' => $_POST['utm_source'] ?? '',
        'utm_medium' => $_POST['utm_medium'] ?? '',
        'utm_campaign' => $_POST['utm_campaign'] ?? ''
    ];
    
    // Sanitize data
    $data = sanitizeFormData($data);
    
    // Validate data
    $validator = validateConsultation($data);
    
    if (!$validator->isValid()) {
        echo json_encode([
            'success' => false,
            'message' => 'Dados inválidos',
            'errors' => $validator->getErrors()
        ]);
        exit();
    }
    
    // Check rate limiting
    if (!checkFormRateLimit('consultation', getClientIP())) {
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
            'message' => 'Solicitação rejeitada pelo filtro de spam.'
        ]);
        exit();
    }
    
    // Calculate lead score
    $lead_score = calculateLeadScore($data);
    
    // Insert consultation request
    $stmt = $pdo->prepare("
        INSERT INTO consultation_requests (
            name, email, phone, company, business_type, monthly_revenue, 
            main_challenge, preferred_date, preferred_time, source, 
            utm_source, utm_medium, utm_campaign, lead_score, 
            ip_address, user_agent
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['company'],
        $data['business_type'],
        $data['monthly_revenue'],
        $data['main_challenge'],
        $data['preferred_date'],
        $data['preferred_time'],
        $data['source'],
        $data['utm_source'],
        $data['utm_medium'],
        $data['utm_campaign'],
        $lead_score,
        getClientIP(),
        getUserAgent()
    ]);
    
    $consultation_id = $pdo->lastInsertId();
    
    // Log the consultation request
    logActivity('consultation_request', [
        'consultation_id' => $consultation_id,
        'email' => $data['email'],
        'lead_score' => $lead_score,
        'source' => $data['source']
    ]);
    
    // Send notification email to admin
    sendConsultationNotification($data, $consultation_id, $lead_score);
    
    // Send confirmation email to user
    sendConsultationConfirmation($data, $consultation_id);
    
    // Integrate with CRM
    integrateCRMConsultation([
        'email' => $data['email'],
        'name' => $data['name'],
        'phone' => $data['phone'],
        'company' => $data['company'],
        'business_type' => $data['business_type'],
        'monthly_revenue' => $data['monthly_revenue'],
        'main_challenge' => $data['main_challenge'],
        'preferred_date' => $data['preferred_date'],
        'preferred_time' => $data['preferred_time'],
        'source' => 'consultation_' . $data['source'],
        'lead_score' => $lead_score,
        'tags' => ['consultation', 'hot-lead', 'priority'],
        'utm_data' => [
            'source' => $data['utm_source'],
            'medium' => $data['utm_medium'],
            'campaign' => $data['utm_campaign']
        ]
    ]);
    
    // Trigger N8N automation
    triggerN8NConsultationWorkflow('consultation_request', [
        'consultation_id' => $consultation_id,
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'company' => $data['company'],
        'business_type' => $data['business_type'],
        'monthly_revenue' => $data['monthly_revenue'],
        'main_challenge' => $data['main_challenge'],
        'preferred_date' => $data['preferred_date'],
        'preferred_time' => $data['preferred_time'],
        'lead_score' => $lead_score,
        'source' => $data['source'],
        'utm_data' => [
            'source' => $data['utm_source'],
            'medium' => $data['utm_medium'],
            'campaign' => $data['utm_campaign']
        ]
    ]);
    
    // Schedule calendar booking if high-priority lead
    if ($lead_score >= 80) {
        scheduleCalendarBooking($data, $consultation_id);
    }
    
    // Track analytics event
    trackEvent('consultation_request', [
        'source' => $data['source'],
        'business_type' => $data['business_type'],
        'monthly_revenue' => $data['monthly_revenue'],
        'lead_score' => $lead_score,
        'utm_source' => $data['utm_source'],
        'utm_medium' => $data['utm_medium'],
        'utm_campaign' => $data['utm_campaign']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Consultoria agendada com sucesso! Entraremos em contato em breve.',
        'consultation_id' => $consultation_id,
        'lead_score' => $lead_score
    ]);
    
} catch (Exception $e) {
    error_log('Consultation API Error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno. Tente novamente mais tarde.'
    ]);
}

/**
 * Calculate lead score based on provided data
 */
function calculateLeadScore($data) {
    $score = 0;
    
    // Company size/revenue scoring
    switch ($data['monthly_revenue']) {
        case 'above_100k':
            $score += 40;
            break;
        case '50k_100k':
            $score += 35;
            break;
        case '20k_50k':
            $score += 25;
            break;
        case '10k_20k':
            $score += 15;
            break;
        case 'below_10k':
            $score += 5;
            break;
    }
    
    // Business type scoring
    $high_value_types = ['tech', 'saas', 'ecommerce', 'digital_marketing', 'real_estate'];
    if (in_array($data['business_type'], $high_value_types)) {
        $score += 20;
    } else {
        $score += 10;
    }
    
    // Challenge complexity scoring
    $high_impact_challenges = ['lead_qualification', 'sales_automation', 'customer_retention', 'process_optimization'];
    if (in_array($data['main_challenge'], $high_impact_challenges)) {
        $score += 15;
    } else {
        $score += 5;
    }
    
    // Contact completeness
    if (!empty($data['phone'])) $score += 10;
    if (!empty($data['company'])) $score += 5;
    
    // UTM source quality
    $high_quality_sources = ['google_ads', 'facebook_ads', 'linkedin', 'referral'];
    if (in_array($data['utm_source'], $high_quality_sources)) {
        $score += 10;
    }
    
    return min($score, 100); // Cap at 100
}

/**
 * Send notification email to admin
 */
function sendConsultationNotification($data, $consultation_id, $lead_score) {
    $admin_email = $_ENV['NOTIFICATION_EMAIL'] ?? 'admin@eloscope.com';
    $priority = $lead_score >= 80 ? '🔥 ALTA PRIORIDADE' : ($lead_score >= 60 ? '⚡ MÉDIA PRIORIDADE' : '📋 BAIXA PRIORIDADE');
    $subject = "$priority - Nova Consultoria Agendada - Eloscope";
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Inter', Arial, sans-serif; background: #f5f5f5; color: #333; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); color: white; padding: 20px; text-align: center; }
            .priority-high { background: #dc3545; }
            .priority-medium { background: #ffc107; color: #000; }
            .priority-low { background: #28a745; }
            .content { padding: 30px; }
            .score-badge { 
                display: inline-block; 
                background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); 
                color: white; 
                padding: 10px 20px; 
                border-radius: 25px; 
                font-weight: bold; 
                font-size: 18px;
                margin: 10px 0;
            }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #555; }
            .value { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 5px; }
            .urgent { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .actions { background: #e9ecef; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .action-button { 
                display: inline-block; 
                background: #007bff; 
                color: white; 
                padding: 10px 20px; 
                text-decoration: none; 
                border-radius: 5px; 
                margin: 5px;
            }
            .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>🎯 Nova Consultoria Agendada</h2>
                <p>ID: #{$consultation_id}</p>
                <div class='score-badge'>Score: {$lead_score}/100</div>
            </div>
            
            <div class='content'>
                <div class='urgent'>
                    $priority<br>
                    ⏰ <strong>Ação necessária:</strong> Entrar em contato em até 1 hora para leads de alta prioridade
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
                    <div class='value'><a href='tel:{$data['phone']}'>{$data['phone']}</a></div>
                </div>
                
                <div class='field'>
                    <div class='label'>🏢 Empresa:</div>
                    <div class='value'>{$data['company']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🏭 Tipo de Negócio:</div>
                    <div class='value'>{$data['business_type']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>💰 Faturamento Mensal:</div>
                    <div class='value'>{$data['monthly_revenue']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🎯 Principal Desafio:</div>
                    <div class='value'>{$data['main_challenge']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>📅 Data Preferida:</div>
                    <div class='value'>{$data['preferred_date']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🕒 Horário Preferido:</div>
                    <div class='value'>{$data['preferred_time']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>📊 Origem:</div>
                    <div class='value'>{$data['source']}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>🔗 UTM Data:</div>
                    <div class='value'>
                        Source: {$data['utm_source']}<br>
                        Medium: {$data['utm_medium']}<br>
                        Campaign: {$data['utm_campaign']}
                    </div>
                </div>
                
                <div class='actions'>
                    <h3>🚀 Ações Rápidas:</h3>
                    <a href='mailto:{$data['email']}' class='action-button'>📧 Enviar Email</a>
                    <a href='tel:{$data['phone']}' class='action-button'>📞 Ligar Agora</a>
                    <a href='https://wa.me/55{$data['phone']}' class='action-button'>💬 WhatsApp</a>
                    <a href='https://calendar.google.com/calendar/render?action=TEMPLATE&text=Consultoria+{$data['name']}&dates={$data['preferred_date']}' class='action-button'>📅 Agendar</a>
                </div>
            </div>
            
            <div class='footer'>
                <p>📈 <strong>Próximos passos sugeridos:</strong></p>
                <p>1. Ligar em até 1 hora (leads quentes)</p>
                <p>2. Enviar material personalizado</p>
                <p>3. Agendar call de qualificação</p>
                <p>4. Adicionar ao pipeline prioritário</p>
                <hr>
                <p>Sistema de Automação Eloscope | " . date('Y') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    sendEmail($admin_email, $subject, $message);
    
    // Send high-priority Slack notification
    if ($lead_score >= 80 && !empty($_ENV['SLACK_WEBHOOK_URL'])) {
        sendSlackNotification([
            'text' => '🔥 LEAD QUENTE! Nova consultoria de alta prioridade!',
            'attachments' => [[
                'color' => 'danger',
                'fields' => [
                    ['title' => 'Nome', 'value' => $data['name'], 'short' => true],
                    ['title' => 'Score', 'value' => $lead_score . '/100', 'short' => true],
                    ['title' => 'Email', 'value' => $data['email'], 'short' => true],
                    ['title' => 'Telefone', 'value' => $data['phone'], 'short' => true],
                    ['title' => 'Empresa', 'value' => $data['company'], 'short' => true],
                    ['title' => 'Faturamento', 'value' => $data['monthly_revenue'], 'short' => true]
                ],
                'actions' => [
                    [
                        'type' => 'button',
                        'text' => '📞 Ligar Agora',
                        'url' => 'tel:' . $data['phone']
                    ],
                    [
                        'type' => 'button',
                        'text' => '💬 WhatsApp',
                        'url' => 'https://wa.me/55' . preg_replace('/[^0-9]/', '', $data['phone'])
                    ]
                ]
            ]]
        ]);
    }
}

/**
 * Send confirmation email to user
 */
function sendConsultationConfirmation($data, $consultation_id) {
    $subject = '✅ Consultoria Agendada com Sucesso! - Eloscope';
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Inter', Arial, sans-serif; background: #0a0a0a; color: #ffffff; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { color: #00ffff; font-size: 28px; font-weight: bold; }
            .content { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 15px; }
            .highlight { background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold; }
            .success-badge { 
                background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); 
                color: #0a0a0a; 
                padding: 15px 30px; 
                border-radius: 25px; 
                font-weight: bold;
                text-align: center;
                margin: 20px 0;
                font-size: 18px;
            }
            .info-box { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px; margin: 20px 0; }
            .timeline { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px; margin: 20px 0; }
            .timeline-item { margin: 15px 0; padding-left: 25px; border-left: 3px solid #00ffff; }
            .cta-button { 
                display: inline-block; 
                background: linear-gradient(135deg, #00ffff 0%, #ff00ff 100%); 
                color: #0a0a0a; 
                padding: 15px 30px; 
                text-decoration: none; 
                border-radius: 8px; 
                font-weight: bold;
                margin: 10px 5px;
            }
            .benefits { background: rgba(0,255,255,0.1); padding: 20px; border-radius: 10px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='logo'>ELOSCOPE</div>
                <p>Automação Inteligente para Negócios</p>
            </div>
            
            <div class='content'>
                <div class='success-badge'>
                    ✅ Consultoria Agendada com Sucesso!
                </div>
                
                <h2>Parabéns, {$data['name']}! 🎉</h2>
                
                <p>Sua solicitação de consultoria foi recebida e nossa equipe está <span class='highlight'>muito animada</span> para ajudar você a revolucionar seu negócio!</p>
                
                <div class='info-box'>
                    <h3>📋 Resumo da sua solicitação:</h3>
                    <p><strong>🏢 Empresa:</strong> {$data['company']}</p>
                    <p><strong>🏭 Tipo de Negócio:</strong> {$data['business_type']}</p>
                    <p><strong>🎯 Principal Desafio:</strong> {$data['main_challenge']}</p>
                    <p><strong>📅 Data Preferida:</strong> {$data['preferred_date']}</p>
                    <p><strong>🕒 Horário Preferido:</strong> {$data['preferred_time']}</p>
                    <p><strong>🆔 ID da Consultoria:</strong> #{$consultation_id}</p>
                </div>
                
                <div class='timeline'>
                    <h3>📋 O que acontece agora:</h3>
                    <div class='timeline-item'>
                        <strong>✅ Agora:</strong> Sua solicitação foi recebida e está sendo analisada por nossa equipe
                    </div>
                    <div class='timeline-item'>
                        <strong>⏰ Em até 1 hora:</strong> Nossa equipe entrará em contato para confirmar o agendamento
                    </div>
                    <div class='timeline-item'>
                        <strong>📞 Próximo passo:</strong> Call de qualificação personalizada (30-45 min)
                    </div>
                    <div class='timeline-item'>
                        <strong>🎯 Resultado:</strong> Plano de automação customizado para seu negócio
                    </div>
                </div>
                
                <div class='benefits'>
                    <h3>🚀 O que você vai receber na consultoria:</h3>
                    <ul>
                        <li>✅ <strong>Análise completa</strong> do seu processo atual</li>
                        <li>✅ <strong>Identificação de gargalos</strong> e oportunidades</li>
                        <li>✅ <strong>Plano de automação</strong> personalizado</li>
                        <li>✅ <strong>ROI projetado</strong> com nossa solução</li>
                        <li>✅ <strong>Demonstração prática</strong> da plataforma</li>
                        <li>✅ <strong>Próximos passos</strong> detalhados</li>
                    </ul>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <h3>Enquanto aguarda, explore nossos recursos:</h3>
                    <a href='https://eloscope.com/cases' class='cta-button'>📊 Cases de Sucesso</a>
                    <a href='https://eloscope.com/blog' class='cta-button'>🎓 Blog</a>
                    <a href='https://wa.me/5511999999999' class='cta-button'>💬 WhatsApp</a>
                </div>
                
                <p>Estamos <span class='highlight'>ansiosos</span> para mostrar como a automação inteligente pode transformar seu negócio e gerar resultados extraordinários!</p>
                
                <p>Atenciosamente,<br>
                <strong>Equipe Eloscope</strong> 🚀</p>
                
                <hr style='border: 1px solid rgba(255,255,255,0.1); margin: 30px 0;'>
                
                <p style='font-size: 12px; color: rgba(255,255,255,0.7);'>
                    📞 <strong>Dúvidas?</strong> Entre em contato:<br>
                    Email: contato@eloscope.com<br>
                    WhatsApp: (11) 99999-9999<br>
                    Site: eloscope.com
                </p>
                
                <p style='font-size: 12px; color: rgba(255,255,255,0.7);'>
                    Se você não solicitou esta consultoria, pode ignorar este email.<br>
                    ID da solicitação: #{$consultation_id}
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    sendEmail($data['email'], $subject, $message);
}

/**
 * Integrate with CRM for consultation
 */
function integrateCRMConsultation($data) {
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
            'consultation_date' => date('Y-m-d H:i:s'),
            'business_type' => $data['business_type'],
            'monthly_revenue' => $data['monthly_revenue'],
            'main_challenge' => $data['main_challenge'],
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'],
            'lead_score' => $data['lead_score'],
            'utm_source' => $data['utm_data']['source'] ?? '',
            'utm_medium' => $data['utm_data']['medium'] ?? '',
            'utm_campaign' => $data['utm_data']['campaign'] ?? ''
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
        error_log('CRM consultation integration failed: ' . $response);
        return false;
    }
    
    return true;
}

/**
 * Trigger N8N consultation workflow
 */
function triggerN8NConsultationWorkflow($workflow, $data) {
    $webhook_url = $_ENV['N8N_CONSULTATION_WEBHOOK_URL'] ?? $_ENV['N8N_WEBHOOK_URL'] ?? '';
    
    if (empty($webhook_url)) {
        error_log('N8N consultation webhook URL not configured');
        return false;
    }
    
    $payload = [
        'workflow' => $workflow,
        'data' => $data,
        'timestamp' => time(),
        'source' => 'eloscope_website',
        'priority' => $data['lead_score'] >= 80 ? 'high' : ($data['lead_score'] >= 60 ? 'medium' : 'low')
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $webhook_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-Source: eloscope-website',
            'X-Priority: ' . ($data['lead_score'] >= 80 ? 'high' : 'normal')
        ],
        CURLOPT_TIMEOUT => 5
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        error_log('N8N consultation workflow trigger failed: ' . $response);
        return false;
    }
    
    return true;
}

/**
 * Schedule calendar booking for high-priority leads
 */
function scheduleCalendarBooking($data, $consultation_id) {
    // This would integrate with calendar APIs like Calendly, Google Calendar, etc.
    // For now, we'll just log the high-priority lead
    
    logActivity('high_priority_consultation', [
        'consultation_id' => $consultation_id,
        'email' => $data['email'],
        'name' => $data['name'],
        'phone' => $data['phone'],
        'preferred_date' => $data['preferred_date'],
        'preferred_time' => $data['preferred_time']
    ]);
    
    // Send immediate notification for high-priority leads
    $admin_email = $_ENV['URGENT_NOTIFICATION_EMAIL'] ?? $_ENV['NOTIFICATION_EMAIL'] ?? 'admin@eloscope.com';
    $subject = '🔥 URGENTE: Lead de Alta Prioridade - Ação Imediata Necessária';
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; background: #ff4444; color: white; padding: 20px;'>
        <div style='background: white; color: #333; padding: 30px; border-radius: 10px; max-width: 500px; margin: 0 auto;'>
            <h1 style='color: #ff4444; text-align: center;'>🔥 LEAD QUENTE DETECTADO!</h1>
            <h2>Ação Imediata Necessária</h2>
            
            <p><strong>Nome:</strong> {$data['name']}</p>
            <p><strong>Email:</strong> {$data['email']}</p>
            <p><strong>Telefone:</strong> {$data['phone']}</p>
            <p><strong>Empresa:</strong> {$data['company']}</p>
            <p><strong>Faturamento:</strong> {$data['monthly_revenue']}</p>
            
            <div style='background: #ff4444; color: white; padding: 15px; border-radius: 5px; margin: 20px 0; text-align: center;'>
                <strong>⏰ LIGAR AGORA!</strong><br>
                Tempo de resposta ideal: 5 minutos
            </div>
            
            <p style='text-align: center;'>
                <a href='tel:{$data['phone']}' style='background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 10px;'>📞 LIGAR</a>
                <a href='https://wa.me/55{$data['phone']}' style='background: #25d366; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 10px;'>💬 WHATSAPP</a>
            </p>
        </div>
    </body>
    </html>
    ";
    
    sendEmail($admin_email, $subject, $message);
    
    return true;
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
?>