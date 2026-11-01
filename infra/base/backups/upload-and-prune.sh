#!/bin/sh
set -eu

: "${BACKUP_KIND:?BACKUP_KIND is required}"
: "${BACKUP_NAMESPACE:?BACKUP_NAMESPACE is required}"
: "${BACKUP_REMOTE:?BACKUP_REMOTE is required}"
: "${BACKUP_RETENTION_DAYS:?BACKUP_RETENTION_DAYS is required}"

destination="${BACKUP_REMOTE}/${BACKUP_NAMESPACE}/${BACKUP_KIND}"

# backup-crypt is an rclone crypt remote. It encrypts file contents and names before
# they leave the Pod; only ciphertext is stored by the S3-compatible provider.
rclone --config /config/rclone.conf copy /work "${destination}" --checksum --immutable
rclone --config /config/rclone.conf delete "${destination}" --min-age "${BACKUP_RETENTION_DAYS}d"
rclone --config /config/rclone.conf rmdirs "${destination}"
