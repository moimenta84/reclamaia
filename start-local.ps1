# ReclamaIA — local dev startup
# Usage: .\start-local.ps1
# Requires: XAMPP MySQL running, Python venv created, .env files configured

$laravelDir = "C:\software\extra\reclamaia\laravel"
$pythonDir  = "C:\software\extra\reclamaia\python-service"
$php        = "C:\xampp\php\php.exe"

Write-Host ""
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host " ReclamaIA - Local Dev" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Check ANTHROPIC_API_KEY in python .env
$envContent = Get-Content "$pythonDir\.env" -Raw
if ($envContent -match "ANTHROPIC_API_KEY=sk-ant-\.\.\.") {
    Write-Host "[!] ANTHROPIC_API_KEY no configurada en python-service\.env" -ForegroundColor Yellow
    Write-Host "    Edita $pythonDir\.env y pon tu clave real" -ForegroundColor Yellow
    Write-Host ""
}

# Check Stripe in laravel .env
$laravelEnv = Get-Content "$laravelDir\.env" -Raw
if ($laravelEnv -match "STRIPE_KEY=pk_test_\.\.\.") {
    Write-Host "[!] STRIPE_KEY no configurada en laravel\.env" -ForegroundColor Yellow
    Write-Host "    Los pagos fallarán hasta que la configures" -ForegroundColor Yellow
    Write-Host ""
}

Write-Host "[1/3] Iniciando Python FastAPI en puerto 8001..." -ForegroundColor Green
$pythonProc = Start-Process -FilePath "$pythonDir\venv\Scripts\uvicorn.exe" `
    -ArgumentList "app.main:app", "--host", "127.0.0.1", "--port", "8001", "--reload" `
    -WorkingDirectory $pythonDir `
    -PassThru -WindowStyle Normal

Start-Sleep -Seconds 3

Write-Host "[2/3] Iniciando Laravel queue worker..." -ForegroundColor Green
$queueProc = Start-Process -FilePath $php `
    -ArgumentList "artisan", "queue:work", "--sleep=3", "--tries=3", "--timeout=90" `
    -WorkingDirectory $laravelDir `
    -PassThru -WindowStyle Normal

Write-Host "[3/3] Iniciando Laravel en http://localhost:8000 ..." -ForegroundColor Green
Write-Host ""
Write-Host "  App:    http://localhost:8000" -ForegroundColor White
Write-Host "  Health: http://localhost:8001/health" -ForegroundColor White
Write-Host ""
Write-Host "Pulsa Ctrl+C para parar el servidor Laravel (los otros procesos cierran su ventana)." -ForegroundColor Gray
Write-Host ""

& $php "$laravelDir\artisan" serve --host=127.0.0.1 --port=8000

# Cleanup
Write-Host ""
Write-Host "Parando procesos..." -ForegroundColor Gray
Stop-Process -Id $pythonProc.Id -ErrorAction SilentlyContinue
Stop-Process -Id $queueProc.Id -ErrorAction SilentlyContinue
