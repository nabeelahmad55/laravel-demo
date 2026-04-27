# Laravel Interview Questions Guide

## BASIC LEVEL

### 1. What is Laravel and what are its key features?
**Answer:**
Laravel is a free, open-source PHP web framework built on top of Symfony components. It follows the MVC (Model-View-Controller) architecture.

**Key Features:**
- Elegant syntax and developer-friendly
- Built-in authentication and authorization
- Database ORM (Eloquent)
- Blade templating engine
- Routing system
- Middleware support
- Migration and seeding
- Testing tools (PHPUnit, Pest)
- Task scheduling
- Caching system

### 2. What is MVC architecture?
**Answer:**
MVC separates an application into three components:

- **Model:** Represents data and business logic. Interacts with the database.
- **View:** Presents data to users. Contains HTML/CSS/JavaScript.
- **Controller:** Handles user requests, processes data through models, and returns responses to views.

Example:
```
User Request → Router → Controller → Model → Database
                                  ↓
                                View → Response
```

### 3. What is Eloquent ORM?
**Answer:**
Eloquent is Laravel's Object-Relational Mapping (ORM) that allows you to interact with databases using models instead of raw SQL queries.

**Example:**
```php
// Without Eloquent (raw SQL)
$users = DB::select('SELECT * FROM users WHERE age > 18');

// With Eloquent
$users = User::where('age', '>', 18)->get();
```

### 4. What is Blade templating engine?
**Answer:**
Blade is Laravel's templating engine that provides a clean syntax for writing views with PHP embedded in HTML.

**Features:**
- Variables: `{{ $variable }}`
- Control structures: `@if`, `@foreach`, `@while`
- Template inheritance: `@extends`, `@section`
- Includes: `@include`
- Escaping: `{{{ $variable }}}` or `{{ $variable }}`

**Example:**
```blade
@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    @if($users->count() > 0)
        @foreach($users as $user)
            <p>{{ $user->name }}</p>
        @endforeach
    @else
        <p>No users found</p>
    @endif
@endsection
```

### 5. What is routing in Laravel?
**Answer:**
Routing defines the URL endpoints of your application and directs them to appropriate controllers or closures.

**Routes file location:** `routes/web.php` or `routes/api.php`

**Examples:**
```php
// Basic route
Route::get('/users', [UserController::class, 'index']);

// Named route
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// Route group
Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});

// Resource route
Route::resource('posts', PostController::class);
```

### 6. What is middleware in Laravel?
**Answer:**
Middleware is a mechanism to filter HTTP requests. It acts as a layer between the request and the application.

**Example:**
```php
// Creating middleware
php artisan make:middleware CheckAdmin

// In the middleware class
public function handle(Request $request, Closure $next)
{
    if (!Auth::user()->isAdmin()) {
        return redirect('/');
    }
    return $next($request);
}

// Using middleware in routes
Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', AdminController::class);
});
```

### 7. What is a migration in Laravel?
**Answer:**
Migrations are version control for your database. They allow you to create, modify, and rollback database tables.

**Create migration:**
```bash
php artisan make:migration create_users_table
```

**Example migration:**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamps();
});
```

### 8. What is dependency injection in Laravel?
**Answer:**
Dependency injection is a design pattern that allows objects to receive their dependencies instead of creating them internally.

**Example:**
```php
// Without DI - creating dependency inside class
class UserController {
    public function store() {
        $userRepository = new UserRepository();
        $userRepository->create($data);
    }
}

// With DI - dependency provided from outside
class UserController {
    public function __construct(private UserRepository $userRepository) {}
    
    public function store(Request $request) {
        $this->userRepository->create($request->validated());
    }
}
```

### 9. What is the Service Container?
**Answer:**
The Service Container (also called IoC Container - Inversion of Control Container) is a powerful tool for managing class dependencies and performing dependency injection.

**Example:**
```php
// Binding a class to the container
$this->app->bind('HelpSpot\API', function ($app) {
    return new \HelpSpot\API($app->make('HttpClient'));
});

// Resolving from container
$api = app('HelpSpot\API');
```

### 10. What is Laravel Tinker?
**Answer:**
Tinker is a REPL (Read-Eval-Print Loop) that allows you to interact with your Laravel application from the command line.

**Usage:**
```bash
php artisan tinker

# In Tinker
User::all();
User::create(['name' => 'John', 'email' => 'john@example.com']);
$user = User::find(1);
```

---

## INTERMEDIATE LEVEL

### 11. What are Query Scopes in Eloquent?
**Answer:**
Scopes allow you to add commonly-used query logic constraints to models. They help keep queries DRY (Don't Repeat Yourself).

**Example:**
```php
class User extends Model {
    // Local scope
    public function scopeActive($query) {
        return $query->where('active', true);
    }
    
    // Global scope
    protected static function booted() {
        static::addGlobalScope('active', function ($query) {
            $query->where('status', 'active');
        });
    }
}

// Usage
$activeUsers = User::active()->get();
User::withoutGlobalScopes()->get(); // Bypass global scopes
```

### 12. What is Eager Loading and Lazy Loading?
**Answer:**
These are two ways to load relationships in Eloquent.

**Lazy Loading** (default - causes N+1 problem):
```php
$users = User::all(); // 1 query
foreach ($users as $user) {
    echo $user->posts; // N queries (one per user)
}
// Total: N+1 queries
```

**Eager Loading** (recommended):
```php
$users = User::with('posts')->get(); // 2 queries
foreach ($users as $user) {
    echo $user->posts; // No additional queries
}
// Total: 2 queries
```

### 13. What are Eloquent Model Relationships?
**Answer:**
Relationships define how models relate to each other. Common types:

**One-to-Many:**
```php
class User extends Model {
    public function posts() {
        return $this->hasMany(Post::class);
    }
}

class Post extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

**Many-to-Many:**
```php
class User extends Model {
    public function roles() {
        return $this->belongsToMany(Role::class);
    }
}

class Role extends Model {
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
```

**One-to-One:**
```php
class User extends Model {
    public function profile() {
        return $this->hasOne(Profile::class);
    }
}
```

### 14. What is N+1 Query Problem and how to solve it?
**Answer:**
The N+1 problem occurs when you execute 1 query to fetch records, then N additional queries to fetch related data for each record.

**Problem:**
```php
$users = User::all(); // 1 query
foreach ($users as $user) {
    echo $user->country->name; // N queries
}
```

**Solutions:**
```php
// 1. Eager Loading with with()
$users = User::with('country')->get();

// 2. Eager Loading with closure
$users = User::with(['posts' => function ($query) {
    $query->where('active', true);
}])->get();

// 3. Using load() for already fetched data
$users = User::all();
$users->load('country');
```

### 15. What is a Facade in Laravel?
**Answer:**
Facades provide a static interface to classes available in the service container. They act as shortcuts to underlying classes.

**Common Facades:**
```php
// These are facades:
Auth::user()
Cache::get('key')
Route::get('/path', 'Controller@action')
DB::table('users')
Mail::send()
Storage::disk('s3')
```

**Creating a custom Facade:**
```php
// Step 1: Create a class
class PaymentProcessor {
    public function process($amount) {
        // Logic here
    }
}

// Step 2: Bind in service provider
$this->app->singleton('payment', PaymentProcessor::class);

// Step 3: Create Facade class
class PaymentFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'payment';
    }
}

// Step 4: Use it
Payment::process(100);
```

### 16. What are Events and Listeners in Laravel?
**Answer:**
Events provide a simple observer pattern implementation, allowing you to subscribe and listen to events in your application.

**Example:**
```php
// Creating an event
php artisan make:event UserCreated
php artisan make:listener SendWelcomeEmail --event=UserCreated

// Dispatching an event
event(new UserCreated($user));
// or
UserCreated::dispatch($user);

// Listening to events (auto-registered in EventServiceProvider)
class SendWelcomeEmail implements ShouldQueue {
    public function handle(UserCreated $event) {
        Mail::send(new WelcomeEmail($event->user));
    }
}
```

### 17. What is a Service Provider?
**Answer:**
Service Providers are the central place to configure and bootstrap your application. They're used to register bindings in the service container.

**Example:**
```php
class AppServiceProvider extends ServiceProvider {
    public function register() {
        // Register bindings
        $this->app->singleton('MyService', function ($app) {
            return new MyService();
        });
    }
    
    public function boot() {
        // Boot your services
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }
}
```

### 18. What is Form Request Validation?
**Answer:**
Form Requests are classes that encapsulate validation logic for specific forms, keeping controllers clean.

**Example:**
```php
php artisan make:request StoreUserRequest

// In the request class
class StoreUserRequest extends FormRequest {
    public function authorize() {
        return true; // Check if user is authorized
    }
    
    public function rules() {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }
}

// In controller
public function store(StoreUserRequest $request) {
    $validated = $request->validated(); // Already validated
    User::create($validated);
}
```

### 19. What are Model Observers?
**Answer:**
Observers allow you to group event listeners for a model. They listen to model lifecycle events.

**Example:**
```php
php artisan make:observer UserObserver --model=User

// In UserObserver
class UserObserver {
    public function created(User $user) {
        // After user is created
        Log::info('User created: ' . $user->email);
    }
    
    public function updating(User $user) {
        // Before user is updated
    }
    
    public function deleted(User $user) {
        // After user is deleted
    }
}

// Register in AppServiceProvider
public function boot() {
    User::observe(UserObserver::class);
}
```

### 20. What is Database Seeding?
**Answer:**
Seeding populates your database with sample/test data.

**Example:**
```bash
php artisan make:seeder UserSeeder
```

```php
class UserSeeder extends Seeder {
    public function run() {
        User::factory()->count(10)->create();
        
        // Or manual seeding
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }
}

// Run seeder
php artisan db:seed
php artisan db:seed --class=UserSeeder
```

---

## ADVANCED LEVEL

### 21. What is the Repository Pattern and why use it?
**Answer:**
The Repository Pattern abstracts data access logic, making code more maintainable and testable. It acts as a middleman between models and controllers.

**Example:**
```php
// Contract/Interface
interface UserRepositoryInterface {
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

// Implementation
class UserRepository implements UserRepositoryInterface {
    public function __construct(private User $model) {}
    
    public function all() {
        return $this->model->with('roles')->get();
    }
    
    public function find($id) {
        return $this->model->findOrFail($id);
    }
    
    public function create(array $data) {
        return $this->model->create($data);
    }
}

// Bind in service provider
$this->app->bind(UserRepositoryInterface::class, UserRepository::class);

// Use in controller
class UserController {
    public function __construct(private UserRepositoryInterface $userRepo) {}
    
    public function index() {
        return $this->userRepo->all();
    }
}
```

**Benefits:**
- Decouples business logic from data access
- Easy to test (mock repositories)
- Easier to switch data sources
- DRY principle

### 22. What is Query Optimization in Laravel?
**Answer:**
Techniques to improve database query performance:

**1. Eager Loading:**
```php
// Bad: N+1 query problem
$users = User::all();
foreach ($users as $user) {
    echo $user->posts->count(); // N queries
}

// Good
$users = User::with('posts')->get();
foreach ($users as $user) {
    echo $user->posts->count(); // No extra queries
}
```

**2. Select Specific Columns:**
```php
// Fetching all columns (wasteful)
$users = User::all();

// Fetch only needed columns
$users = User::select('id', 'name', 'email')->get();
```

**3. Chunking:**
```php
// Processing large datasets
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process
    }
});
```

**4. Lazy Collections:**
```php
// Memory efficient for large datasets
User::lazy()->each(function ($user) {
    // Process each user
});
```

**5. Database Indexing:**
```php
// In migration
$table->index('email');
$table->unique('email');
$table->fullText('description');
```

### 23. What is Caching and how to implement it?
**Answer:**
Caching stores frequently accessed data to improve performance.

**Configuration:** `config/cache.php`

**Example:**
```php
// Store in cache
Cache::put('users', User::all(), now()->addHours(24));

// Retrieve from cache
$users = Cache::get('users');

// Get or put
$users = Cache::remember('users', now()->addHours(24), function () {
    return User::all();
});

// Forget cache
Cache::forget('users');

// Clear all cache
Cache::flush();

// Cache tags (useful for grouping)
Cache::tags(['users', 'posts'])->put('users_1', $user);
Cache::tags('users')->flush(); // Clear all user-related cache
```

**Cache Drivers:**
- File (file system)
- Database
- Redis (recommended for production)
- Memcached

### 24. What are Accessors and Mutators in Eloquent?
**Answer:**
Accessors and Mutators allow you to transform attribute values when retrieving or setting them.

**Example:**
```php
class User extends Model {
    // Mutator - transform when setting
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }
    
    // Accessor - transform when retrieving (Laravel 9+)
    public function getNameAttribute($value) {
        return strtoupper($value);
    }
    
    // New syntax (Laravel 9.x+)
    protected function firstName(): Attribute {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
}

// Usage
$user = new User();
$user->password = 'secret'; // Automatically hashed
echo $user->name; // Automatically uppercased
```

### 25. What is API Authentication and how does it work?
**Answer:**
API authentication verifies that requests come from authorized clients.

**Methods:**

**1. Token-Based (Sanctum):**
```php
// Create token
$user = User::find(1);
$token = $user->createToken('api-token')->plainTextToken;

// Use token in requests
// Header: Authorization: Bearer {token}

// Middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
```

**2. OAuth 2.0 (Passport):**
```bash
php artisan passport:install
```

```php
// In User model
use Laravel\Passport\HasApiTokens;

class User extends Model {
    use HasApiTokens;
}

// Routes
Route::post('/oauth/token', 'OAuthController@token');
```

**3. API Key:**
```php
Route::middleware('api.key')->group(function () {
    Route::get('/users', function () {
        return User::all();
    });
});
```

### 26. What is Job Queuing and background processing?
**Answer:**
Queues allow you to defer time-consuming tasks to be processed asynchronously.

**Example:**
```bash
php artisan make:job SendWelcomeEmail
```

```php
class SendWelcomeEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(private User $user) {}
    
    public function handle() {
        Mail::send(new WelcomeEmail($this->user));
    }
    
    public function failed(Throwable $exception) {
        // Handle failure
        Log::error('Failed to send email', ['exception' => $exception]);
    }
}

// Dispatch job
SendWelcomeEmail::dispatch($user);

// Dispatch with delay
SendWelcomeEmail::dispatch($user)->delay(now()->addMinutes(5));

// Run workers
php artisan queue:work
```

### 27. What is Task Scheduling in Laravel?
**Answer:**
Task Scheduling allows you to schedule commands to run automatically at specific intervals.

**Example:**
```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule) {
    // Run daily
    $schedule->command('send:emails')->daily();
    
    // Run every hour
    $schedule->command('backup:database')->hourly();
    
    // Run at specific time
    $schedule->command('send:reminder')->dailyAt('09:00');
    
    // Run with frequency
    $schedule->command('cleanup:logs')->everyFiveMinutes();
    
    // Run on specific days
    $schedule->command('report:weekly')
        ->weeklyOn(1, '09:00'); // Monday at 9 AM
}

// Set up cron job (runs every minute)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 28. What are API Resources in Laravel?
**Answer:**
Resources transform Eloquent models into JSON responses in a consistent format.

**Example:**
```bash
php artisan make:resource UserResource
```

```php
class UserResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
        ];
    }
}

// In controller
public function show(User $user) {
    return new UserResource($user);
}

// With collections
public function index() {
    return UserResource::collection(User::paginate());
}

// Conditional attributes
public function toArray($request) {
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->when($request->user()?->isAdmin(), $this->email),
        'posts' => PostResource::collection($this->posts),
    ];
}
```

### 29. What are Policies in Laravel?
**Answer:**
Policies organize authorization logic for specific models.

**Example:**
```bash
php artisan make:policy PostPolicy --model=Post
```

```php
class PostPolicy {
    public function view(User $user, Post $post) {
        return $user->id === $post->user_id;
    }
    
    public function create(User $user) {
        return $user->role === 'admin';
    }
    
    public function update(User $user, Post $post) {
        return $user->id === $post->user_id;
    }
    
    public function delete(User $user, Post $post) {
        return $user->id === $post->user_id || $user->isAdmin();
    }
}

// Register in AuthServiceProvider
protected $policies = [
    Post::class => PostPolicy::class,
];

// Use in controller
$this->authorize('update', $post);

// Use in blade
@can('update', $post)
    <a href="#">Edit Post</a>
@endcan

// Use in gate
if ($user->cannot('update', $post)) {
    abort(403);
}
```

### 30. What is Middleware Pipeline and how does it work?
**Answer:**
Middleware Pipeline is how Laravel processes requests through multiple middleware layers.

**How it works:**
```
Request → Middleware 1 → Middleware 2 → Middleware 3 → Controller
    ↑                                                        ↓
    ← Response ← Middleware 1 ← Middleware 2 ← Middleware 3 ←
```

**Example:**
```php
// Middleware
class LogRequests {
    public function handle(Request $request, Closure $next) {
        Log::info('Request started');
        $response = $next($request); // Pass to next middleware
        Log::info('Request ended');
        return $response;
    }
}

// Register in HTTP Kernel
protected $middleware = [
    \App\Http\Middleware\LogRequests::class,
];

// Route-specific middleware
Route::post('/users', function () {
    //
})->middleware('auth', 'admin');

// Middleware groups
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ],
];
```

### 31. What are Collections in Laravel?
**Answer:**
Collections are wrapper objects for arrays, providing convenient methods for working with data sets.

**Common Methods:**
```php
$collection = collect([1, 2, 3, 4, 5]);

// Map
$doubled = $collection->map(fn ($item) => $item * 2); // [2, 4, 6, 8, 10]

// Filter
$evens = $collection->filter(fn ($item) => $item % 2 == 0); // [2, 4]

// Reduce
$sum = $collection->reduce(fn ($carry, $item) => $carry + $item, 0); // 15

// Group
$grouped = $collection->groupBy(fn ($item) => $item % 2);

// Pluck (get column)
$users = collect([
    ['name' => 'John', 'age' => 30],
    ['name' => 'Jane', 'age' => 25],
]);
$names = $users->pluck('name'); // ['John', 'Jane']

// Sort
$sorted = $collection->sort(); // [1, 2, 3, 4, 5]
$sortedDesc = $collection->sortDesc(); // [5, 4, 3, 2, 1]

// Chunk
$chunks = $collection->chunk(2); // [[1,2], [3,4], [5]]

// First/Last
$first = $collection->first(); // 1
$last = $collection->last(); // 5
```

### 32. What is Tinker used for at an advanced level?
**Answer:**
Advanced use cases for Tinker:

```bash
php artisan tinker

# Create and test logic quickly
$user = User::factory()->create();

# Test relationships
$user->posts()->create(['title' => 'Test']);

# Test queries
User::whereDate('created_at', today())->count();

# Test model methods
$user->hasRole('admin');

# Test complex operations
User::with('posts', 'comments')
    ->where('active', true)
    ->get()
    ->groupBy('role');
```

### 33. What is Horizon and how is it used?
**Answer:**
Horizon is a beautiful dashboard for monitoring job queues in Laravel applications.

**Installation:**
```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
```

**Usage:**
```php
// Access at /horizon
// Monitor job processing
// View failed jobs
// Retry failed jobs
// Check queue metrics

// Configuration in config/horizon.php
'production' => [
    'supervisor-1' => [
        'connection' => 'redis',
        'queue' => ['default'],
        'balance' => 'simple',
        'processes' => 1,
        'tries' => 2,
    ],
],
```

### 34. What are Blade Components?
**Answer:**
Blade Components allow you to build reusable template components with their own scope.

**Example:**
```bash
php artisan make:component Alert
```

```php
// app/View/Components/Alert.php
class Alert extends Component {
    public function __construct(
        public string $type = 'info',
        public string $message = ''
    ) {}
    
    public function render() {
        return view('components.alert');
    }
}

// resources/views/components/alert.blade.php
<div class="alert alert-{{ $type }}">
    {{ $message }}
</div>

// Usage in blade
<x-alert type="success" message="Operation successful!" />

// Slot usage
<x-alert type="warning">
    This is a warning message
</x-alert>
```

### 35. What is the Facade Pattern in Laravel architecture?
**Answer:**
Facades provide static-like access to methods available in the service container.

**Why use Facades:**
- Clean, memorable syntax
- Easier to test (can use `fake()`for testing)
- Improved readability

**Example with testing:**
```php
// Using facade
Mail::send($mailable);

// Testing with fake
Mail::fake();
Mail::assertSent(WelcomeEmail::class);

// Using repository pattern
class UserService {
    public function __construct(private UserRepository $repo) {}
    
    public function create($data) {
        return $this->repo->create($data);
    }
}

// Testing
$mockRepo = Mockery::mock(UserRepository::class);
$service = new UserService($mockRepo);
```

---

## BONUS QUESTIONS

### 36. What is Laravel Sanctum?
**Answer:**
Sanctum provides a lightweight authentication system for SPAs (Single Page Applications) and mobile apps.

```php
// Installation
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

// Issue token
$user = User::find(1);
$token = $user->createToken('app-name')->plainTextToken;

// Protect routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
});

// Mobile app
// Send token in Authorization header: Bearer {token}
```

### 37. What is the difference between Eager Loading and Lazy Loading?
**Answer:**
(Already covered in question 12, but key difference):
- **Eager Loading:** Loads relationships upfront (fewer queries, more memory)
- **Lazy Loading:** Loads relationships on-demand (more queries, less memory upfront)

### 38. How to write unit tests in Laravel?
**Answer:**
```php
php artisan make:test UserTest

class UserTest extends TestCase {
    public function test_user_can_be_created() {
        $user = User::factory()->create();
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
    
    public function test_user_routes_require_authentication() {
        $response = $this->get('/user/profile');
        $response->assertRedirect('/login');
    }
}

// Run tests
php artisan test
php artisan test --filter=UserTest
```

### 39. What is Laravel Mix?
**Answer:**
Laravel Mix provides a fluent API for defining Webpack build steps for your application.

```javascript
// webpack.mix.js
mix.js('resources/js/app.js', 'public/js')
   .css('resources/css/app.css', 'public/css')
   .sourceMaps();

// Run
npm run dev
npm run production
```

### 40. What are Global Scopes?
**Answer:**
Global scopes automatically apply to all queries on a model (except when explicitly removed).

```php
class User extends Model {
    protected static function booted() {
        static::addGlobalScope('active', function ($query) {
            $query->where('status', 'active');
        });
    }
}

// Usage
User::all(); // Only active users
User::withoutGlobalScopes()->all(); // All users
User::withoutGlobalScope('active')->all(); // Remove specific scope
```

---

## QUICK REFERENCE: Key Laravel Terms

| Term | Definition |
|------|-----------|
| **Eloquent** | ORM for database interaction |
| **Blade** | Templating engine |
| **Middleware** | HTTP request filters |
| **Service Provider** | Bootstrap configuration |
| **Facade** | Static interface to services |
| **Repository** | Data access abstraction |
| **Observer** | Model lifecycle listener |
| **Policy** | Authorization rules |
| **Queue** | Asynchronous job processing |
| **Cache** | Data storage for performance |
| **Scheduler** | Automated task execution |
| **Resource** | API response transformation |
| **Collection** | Data set wrapper |
| **Migration** | Database version control |
| **Seeder** | Database population |

---

## Tips for Interview Success

1. **Understand the "Why"** - Know not just what but why Laravel features exist
2. **Show Practical Knowledge** - Reference real project examples
3. **Mention Best Practices** - Talk about SOLID principles, design patterns
4. **Stay Current** - Know differences between Laravel versions (8, 9, 10, 11)
5. **Be Honest** - If you don't know something, say so and explain how you'd learn it
6. **Ask Questions** - Understand what they're looking for
7. **Code Examples** - Be ready to write code on a whiteboard or IDE
8. **Performance Awareness** - Discuss optimization techniques
9. **Security Mindedness** - Talk about CSRF, SQL injection prevention, etc.
10. **Testing Skills** - Show knowledge of PHPUnit and testing best practices

Good luck with your interviews!
