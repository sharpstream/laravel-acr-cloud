<?php

namespace Sharpstream\AcrCloud;

/**
 * Class Metadata
 * @package Sharpstream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 */
class Metadata
{
    /** @var string  */
    protected $requestUrl;
    /** @var string  */
    protected $accessKey;
    /** @var string  */
    protected $accessSecret;
    /** @var string  */
    protected $host;

    /**
     * Metadata constructor.
     */
    public function __construct()
    {
        $this->host = env('ACR_HOST');
        $this->requestUrl = env('ACR_REQUEST_URL');
        $this->accessKey = env('ACR_ACCESS_KEY');
        $this->accessSecret = env('ACR_ACCESS_SECRET');
    }

    /**
     * @param $file
     * @param $startSeconds
     * @param $recognizerAudioLength
     * @param int $recognizeType
     * @return mixed
     */
    public function recognizeFile(
        $file,
        $startSeconds,
        $recognizerAudioLength,
        $recognizeType = RecognizeType::ACR_OPT_REC_AUDIO
    ) {
        $config = array(
            'host' => $this->host,
            'access_key' => $this->accessKey,
            'access_secret' => $this->accessSecret,
            'recognize_type' => $recognizeType
        );

        $re = new Recognizer($config);
        try {
            $response = $re->recognizeByFile($file, $startSeconds, $recognizerAudioLength);
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
        }

        return json_decode($response, true);
    }
}
