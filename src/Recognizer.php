<?php

namespace SharpStream\AcrCloud;

/**
 * Class Recognizer
 * @package SharpStream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 * See : https://github.com/acrcloud/acrcloud_sdk_php/tree/master/linux/x86-64/php72
 */
class Recognizer
{
    private $host = "";
    private $access_key = "";
    private $access_secret = "";
    private $timeout = 5; // In seconds
    private $recognizerType = RecognizeType::ACR_OPT_REC_AUDIO; // Default

    /**
     * Recognizer constructor.
     * @param $config
     */
    public function __construct($config)
    {
        if (array_key_exists('host', $config)) {
            $this->host = $config['host'];
        }
        if (array_key_exists('access_key', $config)) {
            $this->access_key = $config['access_key'];
        }
        if (array_key_exists('access_secret', $config)) {
            $this->access_secret = $config['access_secret'];
        }
        if (array_key_exists('timeout', $config)) {
            $this->timeout = $config['timeout'];
        }
        if (array_key_exists('recognize_type', $config)) {
            $this->recognizerType = $config['recognize_type'];
            if ($this->recognizerType > 2) {
                // @todo I think this should probably be $this->recognizerType = ...
                $recognizerType = RecognizeType::ACR_OPT_REC_AUDIO;
            }
        }
    }

    /**
     *
     *  recognize by file path of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     * @param $filePath // query file path
     * @param $startSeconds // skip (startSeconds) seconds from from the beginning of (file_path)
     * @param $recognizerAudioLength
     *
     * @return array // result metainfos https://docs.acrcloud.com/metadata
     *
     **/
    public function recognizeByFile($filePath, $startSeconds, $recognizerAudioLength = 10)
    {
        if (!file_exists($filePath)) {
            return ACRCloudExceptionCode::getCodeResult(ACRCloudExceptionCode::$GEN_FP_ERROR);
        }

        $query_data = array();
        if ($this->recognizerType == RecognizeType::ACR_OPT_REC_AUDIO ||
            $this->recognizerType == RecognizeType::ACR_OPT_REC_BOTH) {
            $query_data['sample'] = ExtrTool::createFingerprintByFile(
                $filePath,
                $startSeconds,
                $recognizerAudioLength,
                false
            );
        }
        if ($this->recognizerType == RecognizeType::ACR_OPT_REC_HUMMING ||
            $this->recognizerType == RecognizeType::ACR_OPT_REC_BOTH) {
            $query_data['sample_hum'] = ExtrTool::createHummingFingerprintByFile(
                $filePath,
                $startSeconds,
                $recognizerAudioLength
            );
        }

        return $this->doRecognize($query_data);
    }

    /**
     *
     *  recognize by buffer of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     * @param $fileBuffer // query buffer
     * @param $startSeconds // skip (startSeconds) seconds from from the beginning of $fileBuffer
     * @param $recognizerAudioLength
     *
     * @return array // result metainfos https://docs.acrcloud.com/metadata
     *
     **/
    public function recognizeByFileBuffer($fileBuffer, $startSeconds, $recognizerAudioLength = 10)
    {
        $query_data = array();
        if ($this->recognizerType == RecognizeType::ACR_OPT_REC_AUDIO ||
            $this->recognizerType == RecognizeType::ACR_OPT_REC_BOTH) {
            $query_data['sample'] = ExtrTool::createFingerprintByFileBuffer(
                $fileBuffer,
                $startSeconds,
                $recognizerAudioLength,
                false
            );
        }
        if ($this->recognizerType == RecognizeType::ACR_OPT_REC_HUMMING ||
            $this->recognizerType == RecognizeType::ACR_OPT_REC_BOTH) {
            $query_data['sample_hum'] = ExtrTool::createHummingFingerprintByFileBuffer(
                $fileBuffer,
                $startSeconds,
                $recognizerAudioLength
            );
        }

        return $this->doRecognize($query_data);
    }

    /**
     *
     *  recognize by wav audio buffer(RIFF (little-endian) data, WAVE audio, Microsoft PCM, 16 bit, mono 8000 Hz)
     *
     * @param $pcmAudioBuffer // query audio buffer
     * @return array // result metainfos https://docs.acrcloud.com/metadata
     */
    public function recognize($pcmAudioBuffer)
    {
        $query_data = array();
        if ($this->recognizerType == RecognizeType::ACR_OPT_REC_AUDIO ||
            $this->recognizerType == RecognizeType::ACR_OPT_REC_BOTH) {
            $query_data['sample'] = ExtrTool::createFingerprint($pcmAudioBuffer, false);
        }
        if ($this->recognizerType == RecognizeType::ACR_OPT_REC_HUMMING ||
            $this->recognizerType == RecognizeType::ACR_OPT_REC_BOTH) {
            $query_data['sample_hum'] = ExtrTool::createHummingFingerprint($pcmAudioBuffer);
        }

        return $this->doRecognize($query_data);
    }

    private function doRecognize($query_data)
    {
        $http_method = "POST";
        $http_uri = "/v1/identify";
        $data_type = "fingerprint";
        $signature_version = "1";
        $timestamp = time();
        $requrl = $this->host . "/v1/identify";

        $string_to_sign = $http_method . "\n" . $http_uri . "\n" . $this->access_key . "\n" .
            $data_type . "\n" . $signature_version . "\n" . $timestamp;

        $result = '';
        try {
            $signature = hash_hmac("sha1", $string_to_sign, $this->access_secret, true);
            $signature = base64_encode($signature);
            $post_arrays = array(
                "access_key" => $this->access_key,
                "data_type" => $data_type,
                "signature" => $signature,
                "signature_version" => $signature_version,
                "timestamp" => $timestamp
            );
            $sample_bytes = 0;
            $sample_hum_bytes = 0;
            if (array_key_exists('sample', $query_data)) {
                if ($query_data["sample"] == false) {
                    return ACRCloudExceptionCode::getCodeResult(ACRCloudExceptionCode::$GEN_FP_ERROR);
                }
                $post_arrays["sample"] = $query_data["sample"];
                $sample_bytes = strlen($query_data["sample"]);
                $post_arrays["sample_bytes"] = $sample_bytes;
            }
            if (array_key_exists('sample_hum', $query_data)) {
                if ($query_data["sample_hum"] == false) {
                    return ACRCloudExceptionCode::getCodeResult(ACRCloudExceptionCode::$GEN_FP_ERROR);
                }
                $post_arrays["sample_hum"] = $query_data["sample_hum"];
                $sample_hum_bytes = strlen($query_data["sample_hum"]);
                $post_arrays["sample_hum_bytes"] = $sample_bytes;
            }
            if ($sample_bytes == 0 && $sample_hum_bytes == 0) {
                return ACRCloudExceptionCode::getCodeResult(ACRCloudExceptionCode::$NO_RESULT);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $requrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arrays);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            if ($errno == 28) {
                return ACRCloudExceptionCode::getCodeResultMsg(ACRCloudExceptionCode::$HTTP_ERROR, "HTTP TIMEOUT");
            } elseif ($errno) {
                return ACRCloudExceptionCode::getCodeResultMsg(ACRCloudExceptionCode::$UNKNOW_ERROR, "errno:" . $errno);
            }

            try {
                if (!json_decode($result)) {
                    return ACRCloudExceptionCode::getCodeResultMsg(ACRCloudExceptionCode::$JSON_ERROR, $result);
                }
            } catch (\Exception $e) {
                return ACRCloudExceptionCode::getCodeResult(ACRCloudExceptionCode::$JSON_ERROR);
            }
        } catch (\Exception $e) {
            $result = ACRCloudExceptionCode::getCodeResultMsg(ACRCloudExceptionCode::$HTTP_ERROR, $e->getMessage());
        }

        return $result;
    }
}
