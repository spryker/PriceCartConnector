<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;

class QuoteApprovalWriter implements QuoteApprovalWriterInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface
     */
    protected $quoteApprovalRequestValidator;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface
     */
    protected $quoteApprovalMessageBuilder;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface
     */
    protected $quoteLocker;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface $quoteApprovalMessageBuilder
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface $quoteLocker
     */
    public function __construct(
        QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator,
        QuoteApprovalMessageBuilderInterface $quoteApprovalMessageBuilder,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteLockerInterface $quoteLocker
    ) {
        $this->quoteApprovalRequestValidator = $quoteApprovalRequestValidator;
        $this->quoteApprovalMessageBuilder = $quoteApprovalMessageBuilder;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
        $this->quoteLocker = $quoteLocker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalRequestValidationResponseTransfer = $this->quoteApprovalRequestValidator
            ->validateQuoteApprovalRequest($quoteApprovalRequestTransfer);

        if (!$quoteApprovalRequestValidationResponseTransfer->getIsSuccessful()) {
            return $this->createNotSuccessfullQuoteApprovalResponseTransfer();
        }

        return $this->updateQuoteApprovalWithStatus(
            $quoteApprovalRequestValidationResponseTransfer->getQuoteApproval(),
            QuoteApprovalConfig::STATUS_APPROVED
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalRequestValidationResponseTransfer = $this->quoteApprovalRequestValidator
            ->validateQuoteApprovalRequest($quoteApprovalRequestTransfer);

        if (!$quoteApprovalRequestValidationResponseTransfer->getIsSuccessful()) {
            return $this->createNotSuccessfullQuoteApprovalResponseTransfer();
        }

        $this->quoteLocker->unlockQuote($quoteApprovalRequestValidationResponseTransfer->getQuote());

        return $this->updateQuoteApprovalWithStatus(
            $quoteApprovalRequestValidationResponseTransfer->getQuoteApproval(),
            QuoteApprovalConfig::STATUS_DECLINED
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function createNotSuccessfullQuoteApprovalResponseTransfer(): QuoteApprovalResponseTransfer
    {
        return (new QuoteApprovalResponseTransfer())->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function updateQuoteApprovalWithStatus(QuoteApprovalTransfer $quoteApprovalTransfer, string $status): QuoteApprovalResponseTransfer
    {
        $quoteApprovalTransfer->setStatus($status);
        $quoteApprovalTransfer = $this->quoteApprovalEntityManager->saveQuoteApproval($quoteApprovalTransfer);

        return (new QuoteApprovalResponseTransfer())
            ->setQuoteApproval($quoteApprovalTransfer)
            ->setIsSuccessful(true)
            ->addMessage($this->quoteApprovalMessageBuilder->getSuccessMessage($quoteApprovalTransfer, $status));
    }
}
