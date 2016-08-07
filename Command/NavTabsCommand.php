<?php

/*
 * This file is made to generate twig templates compiling them in one single form.html.twig file.
 *
 * (c) elparici <lucdedampierre@l-farm.com>
 *
 * PRE REQUISITIES !!!!
 * This script needs Fontawesome + a Commons Dir -> app/Commons containg these 3 templates:
 * - collectionForm.html.twig
 * - dataPrototypeCollection.html.twig
 * - Breadcrumb.html.twig
 *
 * @todo -> Add AutoCompletion
 * @todo -> Update form name in controller editAction
 * @todo -> Inject Collections into Controller (originalCollection + update relationnal remove + orphanRemovals)
 */

namespace Lfarm\DevCollabBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;


class NavTabsCommand extends Command
{

    protected function configure()
    {
       $this
        ->setName('lfarm:gen:navtabs')
        ->setDescription('Generates simple Bootstrap NavPills Structure')
        ->setHelp("Won't need any...")
        ->setDefinition(array(
            new InputArgument('columns', InputArgument::OPTIONAL, 'The number of navigation tabs you need'),
        ));
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $dir = $this->getApplication()->getKernel()->getRootDir(). "/Resources/Test";
        $container = $this->getApplication()->getKernel()->getContainer();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $output->writeln($dir);
        $output->writeln([
            '',
            $this->getHelper('formatter')->formatBlock('OftenUse Code Generator', 'bg=blue;fg=white', true),
            '',
        ]);
        $navNames = array();
        while (true) {

            $question = new Question('Please add a name for a new tab (press enter to stop adding tabs):   ', null);
            $name = $helper->ask($input, $output, $question);
            
            if (!$name) {
                break;
            }else{
              $navNames[] = $name;  
            }
        }

        foreach ($navNames as $data) {
            $output->writeln($data);
        }
        $html = $this->makeTabs($navNames);
        $file = file_put_contents ( $dir . '/navTab.html.twig' , $html);
        $output->writeln('Generated:  '  . $dir . '/navTab.html.twig form template: <info>OK</info>');
        
        $question = new Question('Do you want to copy/paste the html from here? (y,n):   ', 'n');
        $copy = $helper->ask($input, $output, $question);
        if ($copy == 'y') {
            $parameters = array();
            $file = $container->get('twig')->render($dir . '/navTab.html.twig', $parameters);
            var_dump($file);
            $message = 'Just copyPaste the snippet to your workspace';
        }else{
             $message = 'Now Get to work!';
        }

        $info = ';-)';
        $bg = 'green';
        $fg = 'black';

        $output->writeln([
            '',
            '',
            $this->getHelper('formatter')->formatBlock($message, 'bg='.$bg.';fg='.$fg.'', true),

            '',
        ]);
    }

    public function makeTabs($names)
    {
        reset($names);
        $first = key($names);
        $html = array();
        $html[] ="
<div class='col-md-12'>
    <!-- Nav tabs -->
    <ul class='nav nav-tabs' role='tablist'>
    ";
    $inc = 0;
    foreach ($names as $name) {
        if ($inc == 0) {$active = 'active';}else{$active=null;}
        $html[] = "
         <li role='$name' class='$active'><a href='#$name' aria-controls='$name' role='tab' data-toggle='tab'>".ucfirst($name)."</a></li>";
         $inc++;
    }
    $html[] = " 
    </ul>

    <!-- Tab panes -->
    <div class='tab-content'>";
    $inc = 0;
    foreach ($names as $name) {
        if ($inc == 0) {$active = 'active';}else{$active=null;}
        $html[] = "
        <div role='tabpanel' class='tab-pane $active' id='$name'>
             <!-- Add your content here -->
        </div>";
         $inc++;
    }
    $html[] = "
    </div>
</div>
    ";
      return $html;
    }



}

