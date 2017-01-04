#
# Common variables
#

# Application version
VERSION="1.0.36"

# Remote URL to fetch application source archive
APPLICATION_SOURCE_URL="https://kanboard.net/kanboard-${VERSION}.zip"

#
# Common helpers
#

# Download and extract application sources to the given directory
# usage: extract_application_to DESTDIR
extract_application() {
  local DESTDIR=$1
  archive="${DESTDIR}/application.zip"
  wget -q -O "$archive" "$APPLICATION_SOURCE_URL" \
    || ynh_die "Unable to download application archive"
  # Here we process with unzip as would tar "--strip-component" option
  unzip -qq "$archive" -d "$DESTDIR" && rm "$archive" && f=("$DESTDIR"/*) && mv "$DESTDIR"/*/* "$DESTDIR" && rm -f "${f[@]}"/.htaccess && rmdir "${f[@]}" \
    || ynh_die "Unable to extract application archive"
  chmod 755 $DESTDIR
}
