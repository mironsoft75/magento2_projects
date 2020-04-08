<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Migration\Console;

use Codilar\Migration\Model\Migration\Types\MigrationTypeInterface;
use Codilar\Migration\Model\Migration\Types\Pool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;


class Migration extends Command
{
    const TYPE = "type";

    const PASSWORD_SALT = "absadlkkfkjds";
    const PASSWORD = "bc28a2d022377e8b894e34d47aa7c63b"; //Samyakk@codilar@Avengers@123

    /**
     * @var Pool
     */
    private $migrationPool;
    /**
     * @var array
     */
    private $migrationType;

    /**
     * Customer constructor.
     * @param Pool $migrationPool
     * @param array $migrationType
     */
    public function __construct(
        Pool $migrationPool,
        $migrationType = []
    )
    {
        parent::__construct();
        $this->migrationPool = $migrationPool;
        $this->migrationType = $migrationType;
    }

    protected function configure()
    {
        $this->setName('magento:migrate');
        $this->setDescription('Create data required for migration');
        $options = [
            new InputOption(self::TYPE, 't', InputOption::VALUE_REQUIRED, 'type of data which is required to migrate')
        ];
        $this->setDefinition($options);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $passwordQuestion = new Question('Password: ', '');
        $passwordQuestion->setHidden(true);
        $password = $questionHelper->ask($input, $output, $passwordQuestion);
        if (md5(self::PASSWORD_SALT . $password . self::PASSWORD_SALT) !== self::PASSWORD) {
            throw new LocalizedException(__("Invalid Password"));
        }

        $type = $input->getOption(self::TYPE);
        if (!strlen($type)) {
            throw new LocalizedException(__("Threshold value cannot be blank"));
        }
        $symfonyStyle = new SymfonyStyle($input, $output);

        if ($output->isDebug()) {
            $progressBar = $symfonyStyle->createProgressBar(100);
            $progressBar->setFormat('%current%/%max% [%bar%] <info>%percent:3s%%</info>');
        }

        try {
            $fileName = $this->migrationPool->getTypeInstance($type)->startMigration();
            $message = "<info>" . $type . " Migration file complete," . $fileName . " file generated in var directory </info>";
        } catch (NoSuchEntityException $e) {
            $message = "<error>" . $e->getMessage() . "</error>";
        } catch (LocalizedException $e) {
            $message = "<error>" . $e->getMessage() . "</error>";
        }

        if ($output->isDebug()) {
            $progressBar->finish();
        }

        $output->writeln($message);
    }

}