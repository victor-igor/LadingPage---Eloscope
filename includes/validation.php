<?php
/**
 * Validation Functions
 * Eloscope Website Form Validation
 */

class Validator {
    private $errors = [];
    private $data = [];
    
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Validate required field
     */
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || empty(trim($this->data[$field]))) {
            $this->errors[$field] = $message ?: "O campo {$field} é obrigatório";
        }
        return $this;
    }
    
    /**
     * Validate email
     */
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = $message ?: "Email inválido";
            }
        }
        return $this;
    }
    
    /**
     * Validate minimum length
     */
    public function minLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?: "O campo {$field} deve ter pelo menos {$length} caracteres";
        }
        return $this;
    }
    
    /**
     * Validate maximum length
     */
    public function maxLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?: "O campo {$field} deve ter no máximo {$length} caracteres";
        }
        return $this;
    }
    
    /**
     * Validate phone number (Brazilian format)
     */
    public function phone($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $phone = preg_replace('/[^0-9]/', '', $this->data[$field]);
            if (!preg_match('/^[1-9]{2}9?[0-9]{8}$/', $phone)) {
                $this->errors[$field] = $message ?: "Telefone inválido";
            }
        }
        return $this;
    }
    
    /**
     * Validate URL
     */
    public function url($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
                $this->errors[$field] = $message ?: "URL inválida";
            }
        }
        return $this;
    }
    
    /**
     * Validate numeric value
     */
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!is_numeric($this->data[$field])) {
                $this->errors[$field] = $message ?: "O campo {$field} deve ser numérico";
            }
        }
        return $this;
    }
    
    /**
     * Validate integer value
     */
    public function integer($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
                $this->errors[$field] = $message ?: "O campo {$field} deve ser um número inteiro";
            }
        }
        return $this;
    }
    
    /**
     * Validate value is in array
     */
    public function in($field, $values, $message = null) {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = $message ?: "Valor inválido para o campo {$field}";
        }
        return $this;
    }
    
    /**
     * Validate regex pattern
     */
    public function regex($field, $pattern, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!preg_match($pattern, $this->data[$field])) {
                $this->errors[$field] = $message ?: "Formato inválido para o campo {$field}";
            }
        }
        return $this;
    }
    
    /**
     * Validate CNPJ (Brazilian company ID)
     */
    public function cnpj($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $cnpj = preg_replace('/[^0-9]/', '', $this->data[$field]);
            
            if (strlen($cnpj) !== 14 || !$this->isValidCNPJ($cnpj)) {
                $this->errors[$field] = $message ?: "CNPJ inválido";
            }
        }
        return $this;
    }
    
    /**
     * Validate CPF (Brazilian personal ID)
     */
    public function cpf($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $cpf = preg_replace('/[^0-9]/', '', $this->data[$field]);
            
            if (strlen($cpf) !== 11 || !$this->isValidCPF($cpf)) {
                $this->errors[$field] = $message ?: "CPF inválido";
            }
        }
        return $this;
    }
    
    /**
     * Custom validation function
     */
    public function custom($field, $callback, $message = null) {
        if (isset($this->data[$field])) {
            if (!call_user_func($callback, $this->data[$field])) {
                $this->errors[$field] = $message ?: "Valor inválido para o campo {$field}";
            }
        }
        return $this;
    }
    
    /**
     * Check if validation passed
     */
    public function isValid() {
        return empty($this->errors);
    }
    
    /**
     * Get validation errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get first error
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Get errors as string
     */
    public function getErrorsAsString($separator = ', ') {
        return implode($separator, $this->errors);
    }
    
    /**
     * Validate CNPJ algorithm
     */
    private function isValidCNPJ($cnpj) {
        // Check for known invalid CNPJs
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }
        
        // Validate first check digit
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }
        
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;
        
        if ($cnpj[12] != $digit1) {
            return false;
        }
        
        // Validate second check digit
        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }
        
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;
        
        return $cnpj[13] == $digit2;
    }
    
    /**
     * Validate CPF algorithm
     */
    private function isValidCPF($cpf) {
        // Check for known invalid CPFs
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        
        // Validate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;
        
        if ($cpf[9] != $digit1) {
            return false;
        }
        
        // Validate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;
        
        return $cpf[10] == $digit2;
    }
}

/**
 * Newsletter validation rules
 */
function validateNewsletter($data) {
    $validator = new Validator($data);
    
    $validator
        ->required('email', 'Email é obrigatório')
        ->email('email', 'Email inválido');
    
    if (isset($data['name'])) {
        $validator->minLength('name', 2, 'Nome deve ter pelo menos 2 caracteres');
    }
    
    return $validator;
}

/**
 * Contact form validation rules
 */
function validateContact($data) {
    $validator = new Validator($data);
    
    $validator
        ->required('name', 'Nome é obrigatório')
        ->minLength('name', 2, 'Nome deve ter pelo menos 2 caracteres')
        ->maxLength('name', 100, 'Nome deve ter no máximo 100 caracteres')
        ->required('email', 'Email é obrigatório')
        ->email('email', 'Email inválido')
        ->required('message', 'Mensagem é obrigatória')
        ->minLength('message', 10, 'Mensagem deve ter pelo menos 10 caracteres')
        ->maxLength('message', 1000, 'Mensagem deve ter no máximo 1000 caracteres');
    
    if (isset($data['phone']) && !empty($data['phone'])) {
        $validator->phone('phone', 'Telefone inválido');
    }
    
    if (isset($data['company'])) {
        $validator->maxLength('company', 100, 'Nome da empresa deve ter no máximo 100 caracteres');
    }
    
    return $validator;
}

/**
 * Consultation form validation rules
 */
function validateConsultation($data) {
    $validator = new Validator($data);
    
    $validator
        ->required('name', 'Nome é obrigatório')
        ->minLength('name', 2, 'Nome deve ter pelo menos 2 caracteres')
        ->maxLength('name', 100, 'Nome deve ter no máximo 100 caracteres')
        ->required('email', 'Email é obrigatório')
        ->email('email', 'Email inválido')
        ->required('phone', 'Telefone é obrigatório')
        ->phone('phone', 'Telefone inválido')
        ->required('company', 'Nome da empresa é obrigatório')
        ->minLength('company', 2, 'Nome da empresa deve ter pelo menos 2 caracteres')
        ->maxLength('company', 100, 'Nome da empresa deve ter no máximo 100 caracteres');
    
    if (isset($data['website']) && !empty($data['website'])) {
        $validator->url('website', 'Website inválido');
    }
    
    if (isset($data['revenue_range'])) {
        $valid_ranges = [
            'up_to_100k', '100k_500k', '500k_1m', '1m_5m', '5m_10m', 'over_10m'
        ];
        $validator->in('revenue_range', $valid_ranges, 'Faixa de faturamento inválida');
    }
    
    if (isset($data['employees_count'])) {
        $valid_counts = [
            '1_5', '6_20', '21_50', '51_100', '101_500', 'over_500'
        ];
        $validator->in('employees_count', $valid_counts, 'Número de funcionários inválido');
    }
    
    if (isset($data['urgency'])) {
        $valid_urgencies = ['immediate', 'this_month', 'next_quarter', 'exploring'];
        $validator->in('urgency', $valid_urgencies, 'Urgência inválida');
    }
    
    if (isset($data['budget_range'])) {
        $valid_budgets = [
            'up_to_10k', '10k_25k', '25k_50k', '50k_100k', 'over_100k'
        ];
        $validator->in('budget_range', $valid_budgets, 'Faixa de orçamento inválida');
    }
    
    if (isset($data['preferred_contact'])) {
        $valid_contacts = ['email', 'phone', 'whatsapp'];
        $validator->in('preferred_contact', $valid_contacts, 'Forma de contato preferida inválida');
    }
    
    if (isset($data['main_challenge'])) {
        $validator
            ->minLength('main_challenge', 10, 'Desafio principal deve ter pelo menos 10 caracteres')
            ->maxLength('main_challenge', 500, 'Desafio principal deve ter no máximo 500 caracteres');
    }
    
    if (isset($data['current_tools'])) {
        $validator->maxLength('current_tools', 300, 'Ferramentas atuais deve ter no máximo 300 caracteres');
    }
    
    return $validator;
}

/**
 * Sanitize form data
 */
function sanitizeFormData($data) {
    $sanitized = [];
    
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            // Remove HTML tags and trim whitespace
            $sanitized[$key] = trim(strip_tags($value));
            
            // Special handling for specific fields
            switch ($key) {
                case 'email':
                    $sanitized[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
                    break;
                    
                case 'phone':
                    // Keep only numbers, spaces, parentheses, and dashes
                    $sanitized[$key] = preg_replace('/[^0-9\s\(\)\-\+]/', '', $value);
                    break;
                    
                case 'website':
                case 'url':
                    $sanitized[$key] = filter_var($value, FILTER_SANITIZE_URL);
                    break;
                    
                case 'message':
                case 'main_challenge':
                case 'current_tools':
                    // Allow basic formatting but remove dangerous content
                    $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    break;
                    
                default:
                    $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        } else {
            $sanitized[$key] = $value;
        }
    }
    
    return $sanitized;
}

/**
 * Check for spam patterns
 */
function isSpam($data) {
    $spam_patterns = [
        '/\b(viagra|cialis|casino|poker|lottery|winner|congratulations)\b/i',
        '/\b(click here|free money|make money|work from home)\b/i',
        '/\b(buy now|limited time|act now|urgent)\b/i',
        '/[A-Z]{10,}/', // Too many consecutive capitals
        '/\b\w*\d{4,}\w*\b/', // Words with 4+ consecutive numbers
    ];
    
    $text = implode(' ', array_values($data));
    
    foreach ($spam_patterns as $pattern) {
        if (preg_match($pattern, $text)) {
            return true;
        }
    }
    
    // Check for too many URLs
    $url_count = preg_match_all('/https?:\/\//', $text);
    if ($url_count > 2) {
        return true;
    }
    
    // Check for suspicious email patterns
    if (isset($data['email'])) {
        $suspicious_domains = [
            'tempmail.org', '10minutemail.com', 'guerrillamail.com',
            'mailinator.com', 'throwaway.email'
        ];
        
        $email_domain = substr(strrchr($data['email'], '@'), 1);
        if (in_array($email_domain, $suspicious_domains)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Validate reCAPTCHA
 */
function validateRecaptcha($token) {
    $secret_key = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
    
    if (empty($secret_key) || empty($token)) {
        return false;
    }
    
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret_key,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        return false;
    }
    
    $result = json_decode($response, true);
    
    return isset($result['success']) && $result['success'] === true;
}

/**
 * Rate limiting for form submissions
 */
function checkFormRateLimit($form_type, $identifier = null) {
    if (!$identifier) {
        $identifier = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    $limits = [
        'newsletter' => ['max' => 3, 'window' => 3600], // 3 per hour
        'contact' => ['max' => 5, 'window' => 3600],    // 5 per hour
        'consultation' => ['max' => 2, 'window' => 86400] // 2 per day
    ];
    
    if (!isset($limits[$form_type])) {
        return true; // No limit defined
    }
    
    $limit = $limits[$form_type];
    $cache_key = "form_limit_{$form_type}_" . md5($identifier);
    
    return checkRateLimit($cache_key, $limit['max'], $limit['window']);
}
?>