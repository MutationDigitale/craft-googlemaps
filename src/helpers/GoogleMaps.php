<?php
/**
 * Google Maps plugin for Craft CMS
 *
 * Maps in minutes. Powered by Google Maps.
 *
 * @author    Double Secret Agency
 * @link      https://plugins.doublesecretagency.com/
 * @copyright Copyright (c) 2014, 2021 Double Secret Agency
 */

namespace doublesecretagency\googlemaps\helpers;

use Craft;
use doublesecretagency\googlemaps\GoogleMapsPlugin;
use doublesecretagency\googlemaps\models\DynamicMap;
use doublesecretagency\googlemaps\models\Lookup;
use doublesecretagency\googlemaps\models\StaticMap;
use doublesecretagency\googlemaps\models\Visitor;
use doublesecretagency\googlemaps\web\assets\JsApiAsset;
use yii\base\Exception;

/**
 * Class GoogleMaps
 * @since 4.0.0
 */
class GoogleMaps
{

    /**
     * @var array An internally managed collection of Dynamic Maps.
     */
    private static $_maps = [];

    // ========================================================================= //
    // Dynamic Maps
    // https://plugins.doublesecretagency.com/google-maps/dynamic-maps/
    // ========================================================================= //

    /**
     * Load the JavaScript assets which power Dynamic Maps.
     */
    public static function loadAssets()
    {
        Craft::$app->getView()->registerAssetBundle(JsApiAsset::class);
    }

    // ========================================================================= //

    /**
     * Create a new Dynamic Map object.
     *
     * @param mixed $locations
     * @param array $options
     * @return DynamicMap
     */
    public static function map($locations = [], array $options = []): DynamicMap
    {
        // Create a new map object
        $map = new DynamicMap($locations, $options);

        // Store map object for future reference
        static::$_maps[$map->id] = $map;

        // Return the map object
        return $map;
    }

    /**
     * Get an existing Dynamic Map object.
     *
     * @param string $mapId
     * @return DynamicMap|null
     * @throws Exception
     */
    public static function getMap(string $mapId)
    {
        // Get existing map object
        $map = (static::$_maps[$mapId] ?? false);

        // If no map object exists, throw an error
        if (!$map) {
            throw new Exception("Encountered an error using the `getMap` method. The map \"{$mapId}\" does not exist.");
        }

        // Return the map object
        return $map;
    }

    // ========================================================================= //
    // Static Maps
    // https://plugins.doublesecretagency.com/google-maps/static-maps/
    // ========================================================================= //

    /**
     * Create a new Static Map object.
     *
     * @param mixed $locations
     * @param array $options
     * @return StaticMap
     */
    public static function img($locations = [], array $options = []): StaticMap
    {
        return new StaticMap($locations, $options);
    }

    // ========================================================================= //
    // Geocoding (Address Lookups)
    // https://plugins.doublesecretagency.com/google-maps/geocoding/
    // ========================================================================= //

    /**
     * Perform a geocoding lookup.
     *
     * @param array|string $parameters
     * @return Lookup|false
     */
    public static function lookup($target = null)
    {
        return GoogleMapsPlugin::$plugin->geocoding->lookup($target);
    }

    // ========================================================================= //
    // Visitor Geolocation
    // https://plugins.doublesecretagency.com/google-maps/geolocation/
    // ========================================================================= //

    /**
     * Perform a visitor geolocation.
     *
     * @param array $config
     * @return Visitor|false
     */
    public static function getVisitor(array $config = [])
    {
        return GoogleMapsPlugin::$plugin->geolocation->getVisitor($config);
    }

    // ========================================================================= //
    // API Service
    // https://plugins.doublesecretagency.com/google-maps/services/api-service/
    // ========================================================================= //

    /**
     * Get the Google API URL.
     *
     * @param array $params
     * @return string
     */
    public static function getApiUrl(array $params = []): string
    {
        return GoogleMapsPlugin::$plugin->api->getApiUrl($params);
    }

    // ========================================================================= //

    /**
     * Get the Google API server key.
     *
     * @return string
     */
    public static function getServerKey(): string
    {
        return GoogleMapsPlugin::$plugin->api->getServerKey();
    }

    /**
     * Get the Google API browser key.
     *
     * @return string
     */
    public static function getBrowserKey(): string
    {
        return GoogleMapsPlugin::$plugin->api->getBrowserKey();
    }

    /**
     * Set the Google API server key.
     *
     * @param string $key
     * @return string
     */
    public static function setServerKey(string $key): string
    {
        return GoogleMapsPlugin::$plugin->api->setServerKey($key);
    }

    /**
     * Set the Google API browser key.
     *
     * @param string $key
     * @return string
     */
    public static function setBrowserKey(string $key): string
    {
        return GoogleMapsPlugin::$plugin->api->setBrowserKey($key);
    }

}
