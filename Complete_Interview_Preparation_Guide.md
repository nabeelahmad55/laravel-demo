# Complete Interview Preparation Guide (3-3.5 Years Experience)

## TABLE OF CONTENTS
1. PHP OOP Concepts
2. Laravel Advanced Concepts
3. Raw Query vs Eloquent
4. Activity Logs & Compliance
5. Production Debugging & Sentry
6. Database Concepts
7. Coding Problems (Array, String, Database)
8. Project Management (Jira, Sprint Planning)
9. KYC Compliance

---

# SECTION 1: BASIC TO ADVANCED OOP

## 1.1 Classes and Objects

### Basic Class Structure
```php
class User {
    // Properties
    private string $name;
    private string $email;
    private int $age;
    
    // Constructor
    public function __construct(string $name, string $email, int $age) {
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
    }
    
    // Methods
    public function getName(): string {
        return $this->name;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function isAdult(): bool {
        return $this->age >= 18;
    }
}

// Usage
$user = new User('John', 'john@example.com', 25);
echo $user->getName();  // Output: John
```

### Access Modifiers
```php
class BankAccount {
    public float $balance;      // Accessible everywhere
    protected string $type;     // Accessible in class and subclasses
    private string $pin;        // Only accessible in this class
    
    public function __construct(float $balance, string $type, string $pin) {
        $this->balance = $balance;
        $this->type = $type;
        $this->pin = $pin;
    }
    
    private function validatePin(string $enteredPin): bool {
        return $enteredPin === $this->pin;
    }
    
    protected function getAccountType(): string {
        return $this->type;
    }
}

$account = new BankAccount(1000, 'Savings', '1234');
echo $account->balance;           // ✅ Works
echo $account->type;              // ❌ Error: Protected
echo $account->pin;               // ❌ Error: Private
$account->validatePin('1234');    // ❌ Error: Private
```

## 1.2 Inheritance

```php
// Parent class
abstract class Vehicle {
    protected string $brand;
    protected string $color;
    
    public function __construct(string $brand, string $color) {
        $this->brand = $brand;
        $this->color = $color;
    }
    
    abstract public function start(): void;
    abstract public function stop(): void;
    
    public function getBrand(): string {
        return $this->brand;
    }
}

// Child class
class Car extends Vehicle {
    private int $doors;
    
    public function __construct(string $brand, string $color, int $doors) {
        parent::__construct($brand, $color);
        $this->doors = $doors;
    }
    
    public function start(): void {
        echo "{$this->brand} car started with {$this->doors} doors";
    }
    
    public function stop(): void {
        echo "{$this->brand} car stopped";
    }
}

// Usage
$car = new Car('Toyota', 'Red', 4);
$car->start();      // Output: Toyota car started with 4 doors
```

## 1.3 Interfaces and Contracts

```php
// Interface defines contract
interface PaymentInterface {
    public function processPayment(float $amount): bool;
    public function refund(float $amount): bool;
}

// Implementation 1
class StripePayment implements PaymentInterface {
    public function processPayment(float $amount): bool {
        // Stripe API call
        return true;
    }
    
    public function refund(float $amount): bool {
        // Stripe refund logic
        return true;
    }
}

// Implementation 2
class PayPalPayment implements PaymentInterface {
    public function processPayment(float $amount): bool {
        // PayPal API call
        return true;
    }
    
    public function refund(float $amount): bool {
        // PayPal refund logic
        return true;
    }
}

// Type hint using interface
class OrderService {
    public function __construct(private PaymentInterface $payment) {}
    
    public function checkout(float $amount): bool {
        return $this->payment->processPayment($amount);
    }
}

// Usage - works with any implementation
$stripe = new StripePayment();
$paypal = new PayPalPayment();

$order1 = new OrderService($stripe);
$order2 = new OrderService($paypal);

$order1->checkout(100);  // Uses Stripe
$order2->checkout(100);  // Uses PayPal
```

## 1.4 Polymorphism

```php
interface Animal {
    public function makeSound(): string;
}

class Dog implements Animal {
    public function makeSound(): string {
        return 'Woof!';
    }
}

class Cat implements Animal {
    public function makeSound(): string {
        return 'Meow!';
    }
}

class Zoo {
    public function makeAllSounds(array $animals): void {
        foreach ($animals as $animal) {
            echo $animal->makeSound() . "\n";
        }
    }
}

// Usage
$zoo = new Zoo();
$zoo->makeAllSounds([
    new Dog(),
    new Cat(),
    new Dog(),
]);
// Output:
// Woof!
// Meow!
// Woof!
```

## 1.5 Traits

```php
// Trait provides reusable methods
trait TimestampTrait {
    private DateTime $createdAt;
    private DateTime $updatedAt;
    
    public function setTimestamp(): void {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }
    
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
}

class Article {
    use TimestampTrait;
    
    private string $title;
    
    public function __construct(string $title) {
        $this->title = $title;
        $this->setTimestamp();
    }
}

class Comment {
    use TimestampTrait;
    
    private string $content;
    
    public function __construct(string $content) {
        $this->content = $content;
        $this->setTimestamp();
    }
}

// Both Article and Comment have timestamp methods
$article = new Article('Test');
$comment = new Comment('Nice post');
```

## 1.6 Static Properties and Methods

```php
class Counter {
    private static int $count = 0;
    
    public static function increment(): void {
        self::$count++;
    }
    
    public static function getCount(): int {
        return self::$count;
    }
}

Counter::increment();
Counter::increment();
echo Counter::getCount();  // Output: 2

// Static method example
class MathHelper {
    public static function add(int $a, int $b): int {
        return $a + $b;
    }
    
    public static function multiply(int $a, int $b): int {
        return $a * $b;
    }
}

echo MathHelper::add(5, 3);        // Output: 8
echo MathHelper::multiply(5, 3);   // Output: 15
```

## 1.7 Abstract Classes vs Interfaces

```php
// Abstract class (IS-A relationship)
abstract class Database {
    protected string $connection;
    
    abstract public function connect(): void;
    abstract public function disconnect(): void;
    
    public function getConnection(): string {
        return $this->connection;
    }
}

class MySQLDatabase extends Database {
    public function connect(): void {
        $this->connection = 'MySQL Connected';
    }
    
    public function disconnect(): void {
        echo 'MySQL Disconnected';
    }
}

// Interface (CAN-DO contract)
interface Loggable {
    public function log(string $message): void;
}

class MySQLDatabase extends Database implements Loggable {
    public function log(string $message): void {
        file_put_contents('logs.txt', $message);
    }
}

// ✅ Good design: Abstract class for inheritance, Interface for contracts
```

---

# SECTION 2: LARAVEL ADVANCED CONCEPTS

## 2.1 Service Providers & Service Container

### What is Service Container?
```php
// Service Container is Laravel's IoC (Inversion of Control) container
// It manages dependencies and injects them automatically

// Binding in Service Provider
class AppServiceProvider extends ServiceProvider {
    public function register() {
        // ✅ Singleton binding (same instance across app)
        $this->app->singleton('payment', StripePayment::class);
        
        // ✅ Binding (new instance each time)
        $this->app->bind('mailer', SmtpMailer::class);
        
        // ✅ Binding with closure
        $this->app->bind('config.api', function ($app) {
            return [
                'key' => env('API_KEY'),
                'secret' => env('API_SECRET'),
            ];
        });
    }
}

// Using from container
$payment = app('payment');
$payment = app(StripePayment::class);
$payment = resolve('payment');
```

### Type Hinting (Automatic Injection)
```php
class OrderController {
    // Laravel automatically injects StripePayment
    public function __construct(StripePayment $payment) {
        $this->payment = $payment;
    }
}

// Laravel reads type hint, finds binding, injects automatically
```

## 2.2 Dependency Injection

```php
// ❌ Wrong - Hard to test, tightly coupled
class UserService {
    public function register($data) {
        $db = new Database();
        $mailer = new Mailer();
        
        $user = $db->insert('users', $data);
        $mailer->send(new WelcomeEmail($user));
        
        return $user;
    }
}

// ✅ Right - Loose coupling, easy to test
class UserService {
    public function __construct(
        private Database $db,
        private Mailer $mailer
    ) {}
    
    public function register($data) {
        $user = $this->db->insert('users', $data);
        $this->mailer->send(new WelcomeEmail($user));
        return $user;
    }
}

// Testing
class UserServiceTest extends TestCase {
    public function test_register() {
        $mockDb = Mockery::mock(Database::class);
        $mockMailer = Mockery::mock(Mailer::class);
        
        $service = new UserService($mockDb, $mockMailer);
        // Test with mocks
    }
}
```

## 2.3 Contracts (Interfaces in Laravel)

```php
// app/Contracts/PaymentInterface.php
namespace App\Contracts;

interface PaymentInterface {
    public function charge(float $amount): bool;
    public function refund($transactionId): bool;
}

// app/Services/StripePayment.php
class StripePayment implements PaymentInterface {
    public function charge(float $amount): bool {
        // Stripe charging logic
        return true;
    }
    
    public function refund($transactionId): bool {
        // Stripe refund logic
        return true;
    }
}

// In service provider
public function register() {
    $this->app->bind(PaymentInterface::class, StripePayment::class);
}

// In controller - depends on interface, not implementation
class CheckoutController {
    public function __construct(private PaymentInterface $payment) {}
    
    public function process(float $amount) {
        return $this->payment->charge($amount);
    }
}
```

## 2.4 Jobs, Queues, Events & Listeners

### Jobs (Background Processing)

```php
// Create job
php artisan make:job SendWelcomeEmail

// app/Jobs/SendWelcomeEmail.php
class SendWelcomeEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(private User $user) {}
    
    public function handle(Mailer $mailer) {
        $mailer->send(new WelcomeEmail($this->user));
    }
    
    // Handles job failure
    public function failed(Throwable $exception) {
        Log::error('Failed to send email', ['user_id' => $this->user->id]);
    }
}

// Dispatch job
SendWelcomeEmail::dispatch($user);
SendWelcomeEmail::dispatch($user)->delay(now()->addMinutes(5));

// Run workers
php artisan queue:work
```

### Events & Listeners

```php
// Create event
php artisan make:event UserRegistered
php artisan make:listener SendWelcomeEmail --event=UserRegistered

// app/Events/UserRegistered.php
class UserRegistered {
    public function __construct(public User $user) {}
}

// app/Listeners/SendWelcomeEmail.php
class SendWelcomeEmail {
    public function handle(UserRegistered $event) {
        Mail::send(new WelcomeEmail($event->user));
    }
}

// Register in EventServiceProvider
protected $listen = [
    UserRegistered::class => [
        SendWelcomeEmail::class,
    ],
];

// Dispatch event
event(new UserRegistered($user));
// or
UserRegistered::dispatch($user);
```

### Supervisor (for Queue)

```bash
# Install supervisor
sudo apt-get install supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/app/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/app/storage/logs/worker.log
```

## 2.5 Facades

```php
// ❌ Without facade (verbose)
$cache = app('cache');
$value = $cache->get('key');
$cache->put('key', $value, 3600);

// ✅ With facade (clean)
$value = Cache::get('key');
Cache::put('key', $value, 3600);

// How facades work
class CacheFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'cache';  // Points to container binding
    }
}

// Testing with facades
Cache::fake();
Cache::put('key', 'value');
Cache::assertHas('key');
```

---

# SECTION 3: RAW QUERY vs ELOQUENT

## When to Use What?

```php
// ✅ Use ELOQUENT for:
// - Simple CRUD operations
// - When relationships are involved
// - When you need model methods
// - When readability matters
// - Team consistency

// Get all users with posts
$users = User::with('posts')->get();

// Find user by email
$user = User::where('email', $email)->first();

// Create with relationships
$user = User::create($data);
$user->posts()->create($postData);

// ✅ Use RAW QUERIES for:
// - Complex queries with many joins
// - Performance-critical queries
// - Reporting queries with aggregations
// - Queries with database-specific functions
// - Large batch operations

// Complex query with raw SQL
$users = DB::select('
    SELECT u.id, u.name, COUNT(p.id) as post_count
    FROM users u
    LEFT JOIN posts p ON u.id = p.user_id
    WHERE u.created_at > ?
    GROUP BY u.id
    HAVING COUNT(p.id) > ?
    ORDER BY post_count DESC
', [$date, $minCount]);

// Performance: Raw is often 10-20% faster for complex queries

// ✅ Use QUERY BUILDER for:
// - Complex but readable queries
// - Dynamic query building
// - Conditional where clauses

$query = DB::table('users');

if ($status) {
    $query->where('status', $status);
}

if ($role) {
    $query->where('role', $role);
}

$users = $query->where('created_at', '>', $date)
    ->orderBy('name')
    ->get();
```

## Decision Tree

```
Is it a simple CRUD?
├─ YES → Use ELOQUENT
└─ NO → Complex query?
    ├─ YES, but readable with query builder?
    │   └─ Use QUERY BUILDER
    │
    └─ YES, needs raw SQL?
        └─ Use RAW QUERY
```

---

# SECTION 4: ACTIVITY LOGS & COMPLIANCE

## 4.1 Activity Logs Concept

Activity logs track user actions for compliance, auditing, and debugging.

```php
// Install spatie/laravel-activity-log
composer require spatie/laravel-activity-log

php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan migrate

// In model
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Model {
    use LogsActivity;
    
    // Track changes
    protected static $logAttributes = ['name', 'email', 'status'];
    protected static $logOnlyDirty = true;
    
    protected static $recordEvents = ['created', 'updated', 'deleted'];
}

// Automatic logging
$user = User::create(['name' => 'John']);
// Logs: User created with name='John'

$user->update(['name' => 'Jane']);
// Logs: name changed from 'John' to 'Jane'

$user->delete();
// Logs: User deleted

// Query logs
$activities = activity()
    ->where('subject_id', $userId)
    ->where('subject_type', User::class)
    ->get();

foreach ($activities as $activity) {
    echo $activity->description;  // "created", "updated", etc
    echo $activity->changes['attributes']; // New values
    echo $activity->changes['old']; // Old values
}
```

## 4.2 KYC (Know Your Customer) Implementation

```php
// KYC verification steps
class KYCService {
    public function verify(User $user): array {
        $status = [
            'identity_verified' => $this->verifyIdentity($user),
            'document_verified' => $this->verifyDocument($user),
            'selfie_verified' => $this->verifySelfie($user),
            'address_verified' => $this->verifyAddress($user),
        ];
        
        $user->update([
            'kyc_status' => $this->getStatus($status),
            'kyc_verified_at' => now(),
        ]);
        
        // Log KYC action
        activity()
            ->performedOn($user)
            ->log('KYC Verified');
        
        return $status;
    }
    
    private function verifyIdentity(User $user): bool {
        // Verify against Aadhaar/PAN API
        return true;
    }
    
    private function verifyDocument(User $user): bool {
        // OCR on document
        return true;
    }
    
    private function verifySelfie(User $user): bool {
        // Face matching with document
        return true;
    }
    
    private function verifyAddress(User $user): bool {
        // Address verification API
        return true;
    }
}
```

---

# SECTION 5: PRODUCTION DEBUGGING & SENTRY

## 5.1 Production Debugging Steps

When error occurs on production:

```
Step 1: Check logs
├─ storage/logs/laravel.log
├─ Check last 50 lines
├─ Look for error timestamp
└─ Identify error type

Step 2: Check error details
├─ Error message
├─ Stack trace
├─ File and line number
└─ User who triggered it

Step 3: Reproduce locally
├─ Use same data
├─ Use same request
├─ Check if error reproduces
└─ If not, check environment differences

Step 4: Check environment
├─ .env file values
├─ Database connection
├─ Cache configuration
├─ File permissions
└─ Disk space

Step 5: Check recent changes
├─ Recent commits
├─ Recent deployments
├─ Recent migrations
└─ Recent config changes

Step 6: Check database
├─ Run migrations
├─ Check foreign keys
├─ Check data consistency
└─ Check transaction status

Step 7: Check external services
├─ API connections
├─ Payment gateway status
├─ Email service
└─ Third-party services

Step 8: Monitor and fix
├─ Apply fix
├─ Test locally
├─ Deploy to staging
├─ Test on staging
├─ Deploy to production
├─ Monitor logs
└─ Confirm issue resolved
```

### Logging Best Practices

```php
// Good logging
Log::info('User registered', [
    'user_id' => $user->id,
    'email' => $user->email,
    'timestamp' => now(),
]);

// For errors
try {
    // Code
} catch (Exception $e) {
    Log::error('Payment processing failed', [
        'user_id' => $user->id,
        'amount' => $amount,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}

// In config/logging.php
'channels' => [
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'days' => 14,
    ],
]
```

## 5.2 Sentry Integration

### What is Sentry?
Sentry is error tracking platform that:
- Captures exceptions in real-time
- Groups similar errors
- Alerts on new errors
- Shows environment info
- Tracks user sessions
- Shows performance issues

### Setup

```bash
# Install Sentry
composer require sentry/sentry-laravel

# Publish config
php artisan vendor:publish --provider="Sentry\Laravel\ServiceProvider"
```

```php
// .env
SENTRY_LARAVEL_DSN=https://key@sentry.io/project_id

// config/sentry.php
return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    'environment' => env('APP_ENV'),
    'traces_sample_rate' => 0.1,  // 10% of transactions
    'profiles_sample_rate' => 0.1,
];
```

```php
// Automatic error capturing
// Sentry automatically captures:
// - Unhandled exceptions
// - Error messages
// - Warnings

// Manual error capturing
Sentry::captureException($exception);
Sentry::captureMessage('Custom message', 'error');

// Add context
Sentry::setUser([
    'id' => $user->id,
    'email' => $user->email,
]);

Sentry::setTag('feature', 'checkout');
Sentry::setContext('payment', [
    'amount' => 100,
    'currency' => 'USD',
]);

// In Sentry dashboard:
// - See errors in real-time
// - Group errors by type
// - Set alerts for new errors
// - See which users affected
// - Track error trends
```

---

# SECTION 6: DATABASE CONCEPTS

## 6.1 Database Keys

```sql
-- Primary Key: Unique identifier
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    name VARCHAR(255)
);

-- Foreign Key: Relationship between tables
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(255),
    content TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Composite Key: Multiple columns as primary key
CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
```

## 6.2 Indexes

```sql
-- Single column index
CREATE INDEX idx_email ON users(email);

-- Multi-column (composite) index
CREATE INDEX idx_user_post ON posts(user_id, created_at);

-- Unique index
CREATE UNIQUE INDEX idx_email ON users(email);

-- Full-text index
CREATE FULLTEXT INDEX idx_content ON posts(content);

-- Index on migration
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();  // Creates index
    $table->string('name');
    $table->index('created_at');        // Add index
    $table->fullText('bio');            // Full-text
});

-- Query optimization with EXPLAIN
EXPLAIN SELECT * FROM users WHERE email = 'john@example.com';
-- Should show "Using index" if index is used

-- Check if index is being used
ANALYZE TABLE users;
```

## 6.3 Transactions

```php
// ✅ Using transactions
DB::transaction(function () {
    $user = User::create($userData);
    $user->wallet()->create(['balance' => 0]);
    $user->settings()->create(['theme' => 'light']);
});
// All 3 queries execute, or none if error

// ❌ Without transaction - inconsistent data if error mid-way

// Manual transaction
DB::beginTransaction();
try {
    $user = User::create($userData);
    $user->wallet()->create(['balance' => 0]);
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}

// ACID properties
// A - Atomicity: All or nothing
// C - Consistency: Valid data only
// I - Isolation: Concurrent transactions don't interfere
// D - Durability: Committed data persists
```

## 6.4 Views

```sql
-- Database view: Saved query
CREATE VIEW user_post_count AS
SELECT u.id, u.name, COUNT(p.id) as post_count
FROM users u
LEFT JOIN posts p ON u.id = p.user_id
GROUP BY u.id;

-- Query view
SELECT * FROM user_post_count WHERE post_count > 5;

-- In Laravel
$users = DB::table('user_post_count')->get();

-- Advantages:
// - Reusable queries
// - Security (expose only needed columns)
// - Complex logic encapsulated
// - Performance (indexed views)
```

## 6.5 Stored Procedures

```sql
-- Create stored procedure
DELIMITER //

CREATE PROCEDURE transfer_money(
    IN from_user_id INT,
    IN to_user_id INT,
    IN amount DECIMAL(10,2)
)
BEGIN
    START TRANSACTION;
    
    UPDATE users SET balance = balance - amount 
    WHERE id = from_user_id;
    
    UPDATE users SET balance = balance + amount 
    WHERE id = to_user_id;
    
    INSERT INTO transactions (from_id, to_id, amount, created_at)
    VALUES (from_user_id, to_user_id, amount, NOW());
    
    COMMIT;
END //

DELIMITER ;

-- Call procedure
CALL transfer_money(1, 2, 100);

-- In Laravel
DB::statement('CALL transfer_money(?, ?, ?)', [$fromId, $toId, $amount]);
```

---

# SECTION 7: CODING PROBLEMS (ARRAY, STRING, DATABASE)

## 7.1 String Problems (O(N) complexity)

### Problem 1: Check Palindrome (Without built-in functions)

```php
function isPalindrome(string $str): bool {
    // Remove spaces and convert to lowercase
    $str = strtolower(str_replace(' ', '', $str));
    
    $left = 0;
    $right = strlen($str) - 1;
    
    while ($left < $right) {
        if ($str[$left] !== $str[$right]) {
            return false;
        }
        $left++;
        $right--;
    }
    
    return true;
}

// Usage
echo isPalindrome('A man a plan a canal Panama');  // true
echo isPalindrome('hello');                         // false

// Time: O(N), Space: O(N) due to string creation
```

### Problem 2: Most Repetitive Character

```php
function mostRepetitiveChar(string $str): string {
    $charCount = [];
    
    // O(N) - single pass
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str[$i];
        if ($char !== ' ') {  // Skip spaces
            if (!isset($charCount[$char])) {
                $charCount[$char] = 0;
            }
            $charCount[$char]++;
        }
    }
    
    // O(N) - find max
    $maxChar = '';
    $maxCount = 0;
    
    foreach ($charCount as $char => $count) {
        if ($count > $maxCount) {
            $maxCount = $count;
            $maxChar = $char;
        }
    }
    
    return $maxChar;
}

// Usage
echo mostRepetitiveChar('aabbccdddeee');  // e
echo mostRepetitiveChar('hello world');   // l

// Time: O(N), Space: O(K) where K is unique characters
```

### Problem 3: Count Vowels and Consonants

```php
function countVowelsConsonants(string $str): array {
    $vowels = 0;
    $consonants = 0;
    
    for ($i = 0; $i < strlen($str); $i++) {
        $char = strtolower($str[$i]);
        
        if (ctype_alpha($char)) {  // Check if alphabetic
            if (in_array($char, ['a', 'e', 'i', 'o', 'u'])) {
                $vowels++;
            } else {
                $consonants++;
            }
        }
    }
    
    return [
        'vowels' => $vowels,
        'consonants' => $consonants,
    ];
}

// Usage
$result = countVowelsConsonants('hello world');
// ['vowels' => 3, 'consonants' => 7]

// Time: O(N), Space: O(1)
```

### Problem 4: Reverse String

```php
function reverseString(string $str): string {
    $reversed = '';
    
    for ($i = strlen($str) - 1; $i >= 0; $i--) {
        $reversed .= $str[$i];
    }
    
    return $reversed;
}

// Usage
echo reverseString('hello');  // 'olleh'

// Time: O(N), Space: O(N)
```

### Problem 5: Check Anagram

```php
function isAnagram(string $str1, string $str2): bool {
    // Remove spaces and convert to lowercase
    $str1 = strtolower(str_replace(' ', '', $str1));
    $str2 = strtolower(str_replace(' ', '', $str2));
    
    // If different lengths, not anagram
    if (strlen($str1) !== strlen($str2)) {
        return false;
    }
    
    // Count characters in both strings - O(N)
    $charCount1 = [];
    $charCount2 = [];
    
    for ($i = 0; $i < strlen($str1); $i++) {
        $charCount1[$str1[$i]] = ($charCount1[$str1[$i]] ?? 0) + 1;
        $charCount2[$str2[$i]] = ($charCount2[$str2[$i]] ?? 0) + 1;
    }
    
    // Compare counts - O(K) where K is unique chars
    return $charCount1 === $charCount2;
}

// Usage
echo isAnagram('listen', 'silent');  // true
echo isAnagram('hello', 'world');    // false

// Time: O(N), Space: O(K)
```

## 7.2 Array Problems (O(N) complexity)

### Problem 6: Find Second Highest Element

```php
function secondHighest(array $arr): ?int {
    if (count($arr) < 2) {
        return null;
    }
    
    $max = PHP_INT_MIN;
    $secondMax = PHP_INT_MIN;
    
    // O(N) - single pass
    foreach ($arr as $num) {
        if ($num > $max) {
            $secondMax = $max;
            $max = $num;
        } elseif ($num > $secondMax && $num !== $max) {
            $secondMax = $num;
        }
    }
    
    return $secondMax === PHP_INT_MIN ? null : $secondMax;
}

// Usage
echo secondHighest([10, 20, 30, 40, 50]);  // 40
echo secondHighest([5, 5, 5]);             // null

// Time: O(N), Space: O(1)
```

### Problem 7: Remove Duplicates

```php
function removeDuplicates(array $arr): array {
    $result = [];
    $seen = [];
    
    // O(N)
    foreach ($arr as $num) {
        if (!isset($seen[$num])) {
            $seen[$num] = true;
            $result[] = $num;
        }
    }
    
    return $result;
}

// Usage
print_r(removeDuplicates([1, 2, 2, 3, 3, 3]));
// Output: [1, 2, 3]

// Time: O(N), Space: O(N)
```

### Problem 8: Find Missing Number

```php
function findMissing(array $arr): ?int {
    // Given array from 1 to N with one missing
    // arr = [1, 2, 4, 5], returns 3
    
    $n = count($arr) + 1;  // Expected count
    $expectedSum = ($n * ($n + 1)) / 2;
    
    $actualSum = 0;
    // O(N)
    foreach ($arr as $num) {
        $actualSum += $num;
    }
    
    return $expectedSum - $actualSum;
}

// Usage
echo findMissing([1, 2, 4, 5]);  // 3

// Time: O(N), Space: O(1)
```

### Problem 9: Rotate Array

```php
function rotateArray(array &$arr, int $k): void {
    // Rotate right by k positions
    // [1,2,3,4,5], k=2 => [4,5,1,2,3]
    
    $n = count($arr);
    $k = $k % $n;  // Handle k > n
    
    // Reverse entire array - O(N)
    reverseArray($arr, 0, $n - 1);
    
    // Reverse first k elements - O(K)
    reverseArray($arr, 0, $k - 1);
    
    // Reverse remaining elements - O(N-K)
    reverseArray($arr, $k, $n - 1);
}

function reverseArray(array &$arr, int $start, int $end): void {
    while ($start < $end) {
        $temp = $arr[$start];
        $arr[$start] = $arr[$end];
        $arr[$end] = $temp;
        $start++;
        $end--;
    }
}

// Usage
$arr = [1, 2, 3, 4, 5];
rotateArray($arr, 2);
print_r($arr);  // [4, 5, 1, 2, 3]

// Time: O(N), Space: O(1)
```

### Problem 10: Find Intersection of Two Arrays

```php
function intersection(array $arr1, array $arr2): array {
    $result = [];
    $seen = [];
    
    // O(N) - add all from arr1 to seen
    foreach ($arr1 as $num) {
        $seen[$num] = true;
    }
    
    // O(M) - check arr2 against seen
    foreach ($arr2 as $num) {
        if (isset($seen[$num]) && !isset($result[$num])) {
            $result[$num] = $num;
        }
    }
    
    return array_values($result);
}

// Usage
print_r(intersection([1,2,2,1], [2,2]));
// Output: [2]

// Time: O(N + M), Space: O(min(N, M))
```

## 7.3 Database Problems

### Problem 11: Find Second Highest Salary

```sql
-- Database approach
SELECT MAX(salary) as second_highest_salary
FROM employees
WHERE salary < (SELECT MAX(salary) FROM employees);

-- OR using CTE
WITH ranked_salaries AS (
    SELECT salary, DENSE_RANK() OVER (ORDER BY salary DESC) as rnk
    FROM employees
)
SELECT salary
FROM ranked_salaries
WHERE rnk = 2
LIMIT 1;

-- OR using OFFSET
SELECT DISTINCT salary
FROM employees
ORDER BY salary DESC
LIMIT 1 OFFSET 1;
```

### Problem 12: Count Employees by Department

```sql
SELECT 
    d.department_name,
    COUNT(e.id) as employee_count,
    AVG(e.salary) as avg_salary,
    MAX(e.salary) as max_salary
FROM departments d
LEFT JOIN employees e ON d.id = e.department_id
GROUP BY d.id, d.department_name
HAVING COUNT(e.id) > 0
ORDER BY employee_count DESC;
```

### Problem 13: Find Duplicate Emails

```sql
SELECT email, COUNT(*) as count
FROM users
GROUP BY email
HAVING COUNT(*) > 1;
```

### Problem 14: Department with Highest Total Salary

```sql
SELECT 
    d.name,
    SUM(e.salary) as total_salary
FROM departments d
JOIN employees e ON d.id = e.department_id
GROUP BY d.id, d.name
ORDER BY total_salary DESC
LIMIT 1;
```

### Problem 15: Find Employees with Salary > Average

```sql
SELECT 
    name,
    salary,
    (SELECT AVG(salary) FROM employees) as avg_salary
FROM employees
WHERE salary > (SELECT AVG(salary) FROM employees)
ORDER BY salary DESC;
```

---

# SECTION 8: JIRA & SPRINT PLANNING

## 8.1 Jira Basics

```
Jira Hierarchy:
Project
├── Epic (Large feature, e.g., "Payment Module")
│   ├── Story (User requirement, e.g., "As a user, I want to pay with card")
│   │   ├── Task (Subtask, e.g., "Integrate Stripe API")
│   │   └── Task (Subtask, e.g., "Add validation")
│   └── Story (Another user story)
└── Epic (Another large feature)

Issue Types:
- Story: Feature from user perspective
- Task: Technical work
- Bug: Something broken
- Subtask: Part of another issue
- Epic: Large feature spanning multiple stories
```

## 8.2 Sprint Planning

```
Sprint Cycle (2 weeks):

Week 1:
├── Monday: Sprint Planning
│   ├── Team reviews backlog
│   ├── Discuss each story
│   ├── Estimate story points (1,2,3,5,8,13)
│   └── Commit to stories for sprint
│
├── Tuesday-Friday: Development
│   ├── Team members pick issues
│   ├── Work on assigned issues
│   └── Update Jira status
│
└── Daily: Daily Standup (15 mins)
    ├── What did you do yesterday?
    ├── What will you do today?
    └── Any blockers?

Week 2:
├── Tuesday-Thursday: Development continues
│
├── Friday: Sprint Review + Retrospective
│   ├── Demo completed work
│   ├── Get stakeholder feedback
│   └── Discuss improvements
│
└── Friday: Sprint Retrospective
    ├── What went well?
    ├── What went wrong?
    └── What to improve next sprint?
```

## 8.3 Story Points & Estimation

```
Story Points: Relative complexity, not hours

1 point: Very simple (Config change, copy update)
2 points: Simple (New field, simple validation)
3 points: Moderate (New API endpoint, simple logic)
5 points: Complex (Payment integration, complex logic)
8 points: Very complex (New module, database redesign)
13 points: Epic-scale (Large feature with many components)

Why use story points?
- Easier to estimate relative complexity than hours
- Accounts for unknowns
- Consistent across team
- Improves velocity prediction
```

---

# SECTION 9: KEY INTERVIEW QUESTIONS & ANSWERS

## Q1: What's the difference between abstract class and interface?

```
Abstract Class:
- Partial implementation allowed
- Can have state (properties with values)
- Single inheritance
- Access modifiers (public, protected, private)
- IS-A relationship

Interface:
- Only contract (all public)
- No implementation (before PHP 8)
- Multiple inheritance
- All public
- CAN-DO relationship

Use abstract class for:
- Related classes with shared code
- Protected/private members needed

Use interface for:
- Unrelated classes with same contract
- Multiple inheritance needed
```

## Q2: Explain SOLID principles briefly

```
S - Single Responsibility: One class, one job
O - Open/Closed: Extend, don't modify
L - Liskov Substitution: Subtypes are substitutable
I - Interface Segregation: Focused interfaces
D - Dependency Inversion: Depend on abstractions
```

## Q3: What's the N+1 query problem?

```
Problem:
$users = User::all();              // 1 query
foreach ($users as $user) {
    echo $user->posts->count();    // N queries (one per user)
}
Total: N+1 queries

Solution:
$users = User::with('posts')->get();  // 2 queries
foreach ($users as $user) {
    echo $user->posts->count();       // No additional queries
}
Total: 2 queries
```

## Q4: How would you debug a production error?

```
1. Check logs (storage/logs/laravel.log)
2. Get error timestamp and message
3. Check Sentry dashboard
4. Reproduce locally with same data
5. Check environment variables
6. Check recent deployments
7. Check database state
8. Test fix locally
9. Deploy to staging
10. Monitor after production deployment
```

## Q5: What's the difference between job queue and listener?

```
Event/Listener:
- Synchronous by default
- Fast operations
- Immediate response
- Example: Log user action

Job/Queue:
- Asynchronous
- Long operations
- No immediate response
- Example: Send email, process payment

When to use:
- Email sending → Job (can be slow)
- User logged in → Listener (fast, sync)
- Large file processing → Job
- Notification → Job (send in background)
```

---

# SECTION 10: PRACTICE CHECKLIST

## Topics to Practice
- [ ] OOP: Classes, Interfaces, Traits, Inheritance
- [ ] Laravel: Service Container, DI, Contracts
- [ ] Events/Listeners: Create and dispatch
- [ ] Jobs: Create, dispatch, handle failures
- [ ] Query optimization: N+1, eager loading
- [ ] Activity logging: Log user actions
- [ ] Database: Keys, indexes, transactions, views
- [ ] String problems: Palindrome, anagrams, character count
- [ ] Array problems: Rotation, duplicates, intersection
- [ ] SQL: Complex queries, joins, aggregations
- [ ] Error handling: Try-catch, logging, Sentry
- [ ] Testing: Unit tests, mock data

## Preparation Timeline (4 weeks)

**Week 1:**
- [ ] OOP concepts (5 days)
- [ ] Laravel Service Container & DI (2 days)

**Week 2:**
- [ ] Laravel concepts (Contracts, Events, Jobs)
- [ ] Database concepts (Keys, Indexes, Transactions)

**Week 3:**
- [ ] Coding problems (20-30 problems)
- [ ] Raw SQL vs Eloquent
- [ ] Production debugging & Sentry

**Week 4:**
- [ ] Mock interviews
- [ ] Review weak areas
- [ ] Practice explaining concepts

## Mock Interview Questions

1. Explain your last project architecture
2. How did you handle errors in production?
3. Most complex query you've written
4. How did you optimize slow queries?
5. Describe your testing approach
6. How do you structure large Laravel projects?
7. Explain Dependency Injection
8. Why use Repository Pattern?
9. Handle concurrent database transactions
10. Design database schema for complex requirement

---

That's your complete preparation guide! Focus on understanding concepts deeply rather than memorizing. Good luck! 🚀
