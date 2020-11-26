# Using an Address in Twig

In the example below, the `address` variable is being set to an [Address Model](/models/address-model/). Anything you can do with an Address Model can be done with Twig.

```twig
{# Get an Address Model from the `myAddressField` field #}
{% set address = entry.myAddressField %}

<h1>{{ entry.title }}</h1>
<div>
    {{ address.street1 }}<br />
    {{ address.street2 }}<br />
    {{ address.city }}, {{ address.state }} {{ address.zip }}<br />
    Latitude: {{ address.lat }}<br />
    Longitude: {{ address.lng }}
</div>
```

The result will look something like this...

<img class="dropshadow" :src="$withBase('/images/address-field/empire-example.png')" alt="Example using the address of the Empire State Building">