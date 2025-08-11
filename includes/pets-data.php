<?php

declare(strict_types=1);

const NAMED_PET_SLUGS = ['max','rocky','luna','noin','alaris','laura'];
const NON_PET_FOLDERS = ['bg','blog','clients','cta','illustration','person','portfolio','services','steps'];

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

function getDisplayNameForSlug(string $slug): string {
    return ucwords(str_replace('-', ' ', $slug));
}

function getBreedSlugs(): array {
    $imgRoot = __DIR__ . '/../assets/img';
    $slugs = [];
    if (!is_dir($imgRoot)) {
        return $slugs;
    }
    $entries = scandir($imgRoot) ?: [];
    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        $full = $imgRoot . '/' . $entry;
        if (!is_dir($full)) {
            continue;
        }
        if (in_array($entry, NON_PET_FOLDERS, true)) {
            continue;
        }
        if (in_array($entry, NAMED_PET_SLUGS, true)) {
            continue;
        }
        $images = scanPetImages($entry);
        if (count($images) === 0) {
            continue;
        }
        $slugs[] = $entry;
    }
    sort($slugs, SORT_FLAG_CASE | SORT_STRING);
    return $slugs;
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

    // Add many common breeds (images will populate automatically when present in assets/img/<slug>/)
    $breedSlugs = [
        'golden-retriever','labrador-retriever','german-shepherd','poodle','bulldog','beagle','rottweiler','yorkshire-terrier','boxer','dachshund',
        'great-dane','siberian-husky','doberman','australian-shepherd','shih-tzu','pomeranian','chihuahua','border-collie','bernese-mountain-dog','boston-terrier',
        'shetland-sheepdog','cocker-spaniel','mastiff','bichon-frise','havanese','cane-corso','belgian-malinois','weimaraner','newfoundland','vizsla',
        'whippet','akita','bloodhound','bull-terrier','dalmatian','greyhound','irish-setter','keeshond','alaskan-malamute','samoyed',
        'staffordshire-bull-terrier','pointer','saint-bernard','papillon','corgi','basenji','australian-cattle-dog','english-springer-spaniel','french-bulldog','pug'
    ];

    foreach ($breedSlugs as $slug) {
        $images = scanPetImages($slug);
        $displayName = ucwords(str_replace('-', ' ', $slug));
        $pets[] = [
            'name' => $displayName,
            'breed' => $displayName,
            'age' => 'Varies',
            'slug' => $slug,
            'images' => $images,
            'description' => $displayName . ' looking for a loving home.'
        ];
    }

    // Also auto-include any other folders under assets/img (e.g., dog-ceo slugs like "french-bulldog" vs "bulldog-french")
    $existingSlugs = array_fill_keys(array_map(fn($p) => $p['slug'], $pets), true);
    $imgRoot = __DIR__ . '/../assets/img';
    if (is_dir($imgRoot)) {
        $entries = scandir($imgRoot) ?: [];
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $full = $imgRoot . '/' . $entry;
            if (!is_dir($full)) {
                continue;
            }
            // Skip known non-pet asset folders
            $skip = in_array($entry, [
                'bg','blog','clients','cta','illustration','person','portfolio','services','steps'
            ], true);
            if ($skip) {
                continue;
            }
            if (isset($existingSlugs[$entry])) {
                continue;
            }
            $images = scanPetImages($entry);
            if (count($images) === 0) {
                continue;
            }
            $displayName = ucwords(str_replace('-', ' ', $entry));
            $pets[] = [
                'name' => $displayName,
                'breed' => $displayName,
                'age' => 'Varies',
                'slug' => $entry,
                'images' => $images,
                'description' => $displayName . ' looking for a loving home.'
            ];
        }
    }

    return $pets;
}


