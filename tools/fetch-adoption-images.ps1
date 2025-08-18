param(
    [string]$OutputDir = 'assets/img/slide'
)

$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'

if (!(Test-Path $OutputDir)) {
    New-Item -ItemType Directory -Path $OutputDir -Force | Out-Null
}

function Invoke-Download {
    param(
        [Parameter(Mandatory=$true)][string]$Url,
        [Parameter(Mandatory=$true)][string]$OutFile
    )
    try {
        Invoke-WebRequest -Headers @{ 'User-Agent'='Mozilla/5.0'; 'Accept'='image/*,*/*;q=0.8' } `
            -Uri $Url -OutFile $OutFile -MaximumRedirection 10 -TimeoutSec 60
        if ((Get-Item $OutFile).Length -gt 8192) { return $true }
    } catch {}
    return $false
}

function Invoke-UnsplashFallback {
    param(
        [Parameter(Mandatory=$true)][string]$Query,
        [Parameter(Mandatory=$true)][string]$OutFile,
        [int]$Attempts = 6
    )
    for ($i = 0; $i -lt $Attempts; $i++) {
        $sig = Get-Random -Minimum 1000 -Maximum 999999
        $q = [uri]::EscapeDataString($Query)
        $url = "https://source.unsplash.com/1200x675/?$q&sig=$sig"
        if (Invoke-Download -Url $url -OutFile $OutFile) { return $true }
        Start-Sleep -Milliseconds (300 + (Get-Random -Minimum 0 -Maximum 700))
    }
    return $false
}

function Get-ImagesFromOpenverse {
    param(
        [Parameter(Mandatory=$true)][string]$Query,
        [int]$PageSize = 50
    )
    $api = 'https://api.openverse.engineering/v1/images/?q=' + [uri]::EscapeDataString($Query) + `
           '&license=cc0&extension=jpg&page_size=' + $PageSize
    try {
        $resp = Invoke-WebRequest -UseBasicParsing -Uri $api -TimeoutSec 60
        return ($resp.Content | ConvertFrom-Json).results
    } catch {
        return @()
    }
}

$targets = @(
    @{ Name = 'adopt-people-1.jpg'; Query = 'dog adoption people shelter' },
    @{ Name = 'adopt-people-2.jpg'; Query = 'family adopting dog shelter' },
    @{ Name = 'adopt-people-3.jpg'; Query = 'new dog adopter happy' },
    @{ Name = 'adopt-people-4.jpg'; Query = 'owner meeting rescue dog' },
    @{ Name = 'adopt-people-5.jpg'; Query = 'adoption day dog family' },
    @{ Name = 'adopted-home-1.jpg'; Query = 'dog at home indoor living room' },
    @{ Name = 'adopted-home-2.jpg'; Query = 'dog on couch home' },
    @{ Name = 'adopted-home-3.jpg'; Query = 'dog backyard home' },
    @{ Name = 'adopted-home-4.jpg'; Query = 'dog bedroom home' },
    @{ Name = 'adopted-home-5.jpg'; Query = 'dog with toys home' },
    @{ Name = 'adopted-home-parents-1.jpg'; Query = 'dog with owner at home' },
    @{ Name = 'adopted-home-parents-2.jpg'; Query = 'dog with family at home' },
    @{ Name = 'adopted-home-parents-3.jpg'; Query = 'owner hugging dog home' },
    @{ Name = 'adopted-home-parents-4.jpg'; Query = 'family with dog living room' },
    @{ Name = 'adopted-home-parents-5.jpg'; Query = 'dog with parents indoor' },
    @{ Name = 'adopted-home-parents-6.jpg'; Query = 'dog with owner sofa home' },
    @{ Name = 'adopted-home-parents-7.jpg'; Query = 'dog with couple at home' },
    @{ Name = 'adopted-home-parents-8.jpg'; Query = 'family playing with dog home' },
    @{ Name = 'adopted-home-parents-9.jpg'; Query = 'owner and dog in kitchen home' },
    @{ Name = 'adopted-home-parents-10.jpg'; Query = 'owner and dog reading on couch' }
)

$downloaded = @()
foreach ($t in $targets) {
    $out = Join-Path $OutputDir $t.Name
    if (Test-Path $out -PathType Leaf) {
        $downloaded += $t.Name
        continue
    }
    $results = Get-ImagesFromOpenverse -Query $t.Query -PageSize 60
    $picked = $false
    foreach ($item in $results) {
        foreach ($candidate in @($item.url, $item.thumbnail)) {
            if ([string]::IsNullOrWhiteSpace($candidate)) { continue }
            if (Invoke-Download -Url $candidate -OutFile $out) { $picked = $true; break }
        }
        if ($picked) { break }
    }
    if (-not $picked) {
        # Try Unsplash Source fallback with a descriptive query
        if (Invoke-UnsplashFallback -Query $t.Query -OutFile $out -Attempts 8) {
            $picked = $true
        }
    }
    if ($picked) { $downloaded += $t.Name }
}

Write-Output ("Downloaded: " + ($downloaded -join ', '))
 
$expected = $targets | ForEach-Object { $_.Name }
$missing = @()
foreach ($name in $expected) {
    if (-not (Test-Path (Join-Path $OutputDir $name) -PathType Leaf)) {
        $missing += $name
    }
}
if ($missing.Count -gt 0) {
    Write-Output ("Missing: " + ($missing -join ', '))
}

