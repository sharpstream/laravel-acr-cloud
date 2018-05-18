# Laravel ACRCloud Package

## Installation

### Compatibility

This package is using the ACRCloud php 7.2 executible.
This requires Laravel 5.6 or greater

### Composer

```
composer require sharpstream/acrcloud:1.0
```

### Add acrcloud_extr_tool.so
* Copy acrcloud_extr_tool.so file to your extensions directory. Found by viewing your php.ini and searching for : extension_dir
* Add extension=acrcloud_extr_tool.so to the bottom of php.ini
* Restart PHP

## Envirnonment Variables
This package requires a few variables to be added to your projects .env file. All of the values for which can be found by logging into your ACRCloud Account.

Your host and request url will differ dependend on your region.

```
ACR_HOST=http://identify-eu-west-1.acrcloud.com
ACR_REQUEST_URL=http://identify-eu-west-1.acrcloud.com/v1/identify
ACR_ACCESS_KEY=***************************
ACR_ACCESS_SECRET=***************************
```

## Example Controller

```
<?php
namespace App\Http\Controllers;

use App\Storage;
use SharpStream\AcrCloud\Metadata;
use SharpStream\AcrCloud\Formatter;
use Illuminate\Support\Facades\Cache;

class AcrCloudController extends Controller
{
  public function show($file)
    {
        // File is the path to your file
        if (Cache::has(self::ACR_CACHE_PREFIX . $file->name)) {
            return response()->json(Cache::get(self::ACR_CACHE_PREFIX . $file->name), 200);
        }
        
        $recognizerInstance = new Metadata;

        $fileData = $recognizerInstance->recognizeFile($file, 10, 10);
        $metadata = Formatter::translateResponse($fileData);

        if (!empty($metadata)) {
            Cache::forever(self::ACR_CACHE_PREFIX . $file->name, $metadata);
            return response()->json($metadata, 200);
        }
      return response()->json(['errors' => ['Unable to retrieve metadata for this file']], 400);
    }
}
```




