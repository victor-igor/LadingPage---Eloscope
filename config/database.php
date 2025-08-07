<?php
/**
 * Database Configuration
 * Eloscope Website Database Connection
 */

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Database configuration
$db_config = [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname' => $_ENV['DB_NAME'] ?? 'eloscope_db',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]
];

try {
    // Create PDO connection
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], $db_config['options']);
    
    // Set timezone
    $pdo->exec("SET time_zone = '-03:00'"); // Brazil timezone
    
} catch (PDOException $e) {
    // Log error
    error_log('Database connection failed: ' . $e->getMessage());
    
    // Show user-friendly error in development
    if ($_ENV['APP_ENV'] === 'development') {
        die('Database connection failed: ' . $e->getMessage());
    } else {
        die('Database connection failed. Please try again later.');
    }
}

/**
 * Database Helper Functions
 */

/**
 * Execute a query and return results
 */
function executeQuery($query, $params = []) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log('Query execution failed: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Get a single record
 */
function getRecord($query, $params = []) {
    $stmt = executeQuery($query, $params);
    return $stmt->fetch();
}

/**
 * Get multiple records
 */
function getRecords($query, $params = []) {
    $stmt = executeQuery($query, $params);
    return $stmt->fetchAll();
}

/**
 * Insert a record and return the ID
 */
function insertRecord($table, $data) {
    global $pdo;
    
    $columns = implode(',', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    
    $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
    
    $stmt = executeQuery($query, $data);
    return $pdo->lastInsertId();
}

/**
 * Update a record
 */
function updateRecord($table, $data, $where, $whereParams = []) {
    $setParts = [];
    foreach (array_keys($data) as $key) {
        $setParts[] = "{$key} = :{$key}";
    }
    $setClause = implode(', ', $setParts);
    
    $query = "UPDATE {$table} SET {$setClause} WHERE {$where}";
    
    $params = array_merge($data, $whereParams);
    $stmt = executeQuery($query, $params);
    
    return $stmt->rowCount();
}

/**
 * Delete a record
 */
function deleteRecord($table, $where, $params = []) {
    $query = "DELETE FROM {$table} WHERE {$where}";
    $stmt = executeQuery($query, $params);
    return $stmt->rowCount();
}

/**
 * Check if table exists
 */
function tableExists($tableName) {
    global $pdo;
    
    $query = "SHOW TABLES LIKE ?";
    $stmt = executeQuery($query, [$tableName]);
    return $stmt->rowCount() > 0;
}

/**
 * Create database tables if they don't exist
 */
function createTables() {
    global $pdo;
    
    // Newsletter subscribers table
    if (!tableExists('newsletter_subscribers')) {
        $sql = "
            CREATE TABLE newsletter_subscribers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                source VARCHAR(100) DEFAULT 'website',
                status ENUM('active', 'inactive', 'unsubscribed') DEFAULT 'active',
                subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                unsubscribed_at TIMESTAMP NULL,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_status (status),
                INDEX idx_source (source)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
    
    // Contact form submissions
    if (!tableExists('contact_submissions')) {
        $sql = "
            CREATE TABLE contact_submissions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(50),
                company VARCHAR(255),
                message TEXT NOT NULL,
                source VARCHAR(100) DEFAULT 'contact_form',
                status ENUM('new', 'contacted', 'qualified', 'converted', 'closed') DEFAULT 'new',
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_status (status),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
    
    // Consultation requests
    if (!tableExists('consultation_requests')) {
        $sql = "
            CREATE TABLE consultation_requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(50),
                company VARCHAR(255),
                website VARCHAR(255),
                revenue_range VARCHAR(100),
                employees_count VARCHAR(100),
                main_challenge TEXT,
                current_tools TEXT,
                urgency ENUM('immediate', 'this_month', 'next_quarter', 'exploring') DEFAULT 'exploring',
                budget_range VARCHAR(100),
                preferred_contact ENUM('email', 'phone', 'whatsapp') DEFAULT 'email',
                source VARCHAR(100) DEFAULT 'consultation_form',
                status ENUM('new', 'scheduled', 'completed', 'qualified', 'converted', 'closed') DEFAULT 'new',
                scheduled_at TIMESTAMP NULL,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_status (status),
                INDEX idx_urgency (urgency),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
    
    // Activity log
    if (!tableExists('activity_log')) {
        $sql = "
            CREATE TABLE activity_log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                action VARCHAR(100) NOT NULL,
                entity_type VARCHAR(100),
                entity_id INT,
                data JSON,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_action (action),
                INDEX idx_entity (entity_type, entity_id),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
    
    // Analytics data
    if (!tableExists('analytics_events')) {
        $sql = "
            CREATE TABLE analytics_events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_name VARCHAR(100) NOT NULL,
                event_data JSON,
                session_id VARCHAR(255),
                user_id VARCHAR(255),
                page_url VARCHAR(500),
                referrer VARCHAR(500),
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_event (event_name),
                INDEX idx_session (session_id),
                INDEX idx_user (user_id),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
    
    // Blog posts (for future content marketing)
    if (!tableExists('blog_posts')) {
        $sql = "
            CREATE TABLE blog_posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                excerpt TEXT,
                content LONGTEXT NOT NULL,
                featured_image VARCHAR(500),
                author VARCHAR(255),
                category VARCHAR(100),
                tags JSON,
                meta_title VARCHAR(255),
                meta_description TEXT,
                status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
                published_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_slug (slug),
                INDEX idx_status (status),
                INDEX idx_category (category),
                INDEX idx_published (published_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
    
    // Success cases/testimonials
    if (!tableExists('success_cases')) {
        $sql = "
            CREATE TABLE success_cases (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_name VARCHAR(255) NOT NULL,
                client_company VARCHAR(255),
                client_role VARCHAR(255),
                client_photo VARCHAR(500),
                case_title VARCHAR(255) NOT NULL,
                case_description TEXT NOT NULL,
                challenge TEXT,
                solution TEXT,
                results TEXT,
                metrics JSON,
                testimonial TEXT,
                industry VARCHAR(100),
                company_size VARCHAR(100),
                featured BOOLEAN DEFAULT FALSE,
                display_order INT DEFAULT 0,
                status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_status (status),
                INDEX idx_featured (featured),
                INDEX idx_industry (industry),
                INDEX idx_order (display_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        $pdo->exec($sql);
    }
}

// Create tables on first run
if ($_ENV['AUTO_CREATE_TABLES'] !== 'false') {
    createTables();
}

/**
 * Database maintenance functions
 */

/**
 * Clean old analytics data (older than 6 months)
 */
function cleanOldAnalytics() {
    global $pdo;
    
    $query = "DELETE FROM analytics_events WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
    return $pdo->exec($query);
}

/**
 * Clean old activity logs (older than 1 year)
 */
function cleanOldActivityLogs() {
    global $pdo;
    
    $query = "DELETE FROM activity_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR)";
    return $pdo->exec($query);
}

/**
 * Get database statistics
 */
function getDatabaseStats() {
    global $pdo;
    
    $stats = [];
    
    $tables = [
        'newsletter_subscribers',
        'contact_submissions', 
        'consultation_requests',
        'activity_log',
        'analytics_events',
        'blog_posts',
        'success_cases'
    ];
    
    foreach ($tables as $table) {
        if (tableExists($table)) {
            $query = "SELECT COUNT(*) as count FROM {$table}";
            $result = getRecord($query);
            $stats[$table] = $result['count'] ?? 0;
        }
    }
    
    return $stats;
}

/**
 * Backup database (simple mysqldump)
 */
function backupDatabase() {
    global $db_config;
    
    $backup_file = __DIR__ . '/../backups/backup_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Create backups directory if it doesn't exist
    $backup_dir = dirname($backup_file);
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }
    
    $command = sprintf(
        'mysqldump -h%s -u%s -p%s %s > %s',
        escapeshellarg($db_config['host']),
        escapeshellarg($db_config['username']),
        escapeshellarg($db_config['password']),
        escapeshellarg($db_config['dbname']),
        escapeshellarg($backup_file)
    );
    
    exec($command, $output, $return_code);
    
    return $return_code === 0 ? $backup_file : false;
}

// Export PDO instance for global use
$GLOBALS['pdo'] = $pdo;
?>