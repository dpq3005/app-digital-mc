<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpService
{
    private $container, $kernel, $httpClient;

    const MIME_TYPE_INPUT_URL = 'URL';
    const MIME_TYPE_INPUT_BINARY = 'BINARY';

    public function __construct(ContainerInterface $container, KernelInterface $kernel, HttpClientInterface $httpClient)
    {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->httpClient = $httpClient;
    }

    private function getParameter($param)
    {
        return $this->container->getParameter($param);
    }

    public function getMimeType($fileInput, $type = self::MIME_TYPE_INPUT_URL)
    {
        $filePath = null;
        if ($type === self::MIME_TYPE_INPUT_URL) {
            $filePath = $fileInput;
        } elseif ($type === self::MIME_TYPE_INPUT_BINARY) {
            $fileData = $fileInput;
        }

        if ($filePath) {
            $fileData = file_get_contents($filePath);
        }

        $filePath = $this->kernel->getCacheDir().'/../file/temp_'.rand();
        file_put_contents($filePath, $fileData);
        $ctype = mime_content_type($filePath);
        unlink($filePath);
        return $ctype;
    }

    public function get($resourcePath, $uuid = null, $assoc = false, $persistence = 'entity')
    {
        if ($uuid) {
            $apiEndpoint = $this->getParameter('app.persistence.'.$persistence).'/'.$resourcePath.'/'.$uuid;
        } else {
            $apiEndpoint = $this->getParameter('app.persistence.'.$persistence).'/'.$resourcePath;
        }

        $error = [];

        try {
            $response = $this->httpClient->request('GET', $apiEndpoint);
            $content = $response->getContent();
            $headers = $response->getHeaders();
            $data = json_decode($content, $assoc);
        } catch (HttpExceptionInterface $exception) {
            if ($exception->getCode() === 404) {
                if (empty($headers)) {
                    $headers = [];
                }
                $data = null;
            } else {
                throw $exception;
            }
        }

        return ['body' => $data, 'headers' => $headers,
        ];
    }

    public function post($resourcePath, $postdata = [], $assoc, $persistence = 'entity')
    {
        $env = $this->container->get('kernel')->getEnvironment();

        $apiEndpoint = $this->getParameter('app.persistence.'.$persistence).'/'.$resourcePath;

        $error = [];

        try {
            $response = $this->httpClient->request('POST', $apiEndpoint, [
                'verify_peer' => $env === 'prod',  // see https://php.net/context.ssl for the following options
                'verify_host' => $env === 'prod',

                'headers' =>
                    [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ],
                // defining data using a regular string
                'body' => json_encode($postdata),
            ]);
            $content = $response->getContent();
            $headers = $response->getHeaders();
            $data = json_decode($content, $assoc);
        } catch (HttpExceptionInterface $exception) {
            if ($exception->getCode() === 404) {
                if (empty($headers)) {
                    $headers = [];
                }
                $data = null;
            } else {
                throw $exception;
            }
        }

        return ['promise' => null, 'body' => $data, 'headers' => $headers,
        ];
    }

    public function delete($resourcePath, $uuid, $persistence = 'entity'): array
    {
        $acceptHeader = "accept: */*";
        // Création d'un flux
        $opts = array(
            'http' => array(
                'method' => "DELETE",
                'header' =>
                    [
                        $acceptHeader,
                    ],
            )
        );

        $context = stream_context_create($opts);
        json_decode(file_get_contents($this->getParameter('app.persistence.entity').'/'.$resourcePath.'/'.$uuid, false, $context));
        $headers = $this->parseHeaders($http_response_header);
        return $headers;
    }

    public function parseHeaders($headers)
    {
        $head = array();
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1]))
                $head[trim($t[0])] = trim($t[1]);
            else {
                $head[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out))
                    $head['reponse_code'] = intval($out[1]);
            }
        }
        return $head;
    }


    public function put($resourcePath, $uuid, $data = [], $persistence = 'entity')
    {
        $acceptHeader = "accept: application/json";
        $contentTypeHeader = "Content-Type: application/ld+json";

        // Création d'un flux
        $opts = array(
            'http' => array(
                'method' => "PUT",
                'header' =>
                    [
                        $acceptHeader,
                        $contentTypeHeader
                    ],
                'content' => json_encode($data)
            )
        );


        $context = stream_context_create($opts);
        $invoicePromise = json_decode(file_get_contents($this->getParameter('app.persistence.entity').'/'.$resourcePath.'/'.$uuid, false, $context));
        return ['promise' => $invoicePromise, 'header' => $this->parseHeaders($http_response_header),
        ];
    }

}