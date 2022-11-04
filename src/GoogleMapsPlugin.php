<?php
/**
 * Google Maps plugin for Craft CMS
 *
 * Maps in minutes. Powered by the Google Maps API.
 *
 * @author    Double Secret Agency
 * @link      https://plugins.doublesecretagency.com/
 * @copyright Copyright (c) 2014, 2021 Double Secret Agency
 */

namespace doublesecretagency\googlemaps;

use Craft;
use craft\base\Element;
use craft\base\Model;
use craft\base\Plugin;
use craft\elements\Entry;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterElementExportersEvent;
use craft\events\RegisterGqlTypesEvent;
use craft\helpers\UrlHelper;
use craft\services\Fields;
use craft\services\Gql;
use craft\services\Plugins;
use doublesecretagency\googlemaps\exporters\AddressesCondensedExporter;
use doublesecretagency\googlemaps\exporters\AddressesExpandedExporter;
use doublesecretagency\googlemaps\fields\AddressField;
use doublesecretagency\googlemaps\gql\types\AddressGqlType;
use doublesecretagency\googlemaps\models\Settings;
use doublesecretagency\googlemaps\web\assets\SettingsAsset;
use doublesecretagency\googlemaps\web\twig\Extension;
use yii\base\Event;

/**
 * Class GoogleMapsPlugin
 * @since 4.0.0
 */
class GoogleMapsPlugin extends Plugin
{

    /**
     * @event GeocodingEvent The event that is triggered after a geocoding address lookup has been performed.
     */
    public const EVENT_AFTER_GEOCODING = 'afterGeocoding';

    /**
     * @event GeolocationEvent The event that is triggered after a visitor geolocation has been performed.
     */
    public const EVENT_AFTER_GEOLOCATION = 'afterGeolocation';

    /**
     * @var bool The plugin has a settings page.
     */
    public bool $hasCpSettings = true;

    /**
     * @var string Current schema version of the plugin.
     */
    public string $schemaVersion = '4.1.0';

    /**
     * @var GoogleMapsPlugin Self-referential plugin property.
     */
    public static GoogleMapsPlugin $plugin;

    /**
     * @var array Collection of settings to be migrated.
     */
    public static array $migrateSettings = [];

    /**
     * @var string|null Existing license key to be migrated.
     */
    public static ?string $migrateLicenseKey = null;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        // Load Twig extension
        Craft::$app->getView()->registerTwigExtension(new Extension());

        // Register field type
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            static function (RegisterComponentTypesEvent $event) {
                $event->types[] = AddressField::class;
            }
        );

        // Register exporters
        Event::on(
            Entry::class,
            Element::EVENT_REGISTER_EXPORTERS,
            static function (RegisterElementExportersEvent $event) {
                $event->exporters[] = AddressesCondensedExporter::class;
                $event->exporters[] = AddressesExpandedExporter::class;
            }
        );

        // After the plugin has been installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            static function (PluginEvent $event) {

                // If installed plugin isn't Google Maps, bail
                if ('google-maps' !== $event->plugin->handle) {
                    return;
                }

                // Get plugins service
                $plugins = Craft::$app->getPlugins();

                // If settings are being migrated, save them
                if (static::$migrateSettings) {
                    $plugins->savePluginSettings(
                        static::$plugin,
                        static::$migrateSettings
                    );
                }

                // If plugin license key is being migrated, save it
                if (static::$migrateLicenseKey) {
                    $plugins->setPluginLicenseKey(
                        'google-maps',
                        static::$migrateLicenseKey
                    );
                }

                // If installed via console, no need for a redirect
                if (Craft::$app->getRequest()->getIsConsoleRequest()) {
                    return;
                }

                // Redirect to the plugin's settings page (with a welcome message)
                $url = UrlHelper::cpUrl('settings/plugins/google-maps', ['welcome' => 1]);
                Craft::$app->getResponse()->redirect($url)->send();
            }
        );
        
        // Register GraphQL type
        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_TYPES,
            function (RegisterGqlTypesEvent $event) {
                $event->types[] = AddressGqlType::class;
            }
        );
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): ?string
    {
        // Reference assets
        $view = Craft::$app->getView();
        $view->registerAssetBundle(SettingsAsset::class);

        // Get data from config file
        $configFile = Craft::$app->getConfig()->getConfigFromFile('google-maps');

        // Load plugin settings template
        return $view->renderTemplate('google-maps/settings', [
            'configFile' => $configFile,
            'settings' => $this->getSettings(),
        ]);
    }

}
