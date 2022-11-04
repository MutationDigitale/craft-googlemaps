<?php

namespace doublesecretagency\googlemaps\gql\types;

use craft\gql\GqlEntityRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class AddressGqlType
 * @package doublesecretagency\smartmap\models
 */
class AddressGqlType
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'addressField_Address';
    }
    
    /**
     * @return Type
     */
    public static function getType()
    {
        if ($type = GqlEntityRegistry::getEntity(self::class)) {
            return $type;
        }
        
        $type = GqlEntityRegistry::createEntity(
            self::class,
            new ObjectType(
                [
                    'name' => static::getName(),
                    'fields' => self::class . '::getFieldDefinitions',
                ]
            )
        );
        
        return $type;
    }
    
    /**
     * @return array
     */
    public static function getFieldDefinitions(): array
    {
        return [
            'street1' => [
                'name' => 'street1',
                'type' => Type::string()
            ],
            'street2' => [
                'name' => 'street2',
                'type' => Type::string()
            ],
            'city' => [
                'name' => 'city',
                'type' => Type::string()
            ],
            'state' => [
                'name' => 'state',
                'type' => Type::string()
            ],
            'zip' => [
                'name' => 'zip',
                'type' => Type::string()
            ],
            'country' => [
                'name' => 'country',
                'type' => Type::string()
            ],
            'lat' => [
                'name' => 'lat',
                'type' => Type::float()
            ],
            'lng' => [
                'name' => 'lng',
                'type' => Type::float()
            ],
            'distance' => [
                'name' => 'distance',
                'type' => Type::float()
            ]
        ];
    }
}
