<?php

declare(strict_types=1);

const NAMED_PET_SLUGS = ['max','rocky','luna','noin','alaris','laura'];
const NON_PET_FOLDERS = [
    'bg','blog','clients','cta','illustration','person','portfolio','services','steps','logo',
    // Non-breed asset folders that were showing up in the Breeds menu
    'slide','slides','reviewers','team','stories'
];
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

function getDetectedBreedSlugs(): array {
    $baseDir = __DIR__ . '/../assets/img';
    if (!is_dir($baseDir)) { return []; }
    $entries = scandir($baseDir) ?: [];
    $slugs = [];
    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') { continue; }
        $full = $baseDir . '/' . $entry;
        if (!is_dir($full)) { continue; }
        $slug = strtolower($entry);
        if (in_array($slug, NON_PET_FOLDERS, true)) { continue; }
        $slugs[] = $slug;
    }
    sort($slugs, SORT_NATURAL | SORT_FLAG_CASE);
    return $slugs;
}

function getBreedSlugs(): array {
    // Combine explicitly allowed with auto-detected folders (minus non-pet folders)
    $candidates = array_values(array_unique(array_merge(ALLOWED_BREED_SLUGS, getDetectedBreedSlugs())));
    $available = [];
    foreach ($candidates as $slug) {
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
        'Riley','Gracie','Hunter','Hazel','Kona','Beau','Annie','Ollie','Penny','Bentley',
        'Ace','Archie','Arlo','Atlas','Ava','Biscuit','Blue','Bo','Bolt','Bruno',
        'Buster','Casey','Cash','Cassie','Cedar','Chance','Clark','Cleo','Coco','Comet',
        'Copper','Cosmo','Dexter','Diesel','Dolly','Domino','Echo','Emma','Eva','Frankie',
        'Freya','Gigi','Ginger','Goose','Gus','Hank','Harlow','Harper','Hattie','Holly',
        'Indie','Ivy','Jack','Jake','June','Kiki','Koda','Lacey','Layla','Lenny',
        'Lexi','Lily','Loki','Luca','Lucky','Mabel','Macy','Marley','Mason','Matilda',
        'Maverick','Maxwell','Maya','Meadow','Moose','Murphy','Nico','Nova','Oakley','Olive',
        'Oreo','Oscar','Otis','Paisley','Peanut','Pepper','Phoebe','Pippa','Pluto','Pumpkin',
        'Ranger','Remi','Remy','Rex','River','Rocco','Rogue','Romy','Roscoe','Rusty',
        'Sam','Sasha','Scout','Shadow','Simba','Skye','Snoopy','Sophie','Spike','Stella',
        'Sunny','Theo','Thor','Toby','Tucker','Violet','Wally','Willow','Winnie','Wrigley',
        'Zeus','Ziggy','Zyla'
    ];
    $seed = crc32($breedSlug) + $index;
    $name = $names[$seed % count($names)];
    return $name;
}

/**
 * Ensure a name is unique within the current page list. If the name already exists,
 * try picking a different name from the pool using a deterministic seed; as a last
 * resort, append a numeric suffix.
 */
function ensureUniqueName(string $proposedName, array &$usedNames, string $breedSlug, int $index = 0): string {
    $clean = trim(preg_replace('/\s+/', ' ', $proposedName));
    if ($clean === '') { $clean = generateDogName($breedSlug, $index); }

    $key = strtolower($clean);
    if (!isset($usedNames[$key])) {
        $usedNames[$key] = true;
        return $clean;
    }

    $pool = [
        'Bella','Charlie','Luna','Cooper','Lucy','Max','Daisy','Milo','Rosie','Bailey',
        'Sadie','Teddy','Maggie','Rocky','Zoe','Buddy','Chloe','Duke','Lola','Finn',
        'Ruby','Leo','Ellie','Louie','Winston','Mia','Harley','Nala','Jasper','Piper',
        'Riley','Gracie','Hunter','Hazel','Kona','Beau','Annie','Ollie','Penny','Bentley',
        'Ace','Archie','Arlo','Atlas','Ava','Biscuit','Blue','Bo','Bolt','Bruno',
        'Buster','Casey','Cash','Cassie','Cedar','Chance','Clark','Cleo','Coco','Comet',
        'Copper','Cosmo','Dexter','Diesel','Dolly','Domino','Echo','Emma','Eva','Frankie',
        'Freya','Gigi','Ginger','Goose','Gus','Hank','Harlow','Harper','Hattie','Holly',
        'Indie','Ivy','Jack','Jake','June','Kiki','Koda','Lacey','Layla','Lenny',
        'Lexi','Lily','Loki','Luca','Lucky','Mabel','Macy','Marley','Mason','Matilda',
        'Maverick','Maxwell','Maya','Meadow','Moose','Murphy','Nico','Nova','Oakley','Olive',
        'Oreo','Oscar','Otis','Paisley','Peanut','Pepper','Phoebe','Pippa','Pluto','Pumpkin',
        'Ranger','Remi','Remy','Rex','River','Rocco','Rogue','Romy','Roscoe','Rusty',
        'Sam','Sasha','Scout','Shadow','Simba','Skye','Snoopy','Sophie','Spike','Stella',
        'Sunny','Theo','Thor','Toby','Tucker','Violet','Wally','Willow','Winnie','Wrigley',
        'Zeus','Ziggy','Zyla'
    ];

    $seed = (int)(crc32(strtolower($breedSlug . '|' . $proposedName)) + $index);
    $tries = 0;
    while ($tries < count($pool)) {
        $candidate = $pool[($seed + $tries) % count($pool)];
        $ckey = strtolower($candidate);
        if (!isset($usedNames[$ckey])) {
            $usedNames[$ckey] = true;
            return $candidate;
        }
        $tries++;
    }

    // Fallback: append a numeric suffix to the original name
    $suffix = 2;
    while (isset($usedNames[strtolower($clean . ' (' . $suffix . ')')])) {
        $suffix++;
    }
    $final = $clean . ' (' . $suffix . ')';
    $usedNames[strtolower($final)] = true;
    return $final;
}

/** Normalize any raw name to a clean display name */
function normalizeDisplayName(string $raw): string {
    $name = preg_replace('/[-_]+/', ' ', trim($raw));
    $name = preg_replace('/\s+/', ' ', $name);
    return ucwords($name);
}

/** Derive an uppercase abbreviation from a breed slug, e.g. 'german-shepherd' -> 'GS' */
function breedAbbreviation(string $breedSlug): string {
    $parts = array_filter(explode('-', strtolower($breedSlug)), function($p) { return $p !== ''; });
    $abbr = '';
    foreach ($parts as $p) { $abbr .= strtoupper($p[0]); }
    return $abbr ?: strtoupper(substr($breedSlug, 0, 1));
}

/**
 * Build a global count of base dog names (case-insensitive) across all breeds by reading
 * curated subfolder names; if a breed has no subfolders, approximate groups by top-level
 * images (4 per group) and generate placeholder names.
 */
function getGlobalBaseNameCounts(): array {
    static $cache = null;
    if ($cache !== null) { return $cache; }

    $counts = [];
    foreach (ALLOWED_BREED_SLUGS as $slug) {
        $baseDir = __DIR__ . '/../assets/img/' . $slug;
        if (!is_dir($baseDir)) { continue; }
        $entries = scandir($baseDir) ?: [];
        $curated = [];
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') { continue; }
            $full = $baseDir . '/' . $entry;
            if (is_dir($full)) {
                $files = scandir($full) ?: [];
                foreach ($files as $f) {
                    if ($f === '.' || $f === '..') { continue; }
                    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg','jpeg','png','webp'], true)) { $curated[] = $entry; break; }
                }
            }
        }
        if (count($curated) > 0) {
            foreach ($curated as $raw) {
                $name = normalizeDisplayName($raw);
                $key = strtolower($name);
                $counts[$key] = ($counts[$key] ?? 0) + 1;
            }
        } else {
            // Approximate top-level groupings at 4 images per group
            $top = scanPetImages($slug);
            $groups = intdiv(count($top), 4);
            for ($i = 0; $i < $groups; $i++) {
                $name = generateDogName($slug, $i);
                $key = strtolower($name);
                $counts[$key] = ($counts[$key] ?? 0) + 1;
            }
        }
    }
    $cache = $counts;
    return $counts;
}

/**
 * Ensure global uniqueness across the entire website. If a base name appears in multiple
 * breeds, append a breed abbreviation to make it globally distinct, then ensure uniqueness
 * within the current page using ensureUniqueName (adds numeric suffix if still needed).
 */
function ensureGlobalUniqueDisplayName(string $breedSlug, string $rawName, array &$pageUsedNames, int $index = 0): string {
    $base = normalizeDisplayName($rawName);
    $globalCounts = getGlobalBaseNameCounts();
    $key = strtolower($base);
    if (($globalCounts[$key] ?? 0) > 1) {
        // Previously we appended breed initials; new requirement: select a completely different
        // name that is unused site-wide. We'll pick from the global pool deterministically.
        $pool = [
            'Bella','Charlie','Luna','Cooper','Lucy','Max','Daisy','Milo','Rosie','Bailey','Sadie','Teddy','Maggie','Rocky','Zoe','Buddy','Chloe','Duke','Lola','Finn',
            'Ruby','Leo','Ellie','Louie','Winston','Mia','Harley','Nala','Jasper','Piper','Riley','Gracie','Hunter','Hazel','Kona','Beau','Annie','Ollie','Penny','Bentley',
            'Ace','Archie','Arlo','Atlas','Ava','Biscuit','Blue','Bo','Bolt','Bruno','Buster','Casey','Cash','Cassie','Cedar','Chance','Clark','Cleo','Coco','Comet',
            'Copper','Cosmo','Dexter','Diesel','Dolly','Domino','Echo','Emma','Eva','Frankie','Freya','Gigi','Ginger','Goose','Gus','Hank','Harlow','Harper','Hattie','Holly',
            'Indie','Ivy','Jack','Jake','June','Kiki','Koda','Lacey','Layla','Lenny','Lexi','Lily','Loki','Luca','Lucky','Mabel','Macy','Marley','Mason','Matilda','Maverick',
            'Maxwell','Maya','Meadow','Moose','Murphy','Nico','Nova','Oakley','Olive','Oreo','Oscar','Otis','Paisley','Peanut','Pepper','Phoebe','Pippa','Pluto','Pumpkin',
            'Ranger','Remi','Remy','Rex','River','Rocco','Rogue','Romy','Roscoe','Rusty','Sam','Sasha','Scout','Shadow','Simba','Skye','Snoopy','Sophie','Spike','Stella',
            'Sunny','Theo','Thor','Toby','Tucker','Violet','Wally','Willow','Winnie','Wrigley','Zeus','Ziggy','Zyla','Aurora','Blaise','Carmen','Daphne','Eliza','Fiona',
            'Gianna','Helena','Ingrid','Jolie','Kaia','Lara','Mira','Nadia','Opal','Quinn','Raya','Siena','Talia','Uma','Vera','Xena','Yara','Zara'
        ];
        // Gather globally reserved names (curated and top-level approximation + named pets)
        $reserved = getGlobalBaseNameCounts();
        // Also reserve explicitly named pets
        foreach (['Max','Rocky','Luna','Noin','Alaris','Laura'] as $r) { $reserved[strtolower($r)] = ($reserved[strtolower($r)] ?? 0) + 1; }
        $seed = (int)(crc32(strtolower($breedSlug . '|' . $base)));
        for ($i = 0; $i < count($pool); $i++) {
            $candidate = $pool[($seed + $i) % count($pool)];
            if (!isset($reserved[strtolower($candidate)])) { $base = $candidate; break; }
        }
    }
    return ensureUniqueName($base, $pageUsedNames, $breedSlug, $index);
}

/** Enumerate all dogs across the site and assign a globally unique name per dog. */
function getGlobalUniqueNameMap(): array {
    static $map = null;
    if ($map !== null) { return $map; }

    $dogs = [];
    foreach (ALLOWED_BREED_SLUGS as $slug) {
        $baseDir = __DIR__ . '/../assets/img/' . $slug;
        if (!is_dir($baseDir)) { continue; }
        $entries = scandir($baseDir) ?: [];
        $curated = [];
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') { continue; }
            $full = $baseDir . '/' . $entry;
            if (!is_dir($full)) { continue; }
            // Check this subfolder has at least one image
            $files = scandir($full) ?: [];
            foreach ($files as $f) {
                if ($f === '.' || $f === '..') { continue; }
                $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp'], true)) {
                    $curated[] = $entry; break; }
            }
        }
        sort($curated, SORT_NATURAL | SORT_FLAG_CASE);
        if (count($curated) > 0) {
            foreach ($curated as $entry) {
                $id = $slug . '/' . $entry;
                $dogs[] = ['id' => $id, 'slug' => $slug, 'suggested' => normalizeDisplayName($entry)];
            }
        } else {
            $top = scanPetImages($slug);
            $groups = intdiv(count($top), 4);
            for ($i = 0; $i < $groups; $i++) {
                $id = $slug . '/#' . $i;
                $dogs[] = ['id' => $id, 'slug' => $slug, 'suggested' => generateDogName($slug, $i)];
            }
        }
    }

    // Assign unique names
    $used = [];
    // Reserve explicitly named pets
    foreach (['Max','Rocky','Luna','Noin','Alaris','Laura'] as $r) { $used[strtolower($r)] = true; }
    $pool = [
        'Bella','Charlie','Luna','Cooper','Lucy','Max','Daisy','Milo','Rosie','Bailey','Sadie','Teddy','Maggie','Rocky','Zoe','Buddy','Chloe','Duke','Lola','Finn',
        'Ruby','Leo','Ellie','Louie','Winston','Mia','Harley','Nala','Jasper','Piper','Riley','Gracie','Hunter','Hazel','Kona','Beau','Annie','Ollie','Penny','Bentley',
        'Ace','Archie','Arlo','Atlas','Ava','Biscuit','Blue','Bo','Bolt','Bruno','Buster','Casey','Cash','Cassie','Cedar','Chance','Clark','Cleo','Coco','Comet',
        'Copper','Cosmo','Dexter','Diesel','Dolly','Domino','Echo','Emma','Eva','Frankie','Freya','Gigi','Ginger','Goose','Gus','Hank','Harlow','Harper','Hattie','Holly',
        'Indie','Ivy','Jack','Jake','June','Kiki','Koda','Lacey','Layla','Lenny','Lexi','Lily','Loki','Luca','Lucky','Mabel','Macy','Marley','Mason','Matilda','Maverick',
        'Maxwell','Maya','Meadow','Moose','Murphy','Nico','Nova','Oakley','Olive','Oreo','Oscar','Otis','Paisley','Peanut','Pepper','Phoebe','Pippa','Pluto','Pumpkin',
        'Ranger','Remi','Remy','Rex','River','Rocco','Rogue','Romy','Roscoe','Rusty','Sam','Sasha','Scout','Shadow','Simba','Skye','Snoopy','Sophie','Spike','Stella',
        'Sunny','Theo','Thor','Toby','Tucker','Violet','Wally','Willow','Winnie','Wrigley','Zeus','Ziggy','Zyla','Aurora','Blaise','Carmen','Daphne','Eliza','Fiona',
        'Gianna','Helena','Ingrid','Jolie','Kaia','Lara','Mira','Nadia','Opal','Quinn','Raya','Siena','Talia','Uma','Vera','Xena','Yara','Zara'
    ];

    $map = [];
    foreach ($dogs as $order => $dog) {
        $suggested = $dog['suggested'] ?: generateDogName($dog['slug'], $order);
        $candidate = normalizeDisplayName($suggested);
        $key = strtolower($candidate);
        if (!isset($used[$key])) {
            $map[$dog['id']] = $candidate; $used[$key] = true; continue;
        }
        $seed = (int)(crc32(strtolower($dog['id'])));
        for ($i = 0; $i < count($pool); $i++) {
            $cand = $pool[($seed + $i) % count($pool)];
            $ck = strtolower($cand);
            if (!isset($used[$ck])) { $map[$dog['id']] = $cand; $used[$ck] = true; break; }
        }
        if (!isset($map[$dog['id']])) {
            // As a very last resort, append a rare suffix letter to enforce uniqueness without numbers
            $suffixes = ['ix','on','ar','el','or','an','en','is','ia','na'];
            foreach ($suffixes as $sfx) {
                $cand = $candidate . ' ' . strtoupper($sfx);
                $ck = strtolower($cand);
                if (!isset($used[$ck])) { $map[$dog['id']] = $cand; $used[$ck] = true; break; }
            }
            if (!isset($map[$dog['id']])) { $map[$dog['id']] = $candidate . ' X'; }
        }
    }

    return $map;
}

function getUniqueNameForDog(string $breedSlug, string $dogIdentifier, string $suggestedRawName, int $index): string {
    $map = getGlobalUniqueNameMap();
    if (isset($map[$dogIdentifier])) { return $map[$dogIdentifier]; }
    // Fallback to global uniqueness by selection from pool
    $dummy = [];
    return ensureGlobalUniqueDisplayName($breedSlug, $suggestedRawName, $dummy, $index);
}

/**
 * Generate a friendly, deterministic age for a dog based on breed and identifier.
 * Returns values between 3 months and 2 years.
 */
function generateDogAge(string $breedSlug, string $dogIdentifier): string {
    $seed = (int) (crc32(strtolower($breedSlug . '|' . $dogIdentifier)));
    $months = ($seed % 22) + 3; // 3..24 months
    if ($months < 12) {
        return $months . ' months';
    }
    $years = intdiv($months, 12);
    $remMonths = $months % 12;
    $yearLabel = $years === 1 ? 'year' : 'years';
    if ($remMonths === 0) {
        return $years . ' ' . $yearLabel;
    }
    return $years . ' ' . $yearLabel . ' ' . $remMonths . ' months';
}

/**
 * Generate a short, pleasant description for a dog, deterministic per (breed,name).
 */
function generateDogDescription(string $breedSlug, string $dogName): string {
    $displayBreed = getDisplayNameForSlug($breedSlug);
    $seed = (int) (crc32(strtolower($breedSlug . '|' . $dogName)));

    $temperaments = [
        'sweet-natured','playful','gentle','curious','confident','calm','affectionate','friendly','smart','lively',
        'outgoing','loyal','easygoing','energetic','cuddly'
    ];
    $likes = [
        'belly rubs','walks in the park','snack time','learning new tricks','playing fetch','quiet naps','meeting new people',
        'chasing toys','car rides','a good cuddle'
    ];
    $homes = [
        'a loving family','an active home','a calm household','a home with a yard','a cozy apartment','first‑time dog parents',
        'a family with kids','someone who works from home'
    ];

    $t1 = $temperaments[$seed % count($temperaments)];
    $t2 = $temperaments[($seed >> 3) % count($temperaments)];
    if ($t2 === $t1) {
        $t2 = $temperaments[($seed >> 5) % count($temperaments)];
    }
    $like = $likes[($seed >> 7) % count($likes)];
    $home = $homes[($seed >> 11) % count($homes)];

    $name = $dogName;
    $name = preg_replace('/[-_]+/',' ',$name);
    $name = ucwords($name ?: 'This pup');

    return $name . ' is a ' . $t1 . ' and ' . $t2 . ' ' . $displayBreed . ' who enjoys ' . $like . '. '
        . 'Healthy, vaccinated, and ready to join ' . $home . '.';
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
            // Leave empty to allow the UI to generate distinct values per breed
            'age' => '',
            'slug' => $slug,
            'images' => $images,
            'description' => ''
        ];
    }

    // Do not auto-include any other folders; strictly enforce ALLOWED_BREED_SLUGS

    return $pets;
}


