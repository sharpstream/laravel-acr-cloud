<?php

namespace Sharpstream\AcrCloud;

/**
 * Class ExtrTool
 * @package Sharpstream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 * See : https://github.com/acrcloud/acrcloud_sdk_php/tree/master/linux/x86-64/php72
 */
class ExtrTool
{
    /**
     * create "ACRCloud Fingerprint"
     * by wav audio buffer(RIFF (little-endian) data, WAVE audio, Microsoft PCM, 16 bit, mono 8000 Hz)
     *
     * @param $pcm_buffer // Query audio buffer
     * @param $is_db // If it is True, it will create db fingerprint;
     * @return string // "ACRCloud Fingerprint"
     **/
    public static function createFingerprint($pcm_buffer, $is_db)
    {
        return acrcloud_create_fingerprint($pcm_buffer, $is_db);
    }

    /**
     * create "ACRCloud Humming Fingerprint"
     * by wav audio buffer(RIFF (little-endian) data, WAVE audio, Microsoft PCM, 16 bit, mono 8000 Hz)
     *
     * @param $pcm_buffer // Query audio buffer
     * @return string // "ACRCloud Humming Fingerprint"
     *
     **/
    public static function createHummingFingerprint($pcm_buffer)
    {
        return acrcloud_create_humming_fingerprint($pcm_buffer);
    }

    /**
     *
     *  create "ACRCloud Fingerprint" by file path of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     *  @param $file_path // query file path
     *  @param $start_seconds // skip (start_seconds) seconds from from the beginning of (file_path)
     *  @param $audio_len_seconds // Length of audio data you need. if you create recognise fingerprint,
     *  default is 12 seconds, if you create db fingerprint, it is not usefully;
     *  @param $is_db // If it is True, it will create db fingerprint;
     *
     *  @return string // result "ACRCloud Fingerprint"
     *     null: can not create fingerprint, maybe mute.
     *     false: can decode audio from $file_path.
     *     throw Exception: other error, or params error.
     *
     **/
    public static function createFingerprintByFile($file_path, $start_seconds, $audio_len_seconds, $is_db)
    {
        return acrcloud_create_fingerprint_by_file($file_path, $start_seconds, $audio_len_seconds, $is_db);
    }

    /**
     *
     *  create "ACRCloud Humming Fingerprint" by file path of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     *  @param $file_path // query file path
     *  @param $start_seconds // skip (start_seconds) seconds from from the beginning of (file_path)
     *  @param $audio_len_seconds // Length of audio data you need. if you create recogize frigerprint, default is 12 seconds, if you create db frigerprint, it is not usefully;
     *
     *  @return string // "ACRCloud Humming Fingerprint"
     *     null: can not create fingerprint, maybe mute.
     *     false: can decode audio from $file_path.
     *     throw Exception: other error, or params error.
     *
     **/
    public static function createHummingFingerprintByFile($file_path, $start_seconds, $audio_len_seconds)
    {
        return acrcloud_create_humming_fingerprint_by_file($file_path, $start_seconds, $audio_len_seconds);
    }

    /**
     *
     *  create "ACRCloud Fingerprint" by file buffer of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     *  @param $file_buffer // data buffer of input file
     *  @param $start_seconds // skip (start_seconds) seconds from from the beginning of (file_path)
     *  @param $audio_len_seconds // Length of audio data you need. if you create recognise fingerprint,
     *  default is 12 seconds, if you create db fingerprint, it is not usefully;
     *  @param $is_db  // If it is True, it will create db fingerprint;
     *
     *  @return string // result "ACRCloud Fingerprint"
     *     null: can not create fingerprint
     *     false: can decode audio from $file_path.
     *     throw Exception: other error, or params error.
     *
     **/
    public static function createFingerprintByFileBuffer($file_buffer, $start_seconds, $audio_len_seconds, $is_db)
    {
        return acrcloud_create_fingerprint_by_filebuffer($file_buffer, $start_seconds, $audio_len_seconds, $is_db);
    }

    /**
     *
     *  create "ACRCloud Humming Fingerprint" by file buffer of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     *  @param $file_buffer // data buffer of input file
     *  @param $start_seconds // skip (start_seconds) seconds from from the beginning of (file_path)
     *  @param $audio_len_seconds // Length of audio data you need. if you create recognise fingerprint,
     *  default is 12 seconds, if you create db frigerprint, it is not usefully;
     *
     *  @return string // result "ACRCloud Humming Fingerprint"
     *     null: can not create fingerprint
     *     false: can decode audio from $file_path.
     *     throw Exception: other error, or params error.
     *
     **/
    public static function createHummingFingerprintByFileBuffer($file_buffer, $start_seconds, $audio_len_seconds)
    {
        return acrcloud_create_humming_fingerprint_by_filebuffer($file_buffer, $start_seconds, $audio_len_seconds);
    }

    /**
     *
     * decode audio from file path of (Audio/Video file)
     *         Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *         Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     * @param $file_path // query file path
     * @param $start_seconds // skip (start_seconds) seconds from from the beginning of (file_path)
     * @param $audio_len_seconds // Length of audio data you need, if it is 0, will decode all the audio;
     *
     * @return string // result audio data(formatter:RIFF (little-endian) data, WAVE, Microsoft PCM, 16 bit, mono 8000 Hz)
     *      null: can not decode audio from $file_path
     *
     **/
    public static function decodeAudioByFile($file_path, $start_seconds, $audio_len_seconds)
    {
        return acrcloud_decode_audio_by_file($file_path, $start_seconds, $audio_len_seconds);
    }

    /**
     *
     *  decode audio from file buffer of (Audio/Video file)
     *          Audio: mp3, wav, m4a, flac, aac, amr, ape, ogg ...
     *          Video: mp4, mkv, wmv, flv, ts, avi ...
     *
     *  @param $file_buffer // data buffer of input file
     *  @param $start_seconds // skip (start_seconds) seconds from from the beginning of (file_path)
     *  @param $audio_len_seconds // Length of audio data you need, if it is 0, will decode all the audio;
     *
     *  @return string // result audio data(formatter:RIFF (little-endian) data, WAVE audio, Microsoft PCM, 16 bit, mono 8000 Hz)
     *
     */
    public static function decodeAudioByFileBuffer($file_buffer, $start_seconds, $audio_len_seconds)
    {
        return acrcloud_decode_audio_by_filebuffer($file_buffer, $start_seconds, $audio_len_seconds);
    }

    /**
     * @param $file_path
     * @return mixed
     */
    public static function getDurationFromFile($file_path)
    {
        return acrcloud_get_duration_ms_by_file($file_path);
    }

    /**
     * @param $is_debug
     */
    public static function setDebug($is_debug)
    {
        acrcloud_set_debug_mode($is_debug);
    }
}