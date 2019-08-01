<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 16:05
 */

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ErrorObject
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;
    /**
     * @var int|NULL
     */
    private $code;
    /**
     * @var array|string
     */
    private $errors;
    /**
     * @var array
     */
    private $meta;
    /**
     * @var array
     */
    private $links;

    /**
     * ErrorObject constructor.
     *
     * @param            $errors
     * @param  int|NULL  $code
     * @param  array     $meta
     * @param  array     $links
     */
    public function __construct($errors, int $code = NULL, array $meta = [], array $links = [])
    {
        $this->id = Str::uuid();
        $this->errors = $errors;
        $this->code = $code;
        $this->meta = $meta;
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'errors' => [
                'id'     => $this->id,
                'status' => $this->getStatus(),
                'code'   => $this->getCode(),
                'title'  => $this->getTitle(),
                'detail' => $this->getDetail(),
                'links'  => $this->links,
                'meta'   => $this->getMeta(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->getCode() >= 400 && $this->getCode() <= 500) return "{$this->getCode()} - ".Response::$statusTexts[$this->getCode()];

        return (string) $this->getCode();
    }

    /**
     * @return int|NULL
     */
    public function getCode()
    {
        if ($this->code !== NULL && $this->code !== 0) return $this->code;

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @return array|null|string
     */
    public function getTitle()
    {
        return __("http.{$this->getCode()}");
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        if (is_array($this->errors)) {
            return is_array(Arr::first($this->errors))
                ? (string) Arr::first($this->errors)[0]
                : (string) Arr::first($this->errors);
        }

        return (string) $this->errors;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
