<?php

namespace App\Http\Middleware;

use Closure;

class Etag
{
    /**
     * Add Etag HTTP Header
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \InvalidArgumentException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->method() === 'DELETE' || !$response->getContent()) return $response;

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
        $timestamp = (!is_array($content->data) && property_exists($content->data, 'attributes') && property_exists($content->data->attributes, 'updated_at'))
            ? $content->data->attributes->updated_at
            : now()->toW3cString();

        return md5($timestamp);
    }
}
