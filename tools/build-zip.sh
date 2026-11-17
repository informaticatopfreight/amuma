#!/usr/bin/env bash
# Crea amuma.zip listo para subir en WordPress → Apariencia → Temas → Añadir nuevo → Subir tema.
set -euo pipefail
cd "$(dirname "$0")/.."
rm -f amuma.zip
zip -rq amuma.zip amuma -x "*.DS_Store"
echo "Creado: $(pwd)/amuma.zip"
