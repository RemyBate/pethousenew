$ErrorActionPreference = 'Stop'

$repoRoot = Split-Path -Parent $PSScriptRoot
$imgRoot = Join-Path $repoRoot 'assets/img'

# Get active breed slugs from PHP by reading directory names; keep only those that have images
$breedSlugs = @()
Get-ChildItem -Path $imgRoot -Directory | ForEach-Object {
  $name = $_.Name.ToLower()
  if ($name -in @('logo')) { return }
  # Treat anything with at least one image as an active breed
  $hasImage = Get-ChildItem -Path $_.FullName -Recurse -Include *.jpg,*.jpeg,*.png,*.webp -File -ErrorAction SilentlyContinue | Select-Object -First 1
  if ($hasImage) { $breedSlugs += $name }
}

$keepDirs = @('logo') + $breedSlugs
$keepFiles = @('favicon.png','apple-touch-icon.png','mae.jpg','why-us.png')

$removeDirs = @('portfolio','services','steps','illustration','clients','cta','bg')

Write-Host "Cleaning assets/img ..."

# Remove known template directories if present
foreach ($d in $removeDirs) {
  $path = Join-Path $imgRoot $d
  if (Test-Path $path) {
    Write-Host "Removing directory: $d"
    Remove-Item -LiteralPath $path -Recurse -Force -ErrorAction SilentlyContinue
  }
}

# Remove any other directories that are not in keep list
Get-ChildItem -Path $imgRoot -Directory | ForEach-Object {
  if ($keepDirs -contains $_.Name.ToLower()) { return }
  # If this directory is one of the active breed slugs, keep
  if ($breedSlugs -contains $_.Name.ToLower()) { return }
  Write-Host "Removing extra directory: $($_.Name)"
  Remove-Item -LiteralPath $_.FullName -Recurse -Force -ErrorAction SilentlyContinue
}

# Remove stray files except keep list
Get-ChildItem -Path $imgRoot -File | ForEach-Object {
  if ($keepFiles -contains $_.Name) { return }
  if ($_.Name -ieq 'hero-img.png') {
    Write-Host "Removing old hero image: $($_.Name)"
    Remove-Item -LiteralPath $_.FullName -Force -ErrorAction SilentlyContinue
    return
  }
  # Remove any other top-level file that isn't explicitly kept
  Write-Host "Removing extra file: $($_.Name)"
  Remove-Item -LiteralPath $_.FullName -Force -ErrorAction SilentlyContinue
}

Write-Host "Cleanup complete."


