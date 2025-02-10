<?php

namespace VoucherlyApi;

class Api
{
    private static $env = 'production';
    private static $apiKey;
    private static $version = '1.2.0';
    private static $osNameHeader;
    private static $osVersionHeader;
    private static $osFrameworkHeader;
    private static $appVersionHeader;
    private static $appHouseHeader;
    private static $appNameHeader;
    private static $typeHeader;

    public static function testAuthentication($apiKey = null): bool
    {
        try {
            $test = null === $apiKey
              ? Request::get('payment_gateways')
              : Request::get_on_demand($apiKey, 'payment_gateways');

            return true;
        } catch (NotSuccessException $ex) {
            if (401 === $ex->getCode()) {
                return false;
            }

            return true;
        }
    }

    public static function getApiKey()
    {
        return self::$apiKey;
    }

    public static function setApiKey($value)
    {
        self::$apiKey = $value;
    }

    /**
     * Get version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::$version;
    }

    /**
     * Get platform name header.
     *
     * @return string
     */
    public static function getOsNameHeader()
    {
        return self::$osNameHeader;
    }

    /**
     * Set platform name header.
     *
     * @param string $value
     */
    public static function setOsNameHeader($value)
    {
        self::$osNameHeader = $value;
    }

    /**
     * Get platform version header.
     *
     * @return string
     */
    public static function getOsVersionHeader()
    {
        return self::$osVersionHeader;
    }

    /**
     * Set platform version header.
     *
     * @param string $value
     */
    public static function setOsVersionHeader($value)
    {
        self::$osVersionHeader = $value;
    }

    /**
     * Get platform framework header.
     *
     * @return string
     */
    public static function getOsFrameworkHeader()
    {
        return self::$osFrameworkHeader;
    }

    /**
     * Set platform framework header.
     *
     * @param string $value
     */
    public static function setOsFrameworkHeader($value)
    {
        self::$osFrameworkHeader = $value;
    }

    /**
     * Get plugin name header.
     *
     * @return string
     */
    public static function getAppNameHeader()
    {
        return self::$appNameHeader;
    }

    /**
     * Set plugin name header.
     *
     * @param string $value
     */
    public static function setAppNameHeader($value)
    {
        self::$appNameHeader = $value;
    }

    /**
     * Get plugin version header.
     *
     * @return string
     */
    public static function getAppVersionHeader()
    {
        return self::$appVersionHeader;
    }

    /**
     * Set plugin version header.
     *
     * @param string $value
     */
    public static function setAppVersionHeader($value)
    {
        self::$appVersionHeader = $value;
    }


    /**
     * Get plugin house header.
     *
     * @return string
     */
    public static function getAppHouseHeader()
    {
        return self::$appHouseHeader;
    }

    /**
     * Set plugin house header.
     *
     * @param string $value
     */
    public static function setAppHouseHeader($value)
    {
        self::$appHouseHeader = $value;
    }

    /**
     * Get type header.
     *
     * @return string
     */
    public static function getDeviceTypeHeader()
    {
        return self::$typeHeader;
    }

    /**
     * Set type header.
     *
     * @param string $value
     */
    public static function setDeviceTypeHeader($value)
    {
        self::$typeHeader = $value;
    }
}
