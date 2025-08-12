<?php

declare(strict_types=1);

const NAMED_PET_SLUGS = ['max','rocky','luna','noin','alaris','laura'];
const NON_PET_FOLDERS = ['bg','blog','clients','cta','illustration','person','portfolio','services','steps'];
// Only allow these breed slugs to appear anywhere in the UI
const ALLOWED_BREED_SLUGS = [
    'french-bulldog',
    'labrador-retriever',
    'golden-retriever',
    'german-shepherd',
    'poodle',
    'toy-poodle',
    'dachshund',
    'bulldog',
    'beagle',
    'rottweiler',
    'german-shorthaired-pointer',
    'pembroke-welsh-corgi',
    'australian-shepherd',
    'yorkshire-terrier',
    'cavalier-king-charles-spaniel',
    'doberman-pinscher',
    'cane-corso',
    'miniature-schnauzer',
    'boxer',
    'great-dane',
    'shih-tzu',
    'maltipoo',
];

function scanPetImages(string $slug): array {
    $baseDir = __DIR__ . '/../assets/img/' . $slug;
    if (!is_dir($baseDir)) {
        return [];
    }

    $files = scandir($baseDir) ?: [];
    $imageFiles = array_values(array_filter($files, function ($file) use ($baseDir) {
        if ($file === '.' || $file === '..') return false;
        $path = $baseDir . '/' . $file;
        if (!is_file($path)) return false;
        return preg_match('/\.(jpe?g|png|webp)$/i', $file) === 1;
    }));

    sort($imageFiles, SORT_NATURAL | SORT_FLAG_CASE);

    return array_map(function ($file) use ($slug) {
        return 'assets/img/' . $slug . '/' . $file;
    }, $imageFiles);
}

/**
 * Returns images from the first subfolder inside a breed directory.
 * Useful when a breed stores images per-dog in curated subfolders instead of top-level files.
 */
function scanFirstSubfolderImages(string $slug): array {
    $baseDir = __DIR__ . '/../assets/img/' . $slug;
    if (!is_dir($baseDir)) {
        return [];
    }

    $entries = scandir($baseDir) ?: [];
    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') { continue; }
        $full = $baseDir . '/' . $entry;
        if (!is_dir($full)) { continue; }

        $files = scandir($full) ?: [];
        $images = [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') { continue; }
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'], true)) {
                $images[] = 'assets/img/' . $slug . '/' . $entry . '/' . $f;
            }
        }
        sort($images, SORT_NATURAL | SORT_FLAG_CASE);
        if (count($images) > 0) {
            return $images;
        }
    }
    return [];
}

function getDisplayNameForSlug(string $slug): string {
    return ucwords(str_replace('-', ' ', $slug));
}

function getBreedSlugs(): array {
    // Restrict to the allowed set (and only include those with at least one image)
    $available = [];
    foreach (ALLOWED_BREED_SLUGS as $slug) {
        $images = scanPetImages($slug);
        if (count($images) === 0) {
            // Allow breeds that only store images in curated subfolders
            $images = scanFirstSubfolderImages($slug);
        }
        if (count($images) > 0) { $available[] = $slug; }
    }
    return $available;
}

function generateDogName(string $breedSlug, int $index): string {
    $names = [
        'Bella','Charlie','Luna','Cooper','Lucy','Max','Daisy','Milo','Rosie','Bailey',
        'Sadie','Teddy','Maggie','Rocky','Zoe','Buddy','Chloe','Duke','Lola','Finn',
        'Ruby','Leo','Ellie','Louie','Winston','Mia','Harley','Nala','Jasper','Piper',
        'Riley','Gracie','Hunter','Hazel','Kona','Beau','Annie','Ollie','Penny','Bentley'
    ];
    $seed = crc32($breedSlug) + $index;
    $name = $names[$seed % count($names)];
    return $name;
}

function getAllPets(): array {
    $pets = [];

    // Existing pets (use current folders and scans)
    $pets[] = [
        'name' => 'Max',
        'breed' => 'Golden Retriever',
        'age' => '2 years',
        'slug' => 'max',
        'images' => scanPetImages('max'),
        'description' => 'Max is a friendly, energetic Golden Retriever who loves people and playtime. He is healthy, vaccinated, and ready to find his forever home.'
    ];

    $pets[] = [
        'name' => 'Rocky',
        'breed' => 'Mixed',
        'age' => '3 years',
        'slug' => 'rocky',
        'images' => scanPetImages('rocky'),
        'description' => 'Rocky is a loyal and confident dog who enjoys outdoor adventures and cuddle time. Vaccinated and ready for a loving home.'
    ];

    $pets[] = [
        'name' => 'Luna',
        'breed' => 'Mixed',
        'age' => '1 year',
        'slug' => 'luna',
        'images' => scanPetImages('luna'),
        'description' => 'Luna is a sweet and playful companion who loves walks and belly rubs. Vaccinated and ready for a forever home.'
    ];

    $pets[] = [
        'name' => 'Noin',
        'breed' => 'Mixed',
        'age' => '2 years',
        'slug' => 'noin',
        'images' => scanPetImages('noin'),
        'description' => 'Noin is affectionate and playful, loves outdoor time and relaxing with family. Vaccinated and ready to join a loving home.'
    ];

    $pets[] = [
        'name' => 'Alaris',
        'breed' => 'Mixed',
        'age' => '2 years',
        'slug' => 'alaris',
        'images' => scanPetImages('alaris'),
        'description' => 'Alaris is a gentle and loving dog looking for a caring home. Vaccinated and ready to meet you.'
    ];

    $pets[] = [
        'name' => 'Laura',
        'breed' => 'Mixed',
        'age' => '2 years',
        'slug' => 'laura',
        'images' => scanPetImages('laura'),
        'description' => 'Laura is friendly and smart, great with families and other pets. Vaccinated and eager to find a forever home.'
    ];

    // Only include the allowed breeds in the dynamic list
    foreach (ALLOWED_BREED_SLUGS as $slug) {
        $images = scanPetImages($slug);
        if (count($images) === 0) {
            // Fallback to curated subfolders when no top-level images exist (e.g., poodle)
            $images = scanFirstSubfolderImages($slug);
        }
        if (count($images) === 0) { continue; }
        $displayName = getDisplayNameForSlug($slug);
        $pets[] = [
            'name' => $displayName,
            'breed' => $displayName,
            'age' => 'Varies',
            'slug' => $slug,
            'images' => $images,
            'description' => $displayName . ' looking for a loving home.'
        ];
    }

    // Do not auto-include any other folders; strictly enforce ALLOWED_BREED_SLUGS

    return $pets;
}


