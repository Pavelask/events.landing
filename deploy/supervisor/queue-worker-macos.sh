#!/usr/bin/env bash
# Автоподдержка воркера очереди писем на macOS (Herd) без root/supervisor.
# Использование: queue-worker-macos.sh {start|stop|status|restart}
set -euo pipefail

APP_DIR="/Users/pavelklimov/Herd/landing"
PHP="php"
LOG="$APP_DIR/storage/logs/queue-worker.log"
ERR="$APP_DIR/storage/logs/queue-worker-error.log"
PIDFILE="$APP_DIR/storage/logs/queue-worker.pid"
QUEUE="--queue=default,sendnewsletter"

find_pid() {
  pgrep -f "artisan queue:work $QUEUE" || true
}

start() {
  if [ -n "$(find_pid)" ]; then
    echo "Уже запущен: $(find_pid | tr '\n' ' ')"
    return 0
  fi
  cd "$APP_DIR"
  nohup $PHP artisan queue:work $QUEUE --sleep=3 --max-time=3500 --tries=3 \
      >"$LOG" 2>"$ERR" &
  echo $! >"$PIDFILE"
  echo "Воркер запущен, pid=$(cat "$PIDFILE")"
  sleep 2
}

stop() {
  [ -f "$PIDFILE" ] && kill "$(cat "$PIDFILE")" 2>/dev/null || true
  pkill -f "artisan queue:work $QUEUE" 2>/dev/null || true
  sleep 1
  echo "Остановлен"
}

status() {
  local pids
  pids=$(find_pid)
  if [ -n "$pids" ]; then
    echo "RUNNING pid=$(echo "$pids" | tr '\n' ' ')"
  else
    echo "STOPPED"
  fi
}

case "${1:-}" in
  start)   start; status ;;
  stop)    stop ;;
  restart) stop; start; status ;;
  status)  status ;;
  *) echo "Использование: $0 {start|stop|restart|status}"; exit 1 ;;
esac
