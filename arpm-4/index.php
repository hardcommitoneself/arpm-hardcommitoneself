<?php

use Illuminate\Support\Collection;

$employees = collect([
    ['name' => 'John', 'city' => 'Dallas'],
    ['name' => 'Jane', 'city' => 'Austin'],
    ['name' => 'Jake', 'city' => 'Dallas'],
    ['name' => 'Jill', 'city' => 'Dallas'],
]);

$offices = collect([
    ['office' => 'Dallas HQ', 'city' => 'Dallas'],
    ['office' => 'Dallas South', 'city' => 'Dallas'],
    ['office' => 'Austin Branch', 'city' => 'Austin'],
]);

// Group employees by city
$groupedEmployees = $employees->groupBy('city')->map(function ($group) {
    return $group->pluck('name');
});

// Generate the output array
$output = $offices->groupBy('city')->map(function ($officesGroup, $city) use ($groupedEmployees) {
    return $officesGroup->mapWithKeys(function ($office) use ($groupedEmployees) {
        return [$office['office'] => $groupedEmployees->get($office['city'], collect())->toArray()];
    });
});

// Convert the output collection to a plain array
$output = $output->toArray();
