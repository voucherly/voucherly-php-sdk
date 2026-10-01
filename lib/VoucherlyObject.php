<?php

namespace VoucherlyApi;

/**
 * Base of every object sent to or received from the API.
 * Only the properties that have been assigned are serialized, an explicit null included, so a request never sends a field its caller did not set.
 * An object read from a response has every property initialized, to null when the response left it out.
 */
abstract class VoucherlyObject implements \JsonSerializable
{
    /** @var array<string, mixed> */
    private $extensionData = [];

    /** @var array<class-string, array<string, \ReflectionProperty>> */
    private static $properties = [];

    /**
     * Builds the object from its JSON form, assigning only the members present in $data.
     *
     * @param array<string, mixed> $data
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        return static::hydrate($data, false);
    }

    /**
     * @internal builds the object from a response body
     *
     * @param array<string, mixed> $data
     *
     * @return static
     */
    public static function constructFrom(array $data)
    {
        return static::hydrate($data, true);
    }

    /**
     * The members of the JSON object that this version of the SDK does not know.
     *
     * @return array<string, mixed>
     */
    public function getExtensionData(): array
    {
        return $this->extensionData;
    }

    public function jsonSerialize(): object
    {
        $types = static::types();
        $jsonNames = static::jsonNames();
        $data = [];
        foreach (self::properties(static::class) as $name => $property) {
            if ($property->isInitialized($this)) {
                $data[$jsonNames[$name] ?? $name] = self::toJsonValue($this->{$name}, $types[$name] ?? null);
            }
        }

        return (object) $data;
    }

    /**
     * The type each property is read into, for the properties that are not scalars: a VoucherlyObject class, a one-element list with the class of the items, 'map' for a JSON object of strings, or 'date' for a date without time.
     *
     * @return array<string, array{0: class-string<VoucherlyObject>}|string>
     */
    protected static function types(): array
    {
        return [];
    }

    /**
     * The JSON name of each property whose name differs from it.
     *
     * @return array<string, string>
     */
    protected static function jsonNames(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return static
     */
    protected static function hydrate(array $data, bool $complete)
    {
        $object = new static();
        $types = static::types();
        $jsonNames = static::jsonNames();
        foreach (self::properties(static::class) as $name => $property) {
            $key = $jsonNames[$name] ?? $name;
            if (\array_key_exists($key, $data)) {
                $object->{$name} = self::fromJsonValue($data[$key], $types[$name] ?? null, $complete);
                unset($data[$key]);
            } elseif ($complete && !$property->isInitialized($object) && self::allowsNull($property)) {
                $object->{$name} = null;
            }
        }
        $object->extensionData = $data;

        return $object;
    }

    /**
     * @param array<string, mixed> $data
     * @param class-string<VoucherlyObject> $itemClass
     *
     * @return static
     */
    protected static function hydrateWithItems(array $data, string $itemClass)
    {
        $items = $data['items'] ?? [];
        unset($data['items']);

        $object = static::hydrate($data, true);
        $object->items = [];
        foreach ($items as $item) {
            $object->items[] = \is_array($item) ? $itemClass::constructFrom($item) : $item;
        }

        return $object;
    }

    /**
     * @param mixed                     $value
     * @param null|array|string         $type
     *
     * @return mixed
     */
    private static function fromJsonValue($value, $type, bool $complete)
    {
        if (null === $value || null === $type || 'map' === $type) {
            return $value;
        }

        if ('date' === $type) {
            $date = \is_string($value) ? \DateTimeImmutable::createFromFormat('!Y-m-d', $value) : false;

            return false !== $date ? $date : $value;
        }

        if (\is_array($type)) {
            if (!\is_array($value)) {
                return $value;
            }

            return array_map(static fn ($item) => \is_array($item) ? $type[0]::hydrate($item, $complete) : $item, $value);
        }

        return \is_array($value) ? $type::hydrate($value, $complete) : $value;
    }

    /**
     * @param mixed             $value
     * @param null|array|string $type
     *
     * @return mixed
     */
    private static function toJsonValue($value, $type)
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('date' === $type ? 'Y-m-d' : \DATE_ATOM);
        }

        if ('map' === $type && \is_array($value)) {
            return (object) $value;
        }

        return $value;
    }

    private static function allowsNull(\ReflectionProperty $property): bool
    {
        $type = $property->getType();

        return null === $type || $type->allowsNull();
    }

    /**
     * @return array<string, \ReflectionProperty>
     */
    private static function properties(string $class): array
    {
        if (!isset(self::$properties[$class])) {
            self::$properties[$class] = [];
            foreach ((new \ReflectionClass($class))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                if (!$property->isStatic()) {
                    self::$properties[$class][$property->getName()] = $property;
                }
            }
        }

        return self::$properties[$class];
    }
}
