<?php

namespace App\Postman;

use App\Writer\AbstractConvert;

class Response extends AbstractConvert
{
    use CollectionTrait;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var Body
     */
    protected $body;

    /**
     * @var Url
     */
    protected $url;

    /**
     * Request constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->parseRequest($request);
    }

    /**
     * @param array $request
     */
    protected function parseRequest(array $request): void
    {
        $this->setRaw($request);

        isset($request['auth']) && $this->setAuth($request['auth']);
        $request['method'] = '';
        $this->setMethod($request['method']);

        $this->setHeader($request['header']);

        $request['body'] = $request['body']?$request['body']:'';

        $this->setBody($request['body']);

        $this->setUrl($request['url']);

        isset($request['description']) && $this->setDescription($request['description']);
    }

    /**
     * @param array $auth
     */
    protected function setAuth(array $auth): void
    {
        $this->auth = new Auth($auth);
    }

    /**
     * @param string $method
     */
    protected function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @param array $header
     */
    protected function setHeader(array $header): void
    {
        $this->header = new Header($header);
    }

    /**
     * @param array $body
     */
    protected function setBody($body): void
    {

        $this->body = $body;
    }

    /**
     * @param $url
     */
    protected function setUrl($url): void
    {
        $this->url = '';
    }

    /**
     * @param string $type
     */
    public function convert(string $type): void
    {
        /**
         * @var \App\Writer\Markdown|\App\Writer\Html|\App\Writer\Docx $writer
         */
        $writer = app($type);

        !empty($this->description) && $writer->line($this->description);

//        $writer->h('URL', 5);
//        $writer->code($this->method);
//        $this->url->convert($type);
        $writer->enter(2);

        /*if ($this->header->hasHeader()) {
            $writer->h('HEADER', 5);
            $this->header->convert($type);
            $writer->enter();
        }*/

        if ($this->body) {
            $writer->h('BODY', 5);
            $writer->code($this->body, true);
//            $this->body->convert($type);
            $writer->enter();
        }
    }
}