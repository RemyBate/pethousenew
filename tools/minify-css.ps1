$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$in = Join-Path $root 'assets/css/main.css'
$out = Join-Path $root 'assets/css/main.min.css'

if (!(Test-Path $in)) { throw "CSS file not found: $in" }

$css = Get-Content -Raw $in
# Remove comments
$css = [System.Text.RegularExpressions.Regex]::Replace($css, '/\*[\s\S]*?\*/', '')
# Collapse whitespace
$css = [System.Text.RegularExpressions.Regex]::Replace($css, '\\s+', ' ')
# Trim spaces around symbols
$css = $css -replace '\\s*\{\\s*','{' -replace '\\s*\}\\s*','}' -replace '\\s*:\\s*',':' -replace '\\s*;\\s*',';' -replace '\\s*,\\s*',','

Set-Content -Path $out -Value $css -NoNewline
Write-Host "Minified CSS written to $out"


