<!DOCTYPE html>
<html>
<head>
    <title>Escaping Demo (XSS Protection)</title>
    <style>
        body { font-family: sans-serif; padding: 50px; max-width: 800px; margin: auto; }
        .box { padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .secure { background-color: #d4edda; border: 1px solid #c3e6cb; }
        .insecure { background-color: #f8d7da; border: 1px solid #f5c6cb; }
        pre { background: #eee; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>PHP/Laravel Escaping Demonstration</h2>
    <p>Escaping is the process of converting special characters (like <code>&lt;</code> and <code>&gt;</code>) into HTML entities (like <code>&amp;lt;</code> and <code>&amp;gt;</code>) before rendering them in the browser. This prevents Cross-Site Scripting (XSS) attacks.</p>

    @php
        // Simulating a malicious input from a user (e.g., a comment or profile name)
        $maliciousInput = '<script>alert("You have been hacked! (XSS Attack)");</script><b>This text is bold, but it shouldn\'t be!</b>';
    @endphp

    <div class="box secure">
        <h3>1. Secure Output (Escaped)</h3>
        <p>In Laravel Blade, using double curly braces automatically runs PHP's <code>htmlspecialchars()</code> function under the hood.</p>
        <p><strong>Code:</strong> <code>&#123;&#123; $maliciousInput &#125;&#125;</code></p>
        <p><strong>Result:</strong></p>
        <div style="background: white; padding: 10px; border: 1px dashed #ccc;">
            {{ $maliciousInput }}
        </div>
        <p><em>Notice how the script tag is printed as plain text and doesn't execute? The browser sees it as data, not code.</em></p>
    </div>

    <div class="box insecure">
        <h3>2. Insecure Output (Unescaped)</h3>
        <p>Using the unescaped syntax tells Laravel to render the raw HTML/JavaScript directly.</p>
        <p><strong>Code:</strong> <code>&#123;!! $maliciousInput !!&#125;</code></p>
        <p><strong>Result:</strong></p>
        <div style="background: white; padding: 10px; border: 1px dashed #ccc;">
            {!! $maliciousInput !!}
        </div>
        <p><em>If you see an alert box and bold text, the XSS attack was successful!</em></p>
    </div>
</body>
</html>