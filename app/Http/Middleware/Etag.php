<?php

namespace App\Http\Middleware;

use App\Traits\PropertyPath;
use Closure;

class Etag
{
    use PropertyPath;

    /**
     * Add Etag HTTP Header
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \InvalidArgumentException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$response->isSuccessful() || !$response->getContent()) return $response;

        $content = json_decode($response->getContent());
        $etag = $this->getEtag($content);

        $response->headers->set('Etag', $etag);
        $response->isNotModified($request);

        return $response;
    }

    /**
     * @param $content
     *
     * @return string
     */
    private function getEtag($content)
    {
        $timestamp = (is_object($content) && static::property_path_exists($content, 'data->attributes->updated_at'))
            ? $content->data->attributes->updated_at
            : now()->toW3cString();

        return md5($timestamp);
    }
}
