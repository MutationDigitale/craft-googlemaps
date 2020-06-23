import AddressField from './../vue/address';
import SubfieldManager from './../vue/subfield-manager';
import DefaultCoords from './../vue/default-coords';

// Disable silly message
Vue.config.productionTip = false;

// Create Vue instance
window.addressFieldSettings = new Vue({
    components: {
        'address-field': AddressField,
        'subfield-manager': SubfieldManager,
        'default-coords': DefaultCoords
    },
    data: {
        settings: settings,
        data: data,
        icons: icons
    }
});

// Initialize Vue instance
window.initAddressFieldSettings = () => {
    console.log('callback triggered!');
    // window.addressFieldSettings.$mount('#types-doublesecretagency-googlemaps-fields-AddressField-address-settings');
}
