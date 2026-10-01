<?php

namespace VoucherlyApi\Request;

/**
 * Base of the query and header parameters of an operation.
 * Only the properties that have been assigned a value other than null are sent.
 */
abstract class RequestParams
{
    /** @var array<class-string, array<string, \ReflectionProperty>> */
    private static $properties = [];

    /**
     * @internal
     */
    public function toQueryString(): string
    {
        $headers = static::headers();
        $types = static::types();
        $pairs = [];
        foreach ($this->assignedValues() as $name => $value) {
            if (isset($headers[$name])) {
                continue;
            }
            foreach (\is_array($value) ? $value : [$value] as $item) {
                $pairs[] = rawurlencode($name) . '=' . rawurlencode(self::format($item, $types[$name] ?? null));
            }
        }

        return implode('&', $pairs);
    }

    /**
     * @internal
     *
     * @return array<string, string>
     */
    public function toHeaders(): array
    {
        $headers = [];
        $values = $this->assignedValues();
        foreach (static::headers() as $name => $header) {
            if (isset($values[$name])) {
                $headers[$header] = self::format($values[$name], null);
            }
        }

        return $headers;
    }

    /**
     * The header each property is sent as, for the properties that are not query parameters.
     *
     * @return array<string, string>
     */
    protected static function headers(): array
    {
        return [];
    }

    /**
     * 'date' for each property that holds a date without time.
     *
     * @return array<string, string>
     */
    protected static function types(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function assignedValues(): array
    {
        $class = static::class;
        if (!isset(self::$properties[$class])) {
            self::$properties[$class] = [];
            foreach ((new \ReflectionClass($class))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                if (!$property->isStatic()) {
                    self::$properties[$class][$property->getName()] = $property;
                }
            }
        }

        $values = [];
        foreach (self::$properties[$class] as $name => $property) {
            if ($property->isInitialized($this) && null !== $this->{$name}) {
                $values[$name] = $this->{$name};
            }
        }

        return $values;
    }

    /**
     * @param mixed $value
     */
    private static function format($value, ?string $type): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('date' === $type ? 'Y-m-d' : \DATE_ATOM);
        }

        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
