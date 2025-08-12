$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$vendor = Join-Path $root 'assets/vendor'

Write-Host "Cleaning vendor assets ..."

# Keep only the vendors referenced in includes/head.php and includes/footerscripts.php
$keep = @('bootstrap','bootstrap-icons','aos','glightbox')

Get-ChildItem -Path $vendor -Directory -ErrorAction SilentlyContinue | ForEach-Object {
  if ($keep -contains $_.Name) { return }
  Write-Host "Removing vendor: $($_.Name)"
  Remove-Item -LiteralPath $_.FullName -Recurse -Force -ErrorAction SilentlyContinue
}

# Remove public/image if not referenced
$publicImg = Join-Path $root 'public/image'
if (Test-Path $publicImg) {
  Write-Host "Removing unused directory: public/image"
  Remove-Item -LiteralPath $publicImg -Recurse -Force -ErrorAction SilentlyContinue
}

Write-Host "Vendor cleanup complete."


