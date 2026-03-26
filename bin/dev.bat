@echo off
echo ============================================
echo   FVMP Theme — Starting Dev Environment
echo ============================================
echo.
echo [1/2] Opening Local site in browser...
start http://fvmp-tema.local
echo.
echo [2/2] Starting Vite dev server...
echo      SCSS changes = instant hot reload
echo      PHP changes  = full page reload
echo      Press Ctrl+C to stop
echo.
cd /d "%~dp0.."
npx vite