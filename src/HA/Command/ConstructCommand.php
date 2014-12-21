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
             ->addOption(
                 'eothinon',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'The Eothinon for the week. Sets the Exaposteilarion and Doxasticon.'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set up question helper
        $helper = $this->getHelper('question');

        if (!$input->getArgument('tone')) {
            $question = new Question('Enter tone of the week: ');
            $question->setValidator(array($this, 'toneQuestionValidator'));
            $tone = $helper->ask($input, $output, $question);
        } else {
            $output->writeln("We have a tone!");
            $tone = $input->getArgument('tone');
        }
        $output->writeln("The tone is " . $tone);
    }


    public function toneQuestionValidator($answer)
    {
        $answer = (int) $answer;
        if ($answer < 1 || $answer > 8) {
            throw new \RuntimeException('Tone must be a number between 1 and 8');
        }
        return $answer;
    }
}
