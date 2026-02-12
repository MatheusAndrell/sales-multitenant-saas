#!/bin/sh
set -e

# Ensure app dir exists
mkdir -p /app
cd /app

if [ ! -f package.json ]; then
  echo "Initializing Vue 3 (Vite) project in /app..."
  npm create vite@latest . -- --template vue
fi

if [ -f package.json ]; then
  echo "Installing frontend dependencies..."
  npm install
  echo "Starting Vite dev server on 0.0.0.0:5173..."
  npm run dev -- --host 0.0.0.0 --port 5173
else
  echo "No package.json found; nothing to run. Exiting."
  exit 1
fi
