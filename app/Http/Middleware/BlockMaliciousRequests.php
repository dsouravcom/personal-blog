<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockMaliciousRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recursively inspect all inputs (GET, POST, etc.) for malicious payloads
        $input = $request->all();
        array_walk_recursive($input, function ($value) {
            $this->detectMaliciousPatterns($value);
        });

        // Block specific headers or referrers if suspicious
        $this->checkHeaders($request);

        return $next($request);
    }

    private function detectMaliciousPatterns($value)
    {
        if (!is_string($value)) {
            return;
        }

        // List of regex patterns for common attacks
        // NOTE: These are basic heuristics. Sophisticated WAFs use more complex logic.
        $patterns = [
            // SQL Injection (Strict)
            '/\bunion\s+(select|all|distinct)\b/i',
            // '/\bselect\s+.*\s+from\b/i', // Disabled: Too broad, can block legitimate comments
            '/\binformation_schema\b/i',
            '/\bwaitfor\s+delay\b/i',  // SQL Server delay
            '/\bsleep\(\d+\)/i',       // MySQL sleep injection
            '/\bbenchmark\(\d+,.*\)/i', // MySQL benchmark
            '/\bexec\s?\(.*\)/i',      // RCE via SQL exec
            // '/0x[0-9a-f]+/i',          // Disabled: Can block legitimate tokens or hashes

            // XSS / HTML Injection
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/javascript:\s*[^\s]*/i',
            '/vbscript:\s*[^\s]*/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',

            // RCE / File Inclusion (LFI/RFI)
            '/(\.\.\/|\.\.\\\\)/',     // Directory traversal ../ or ..\
            '/\/etc\/passwd/i',        // Linux system file
            '/\/proc\/self\/environ/i',// Proc file access
            '/php:\/\/input/i',        // PHP wrapper
            '/php:\/\/filter/i',       // PHP wrapper
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                // Log the attempt with IP for auditing
                abort(403, 'Malicious request detected and blocked.');
            }
        }
    }

    private function checkHeaders(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        // Block known vulnerability scanners
        if (preg_match('/(sqlmap|nikto|acunetix|nessus|nmap)/i', $userAgent)) {
            abort(403, 'Automated scanner detected.');
        }
    }
}
