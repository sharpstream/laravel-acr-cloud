<?php

namespace SharpStream\AcrCloud;

use Carbon\Carbon;

/**
 * Class Formatter
 * @package SharpStream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 */
class Formatter
{
    /**
     * Format response from ACR cloud to more simple array
     * @param $acrResponse
     * @return array
     */
    public static function translateResponse($acrResponse)
    {
        $data = [];
        if (isset($acrResponse['metadata']) && isset($acrResponse['metadata']['music'])) {
            $musicResults = $acrResponse['metadata']['music'];
            $i = 0;
            foreach ($musicResults as $music) {
                $data[$i] = [
                    'title' => isset($music['title']) ? $music['title'] : false,
                    'album' => self::retrieveNestedMeta($music, 'album', 'name'),
                    'artist' => self::retrieveNestedMeta($music, 'artists', 'name'),
                    'genre' => self::retrieveNestedMeta($music, 'genres', 'name'),
                    'publisher' => isset($music['label']) ? $music['label'] : false,
                    'year' => self::retrieveYear($music),
                    'date' => self::retrieveYear($music)
                ];

                // Remove empty fields
                foreach ($data[$i] as $key => $value) {
                    if (!$value) {
                        unset($data[$i][$key]);
                    }
                }
                $i ++;
            }
        }

        return $data;
    }

    /**
     * @param $metadata
     * @param $key
     * @param $value
     * @return mixed
     */
    protected static function retrieveNestedMeta($metadata, $key, $value)
    {
        if (!isset($metadata[$key])) {
            return false;
        }

        /*
         * Example :
         * metadata
         * |--album
         *    |--name
         */
        if (isset($metadata[$key][$value])) {
            return $metadata[$key][$value];
        }

        /*
         * Todo:: at the moment we just get the first - we may want to provide all results
         * Check if key is an array
         * metadata
         * |--artists
         *    |--0
         *       |--name
         */
        if (isset($metadata[$key][0]) && isset($metadata[$key][0][$value])) {
            return $metadata[$key][0][$value];
        }
        return false;
    }

    protected static function retrieveYear($metadata)
    {
        if (isset($metadata['release_date'])) {
            $date = $metadata['release_date'];
            $year = Carbon::createFromFormat('Y-m-d', $date)->year;
            return $year;
        }

        return false;
    }
}