<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5645033207ccec85280b50dabcf8f9ac
{
    public static $files = array (
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Slim\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Container\\' => 14,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Slim\\' => 
        array (
            0 => __DIR__ . '/..' . '/slim/slim/Slim',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static $classMap = array (
        'XMLSchema' => __DIR__ . '/../..' . '/lib/class.xmlschema.php',
        'nusoap_base' => __DIR__ . '/../..' . '/lib/class.nusoap_base.php',
        'nusoap_client' => __DIR__ . '/../..' . '/lib/class.soapclient.php',
        'nusoap_client_mime' => __DIR__ . '/../..' . '/lib/nusoapmime.php',
        'nusoap_fault' => __DIR__ . '/../..' . '/lib/class.soap_fault.php',
        'nusoap_parser' => __DIR__ . '/../..' . '/lib/class.soap_parser.php',
        'nusoap_server' => __DIR__ . '/../..' . '/lib/class.soap_server.php',
        'nusoap_server_mime' => __DIR__ . '/../..' . '/lib/nusoapmime.php',
        'nusoap_wsdlcache' => __DIR__ . '/../..' . '/lib/class.wsdlcache.php',
        'nusoap_xmlschema' => __DIR__ . '/../..' . '/lib/class.xmlschema.php',
        'nusoapservermime' => __DIR__ . '/../..' . '/lib/nusoapmime.php',
        'soap_fault' => __DIR__ . '/../..' . '/lib/class.soap_fault.php',
        'soap_parser' => __DIR__ . '/../..' . '/lib/class.soap_parser.php',
        'soap_server' => __DIR__ . '/../..' . '/lib/class.soap_server.php',
        'soap_transport_http' => __DIR__ . '/../..' . '/lib/class.soap_transport_http.php',
        'soapclient' => __DIR__ . '/../..' . '/lib/class.soapclient.php',
        'soapclientmime' => __DIR__ . '/../..' . '/lib/nusoapmime.php',
        'soapval' => __DIR__ . '/../..' . '/lib/class.soap_val.php',
        'wsdl' => __DIR__ . '/../..' . '/lib/class.wsdl.php',
        'wsdlcache' => __DIR__ . '/../..' . '/lib/class.wsdlcache.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5645033207ccec85280b50dabcf8f9ac::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5645033207ccec85280b50dabcf8f9ac::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit5645033207ccec85280b50dabcf8f9ac::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit5645033207ccec85280b50dabcf8f9ac::$classMap;

        }, null, ClassLoader::class);
    }
}
