<?php

namespace HA\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class ConstructCommand
 * @package HA\Command
 */
class ConstructCommand extends Command
{
    protected $god_is_the_lord = array(
        '1' => '8',
        '2' => '11',
        '3' => '14',
        '4' => '17',
        '5' => '20',
        '6' => '23',
        '7' => '26',
        '8' => '29',
    );
    protected $little_ektenia = '32';
    protected $kathismata = array(
        '1' => '33',
        '2' => '34',
        '3' => '35',
        '4' => '36',
        '5' => '37',
        '6' => '38',
        '7' => '39',
        '8' => '40',
    );
    protected $evlogetaria = '41';
    protected $little_ektenia_2 = '45';
    protected $hypakoe = array(
        '1' => '46',
        '2' => '48',
        '3' => '50',
        '4' => '52',
        '5' => '54',
        '6' => '56',
        '7' => '58',
        '8' => '60',
    );
    protected $prokeimenon = array(
        '1' => '47',
        '2' => '49',
        '3' => '51',
        '4' => '53',
        '5' => '55',
        '6' => '57',
        '7' => '59',
        '8' => '61',
    );
    protected $orthros_gospel = '65';
    protected $canon = array(
        'Akathist',
        'Nativity',
        'Cross',
    );
    protected $little_ektenia_3 = array(
        '81',
        '81A',
    );
    protected $exaposteilarion = array(
        '1'  => '82',
        '2'  => '84',
        '3'  => '86',
        '4'  => '88',
        '5'  => '90',
        '6'  => '92',
        '7'  => '94',
        '8'  => '96',
        '9'  => '98',
        '10' => '100',
        '11' => '102'
    );
    protected $praises = array(
        '1' => '104',
        '2' => '111',
        '3' => '119',
        '4' => '127',
        '5' => '135',
        '6' => '145',
        '7' => '152',
        '8' => '159',
    );
    protected $doxasticon = array(
        '1'  => array(
            'tone' => '1',
            'page' => '166',
        ),
        '2'  => array(
            'tone' => '2',
            'page' => '169',
        ),
        '3'  => array(
            'tone' => '3',
            'page' => '172',
        ),
        '4'  => array(
            'tone' => '4',
            'page' => '176',
        ),
        '5'  => array(
            'tone' => '5',
            'page' => '179',
        ),
        '6'  => array(
            'tone' => '6',
            'page' => '182',
        ),
        '7'  => array(
            'tone' => '7',
            'page' => '185',
        ),
        '8'  => array(
            'tone' => '8',
            'page' => '188',
        ),
        '9'  => array(
            'tone' => '5',
            'page' => '191',
        ),
        '10' => array(
            'tone' => '6',
            'page' => '194',
        ),
        '11' => array(
            'tone' => '8',
            'page' => '197',
        ),
    );

    protected function configure()
    {
        $this->setName('construct')
             ->setDescription('Construct the Service variable sheet.')
             ->addArgument(
                 'tone',
                 InputArgument::OPTIONAL,
                 'Tone of the week',
                 null
             )
             ->addArgument(
                 'eothinon',
                 InputArgument::OPTIONAL,
                 'The Eothinon for the week. Sets the Exaposteilarion and Doxasticon.',
                 null
             )
             ->addOption(
                 'canon',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'The Canon for the service. Valid options are "Akathist", "Nativity", or "Cross"'
             )
             ->addOption(
                 'date',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'The date of the service. Use quotes to enter a human-readable date. Defaults to the next Sunday.'
             )
             ->addOption(
                 'saints',
                 null,
                 InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                 'The saint of the day. Multiple saints can be specified by using this option multiple times.'
             )
             ->addOption(
                 'doxastikon',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Set a Doxastikon different from the one given by the Eothinon. Valid numbers 1-11.'
             )
             ->addOption(
                 'kathismata',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Set a Kathismata different from the one given by the Tone of the week.'
             )
             ->addOption(
                 'great_doxology',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Tone for the Great Doxology. Valid numbers 1-8'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set up question helper
        $helper = $this->getHelper('question');

        // Set the date
        if (!$date = $input->getOption('date')) {
            $date = strtotime('next Sunday');
        }

        // Check for the tone of the week
        if (!$tone = $input->getArgument('tone')) {
            $question = new Question('Enter tone of the week: ');
            $question->setValidator(array($this, 'toneQuestionValidator'));
            $tone = $helper->ask($input, $output, $question);
        }

        // Check for the Eothinon
        if (!$eothinon = $input->getArgument('eothinon')) {
            $question = new Question('Enter the Eothinon of the week: ');
            $eothinon = $helper->ask($input, $output, $question);
        }

        // Get saints
        $saints = $input->getOption('saints');

        // Possible variables
        if (!$doxastikon = $input->getOption('doxastikon')) {
            $doxastikon = $eothinon;
        }

        if (!$kathismata = $input->getOption('kathismata')) {
            $kathismata = $tone;
        }

        $custom_troparion = true;
        if (!$great_doxology = $input->getOption('great_doxology')) {
            $great_doxology = $tone;
            $custom_troparion = false;
        }

        switch ($tone) {
            case '1':
            case '2':
            case '3':
            case '4':
                $troparion = '4';
                break;
            case '5':
            case '6':
            case '7':
            case '8':
                $troparion = '8';
                break;
        }

        /*
         * Below here is all of the output.
         */

        // Header
        $output->writeln("\n");
        $output->writeln(date('F d, Y', $date));
        $output->writeln('Tone: ' . $tone);
        $output->writeln('Eothinon: ' . $eothinon);

        // Saints
        foreach ($saints as $saint) {
            $output->writeln($saint);
        }

        $output->writeln('');
        $output->writeln("==============================");
        $output->writeln('');

        // Variables
        $output->writeln('ORTHROS');
        $output->writeln('======');
        $output->writeln($this->addPadding('GOD IS THE LORD - Tone ' . $tone) . $this->god_is_the_lord[$tone]);
        $output->writeln('    - Resurrectional Troparion - Tone ' . $tone);
        $output->writeln('    - ');

        // @todo: other variables here

        $output->writeln($this->addPadding('LITTLE LITTANY') . $this->little_ektenia);
        $output->writeln($this->addPadding('KATHISMATA - Tone ' . $kathismata) . $this->kathismata[$kathismata]);
        $output->writeln($this->addPadding('EVLOGETARIA - Tone 5') . $this->evlogetaria);
        $output->writeln($this->addPadding('LITTLE LITTANY') . $this->little_ektenia_2);
        $output->writeln($this->addPadding('HYPAKOE & PROKEIMENON - Tone ' . $tone) . $this->hypakoe[$tone]);
        $output->writeln($this->addPadding('ORTHROS GOSPEL, etc.') . $this->orthros_gospel);

        // @todo: don't hard-code canon
        $output->writeln('');
        $output->writeln('CANON:   ' . $this->canon[1]);
        $output->writeln('');

        $output->writeln('EXAPOSTEILARIA');
        $output->writeln($this->addPadding("    - Litany & 'Holy is the Lord'") . $this->little_ektenia_3[0]);
        $output->writeln($this->addPadding("    - Exaposteilarion {$eothinon}") . $this->exaposteilarion[$eothinon]);
        $output->writeln("    - Theotokion {$eothinon}");
        $output->writeln('');

        $output->writeln($this->addPadding('PRAISES - Tone ' . $tone) . $this->praises[$tone]);
        $output->writeln(
            $this->addPadding(
                'DOXASTIKON - Tone ' . $this->doxasticon[$doxastikon]['tone']
            ) . $this->doxasticon[$doxastikon]['page']
        );

        $output->writeln('GREAT DOXOLOGY - Tone ' . $great_doxology);
        if ($custom_troparion) {
            $output->writeln('    - Troparion - Tone ' . $troparion);
        }
    }

    /**
     * Validate that a tone is between 1 and 8.
     *
     * @throws \RuntimeException When the value is not between 1 and 8.
     *
     * @param int $answer The given value.
     * @return int The given value.
     */
    public function toneQuestionValidator($answer)
    {
        $answer = (int) $answer;
        if ($answer < 1 || $answer > 8) {
            throw new \RuntimeException('Tone must be a number between 1 and 8');
        }
        return $answer;
    }

    /**
     * Pad a string and append 'p. ' to the end of it.
     *
     * @param string $string  The string to pad
     * @param int    $padding The padding amount. Defaults to 50.
     * @return string The padded string.
     */
    private function addPadding($string, $padding = 50)
    {
        return str_pad($string, $padding, '.') . 'p. ';
    }
}
