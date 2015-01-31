# DEVELOPMENT #

## Development Install ##

1. Put the ssl files in ``ssl`` folder in the root of the project.
2. Get Docker
3. Execute ``docker build .``
4. Execute ``docker run <hash from build>``

# CONFIGURATION #

Configuration is done at runtime using environment variables. Below is a list of
environment variables that can be set for production use instead of the development
defaults.

| Name                          | Description
|-------------------------------|-----------------------------------------------------------------------------
| API_HOST                      | The api host to use (default: http://devapi.snapable.com)
| API_KEY                       | The api key to use (default: key123)
| API_SECRET                    | The api secret to use (default: sec123)
| DEBUG                         | If debug traces should be displayed [true/false] (default: false)
| STRIPE_KEY_PUBLIC             | The public key for the Stripe API
| SENTRY_DSN                    | The DSN string to use for Sentry (default: '')
| SSL_REDIRECT                  | If the website should redirect to secure pages [true/false] (default: false)