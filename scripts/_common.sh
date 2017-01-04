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
  TMPDIR=$(mktemp -d)
  chmod 755 $TMPDIR
  archive="${TMPDIR}/application.zip"
  wget -q -O "$archive" "$APPLICATION_SOURCE_URL" \
    || ynh_die "Unable to download application archive"
  unzip -qq "$archive" -d "$TMPDIR" \
    || ynh_die "Unable to extract application archive"
  rm "$archive"
  sudo rsync -a "$TMPDIR"/*/* "$DESTDIR"
}
