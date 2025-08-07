<?php
/**
 * Eloscope Website Helper Functions
 * Common functions used throughout the application
 */

/**
 * Log activity to database
 */
function logActivity($action, $data = [], $entity_type = null, $entity_id = null) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (action, entity_type, entity_id, data, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $action,
            $entity_type,
            $entity_id,
            json_encode($data),
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
        
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        error_log('Failed to log activity: ' . $e->getMessage());
        return false;
    }
}

/**
 * Track analytics event
 */
function trackEvent($event_name, $event_data = [], $session_id = null, $user_id = null) {
    global $pdo;
    
    try {
        if (!$session_id) {
            session_start();
            $session_id = session_id();
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO analytics_events (event_name, event_data, session_id, user_id, page_url, referrer, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $event_name,
            json_encode($event_data),
            $session_id,
            $user_id,
            $_SERVER['REQUEST_URI'] ?? '',
            $_SERVER['HTTP_REFERER'] ?? '',
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
        
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        error_log('Failed to track event: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send email using PHP mail or SMTP
 */
function sendEmail($to, $subject, $message, $from = null, $reply_to = null) {
    if (!$from) {
        $from = 'Eloscope <noreply@eloscope.com>';
    }
    
    if (!$reply_to) {
        $reply_to = 'contato@eloscope.com';
    }
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $from,
        'Reply-To: ' . $reply_to,
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 3',
        'X-MSMail-Priority: Normal'
    ];
    
    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Generate secure token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Brazilian format)
 */
function isValidPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^[1-9]{2}9?[0-9]{8}$/', $phone);
}

/**
 * Format phone number for display
 */
function formatPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    if (strlen($phone) === 11) {
        return preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $phone);
    } elseif (strlen($phone) === 10) {
        return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $phone);
    }
    
    return $phone;
}

/**
 * Get client IP address
 */
function getClientIP() {
    $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    
    foreach ($ip_keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/**
 * Get user agent information
 */
function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
}

/**
 * Check if request is from mobile device
 */
function isMobile() {
    $user_agent = getUserAgent();
    return preg_match('/Mobile|Android|iPhone|iPad|BlackBerry|Windows Phone/i', $user_agent);
}

/**
 * Get browser information
 */
function getBrowserInfo() {
    $user_agent = getUserAgent();
    
    $browsers = [
        'Chrome' => '/Chrome\/([0-9.]+)/',
        'Firefox' => '/Firefox\/([0-9.]+)/',
        'Safari' => '/Safari\/([0-9.]+)/',
        'Edge' => '/Edge\/([0-9.]+)/',
        'Opera' => '/Opera\/([0-9.]+)/',
        'Internet Explorer' => '/MSIE ([0-9.]+)/'
    ];
    
    foreach ($browsers as $browser => $pattern) {
        if (preg_match($pattern, $user_agent, $matches)) {
            return [
                'name' => $browser,
                'version' => $matches[1] ?? 'unknown'
            ];
        }
    }
    
    return ['name' => 'Unknown', 'version' => 'unknown'];
}

/**
 * Generate slug from string
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[áàâãä]/u', 'a', $string);
    $string = preg_replace('/[éèêë]/u', 'e', $string);
    $string = preg_replace('/[íìîï]/u', 'i', $string);
    $string = preg_replace('/[óòôõö]/u', 'o', $string);
    $string = preg_replace('/[úùûü]/u', 'u', $string);
    $string = preg_replace('/[ç]/u', 'c', $string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}

/**
 * Format currency (Brazilian Real)
 */
function formatCurrency($amount) {
    return 'R$ ' . number_format($amount, 2, ',', '.');
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    return $date->format($format);
}

/**
 * Time ago function
 */
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'agora mesmo';
    if ($time < 3600) return floor($time/60) . ' minutos atrás';
    if ($time < 86400) return floor($time/3600) . ' horas atrás';
    if ($time < 2592000) return floor($time/86400) . ' dias atrás';
    if ($time < 31536000) return floor($time/2592000) . ' meses atrás';
    return floor($time/31536000) . ' anos atrás';
}

/**
 * Truncate text
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Generate excerpt from content
 */
function generateExcerpt($content, $length = 160) {
    $content = strip_tags($content);
    $content = preg_replace('/\s+/', ' ', $content);
    return truncateText(trim($content), $length);
}

/**
 * Check if string contains any of the given needles
 */
function containsAny($haystack, $needles) {
    foreach ($needles as $needle) {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Rate limiting function
 */
function checkRateLimit($identifier, $max_attempts = 5, $time_window = 300) {
    $cache_key = 'rate_limit_' . md5($identifier);
    $cache_file = sys_get_temp_dir() . '/' . $cache_key;
    
    $attempts = [];
    if (file_exists($cache_file)) {
        $attempts = json_decode(file_get_contents($cache_file), true) ?: [];
    }
    
    // Remove old attempts
    $current_time = time();
    $attempts = array_filter($attempts, function($timestamp) use ($current_time, $time_window) {
        return ($current_time - $timestamp) < $time_window;
    });
    
    if (count($attempts) >= $max_attempts) {
        return false; // Rate limit exceeded
    }
    
    // Add current attempt
    $attempts[] = $current_time;
    file_put_contents($cache_file, json_encode($attempts));
    
    return true; // Within rate limit
}

/**
 * Generate QR Code URL
 */
function generateQRCode($data, $size = 200) {
    return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($data);
}

/**
 * Get country from IP address
 */
function getCountryFromIP($ip = null) {
    if (!$ip) {
        $ip = getClientIP();
    }
    
    // Simple IP to country lookup (you might want to use a proper service)
    $url = "http://ip-api.com/json/{$ip}";
    $response = @file_get_contents($url);
    
    if ($response) {
        $data = json_decode($response, true);
        return $data['country'] ?? 'Unknown';
    }
    
    return 'Unknown';
}

/**
 * Convert array to CSV
 */
function arrayToCSV($array, $filename = null) {
    if ($filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }
    
    $output = fopen('php://output', 'w');
    
    if (!empty($array)) {
        // Add headers
        fputcsv($output, array_keys($array[0]));
        
        // Add data
        foreach ($array as $row) {
            fputcsv($output, $row);
        }
    }
    
    fclose($output);
}

/**
 * Upload file securely
 */
function uploadFile($file, $upload_dir = 'uploads/', $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf']) {
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }
    
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
    
    if ($file['size'] > 5000000) { // 5MB limit
        throw new RuntimeException('Exceeded filesize limit.');
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    
    $allowed_mimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf'
    ];
    
    $ext = array_search($mime_type, $allowed_mimes, true);
    
    if (!$ext || !in_array($ext, $allowed_types)) {
        throw new RuntimeException('Invalid file format.');
    }
    
    $filename = sprintf('%s.%s', sha1_file($file['tmp_name']), $ext);
    $filepath = $upload_dir . $filename;
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    
    return $filepath;
}

/**
 * Generate random password
 */
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 0, $length);
}

/**
 * Check if URL is valid
 */
function isValidURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Get file extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Format file size
 */
function formatFileSize($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Debug function
 */
function debug($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Log to file
 */
function logToFile($message, $file = 'app.log') {
    $log_dir = __DIR__ . '/../logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[{$timestamp}] {$message}" . PHP_EOL;
    
    file_put_contents($log_dir . '/' . $file, $log_message, FILE_APPEND | LOCK_EX);
}

/**
 * Get system information
 */
function getSystemInfo() {
    return [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown',
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size')
    ];
}
?>