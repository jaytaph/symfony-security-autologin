<?php

namespace Rainbow\SecurityBundle\Firewall;

use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;

/*
 * This token extends the RememberMeToken so we can deal with IS_AUTHENTICATED_REMEMBERED etc
 */

class AutoLoginToken extends RememberMeToken { }
