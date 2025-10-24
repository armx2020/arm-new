<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the client accepts gzip encoding
        $acceptEncoding = $request->header('Accept-Encoding', '');
        
        if (strpos($acceptEncoding, 'gzip') === false) {
            return $response;
        }

        // Don't compress if already compressed or if it's not compressible content
        $contentType = $response->headers->get('Content-Type', '');
        
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'text/xml',
            'application/xml',
        ];

        $isCompressible = false;
        foreach ($compressibleTypes as $type) {
            if (strpos($contentType, $type) !== false) {
                $isCompressible = true;
                break;
            }
        }

        if (!$isCompressible) {
            return $response;
        }

        // Get the original content
        $content = $response->getContent();
        
        // Don't compress empty or small content
        if (empty($content) || strlen($content) < 1024) {
            return $response;
        }

        // Compress the content
        $compressedContent = gzencode($content, 6); // Level 6 is a good balance
        
        if ($compressedContent === false) {
            return $response;
        }

        // Set the compressed content and headers
        $response->setContent($compressedContent);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', strlen($compressedContent));
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }
}
