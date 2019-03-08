#
# Create a new staging deployment
#

ENVIRONMENT_TYPE="staging"

eb init
eb create ENVIRONMENT_TYPE

echo "You can connect to the box via `eb ssh`"