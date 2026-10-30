#!/usr/bin/env bash
# Installs a throwaway WordPress on SQLite for the integration tests, with this
# theme symlinked in and active. No MySQL or Docker needed.
#
# Usage: bin/install-wp.sh <dir>     then: WP_PATH=<dir> composer test
set -euo pipefail

dir=${1:?usage: bin/install-wp.sh <dir>}
theme_dir=$(cd "$(dirname "$0")/.." && pwd)
wp_version=${WP_VERSION:-latest}

mkdir -p "$dir"
dir=$(cd "$dir" && pwd)
cd "$dir"

if [[ ! -f wp-settings.php ]]; then
	if [[ $wp_version == latest ]]; then
		url=https://wordpress.org/latest.tar.gz
	else
		url="https://wordpress.org/wordpress-${wp_version}.tar.gz"
	fi
	curl -fsSL "$url" | tar -xz --strip-components=1
fi

if [[ ! -f wp-cli.phar ]]; then
	curl -fsSLo wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
fi
wp() { php -d 'error_reporting=E_ALL & ~E_DEPRECATED' "$dir/wp-cli.phar" --path="$dir" "$@"; }

sqlite_dir=wp-content/plugins/sqlite-database-integration
if [[ ! -d $sqlite_dir ]]; then
	curl -fsSLo sqlite.zip https://downloads.wordpress.org/plugin/sqlite-database-integration.latest-stable.zip
	unzip -q sqlite.zip -d wp-content/plugins
	rm sqlite.zip
fi
sed -e "s#{SQLITE_IMPLEMENTATION_FOLDER_PATH}#$dir/$sqlite_dir#" \
	-e 's#{SQLITE_PLUGIN}#sqlite-database-integration/load.php#' \
	"$sqlite_dir/db.copy" > wp-content/db.php

if [[ ! -f wp-config.php ]]; then
	wp config create --dbname=wp --dbuser=wp --dbpass=wp --skip-check --quiet
fi

if ! wp core is-installed 2>/dev/null; then
	wp core install --url=http://localhost:8765 --title='Block Starter' \
		--admin_user=admin --admin_email=admin@example.com --admin_password="$(openssl rand -hex 12)" \
		--skip-email --quiet
fi

ln -sfn "$theme_dir" wp-content/themes/block-starter
wp theme activate block-starter --quiet
wp rewrite structure '/%postname%/' --quiet
echo "WordPress ready in $dir"
