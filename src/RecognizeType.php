<?php

namespace Sharpstream\AcrCloud;

/**
 * Class RecognizeType
 * @package Sharpstream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 * See : https://github.com/acrcloud/acrcloud_sdk_php/tree/master/linux/x86-64/php72
 */
class RecognizeType
{
    const ACR_OPT_REC_AUDIO = 0;  # audio fingerprint
    const ACR_OPT_REC_HUMMING = 1; # humming fingerprint
    const ACR_OPT_REC_BOTH = 2; # audio and humming fingerprint
}
