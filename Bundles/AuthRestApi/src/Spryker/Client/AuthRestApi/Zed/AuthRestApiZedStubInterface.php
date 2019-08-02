<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApi\Zed;

use Generated\Shared\Transfer\OauthRequestTransfer;

interface AuthRestApiZedStubInterface
{
    /**
     * Specification: specification
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessToken(OauthRequestTransfer $oauthRequestTransfer): \Generated\Shared\Transfer\OauthResponseTransfer;
}
