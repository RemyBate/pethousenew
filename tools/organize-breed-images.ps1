$ErrorActionPreference = 'Stop'

function Get-ImageFiles($path) {
  Get-ChildItem -Path $path -File -Include *.jpg,*.jpeg,*.png,*.webp -ErrorAction SilentlyContinue | Sort-Object Name
}

$repoRoot = Split-Path -Parent $PSScriptRoot
$imgRoot = Join-Path $repoRoot 'assets/img'

$nonPetFolders = @('bg','blog','clients','cta','illustration','person','portfolio','services','steps')

# Names pool (same spirit as generateDogName in PHP)
$namePool = @(
  'Bella','Charlie','Luna','Cooper','Lucy','Max','Daisy','Milo','Rosie','Bailey',
  'Sadie','Teddy','Maggie','Rocky','Zoe','Buddy','Chloe','Duke','Lola','Finn',
  'Ruby','Leo','Ellie','Louie','Winston','Mia','Harley','Nala','Jasper','Piper',
  'Riley','Gracie','Hunter','Hazel','Kona','Beau','Annie','Ollie','Penny','Bentley'
)

Write-Host "Organizing breed images under: $imgRoot"

Get-ChildItem -Path $imgRoot -Directory | ForEach-Object {
  $breedDir = $_.FullName
  $breedName = $_.Name
  if ($nonPetFolders -contains $breedName) { return }

  # If it already has subfolders with images, consider curated and skip
  $subdirs = Get-ChildItem -Path $breedDir -Directory -ErrorAction SilentlyContinue
  $hasCurated = $false
  foreach ($sd in $subdirs) {
    if ((Get-ImageFiles $sd.FullName).Count -gt 0) { $hasCurated = $true; break }
  }
  if ($hasCurated) {
    Write-Host "[SKIP] $breedName already curated"
    return
  }

  $images = Get-ImageFiles $breedDir
  if ($images.Count -eq 0) { return }

  Write-Host "[ORGANIZE] $breedName ($($images.Count) images)"

  $usedNames = @{}
  $chunkSize = 4
  for ($i = 0; $i -lt $images.Count; $i += $chunkSize) {
    $end = [Math]::Min($i + $chunkSize - 1, $images.Count - 1)
    $group = $images[$i..$end]

    # Pick a unique folder name from the pool
    $candidate = $namePool[($i / $chunkSize) % $namePool.Count]
    $folderName = $candidate
    $suffix = 2
    while (Test-Path (Join-Path $breedDir $folderName) -or $usedNames.ContainsKey($folderName)) {
      $folderName = "$candidate-$suffix"
      $suffix += 1
    }
    $usedNames[$folderName] = $true

    $dest = Join-Path $breedDir $folderName
    New-Item -ItemType Directory -Path $dest -Force | Out-Null

    foreach ($img in $group) {
      Move-Item -LiteralPath $img.FullName -Destination (Join-Path $dest $img.Name) -Force
    }
  }
}

Write-Host "Done."


