#
# Common variables
#

# Application version
VERSION="1.0.36"

# Remote URL to fetch application source tarball
APPLICATION_SOURCE_URL="https://github.com/kanboard/kanboard/archive/v${VERSION}.tar.gz"

#
# Common helpers
#

# Download and extract application sources to the given directory
# usage: extract_application_to DESTDIR
extract_application() {
  local DESTDIR=$1
  rc_tarball="${DESTDIR}/application.tar.gz"
  wget -q -O "$rc_tarball" "$APPLICATION_SOURCE_URL" \
|| ynh_die "Unable to download application tarball"
  tar xf "$rc_tarball" -C "$DESTDIR" --strip-components 1 \
    || ynh_die "Unable to extract application tarball"
  rm "$rc_tarball"
}

# Execute a command as another user
# usage: exec_as USER COMMAND [ARG ...]
exec_as() {
  local USER=$1
  shift 1

  if [[ $USER = $(whoami) ]]; then
    eval $@
  else
    # use sudo twice to be root and be allowed to use another user
    sudo sudo -u "$USER" $@
  fi
}

# Execute a composer command from a given directory
# usage: composer_exec AS_USER WORKDIR COMMAND [ARG ...]
exec_composer() {
  local AS_USER=$1
  local WORKDIR=$2
  shift 2

  exec_as "$AS_USER" COMPOSER_HOME="${WORKDIR}/.composer" \
    php "${WORKDIR}/composer.phar" $@ \
      -d "${WORKDIR}" --quiet --no-interaction
}

# Install and initialize Composer in the given directory
# usage: init_composer DESTDIR [AS_USER]
init_composer() {
  local DESTDIR=$1
  local AS_USER=${2:-admin}

  # install composer
  curl -sS https://getcomposer.org/installer \
    | exec_as "$AS_USER" COMPOSER_HOME="${DESTDIR}/.composer" \
        php -- --quiet --install-dir="$DESTDIR" \
    || ynh_die "Unable to install Composer"

  # update dependencies to create composer.lock
  exec_composer "$AS_USER" "$DESTDIR" install --no-dev \
    || ynh_die "Unable to update application core dependencies"
}
