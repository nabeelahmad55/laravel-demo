<!DOCTYPE html>
<html>
<head>
    <title>CSRF Protection Demo</title>
</head>
<body style="font-family: sans-serif; padding: 50px; max-width: 800px; margin: auto;">
    <h2>Submit Secure Data (With CSRF)</h2>
    <p>This form uses Laravel's <code>@csrf</code> directive. It generates a hidden input field containing a secret token that Laravel validates before accepting the POST request.</p>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/submit-secure-data" style="background: #f8f9fa; padding: 20px; border-radius: 5px; border: 1px solid #ddd;">
        <!-- The magic happens here -->
        @csrf 
        
        <div style="margin-bottom: 10px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Enter your name:</label>
            <input type="text" name="name" required style="padding: 8px; width: 100%; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Submit Securely</button>
    </form>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #eee;">

    <h2 style="color: #dc3545;">Submit Insecure Data (Will Fail)</h2>
    <p>This form is missing the <code>@csrf</code> token. Laravel's global middleware will intercept this POST request, see the missing token, and throw a <strong>419 Page Expired</strong> error to protect you from malicious attacks.</p>
    
    <form method="POST" action="/submit-secure-data" style="background: #fff3f3; padding: 20px; border-radius: 5px; border: 1px solid #ffcccc;">
        <!-- Missing @csrf token here on purpose -->
        
        <div style="margin-bottom: 10px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #dc3545;">Enter your name:</label>
            <input type="text" name="name" required style="padding: 8px; width: 100%; box-sizing: border-box; border: 1px solid #ffcccc; border-radius: 4px;">
        </div>
        <button type="submit" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Submit Insecurely</button>
    </form>
</body>
</html>
