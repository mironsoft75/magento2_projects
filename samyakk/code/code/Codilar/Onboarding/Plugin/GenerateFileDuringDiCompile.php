<?php

namespace Codilar\Onboarding\Plugin;

use Codilar\Onboarding\Model\Onboarding\Config as OnboardingConfig;
use Magento\Setup\Module\Di\App\Task\Operation\ApplicationCodeGenerator as Subject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateOnboardingData
{
    /**
     * @var OnboardingConfig
     */
    private $onboardingConfig;

    /**
     * GenerateFileDuringDiCompile constructor.
     * @param OnboardingConfig $onboardingConfig
     */
    public function __construct(
        OnboardingConfig $onboardingConfig
    ) {
        $this->onboardingConfig = $onboardingConfig;
    }

    /**
     * @param Subject $subject
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function beforeDoOperation(Subject $subject)
    {
        $this->onboardingConfig->generateAggregatedData();
        return [];
    }
}
