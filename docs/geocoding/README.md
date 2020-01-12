# Geocoding (Address Lookups)

You will often need to perform a geocoding lookup to get the full address (including coordinates) based on a partial address or postal code. Indeed, the Google Maps plugin itself uses these lookup methods frequently under the hood.

![](/images/geocoding/flow-chart.png)

If you want to get the complete set of geocoding results from Google, you would use the [`all()`](/models/lookup-model/#all) method. The results will be sorted in order of what Google considers to be the best match.

Generally (but not always), it's safe to assume that Google hunch is correct, and the _first_ result is likely to be the correct match. In this case, you may feel comfortable using the [`one()`](/models/lookup-model/#one) method.

And if all you really need are the _coordinates_ of the _best possible match_, then it's probably safe to use the [`coords()`](/models/lookup-model/#coords) method.

## Geocoding Service

Most of the lookup behavior is handled by the [Geocoding Service](/services/geocoding-service/). Specifically, the `lookup` method is the only one you really need to worry about.

There are many ways to access the `lookup` method, all of them are equally valid.

**Twig**

```twig
googleMaps.lookup('123 Main St')
```

[_See more Geocoding in Twig..._](/geocoding/in-twig/)

**PHP**

```php
use doublesecretagency\googlemaps\helpers\GoogleMaps;

GoogleMaps::lookup('123 Main St')
```

[_See more Geocoding in PHP..._](/geocoding/in-php/)

The `googleMaps.lookup` Twig method is the same function as the `GoogleMaps::lookup` PHP equivalent. In fact, the `googleMaps` Twig object is an instance of the `GoogleMaps` PHP helper class.

The following two approaches are also identical. The first example is the "quicker" way to call the `lookup` method, but the second example is the more "traditional" way. Either approach is acceptable, they both point to the exact same function. 

```php
use doublesecretagency\googlemaps\services\Geocoding;

Geocoding::lookup('123 Main St')
```

```php
use doublesecretagency\googlemaps\GoogleMapsPlugin;

GoogleMapsPlugin::$plugin->geocoding->lookup('123 Main St')
```

All of these `lookup` methods will return the same thing: a [Lookup Model](/models/lookup-model/). There isn't much you can do with a Lookup Model directly, until you append `.all()` (or `.one()`, or `.coords()`) onto the end of it.

## How the `lookup` method works

This service method prepares a lookup API call based on the specified `parameters`. It will use a [Lookup Model](/models/lookup-model/) to compile all of the parameters specified into a Google Geocoding API endpoint URL. A fully compiled URL will look something like this...

```html
https://maps.googleapis.com/maps/api/geocode/json?address={ADDRESS}&key={KEY}
```

The fully-compiled endpoint will be pinged as soon as the `all` (or `one`, or `coords`) method is executed.

::: warning OMIT THE KEY
You don't need to manually specify the `key` value. It is stored internally, and appended to the API endpoint URL automatically.
:::

### Example using a string:

If you don't need any other parameters, then it's perfectly acceptable to pass in a string directly.

```twig
{# Lookup based on simple string #}
{% do googleMaps.lookup('123 Main St') %}

{# Generates the following API URL: #}
https://maps.googleapis.com/maps/api/geocode/json?address=123+Main+St&key={KEY}
```

### Example using an array of parameters:

If your lookup needs are more complex, you can pass in any values [allowed by the API](https://developers.google.com/maps/documentation/geocoding/intro#geocoding).

```twig
{# Lookup based on complex array #}
{% do googleMaps.lookup({
    'address': '123 Main St',
    'language': 'de',
    'components': 'country:DE'
}) %}

{# Generates the following API URL: #}
https://maps.googleapis.com/maps/api/geocode/json?address=123+Main+St&language=de&components=country:DE&key={KEY}
```