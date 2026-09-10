#!/usr/bin/env bash
#
# Centralized log viewer for the lab.
#
# Every service bind-mounts the project into /appdata/www, so Monolog's
# var/log/dev.log is available on the host. This script follows all of them
# in a single terminal, prefixing each line with the service name.
#
# Usage:
#   ./scripts/logs.sh                  # follow every service
#   ./scripts/logs.sh payments esim    # follow only some services
#
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

ALL_SERVICES=(register application carts products inventory mailer payments esim)
TAIL_LINES="${TAIL_LINES:-20}"

if [ "$#" -gt 0 ]; then
  SERVICES=("$@")
else
  SERVICES=("${ALL_SERVICES[@]}")
fi

COLORS=(36 32 33 35 34 31 96 95)

pids=()
cleanup() {
  for pid in "${pids[@]:-}"; do
    kill "$pid" 2>/dev/null || true
  done
}
trap cleanup EXIT INT TERM

i=0
for svc in "${SERVICES[@]}"; do
  logfile="$svc/var/log/dev.log"
  if [ ! -d "$svc" ]; then
    echo "Skipping unknown service: $svc" >&2
    continue
  fi

  mkdir -p "$svc/var/log"
  touch "$logfile"

  color=${COLORS[$((i % ${#COLORS[@]}))]}
  prefix=$(printf '\033[%sm[%-11s]\033[0m ' "$color" "$svc")

  (
    tail -n "$TAIL_LINES" -F "$logfile" 2>/dev/null \
      | awk -v p="$prefix" '{ printf "%s%s\n", p, $0; fflush() }'
  ) &
  pids+=("$!")
  i=$((i + 1))
done

wait
