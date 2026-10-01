<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Tests\Support\SampleChild;
use VoucherlyApi\Tests\Support\SampleObject;

final class VoucherlyObjectTest extends TestCase
{
    public function testSerializesOnlyAssignedPropertiesIncludingExplicitNull(): void
    {
        $object = new SampleObject();
        $object->name = 'Mario';
        $object->quantity = null;

        self::assertSame('{"name":"Mario","quantity":null}', json_encode($object));
    }

    public function testSerializesAnObjectWithNothingAssignedAsAnEmptyJsonObject(): void
    {
        self::assertSame('{}', json_encode(new SampleObject()));
    }

    public function testSerializesAnEmptyMapAsAJsonObject(): void
    {
        $object = new SampleObject();
        $object->metadata = [];

        self::assertSame('{"metadata":{}}', json_encode($object));
    }

    public function testSerializesDatesNestedObjectsAndRenamedProperties(): void
    {
        $child = new SampleChild();
        $child->code = 'KG';
        $object = new SampleObject();
        $object->day = new \DateTimeImmutable('2026-09-30 15:00:00', new \DateTimeZone('Europe/Rome'));
        $object->moment = new \DateTimeImmutable('2026-09-30 15:00:00', new \DateTimeZone('Europe/Rome'));
        $object->child = $child;
        $object->children = [$child];
        $object->kind = 'stripe';

        self::assertSame(
            '{"day":"2026-09-30","moment":"2026-09-30T15:00:00+02:00","child":{"code":"KG"},"children":[{"code":"KG"}],"$type":"stripe"}',
            json_encode($object, JSON_UNESCAPED_SLASHES)
        );
    }

    public function testConstructFromInitializesTheMissingPropertiesToNull(): void
    {
        $object = SampleObject::constructFrom(['name' => 'Mario']);

        self::assertSame('Mario', $object->name);
        self::assertNull($object->quantity);
        self::assertNull($object->child);
        self::assertNull($object->children);
    }

    public function testConstructFromHydratesNestedObjectsAndLists(): void
    {
        $object = SampleObject::constructFrom([
            'child' => ['code' => 'KG', 'size' => 500],
            'children' => [['code' => 'G'], ['code' => 'L']],
            'metadata' => ['order' => '42'],
            '$type' => 'satispay',
        ]);

        self::assertInstanceOf(SampleChild::class, $object->child);
        self::assertSame(500, $object->child->size);
        self::assertCount(2, $object->children);
        self::assertSame('L', $object->children[1]->code);
        self::assertNull($object->children[1]->size);
        self::assertSame(['order' => '42'], $object->metadata);
        self::assertSame('satispay', $object->kind);
    }

    public function testKeepsTheUnknownMembersAsExtensionData(): void
    {
        $object = SampleObject::constructFrom(['name' => 'Mario', 'newField' => ['a' => 1], 'child' => ['code' => 'KG', 'unit' => 'g']]);

        self::assertSame(['newField' => ['a' => 1]], $object->getExtensionData());
        self::assertSame(['unit' => 'g'], $object->child->getExtensionData());
        self::assertSame('{"name":"Mario","child":{"code":"KG"}}', json_encode(SampleObject::fromArray(['name' => 'Mario', 'newField' => 1, 'child' => ['code' => 'KG', 'unit' => 'g']])));
    }

    public function testFromArrayAssignsOnlyThePresentMembersSoItRoundTrips(): void
    {
        $data = ['name' => 'Mario', 'quantity' => null, 'day' => '2026-09-30', 'child' => ['code' => 'KG'], 'children' => [['size' => 1]]];

        $object = SampleObject::fromArray($data);

        self::assertInstanceOf(\DateTimeImmutable::class, $object->day);
        self::assertSame($data, json_decode(json_encode($object), true));
    }
}
