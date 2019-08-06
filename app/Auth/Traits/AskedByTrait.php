<?php

namespace App\Auth\Traits;

trait AskedByTrait
{
    /**
     * @param  string  $referer
     *
     * @return string
     */
    protected function getAskedBy(string $referer): string
    {
        $data = parse_url($referer);

        $asked_by = '';
        if (array_key_exists('scheme', $data) && filled($data['scheme']))
            $asked_by .= ($data['scheme'] . ':');
        if (array_key_exists('user', $data) && filled($data['user'])) {
            $asked_by .= $data['user'];
            if (array_key_exists('pass', $data)) {
                $asked_by .= (':' . $data['pass']);
            }

            $asked_by .= '@';
        }
        if (array_key_exists('host', $data) && filled($data['host']))
            $asked_by .= $data['host'];
        if (array_key_exists('port', $data) && filled($data['port']) && $data['port'] !== 80)
            $asked_by .= (':' . $data['port']);

        return $asked_by . '/';
    }
}
